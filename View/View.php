<?php
require_once("iView.php");
class View implements iView
{
    private $dReponse;

    public function __construct($viewName, $dReponse = [])  //$viewName est de la forme "Index/index.html" ce qui désigne la vue "index.html" du dossier "Index"
    {
        $viewFilename = "View/" . $viewName;
        if(file_exists($viewFilename))
        {
            $this->dReponse = $dReponse;
            $this->dReponse["viewFileName"] = $viewFilename;
            $this->init();                  
        }
        else
        {
            echo "ERREUR : Le fichier de la vue " . $viewFilename . " n'existe pas.";
        }
    }

    public function init()
    {
        if(!isset($_SESSION["theme"]) || ($_SESSION["theme"] != "noir" && $_SESSION["theme"] != "blanc"))
            $this->dReponse["style"][0] = SITE_ROOT . "src/css/theme_noir.css";
        else
            $this->dReponse["style"][0] = SITE_ROOT . "src/css/theme_" . $_SESSION["theme"] . ".css";
        $this->dReponse["style"][1] = SITE_ROOT . "src/css/main.css";
        $this->dReponse["font"][0] ="https://fonts.googleapis.com/css?family=Bowlby+One+SC|Poppins|Roboto:300&display=swap";
    }

    public function rend()
    {
        $dReponse = $this->dReponse;
        include_once("View/Includes/template.php");
    }
}
?>