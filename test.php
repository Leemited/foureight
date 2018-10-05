<?php
include_once ("./common.php");
include_once (G5_MOBILE_PATH."/head.login.php");
?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.1.1/socket.io.js"></script>
<script src="<?php echo G5_URL?>/client.js"></script>

<div>
    <input type="text" id="test">
    <button onclick="send();">전송</button>
</div>
<?php
include_once (G5_MOBILE_PATH."/tail.view.php");
?>

