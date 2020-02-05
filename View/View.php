<?php
class View
{
    private $dReponse;

    public function __construct($viewName, $dReponse = [])  //$viewName est de la forme "Index/index.html" ce qui désigne la vue "index.html" du dossier "Index"
    {
        $viewFilename = "View/" . $viewName;
        if(file_exists($viewFilename))
        {
            $this->viewFilename = $viewFilename; 
            $this->dReponse = $dReponse;
            $this->dReponse["viewFileName"] = $viewFilename;
            $this->init();                  
        }
        else
        {
            echo "ERREUR : Le fichier de la vue " . $viewFilename . " n'existe pas.";
        }
    }

    private function init()
    {
        $this->dReponse["style"][0] = "http://localhost/PROJETWEB/src/css/style.css";
        $this->dReponse["font"][0] ="https://fonts.googleapis.com/css?family=Bowlby+One+SC|Poppins|Roboto:300&display=swap";
    }

    public function rend()
    {
        $dReponse = $this->dReponse;
        include_once("View/Includes/template.php");
    }
}
?>