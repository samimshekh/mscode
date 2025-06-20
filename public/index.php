<?php
define('BASE_PATH', __DIR__ . "\\..\\");
require_once BASE_PATH . "logic\\Settings\\Paths.php";

if (file_exists(BASE_PATH . "vendor\\autoload.php")) require_once BASE_PATH . "vendor\\autoload.php";
else 
{
    throw new Exception(BASE_PATH . "vendor\\autoload.php" . "does not exist in mscode.", 1);
    
}
use Logic\Boot;
$system = new Boot;
$system->boot();
$system->run();
$system->send();