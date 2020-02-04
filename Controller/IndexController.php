<?php

class IndexController
{
    public static function index()
    {
        $dReponse["title"] = "Bienvenue";
        return new View("Index/index.php", $dReponse);
    }

    public static function test()
    {
        $dReponse = ["message" => "ok", "title" => "ok"];        
        return new View("Index/test.php", $dReponse);
    }
}
?>