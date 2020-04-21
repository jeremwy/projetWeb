<?php
require_once("Controller.php");
require_once("Model/PartieManager.php");
require_once("Model/Class/Partie.php");
require_once("View/AjaxView.php");
require_once("vendor/XMLPartieHistorique.php");

/*
    Classe qui gère les évènements liés à une partie (ajout de personnages, horloge ...)
*/
class PartieEventController extends Controller
{
    /*
        Cette méthode fait office d'horloge. Le maître du jeu envoie une requète Ajax touts les X secondes à cette méthode
        et elle s'occupe d'appeler les traitements à effectuer sur la partie (déplacements ...).
        Elle renvoie 1 en cas de succès et 0 sinon.
    */
    public static function horloge()
    {
        if(parent::isConnected() && parent::isInPartie())
        {
            //on ajoute 1 seconde à l'horloge de la partie dans la base de données.
            $manager = new PartieManager();
            $manager->ajoutHorloge(1);
            $view = new AjaxView("1", "text");
            return $view;
        }
        else
        {
            $dReponse["title"] = "Page introuvable";
            return new View("Error/404.html", $dReponse);
        }
    }

    /*
        Ajout un événenement "$evenement" (décrit par "$description") se produisant à l'instant "$temps" au fichier historique de la partie.
    */
    private static function ajoutEvenement($evenement, $description, $temps)
    {
        /*
            L'horloge est mise à jour de manière périodique par le maître du jeu (requête AJAX).
            Ainsi, l'horloge change dans la base de données mais pas dans la session des clients (car ils ne récupère pas la nouvelle valeur de l'horloge une fois que'elle est mise à jour).
            Il faut donc récupérer la valeur de l'horloge avant d'ajouter un événement à l'historique.
        */
        if(parent::isConnected() && parent::isInPartie())
        {
            $manager = new PartieManager();
            $horloge = $manager->getHorloge();
            $xml_partie = new XMLPartieHistorique($_SESSION["partie"]);
            $xml_partie->ajoutEvenement($evenement, $description, $horloge);
        }
        else
        {
            $dReponse["title"] = "Page introuvable";
            return new View("Error/404.html", $dReponse);
        }
    }



    //fonctions de test (à supprimer):
    
    public static function test()
    {
        $xml_partie = new XMLPartieHistorique($_SESSION["partie"]);
        self::ajoutEvenement("ajout", "Ajout d'un véhicule de police aux coordonnées X, Y, Z.", 654);
        die();
    }
}