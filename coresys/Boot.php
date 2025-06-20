<?php
namespace Logic;
use Logic\App;
use Settings\Repository;
use Logic\Repository\DB;
use Logic\Router\BeseRouteing;

class Boot
{
    private App $App;
    function __construct()
    {
       $this->App = new App;
    }

    public function boot()
    {
        BeseRouteing::initialization($this->App);
        if (Repository::$use) 
            DB::initialization(Repository::$host, Repository::$username, Repository::$password, Repository::$dbname);
    }

    function run() 
    {
        $this->App->run();
    }

    function send()
    {
        $this->App->Response->send();
    }
}