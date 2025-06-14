<?php
namespace Logic\Http;
class Response 
{
    private $body;
    function send() 
    {
        $this->sendBody();
    }

    function sendBody()
    {
        echo $this->body;         
    }

    function setBody(string $body) 
    {
        $this->body = $body;
    }
}