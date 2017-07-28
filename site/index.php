<?php
ob_start("ob_gzhandler");

require_once "./config.php";

$pages = array("Index", "Account", "Item", "Cart", "Order"); // Allowed pages

$core = new Core($pages, "Index");
$core->process();

ob_end_flush();