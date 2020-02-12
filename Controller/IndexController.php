<?php

class IndexController extends Controller
{
    public static function index()
    {
        $dReponse["title"] = "Bienvenue";
        if(isset($_SESSION["user"]))
        {
            return new View("Index/index.php", $dReponse);
        }
        else
        {
            return new View("User/login.php", $dReponse);
        }
    }
}
?>