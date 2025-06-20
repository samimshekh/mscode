<?php
namespace Rep;

use Logic\Repository\DB;

class News 
{
    public static function getdata(string $name)
    {
        $sql = "SELECT * FROM `$name`";
        $result = DB::query($sql);

        if (!$result) {
            throw new \Exception("Query failed: " . DB::get_error());
        }

        return $result->fetch_all(MYSQLI_ASSOC);
    }
}