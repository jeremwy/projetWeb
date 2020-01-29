<?php
require_once("View/View.php");

class IndexController
{
    public static function index()
    {
        return new View("Index/index.html");
    }

    public static function test()
    {
        return new View("Index/test.html");
    }
}
?>