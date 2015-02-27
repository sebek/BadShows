<?php

date_default_timezone_set("Europe/Stockholm");

/**
 * The simplest of bootstraping
 * Using composers autoload and then we'll go directly to the routes
 */

$basePath = dirname(dirname(__FILE__));


require $basePath . "/vendor/autoload.php";
require $basePath . "/app/routes.php";
