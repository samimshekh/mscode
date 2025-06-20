<?php
namespace Logic\Guards;
class Home 
{
    protected $secretkye;
    public function index() 
    {
        $this->secretkye = "13bffanbg1saaga1";
        echo "<pre>";
        print_r(get_object_vars($this));
        return true;
    }
}
