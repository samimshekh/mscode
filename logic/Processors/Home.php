<?php
namespace Logic\Processors;
//$this->Guard->group->hooks->hookName->type;
//$this->App;
class Home 
{
    public function index()      
    {
        view("Welcome", ["title"=> "Welcome page!.."]);
    }

    public function home()      
    {
        $this->id3 = 2;
        echo "<pre>";
        print_r(get_object_vars($this));
    }
}