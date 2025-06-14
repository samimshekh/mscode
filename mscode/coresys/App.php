<?php
namespace Logic;
use Logic\Http\Request;
use Logic\Http\Response;

class App 
{
    private $Request;
    private $Response;

    function __construct() 
    {
        $this->Request = new Request;
        $this->Response = new Response;
    }

    function run() 
    {
        
    }
}