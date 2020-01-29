<?php
class View
{
    private $content;

    public function __construct($viewName)  //$viewName est de la forme "Index/index.html" ce qui désigne la vue "index.html" du dossier "Index"
    {
        $viewFilename = "View/" . $viewName;
        if(file_exists($viewFilename))
        {
            $this->content = file_get_contents($viewFilename);
        }
        else
        {
            echo "ERREUR : Le fichier de la vue " . $viewFilename . " n'existe pas.";
        }
    }

    public function rend()
    {
        echo $this->content;
    }
}
?>