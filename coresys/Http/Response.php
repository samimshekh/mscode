<?php
namespace Logic\Http;

class Response 
{
    private array $body = [];
    private array $headers = [];
    private $type = 'ALL';
    private ?string $rootNode = null;
    private int $response_code = -1;
    public function setresponseCode(int $code) : self
    {
        $this->response_code = $code;
        return $this;
    } 

    public function send(): void
    {
        if ($this->response_code !== -1) http_response_code($this->response_code);
        $this->sendHeaders();
        $this->sendBody();
    }

    public function getBody(): array
    {
        return $this->body;
    }

    private function sendHeaders(): void
    {
        if (headers_sent($file, $line)) {
            echo "Headers already sent in $file on line $line";
            return;
        }

        foreach ($this->headers as $name => $value) {
            header("{$name}: {$value}");
        }
    }

    private function sendBody(): void
    {
        if ($this->type === "HTML" ) {
            foreach ($this->body as $value) {
                echo $value;
            }
        }elseif ($this->type === "JSON")
        {
            echo json_encode($this->body, JSON_PRETTY_PRINT);
        }elseif ($this->type === "XML")
        {
            echo $this->xmlEncode($this->body, $this->rootNode);
        }
    }

    public function header(string $name, string $value): self
    {
        $this->headers[$name] = $value;
        return $this;
    }

    public function setHtml(string $html, bool $flag = false): self
    {
        if (!$flag)
            if ($this->type != "HTML" and $this->type != "ALL") return $this;

        if (($this->body === []) or $flag) 
        {
            $this->header('Content-Type', 'text/html');
            $this->type = "HTML";
        }
        $this->body[] = $html;
        return $this;
    }

    public function setJson(array $data, bool $flag = false): self
    {
        if (!$flag) 
            if ($this->type != "JSON" and $this->type != "ALL") return $this;
        if (($this->body === []) or $flag) 
        {
            $this->header('Content-Type', 'application/json');
            $this->type = "JSON";
        }
        $this->body = array_merge_recursive($this->body, $data);
        return $this;
    }

    public function setXml(array $data, bool $flag = false, string $rootNode = 'response', ): self
    {
        if (!$flag) 
            if (($this->type != "XML" and $this->type != "ALL")) return $this;

        if (($this->body === []) or $flag) 
        {
            $this->header('Content-Type', 'application/xml');
            $this->type = "XML";
            $this->rootNode = $rootNode;
        }
        $this->body = array_merge_recursive($this->body, $data);
        if (($this->rootNode === null)) $this->rootNode = $rootNode;
        return $this;
    }

    public function setXhtmlxml(array $data, bool $flag = false, string $rootNode = 'html'): self
    {
        if (!$flag) 
            if (($this->type != "XML" and $this->type != "ALL")) return $this;
        if (($this->body === []) or $flag) 
        {
            $this->header('Content-Type', 'application/xhtml+xml');
            $this->type = "XML";
            $this->rootNode = $rootNode;
        }
        $this->body = array_merge_recursive($this->body, $data);
        if (($this->rootNode === null)) $this->rootNode = $rootNode;
        return $this;
    }

    public function setXhxml(array $data, bool $flag = false, string $rootNode = 'hxml'): self
    {
        if (!$flag) 
            if (($this->type != "XML" and $this->type != "ALL")) return $this;

        if (($this->body === []) or $flag) 
        {
            $this->header('Content-Type', 'application/x-hxml');
            $this->type = "XML";
            $this->rootNode = $rootNode;
        }
        $this->body = array_merge_recursive($this->body, $data);
        if (($this->rootNode === null)) $this->rootNode = $rootNode;
        return $this;
    }

    public function cleneBody()
    {
        $this->body = [];
        $this->type = "ALL";
        $this->rootNode = null;
        return $this;
    }

    public function cleneHeader()
    {
        if (isset($this->headers["Content-Type"]))
        {
            $s = $this->headers["Content-Type"];
            $this->headers = [];
            $this->headers["Content-Type"] = $s;
        }else $this->headers = [];
        return $this;
    }


    private function xmlDecode(string $xml): array
    {
        libxml_use_internal_errors(true);
        $sxe = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
        if ($sxe === false) return [];

        return json_decode(json_encode($sxe), true);
    }

    private function json_decode(string $json, ?bool $associative = null, int $depth = 512, int $flags = 0): mixed
    {
        return json_decode($json, $associative, $depth, $flags);
    }

    private function xmlEncode(array $data, string $rootNode = 'response'): string
    {
        $xml = new \SimpleXMLElement("<{$rootNode}/>");
        $this->arrayToXml($data, $xml);
        return $xml->asXML();
    }

    private function arrayToXml(array $data, \SimpleXMLElement &$xml): void
    {
        foreach ($data as $key => $value) {
            $key = is_numeric($key) ? 'item' : $key;

            if (is_array($value)) {
                $sub = $xml->addChild($key);
                $this->arrayToXml($value, $sub);
            } else {
                $xml->addChild($key, htmlspecialchars((string) $value));
            }
        }
    }
}
