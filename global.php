<?php

session_start();
//For development
ini_set("display_errors", 1);
error_reporting(E_ALL ^ E_NOTICE);
/*
 * Change to this for production use. Not that any of this should be used in production.
 * ini_set("display_errors", 0);
 * error_reporting(0);
 */

define("C", "Classes/");
require_once(C . "Database.php");
require_once(C . "Users.php");
