<?php
namespace Settings;
use Logic\Router\BeseRouteing;
class Route extends BeseRouteing 
{
    public static bool|string $error404Processors = false;
}
