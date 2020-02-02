<?php
require_once("View/View.php");

class IndexController
{
    public static function index()
    {
        $dResponse["title"] = "Bienvenue";
        return new View("Index/index.php", $dResponse);
    }

    public static function test()
    {
        $dResponse = ["message" => "ok", "title" => "ok"];        
        return new View("Index/test.php", $dResponse);
    }
}
?>