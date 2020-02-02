<?php
class View
{
    private $dResponse;

    public function __construct($viewName, $dReponse = [])  //$viewName est de la forme "Index/index.html" ce qui désigne la vue "index.html" du dossier "Index"
    {
        $viewFilename = "View/" . $viewName;
        if(file_exists($viewFilename))
        {
            $this->viewFilename = $viewFilename; 
            $this->dResponse = $dReponse;
            $this->dResponse["viewFileName"] = $viewFilename;
            $this->init();                  
        }
        else
        {
            echo "ERREUR : Le fichier de la vue " . $viewFilename . " n'existe pas.";
        }
    }

    private function init()
    {
        $this->dResponse["style"][0] = "http://localhost/PROJETWEB/src/css/styleConnexion.css";
        $this->dResponse["font"][0] ="https://fonts.googleapis.com/css?family=Poppins&display=swap";
        $this->dResponse["font"][1] ="https://fonts.googleapis.com/css?family=Bowlby+One+SC&display=swap";
        $this->dResponse["js"][0] = "test.js";
    }

    public function rend()
    {
        $dResponse = $this->dResponse;
        include_once("View/Includes/template.php");
    }
}
?>