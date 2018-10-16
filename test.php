<?php
include_once ("./common.php");
include_once (G5_MOBILE_PATH."/head.login.php");
?>
<form action="http://222.122.203.143:3000" method="post" name="form">
    <input type="submit" value="이동">
</form>
<?php
include_once (G5_MOBILE_PATH."/tail.view.php");
?>

SELECT * FROM flug48.product;


select *,IF(p.pd_lat != '' and p.pd_lng != '', 6371 * 2 * ATAN2(SQRT(POW(SIN(RADIANS(36.6231776 - p.pd_lat)/2), 2) + POW(SIN(RADIANS(127.51288551 - p.pd_lng)/2), 2) * COS(RADIANS(p.pd_lat)) * COS(RADIANS(36.6231776))), SQRT(1 - POW(SIN(RADIANS(36.6231776 - p.pd_lat)/2), 2) + POW(SIN(RADIANS(127.51288551 - p.pd_lng)/2), 2) * COS(RADIANS(p.pd_lat)) * COS(RADIANS(36.6231776)))) AS distance, '거리정보 없음' as distance) from `product_pricing` as c left join `product` as p on c.pricing_pd_id = p.pd_id where c.pd_id = '{$pd_id}' order by c.sign_date asc;