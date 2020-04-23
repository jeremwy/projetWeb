<?php
require_once("Controller.php");
require_once("Model/PartieManager.php");
require_once("Model/VictimeManager.php");
require_once("Model/VoitureManager.php");
require_once("Model/Class/Partie.php");
require_once("Model/Class/Voiture.php");
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
            $victimeManager = new VictimeManager();
            $victimes = $victimeManager->getVictimes($_SESSION["partie"]->getId());
            foreach($victimes as $victime)
            {
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
        Permet d'ajouter un objets (véhicule ...) dans la base de données.
        Le serveur reçoit une requête ajax pour indiquer ce que l'utilisateur souhaite ajouter.
        Le serveur vérifie ensuite si cela est possible et renvoie le résultat (0 ou 1).
    */
    public static function ajout()
    {
        //si l'utilisateur n'eset ps connecté ou n'est pas dans une partie alors on renvoie un page 404 erreur.
        //de même si les variables POST nécessaires ne sont pas envoyées ou vides.
        if(!parent::isConnected() || !parent::isInPartie() || !(isset($_POST["objet"]) && isset($_POST["fonction"]) && isset($_POST["x"]) && isset($_POST["y"]) && isset($_POST["z"])) || $_POST["objet"] == "" || $_POST["fonction"] == "" || $_POST["x"] == "" || $_POST["y"] == "" || $_POST["z"] == "")
        {
            $dReponse["title"] = "Page introuvable";
            return new View("Error/404.html", $dReponse);
        }
        else
        {   
            //on convertit les coordonnées en entiers (les variables POST contiennent des chaînes de caractères)
            $x = intval($_POST["x"]);
            $y = intval($_POST["y"]);
            $z = intval($_POST["z"]);
            if($_POST["objet"] == "Voiture")    //si l'utilisateur souhaite ajouter une voiture
            {
                //on crée un nouvelle voiture et on l'ajoute à la base
                $voiture = new Voiture(null, $_SESSION["partie"]->getId(), $_POST["fonction"], $x, $y, $z);
                $voitureManager = new VoitureManager($voiture);
                $result = $voitureManager->save();
                if($result)
                {
                    self::ajoutEvenement("ajoutVoiture", "Ajout d'une voiture de " . $_POST["fonction"]);
                    return new AjaxView("1", "text");
                }
                else
                    return new AjaxView("0", "text");
            }
            return new AjaxView("0", "text");
        }
    }

    /*
        Ajout un événenement "$evenement" (décrit par "$description") se produisant à l'instant t au fichier historique de la partie.
    */
    private static function ajoutEvenement($evenement, $description)
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