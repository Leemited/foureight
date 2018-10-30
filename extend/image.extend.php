<?php
@ini_set('gd.jpeg_ignore_warning', 1);
@ini_set('memory_limit','-1');
//src : $_FILES["file"]["tmp_name"], dst : $_FILES["file"]["name"], $output : 파일명
function image_resize_update($src, $dst, $output, $resize_width, $resize_height=''){
    $uploadedfile = $src;
    $filename = str_replace("%","",$dst);
    $filename = stripslashes($filename);
    $str = $filename;

    $i = strrpos($str,".");
    if (!$i)
        return false;
    $l = strlen($str) - $i;
    $ext = substr($str,$i+1,$l);
    $extension = $ext;
    $extension = strtolower($extension);

    if (($extension != "jpg") && ($extension != "jpeg")
        && ($extension != "png") && ($extension != "gif"))
    {
        move_uploaded_file($src, $output);//return false;
    }
    else
    {
        $uploadedfile = $src;
        if($extension=="jpg" || $extension=="jpeg" ){
            $src = imagecreatefromjpeg($uploadedfile);
        }
        else if($extension=="png"){
            $src = imagecreatefrompng($uploadedfile);
        }else{
            $src = imagecreatefromgif($uploadedfile);
        }

        list($width,$height)=getimagesize($uploadedfile);

        if($width<$resize_width){
            $newwidth=$width;
        }else{
            $newwidth=$resize_width;
        }

        if($resize_height)
            $newheight = $resize_height;
        else
            $newheight= ceil(($height/$width)*$newwidth);

        $tmp=imagecreatetruecolor($newwidth,$newheight);
        if($extension=="png"){
            imagealphablending($tmp, false);
            imagesavealpha($tmp, true);
            $white=ImageColorAllocate($tmp,255,255,255);
            imagecolortransparent($tmp,$white);
            //$transparent = imagecolorallocatealpha($tmp, 255, 255, 255, 127);
            //imagefilledrectangle($tmp, 0, 0, $width, $height, $transparent);
        }
        imagecopyresampled($tmp,$src,0,0,0,0,$newwidth,$newheight,$width,$height);
        if($extension=="jpg" || $extension == "jpeg"){
            $exif = exif_read_data($uploadedfile);
            if(!empty($exif['Orientation'])) {
                switch($exif['Orientation']) {
                    case 8:
                        $tmp = imagerotate($tmp,90,0);
                        break;
                    case 3:
                        $tmp = imagerotate($tmp,180,0);
                        break;
                    case 6:
                        $tmp = imagerotate($tmp,-90,0);
                        break;
                }
            }
        }
        $filename = $output;
        if($extension=="jpg" || $extension == "jpeg"){
            imagejpeg($tmp,$filename,100);
        }else if($extension=="png"){
            imagepng($tmp,$filename);
        }else{
            imagejpeg($tmp,$filename,100);
        }
        imagedestroy($src);
        imagedestroy($tmp);

        return true;
    }
}
function get_images($srcfile,$dWidth="",$dHeight=""){
    $size = @getimagesize($srcfile);
    if(empty($size))
        return false;
    // jpg 이면 exif 체크
    if($size[2] == 2 && function_exists('exif_read_data')) {
        $degree = 0;
        $exif = @exif_read_data($srcfile);
        //print_r2($exif);
        if(!empty($exif['Orientation'])) {
            switch($exif['Orientation']) {
                case 8:
                    $degree = 90;
                    break;
                case 3:
                    $degree = 180;
                    break;
                case 6:
                    $degree = -90;
                    break;
            }

            // 세로사진의 경우 가로, 세로 값 바꿈
            if($degree == 90 || $degree == -90) {
                $tmp = $size;
                $size[0] = $tmp[1];
                $size[1] = $tmp[0];
            }
        }
    }
    if($dWidth==""){
        $ratio = $size[0] / $size[1];
        //세로
        if($ratio > 1){
            $dWidth = $size[1]/2;
            $degree = 0;
        }
        //가로
        else if($ratio < 1){
            $dWidth = $size[0]/2;
        }
    }else{
        $dWidth = $size[0];
    }


    // 썸네일 높이
    $thumb_height = round(($dWidth * $size[1]) / $size[0]);
    $filename = basename($srcfile);
    $filepath = dirname($srcfile);

    // 썸네일 생성
    $thumb_file = thumbnails($filename, $filepath, $filepath, $dWidth, $thumb_height, false);

    if(!$thumb_file)
        return false;

    return $thumb_file;
}

function get_images2($srcfile){
    $size = @getimagesize($srcfile);
    if(empty($size))
        return false;
    // jpg 이면 exif 체크
    if($size[2] == 2 && function_exists('exif_read_data')) {
        $degree = 0;
        $exif = @exif_read_data($srcfile);
        if(!empty($exif['Orientation'])) {
            switch($exif['Orientation']) {
                case 8:
                    $degree = 90;
                    break;
                case 3:
                    $degree = 180;
                    break;
                case 6:
                    $degree = -90;
                    break;
            }

            // 세로사진의 경우 가로, 세로 값 바꿈
            if($degree == 90 || $degree == -90) {
                $tmp = $size;
                $size[0] = $tmp[1];
                $size[1] = $tmp[0];
            }
        }
    }

    // 썸네일 높이
    //$thumb_height = 800;
    $filename = basename($srcfile);
    $filepath = dirname($srcfile);

    // 썸네일 생성
    $thumb_file = thumbnails($filename, $filepath, $filepath, 150, 150, false);

    if(!$thumb_file)
        return false;

    return $thumb_file;
}

function thumbnails($filename, $source_path, $target_path, $thumb_width, $thumb_height, $is_create, $is_crop=false, $crop_mode='center', $is_sharpen=false, $um_value='80/0.5/3')
{
    global $g5;
    if(!$thumb_width && !$thumb_height)
        return;
    $source_file = "$source_path/$filename";

    if(!is_file($source_file)) // 원본 파일이 없다면
        return;

    $size = @getimagesize($source_file);

    if($size[2] < 1 || $size[2] > 3) // gif, jpg, png 에 대해서만 적용
        return;
    if (!is_dir($target_path)) {
        @mkdir($target_path, G5_DIR_PERMISSION);
        @chmod($target_path, G5_DIR_PERMISSION);
    }

    // 디렉토리가 존재하지 않거나 쓰기 권한이 없으면 썸네일 생성하지 않음
    if(!(is_dir($target_path) && is_writable($target_path)))
        return '';
    // Animated GIF는 썸네일 생성하지 않음
    if($size[2] == 1) {
        if(is_animated_gif($source_file))
            return basename($source_file);
    }
    $ext = array(1 => 'gif', 2 => 'jpg', 3 => 'png');

    $thumb_filename = preg_replace("/\.[^\.]+$/i", "", $filename); // 확장자제거
    $thumb_file = "$target_path/thumb-{$thumb_filename}_{$thumb_width}x{$thumb_height}.".$ext[$size[2]];

    $thumb_time = @filemtime($thumb_file);
    $source_time = @filemtime($source_file);

    if (file_exists($thumb_file)) {
        if ($is_create == false && $source_time < $thumb_time) {
            return basename($thumb_file);
        }
    }
    // 원본파일의 GD 이미지 생성
    $src = null;
    $degree = 0;

    if ($size[2] == 1) {
        $src = @imagecreatefromgif($source_file);
        $src_transparency = @imagecolortransparent($src);
    } else if ($size[2] == 2) {
        $src = @imagecreatefromjpeg($source_file);

        if(function_exists('exif_read_data')) {
            // exif 정보를 기준으로 회전각도 구함
            $exif = @exif_read_data($source_file);
            if(!empty($exif['Orientation'])) {
                switch($exif['Orientation']) {
                    case 8:
                        $degree = 90;
                        break;
                    case 3:
                        $degree = 180;
                        break;
                    case 6:
                        $degree = -90;
                        break;
                }

                // 회전각도 있으면 이미지 회전
                if($degree) {
                    $src = imagerotate($src, $degree, 0);

                    // 세로사진의 경우 가로, 세로 값 바꿈
                    if($degree == 90 || $degree == -90) {
                        $tmp = $size;
                        $size[0] = $tmp[1];
                        $size[1] = $tmp[0];
                    }
                }
            }
        }
    } else if ($size[2] == 3) {
        $src = @imagecreatefrompng($source_file);
        @imagealphablending($src, true);
    } else {
        return;
    }

    if(!$src)
        return;

    $is_large = true;
    // width, height 설정

    if($thumb_width) {
        if(!$thumb_height) {
            $thumb_height = round(($thumb_width * $size[1]) / $size[0]);
        } else {
            if($size[0] < $thumb_width || $size[1] < $thumb_height)
                $is_large = false;
        }
    } else {
        if($thumb_height) {
            $thumb_width = round(($thumb_height * $size[0]) / $size[1]);
        }
    }

    $dst_x = 0;
    $dst_y = 0;
    $src_x = 0;
    $src_y = 0;
    $dst_w = $thumb_width;
    $dst_h = $thumb_height;
    $src_w = $size[0];
    $src_h = $size[1];

    $ratio = $dst_h / $dst_w;

    if($is_large) {
        // 크롭처리
        if($is_crop) {
            switch($crop_mode)
            {
                case 'center':
                    if($size[1] / $size[0] >= $ratio) {
                        $src_h = round($src_w * $ratio);
                        $src_y = round(($size[1] - $src_h) / 2);
                    } else {
                        $src_w = round($size[1] / $ratio);
                        $src_x = round(($size[0] - $src_w) / 2);
                    }
                    break;
                default:
                    if($size[1] / $size[0] >= $ratio) {
                        $src_h = round($src_w * $ratio);
                    } else {
                        $src_w = round($size[1] / $ratio);
                    }
                    break;
            }

            $dst = imagecreatetruecolor($dst_w, $dst_h);

            if($size[2] == 3) {
                imagealphablending($dst, false);
                imagesavealpha($dst, true);
            } else if($size[2] == 1) {
                $palletsize = imagecolorstotal($src);
                if($src_transparency >= 0 && $src_transparency < $palletsize) {
                    $transparent_color   = imagecolorsforindex($src, $src_transparency);
                    $current_transparent = imagecolorallocate($dst, $transparent_color['red'], $transparent_color['green'], $transparent_color['blue']);
                    imagefill($dst, 0, 0, $current_transparent);
                    imagecolortransparent($dst, $current_transparent);
                }
            }
        } else { // 비율에 맞게 생성
            $dst = imagecreatetruecolor($dst_w, $dst_h);
            $bgcolor = imagecolorallocate($dst, 255, 255, 255); // 배경색
            if($src_w > $src_h) {
                $tmp_h = round(($dst_w * $src_h) / $src_w);
                $dst_y = round(($dst_h - $tmp_h) / 2);
                $dst_h = $tmp_h;
            } else {
                $tmp_w = round(($dst_h * $src_w) / $src_h);
                $dst_x = round(($dst_w - $tmp_w) / 2);
                $dst_w = $tmp_w;
            }

            if($size[2] == 3) {
                $bgcolor = imagecolorallocatealpha($dst, 0, 0, 0, 127);
                imagefill($dst, 0, 0, $bgcolor);
                imagealphablending($dst, false);
                imagesavealpha($dst, true);
            } else if($size[2] == 1) {
                $palletsize = imagecolorstotal($src);
                if($src_transparency >= 0 && $src_transparency < $palletsize) {
                    $transparent_color   = imagecolorsforindex($src, $src_transparency);
                    $current_transparent = imagecolorallocate($dst, $transparent_color['red'], $transparent_color['green'], $transparent_color['blue']);
                    imagefill($dst, 0, 0, $current_transparent);
                    imagecolortransparent($dst, $current_transparent);
                } else {
                    imagefill($dst, 0, 0, $bgcolor);
                }
            } else {
                imagefill($dst, 0, 0, $bgcolor);
            }
        }
    } else {
        $dst = imagecreatetruecolor($dst_w, $dst_h);
        $bgcolor = imagecolorallocate($dst, 255, 255, 255); // 배경색

        if($src_w < $dst_w) {
            if($src_h >= $dst_h) {
                if( $src_h > $src_w ){
                    $tmp_w = round(($dst_h * $src_w) / $src_h);
                    $dst_x = round(($dst_w - $tmp_w) / 2);
                    $dst_w = $tmp_w;
                } else {
                    $dst_x = round(($dst_w - $src_w) / 2);
                    $src_h = $dst_h;
                }
            } else {
                $dst_x = round(($dst_w - $src_w) / 2);
                $dst_y = round(($dst_h - $src_h) / 2);
                $dst_w = $src_w;
                $dst_h = $src_h;
            }
        } else {
            if($src_h < $dst_h) {
                if( $src_w > $dst_w ){
                    $tmp_h = round(($dst_w * $src_h) / $src_w);
                    $dst_y = round(($dst_h - $tmp_h) / 2);
                    $dst_h = $tmp_h;
                } else {
                    $dst_y = round(($dst_h - $src_h) / 2);
                    $dst_h = $src_h;
                    $src_w = $dst_w;
                }
            }
        }

        if($size[2] == 3) {
            $bgcolor = imagecolorallocatealpha($dst, 0, 0, 0, 127);
            imagefill($dst, 0, 0, $bgcolor);
            imagealphablending($dst, false);
            imagesavealpha($dst, true);
        } else if($size[2] == 1) {
            $palletsize = imagecolorstotal($src);
            if($src_transparency >= 0 && $src_transparency < $palletsize) {
                $transparent_color   = imagecolorsforindex($src, $src_transparency);
                $current_transparent = imagecolorallocate($dst, $transparent_color['red'], $transparent_color['green'], $transparent_color['blue']);
                imagefill($dst, 0, 0, $current_transparent);
                imagecolortransparent($dst, $current_transparent);
            } else {
                imagefill($dst, 0, 0, $bgcolor);
            }
        } else {
            imagefill($dst, 0, 0, $bgcolor);
        }
    }
    imagecopyresampled($dst, $src, $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h);

    // sharpen 적용
    if($is_sharpen && $is_large) {
        $val = explode('/', $um_value);
        UnsharpMask($dst, $val[0], $val[1], $val[2]);
    }

    if($size[2] == 1) {
        imagegif($dst, $thumb_file);
    } else if($size[2] == 3) {
        if(!defined('G5_THUMB_PNG_COMPRESS'))
            $png_compress = 5;
        else
            $png_compress = G5_THUMB_PNG_COMPRESS;

        imagepng($dst, $thumb_file, $png_compress);
    } else {
        if(!defined('G5_THUMB_JPG_QUALITY'))
            $jpg_quality = 90;
        else
            $jpg_quality = G5_THUMB_JPG_QUALITY;

        imagejpeg($dst, $thumb_file, $jpg_quality);
    }

    chmod($thumb_file, G5_FILE_PERMISSION); // 추후 삭제를 위하여 파일모드 변경

    imagedestroy($src);
    imagedestroy($dst);

    return basename($thumb_file);
}

?>