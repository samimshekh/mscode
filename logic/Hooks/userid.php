<?php
namespace Logic\Hooks;
// /(int id=userid::id) int this->id name se var mile ga   
class userid  
{
    public function id() : bool
    {
        echo "<pre>";
        print_r(get_object_vars($this));
        return true;
    }   
}
