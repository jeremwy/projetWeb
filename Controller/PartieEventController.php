<?php
require_once("Controller.php");
require_once("Model/PartieManager.php");
require_once("Model/VictimeManager.php");
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
        if(parent::isConnected() && parent::isInPartie() && isset($_POST["vitesse"]) && !empty($_POST["vitesse"]))
        {
            $vitesse = intval($_POST["vitesse"]);   //la valeur peut être 1, 2 ou 4 (correspond à l'accélération du temps fixée par le maître du jeu)
            if($vitesse !== 1 && $vitesse !== 2 && $vitesse !== 4)
                return new AjaxView("0", "text");

            //on ajoute $vitesse seconde(s) à l'horloge de la partie dans la base de données.
            $manager = new PartieManager();
            $manager->ajoutHorloge($vitesse);
            var_dump($manager->getHorloge());
            $victimeManager = new VictimeManager();
            $victimes = $victimeManager->getVictimes($_SESSION["partie"]->getId());
            foreach($victimes as $victime)
            {
                var_dump($victime->getVie());
                if($victime->getVie() > 0)
                    $victime->setVie($victime->getVie() - 1.2 * $victime->getEtat() * $vitesse);   //modifier le ration si besoin
            }
            $victimeManager->updateVictimesVie($victimes);
            return new AjaxView("1", "text");
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