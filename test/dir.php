<?php
/**
 * Created by PhpStorm.
 * User: zhangqiushi
 * Date: 17/4/20
 * Time: 下午9:42
 */
$re = opendir('/private/var/www/Pcs/test');
$type = pathinfo('/private/var/www/Pcs/test/class.php',PATHINFO_EXTENSION);
var_dump($type);
/*while ($file !== false) {
    $file = readdir($re);
    var_dump($file);
}*/