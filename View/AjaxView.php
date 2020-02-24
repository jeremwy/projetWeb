<?php
require_once("iView.php");
/*
    Cette classe permet de retourner du contenu suite à une requète AJAX. Cette vu permet d'afficher
    la réponse sous plusieurs format (JSON, text/plain ...) sans affin un page complète du site (template avec header, footer ...).
*/
class AjaxView implements iView
{
    private $contenu;
    private $type; //JSON, text/plain ...

    public function __construct($contenu = "", $type = "")
    {
        $this->contenu = $contenu;
        $this->type = $type;
    }

    public function rend()
    {
        $header = "Content-type: ";
        switch($this->type)
        {
            case "json":
                $header .= "application/json";
                break;
            case "text":
                $header .= "text/plain";
                break;
            case "html":
                $header .= "text/html";
                break;
            case "pdf":
                $header .= "application/pdf";
                break;
            default:
                $header .= $this->type;
        }

        header($header);
        echo $this->contenu;
    }
}
?>