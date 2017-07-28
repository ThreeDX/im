<?php

//For production
//@ini_set('display_errors',0);
//error_reporting(0);
//@ini_set('display_errors',1);
//error_reporting(1);

header('Content-Type: text/html; charset=utf-8');

/*-----------------------------------------------------------------
| Config
------------------------------------------------------------------*/
define("THREED", 	    true);
define("TEMPLATES_DIR", "./templates/");
date_default_timezone_set("Europe/Moscow");
//define("DB_CONNECT_STRING", "host=localhost dbname=twi2_sql user=postgres password=330117 options='--client_encoding=UTF8'");
define("DB_CONNECT_STRING", "host=localhost dbname=new_db user=postgres password=753159 options='--client_encoding=UTF8'");

/*-----------------------------------------------------------------
| Classes
------------------------------------------------------------------*/
require_once("./classes/SesHelper.php");
require_once("./classes/DBWorker.php");
require_once("./classes/Template.php");
require_once("./classes/Controller.php");
require_once("./classes/Core.php");

// Autoload classes
spl_autoload_register(function($name) {
    include "./classes/".$name.".php";
});