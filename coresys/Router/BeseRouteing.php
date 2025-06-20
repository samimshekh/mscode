<?php
declare(strict_types=1);
namespace Logic\Router;
use Settings\Paths;
use Logic\App;
class Method
{
    public const GET     = 'GET';
    public const POST    = 'POST';
    public const PUT     = 'PUT';
    public const DELETE  = 'DELETE';
    public const PATCH   = 'PATCH';
    public const OPTIONS = 'OPTIONS';
    public const HEAD    = 'HEAD';
    public const CONNECT = 'CONNECT';
    public const TRACE   = 'TRACE';

    /**
     * Return all supported methods
     */
    public static function all(): array
    {
        return [
            self::GET,
            self::POST,
            self::PUT,
            self::DELETE,
            self::PATCH,
            self::OPTIONS,
            self::HEAD,
            self::CONNECT,
            self::TRACE,
        ];
    }
}


class baseRoute
{
    protected App $App;
    protected bool $Grouping = false;
    protected ?string $ProcessorsName = null;
    protected ?string $ProcessorsFunctionName = null;
    protected ?string $GuardsName = null;
    protected ?string $GuardsFunctionName = null;
    protected ?string $GroupGuardsName = null;
    protected ?string $GroupGuardsFunctionName = null;
    /**
    * -------------------------------------------------------------
    * parameters: 
    * -------------------------------------------------------------
    *   string $str me (Namespace::Method) hoga yaniki (test\test::test) is tarah ka hoga
    *
    * -------------------------------------------------------------
    * return: 
    * -------------------------------------------------------------
    * return me false hoga agar $str sahi nahi hai ya return me [Namespace, Method] hoga string $str sahi hoaa do
    */
    protected function CheckNamespaceAndMethod(string $str) : array|bool {
        if (preg_match('/^'. '([a-zA-Z_][a-zA-Z0-9_]*(?:\\\\[a-zA-Z_][a-zA-Z0-9_]*)*)' . // Class name (with \ only)
            '::' .
            '([a-zA-Z_][a-zA-Z0-9_]*)' .'$/', $str, $Mach))
            return [$Mach[1], $Mach[2]];
        return false;
    }

    private function mUrlGetVar(string $murl) : bool|array  
    {

        $r = '/\(\s*' .
        '(int|str|fun|class)\s+' .                        // Type
        '([a-zA-Z_][a-zA-Z0-9_]*)\s*' .                   // Variable name
        '(?:=\s*' .
            '([a-zA-Z_][a-zA-Z0-9_]*(?:\\\\[a-zA-Z_][a-zA-Z0-9_]*)*)' . // Class name (with \ only)
            '::' .
            '([a-zA-Z_][a-zA-Z0-9_]*)' .                  // Method name
        ')?' .
        '\s*\)/';

        if (preg_match_all($r, $murl, $Mach, PREG_OFFSET_CAPTURE))
        {
            $setdata = array();
            foreach ($Mach[0] as $value) $setdata[] = ["type" => null, "name" => null, "hookName" => null, "hookMethod" => null, "start" => $value[1], "end" => strlen($value[0])];
            for ($i=0; $i < count($setdata); $i++) { 
                $setdata[$i]["type"]       = $Mach[1][$i][0];
                $setdata[$i]["name"]       = $Mach[2][$i][0];
                $setdata[$i]["hookName"]   = $Mach[3][$i][0];
                $setdata[$i]["hookMethod"] = $Mach[4][$i][0];
            }
            return $setdata;
        }
        return false;
    }

    private function VarTypeRegex(string $type) : string 
    {
        $types = [
            'int'     => '([0-9]+)',
            'str'  => '([a-zA-Z0-9]+)',
            'fun'     => '([a-zA-Z_][a-zA-Z0-9_]*)',
            'class'     => '([a-zA-Z_][a-zA-Z0-9_]*)',
        ];
        return $types[$type];
    }

    private function Regex(string $murl)
    {
        $r =  '/\(.*?\)/';
        $variable = [];
        $varlen = preg_match_all($r, $murl);
        if($varlen) 
        {
            if (($variable = $this->mUrlGetVar($murl)) !== false)
            {
                for ($i = count($variable) - 1; $i >= 0; $i--) {
                    $value = $variable[$i];
                    $murl = substr_replace(
                        $murl,
                        $this->VarTypeRegex($value["type"]),
                        $value["start"],
                        $value["end"]
                    );
                }
                if ($varlen != count($variable)) 
                    throw new \Exception("Syntax Error: samthing Missing rul ()");
            }else 
                throw new \Exception("Syntax Error: samthing Missing rul ()");
        }
        
        $murl = str_replace('/', '\/', $murl);
        $murl = "/^" . $murl . "$/";
        return [$murl, $variable];

    }


    protected function compiler(string $url, string $murl) : array|bool {
        [$Regex, $variable] = $this->Regex(trim($murl, '/'));
        
        if (preg_match($Regex, trim($url, '/'), $Match))
        {
            for ($i=1; $i < count($Match); $i++) $variable[($i - 1)]["urlValue"] = $Match[$i]; 

            return ($variable === false) ? array() : $variable;
        }
        return false;
    }
}

interface Routeinterface
{
    public function group($function, string $Guards = NULL);
    public function __construct(App $app); 
    public function Match(string $url, string $Processors, ?string $Guards = null) : bool|array;
}

class Routes extends baseRoute implements Routeinterface 
{
    private bool $group;
    public function __construct(App $app) 
    {
        $this->App = $app;
    }

    public function group($function, ?string $Guards = null)
    {
        if (($function instanceof Closure)) throw new \Exception("Invalid function");
        if ($Guards !== null)
        {
            if (($Guard = $this->CheckNamespaceAndMethod($Guards)) === false) throw new Exception("'$Guards' is not a valid PHP Guards name.");
            else{
                $this->GroupGuardsName = $Guard[0];
                $this->GroupGuardsFunctionName = $Guard[1];
            }
        } 
        $this->Grouping = true;
        $function();
        $this->Grouping = false;
    }

    public function Match(string $url, string $Processors, ?string $Guards = null) : array|bool
    {
        
        if (($Processors = $this->CheckNamespaceAndMethod($Processors)) === false) throw new Exception("'$Processors' is not a valid PHP Processors name.");
        else{
            $this->ProcessorsName = $Processors[0];
            $this->ProcessorsFunctionName = $Processors[1];
        }

        if ($Guards !== null)
        {
            if (($Guard = $this->CheckNamespaceAndMethod($Guards)) === false) throw new Exception("'$Guards' is not a valid PHP Guards name.");
            else{
                $this->GuardsName = $Guard[0];
                $this->GuardsFunctionName = $Guard[1];
            }
        } 
        return $this->compiler($this->App->Request->getUrl(), $url);
    }

    public function Method() : string
    {
        return  $this->App->Request->getMethod();
    }
    public function ifexists($Path, $pathError, $cls, $clsError, $Method, $MethodError)
    {
        if (!file_exists($Path)) 
            throw new \Exception($pathError);
        require_once $Path; 
        if (!class_exists($cls)) 
            throw new \Exception($clsError);
        $cls_ = new ($cls)();
        if (!method_exists($cls_, $Method)) 
            throw new \Exception($MethodError);
        $cls_->App = $this->App;
        return $cls_;
    }

    public function getusevar($obj)
    {
        $newobj= new \stdClass;
        foreach (get_object_vars($obj) as $key => $value) {
            if($key == "App") continue;
            $newobj->$key = $value;
        }
        return $newobj;
    }

    public function hooksProcessors(string $url, string $Processors, ?string $Guard = null, $variable)
    {
       $hooks = array();
        foreach ($variable as $value) {
            if ($value["hookName"] != null) 
            {
                $name = $value['hookName'];
                $hookMethod = $value["hookMethod"];
                $hookdata = $this->ifexists(
                    Paths::HOOKS_PATH . $name . ".php",
                    "Hook '$name' does not exist. Please check the hook name in mscode.",
                    ("Logic\\Hooks\\" . $name),
                    "Hook class 'Logic\\Hooks\\$name' does not exist in mscode.",
                    $hookMethod,
                    "The method '$hookMethod' was not found in hook class '$name'."
                );
                $hookdata->Type = $value["type"];
                $hookdata->{$value["name"]} = $value["urlValue"];
                $hookret = $hookdata->$hookMethod();
                if ($hookret === false) exit;
                
                $hooks[] = [
                    "hook" => $hookdata,
                    "name" => $value["name"]
                ];
            }
        }
        return $hooks;
    }

    public function groupProcessors(string $url, string $Processors, ?string $Guard = null, $hooks)
    {
        $group = new \stdClass;
        if (($this->Grouping) and ($this->GroupGuardsName)) {
            $group = $this->ifexists(
                Paths::GUARDS_PATH . $this->GroupGuardsName . ".php",
                "Group file '{$this->GroupGuardsName}' does not exist in mscode.",
                "Logic\\Guards\\" . $this->GroupGuardsName,
                "Group class '{$this->GroupGuardsName}' does not exist in mscode.",
                $this->GroupGuardsFunctionName,
                "Method '{$this->GroupGuardsFunctionName}' does not exist in class '{$this->GroupGuardsName}'."
            );
            $group->hooks = new \stdClass;
            foreach ($hooks as $value) {
                $group->hooks->{$value["name"]} = $this->getusevar($value["hook"]); 
            }
            $groupret = $group->{$this->GroupGuardsFunctionName}();
        }else
        {
            foreach ($hooks as $value) {
                $group->hooks = new \stdClass;
                $group->hooks->{$value["name"]} = $this->getusevar($value["hook"]); 
            }
        }
        return $group;
    }


    public function MainProcessors(string $url, string $Processors, ?string $Guard = null)
    {
        if (!$Processors) throw new Exception("'$Processors' is not a valid mscode Processors name.");
        $variable = $this->Match($url, $Processors, $Guard);
        if ($variable === false) return false;

        ob_start();
        $hooks = $this->hooksProcessors($url, $Processors, $Guard, $variable);
        if (($hooks === false) and ($this->App->Response->getBody() === [])) {
            $this->App->Response->setHtml(ob_get_clean());
            return true;
        }

        $group = $this->groupProcessors($url, $Processors, $Guard, $hooks);
        if (($group === false) and ($this->App->Response->getBody() === [])) {
            $this->App->Response->setHtml(ob_get_clean());
            return true;
        }
        
        $Guard = new \stdClass;
        if ($this->GuardsName) {
            $Guard = $this->ifexists(
                Paths::GUARDS_PATH . $this->GuardsName . ".php",
                "Guard file '{$this->GuardsName}.php' does not exist in mscode.",
                "Logic\\Guards\\" . $this->GuardsName,
                "Guard class 'Logic\\Guards\\{$this->GuardsName}' does not exist.",
                $this->GuardsFunctionName,
                "Guard method '{$this->GuardsName}::{$this->GuardsFunctionName}' does not exist."
            );       
            $Guard->group = $this->getusevar($group);
            $Guardret = $Guard->{$this->GuardsFunctionName}();
            if (($Guardret === false) and ($this->App->Response->getBody() === [])) {
                $this->App->Response->setHtml(ob_get_clean());
                return true;
            }
        }

        $Processor = $this->ifexists(
            Paths::PROCESSORS_PATH . $this->ProcessorsName . ".php",
            "Processor file '{$this->ProcessorsName}.php' does not exist in mscode.",
            "Logic\\Processors\\" . $this->ProcessorsName,
            "Processor class 'Logic\\Processors\\{$this->ProcessorsName}' does not exist in mscode.",
            $this->ProcessorsFunctionName,
            "Processor method '{$this->ProcessorsName}::{$this->ProcessorsFunctionName}' does not exist."
        );

        $Processor->Guard = $this->getusevar($Guard);
        $Processor->{$this->ProcessorsFunctionName}();
        if ($this->App->Response->getBody() === []) 
            $this->App->Response->setHtml(ob_get_clean());
        else 
            ob_end_clean();
        return true;
    }

    public function Processors(string $url, string $Processors, ?string $Guard = null)
    {
        if ($this->MainProcessors($url, $Processors, $Guard) !== false)
        {
            $this->App->Response->send();
            exit;
        } 
    }
}

class BeseRouteing
{
    private static Routes $Routes;
    private static App $App;

    public static function initialization(App $app) 
    {

        self::$App = $app;
        self::$Routes = new Routes($app);
    }

    final public static function group($function, ?string $Guards = NULL)
    {
        self::$Routes->group($function, $Guards);
    }

    final public static function get(string $url, string $Processors, ?string $Guard = null)
    {
        if (self::$Routes->Method() !== Method::GET) return;
        self::$Routes->Processors($url, $Processors, $Guard);
    }

    final public static function post(string $url, string $Processors, ?string $Guard = null)
    {
        if (self::$Routes->Method() !== Method::POST) return;
        self::$Routes->Processors($url, $Processors, $Guard);
    }

    final public static function put(string $url, string $Processors, ?string $Guard = null)
    {
        if (self::$Routes->Method() !== Method::PUT) return;
        self::$Routes->Processors($url, $Processors, $Guard);
    }

    final public static function delete(string $url, string $Processors, ?string $Guard = null)
    {
        if (self::$Routes->Method() !== Method::DELETE) return;
        self::$Routes->Processors($url, $Processors, $Guard);
    }

    final public static function patch(string $url, string $Processors, ?string $Guard = null)
    {
        if (self::$Routes->Method() !== Method::PATCH) return;
        self::$Routes->Processors($url, $Processors, $Guard);
    }

    final public static function options(string $url, string $Processors, ?string $Guard = null)
    {
        if (self::$Routes->Method() !== Method::OPTIONS) return;
        self::$Routes->Processors($url, $Processors, $Guard);
    }

    final public static function head(string $url, string $Processors, ?string $Guard = null)
    {
        if (self::$Routes->Method() !== Method::HEAD) return;
        self::$Routes->Processors($url, $Processors, $Guard);
    }

    final public static function connect(string $url, string $Processors, ?string $Guard = null)
    {
        if (self::$Routes->Method() !== Method::CONNECT) return;
        self::$Routes->Processors($url, $Processors, $Guard);
    }

    final public static function trace(string $url, string $Processors, ?string $Guard = null)
    {
        if (self::$Routes->Method() !== Method::TRACE) return;
        self::$Routes->Processors($url, $Processors, $Guard);
    }

    final public static function match(array $methods, string $url, string $Processors, ?string $Guard = null)
    {
        if (!in_array(self::$Routes->Method(), $methods, true)) return;
        self::$Routes->Processors($url, $Processors, $Guard);
    }

    final public static function all(string $url, string $Processors, ?string $Guard = null)
    {
        self::$Routes->Processors($url, $Processors, $Guard);
    }
}