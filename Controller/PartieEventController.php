<?php
require_once("Controller.php");
require_once("Model/PartieManager.php");
require_once("Model/Class/Partie.php");
require_once("View/AjaxView.php");

/*
    Classe qui gère les évènements liés à une partie (ajout de personnages ..)
*/
class PartieEventController extends Controller
{
    /*
        Cette méthode fait office d'horloge. Le maître du jeu envoie une requète Ajax touts les X secondes à cette méthode
        et elle s'occupe d'appeler les traitements à effectuer sur la partie (déplacements ...).
        Elle renvoie 1 en cas de succès et 0 sinon.
    */
    public function horloge()
    {
        if(parent::isConnected() && parent::isInPartie())
        {
            $view = new AjaxView("1", "text");
            return $view;
        }
        else
        {
            $dReponse["title"] = "Page introuvable";
            return new View("Error/404.html", $dReponse);
        }
    }
}