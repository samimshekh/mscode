<?php
namespace Logic\Router;
class Method
{
    public const GET = 'GET'; 
    public const POST = 'POST'; 
}

class baseRoute
{
    protected bool $grouping;
    protected bool $Grouping;
    protected Logic\App $App;
    protected string $ProcessorsName;
    protected string $ProcessorsFunctionName;
    protected string $GuardsName;
    protected string $GuardsFunctionName;
    protected ?string $GroupGuardsName;
    protected ?string $GroupGuardsFunctionName;
    protected array $HooksNames;
}

interface Routeinterface
{
    public function group($function, string $Guards = NULL);
    public function __construct(Logic\App $app); 
}

class Routes extends baseRoute implements Routeinterface 
{
    public function __construct(Logic\App $app) 
    {
        $this->App = $app;
    }

    public function group($function, ?string $Guards = NULL)
    {
        if ($Guards !== null)
        {
            preg_match('/^([a-zA-Z_][a-zA-Z0-9_]*)::([a-zA-Z_][a-zA-Z0-9_]*)$/', $call, $mach1);
            //throw new Exception("group call class aur fun  sahi nahi hai url=\"$url\", call=\"$call\"");
        } 
        $this->$grouping = true;
        $function();
        $this->$grouping = false;
    }
}

class Route
{
    private $Routes;
    public static function initialization(Logic\App $app) 
    {
        static::$Routes = new Routes($app);
    }

    public static function group($function, string $Guards = NULL)
    {
        
    }
}