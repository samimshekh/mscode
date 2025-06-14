<?php
namespace Logic\Http;
class Request 
{
    private $url;
    private $RequestMethod;
    private $Encoding;
    private $Language;

    public function __construct() 
    {
        if (isset($_SERVER["PATH_INFO"])) {
            $this->url = $_SERVER["PATH_INFO"];
        }else{
            $this->url = '/';
        };

        $this->RequestMethod = $_SERVER["REQUEST_METHOD"];
    }

    public function getUrl() : string {
        return $this->url;
    }

    public function getMethod() : string {
        return $this->RequestMethod;
    }
}