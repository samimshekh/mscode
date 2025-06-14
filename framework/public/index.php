<?php
define('BASE_PATH', __DIR__ . "\\..\\");

require_once BASE_PATH . "logic\\Settings\\Paths.php";
require_once BASE_PATH . "vendor\\autoload.php";

//use Logic\App;
//view("Welcome", array("data"=>["title"=>"Welcome Page!"]));
//use Logic\Boot;
echo "<pre>";
print_r($_SERVER);