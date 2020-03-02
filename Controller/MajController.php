<?php
require_once("Controller.php");
require_once("View/AjaxView.php");
require_once("View/View.php");

class MajController extends Controller
{
    public static function index()
    {

        if(parent::isConnected())
        {
            if(isset($_SESSION["partie"]["roles"]) && in_array("maitre", $_SESSION["partie"]["roles"]))
            {
                //traitements
                return new AjaxView("ok c'est bon", "text");
            }
        }
        $dReponse["title"] = "Page introuvable";
        $view = new View("Error/404.html", $dReponse);
        return $view;
    }
}