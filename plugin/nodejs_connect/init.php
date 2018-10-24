<?php

require_once dirname(__FILE__) . '/config.php';

?>

<script src='http://<?php echo $nc_config['server_ip'];?>:<?php echo $nc_config['server_port'];?>/socket.io/socket.io.js'></script>
<script>

var serverUrl = 'http://<?php echo $nc_config['server_ip'];?>:<?php echo $nc_config['server_port'];?>';
var socket = io.connect(serverUrl);

</script>
