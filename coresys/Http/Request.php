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
    
    function decode(): array
    {
        $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
        $rawBody = file_get_contents("php://input");

        if (stripos($contentType, 'application/json') !== false) {
            return json_decode($rawBody, true) ?? [];
        }

        if (stripos($contentType, 'xml') !== false) {
            return $this->xmlDecode($rawBody);
        }

        if (stripos($contentType, 'application/x-www-form-urlencoded') !== false) {
            parse_str($rawBody, $parsed);
            return $parsed;
        }

        return [];
    }


    private function xmlDecode(string $xml): array
    {
        libxml_use_internal_errors(true);
        $sxe = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
        if ($sxe === false) return [];

        return json_decode(json_encode($sxe), true);
    }
}