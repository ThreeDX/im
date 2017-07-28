<?php
/*function microtime_float()
{
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}
$time_start = microtime_float();*/

ob_start("ob_gzhandler");

require_once "./config.php";

$pages = array("AdminOrders", "AdminUsers", "AdminCategories", "AdminItems", "Account", "AdminLogs"); // Allowed pages

$core = new Core($pages, "AdminOrders");
$core->process();
ob_end_flush();

/*$time_end = microtime_float();
$time = $time_end - $time_start;
echo $time;
*/