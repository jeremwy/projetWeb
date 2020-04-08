<?php
require_once("Model/PartieManager.php");
require_once("Model/Class/Partie.php");
require_once("View/AjaxView.php");

/*
Cette classe est utilisée pour gérer les traitements relatifs à une partie (selection de rôles, lancer la partie ...).
Pour le déroulement d'une partie (ajout de personnages ...) voir la classe PartieEventController
*/
class PartieController extends Controller
{
    public static function index()
    {
        //force la connnexion
        $view = parent::needToConnect();
        if($view != NULL) return $view;
        
        parent::cleanPartie();
        //si le joueur n'est dans aucune partie
        if(!parent::isInPartie())
        {
            $dReponse["title"] = "Redirection sélection d'une partie";
            $dReponse["message"] = "Vous n'êtes dans aucune partie, redirection vers la sélection d'une partie.";
            return new RedirectView("Message.php", SITE_ROOT . "jouer", 5, $dReponse);
        }
        
        $manager = new PartieManager();
        $partie = $manager->getPartie($_SESSION["partie"]->getId());
        if($partie && $partie->isEnCours())
        {
            $dReponse["title"] = "Jouer";
            $dReponse["js"][0] = "chat.js";
            return new View("Partie/plateauPartie.php", $dReponse);
        }
        else
        {
            $dReponse["title"] = "Par non lancée";
            $dReponse["message"] = "La partie n'est pas encore lancée, merci d'attendre que le maître du jeu la lance.";
            return new View("Message.php", $dReponse);
        }       
    }

    public static function loby()
    {
        parent::cleanPartie();
        //force la connnexion
        $view = parent::needToConnect();
        if($view != NULL) return $view;

        if(!isset($_GET["id"]) || empty($_GET["id"]))
        {
            /*
                Il se peut que l'utilisateur tente d'accéder à une partie qui n'existe plus (Ex: le maitre à quitter et la partie est supprimée).
                Il faut alors vider la session de l'utilisateur si la partie dans laquelle il est n'existe plus.
            */
            $dReponse["title"] = "Page introuvable";
            return new View("Error/404.html", $dReponse);
        }

        //on vérifie si l'utilisateur n'est pas déjà dans une partie
        $idPartie = $_GET["id"];
        if(parent::isInPartie() && $_SESSION["partie"]->getId() != $idPartie)
        {
            $dReponse["title"] = "Impossible de rejoindre cette partie";
            $dReponse["message"] = "Impossible de rejoindre cette partie car vous êtes déjà dans une autre partie.";
            return new View("Message.php", $dReponse);
        }
        else
        {
            $manager = new PartieManager();
            //si l'identifiantest bien utilisé alors on laisse aller sur la page sinon, message d'erreur
            if($manager->isIdUsed($idPartie))
            {
                $dReponse["title"] = htmlspecialchars($idPartie);
                $dReponse["js"][0] = "selectionBouton.js";
                $dReponse["js"][1] = "chat.js";
                return new View("Partie/loby.php", $dReponse);
            }
            else
            {
                $dReponse["title"] = "Partie introuvable";
                $dReponse["message"] = "La partie est introuvable.";
                return new View("Message.php", $dReponse);
            }
        }
    }

    public static function selectRole()
    {
        //si l'utilisateur ,'est pas connecté ou qu'aucun identifiant de partie n'est envoyé
        if(!parent::isConnected() || !isset($_POST["partieId"]) || empty($_POST["partieId"]))
        {
            $dReponse["title"] = "Page introuvable";
            return new View("Error/404.html", $dReponse);
        }
        //si l'utilisateur est déjà dans une partie dont l'identifiant ne correspond pas à celui envoyé alors on retoune 0
        if(parent::isInPartie() && $_SESSION["partie"]->getId() != $_POST["partieId"])
        {
            return new AjaxView("0", "text");
        }

        //si un rôle a été envoyé
        if(isset($_POST["role"]) && !empty($_POST["role"]))
        {
            $roles = array("maitre", "chefPompier", "chefPolicier", "chefMedecin"); //tableau qui contient tous les rôles.
            $roleChoisi = $_POST["role"];
            //on vérifie si le rôle choisi est bien dans le tableau des rôles.
            if(in_array($roleChoisi, $roles))
            {
                $manager = new PartieManager();
                $user = parent::getUser();

                /*
                    On met à jour la base de données (ajout du joueur au rôle spécifique de la partie).
                    Le test pour savoir si l rôle est libre est fait de manière automatique dans la recherche SQL.
                    Si "$result" vaut faux c'est que l'utilisateur ne peut pas avoir le rôle car il est déjà pris
                */
                $result = $manager->addRole($roleChoisi, $user->getId(), $_POST["partieId"]);
                
                /*
                    Si le rôle n'est pas libre ("$result" == false), on retournera 0.
                    L'utilisateur n'est pas censé pouvoir cliquer sur un bouton d'un rôle déjà séléctionné (voir JS)
                    Si le rôle a peu être ajouté, on sauvegarde la partie dans la session de l'utilisateur.
                */
                if($result)
                {
                    /*
                        Après la mise à jour de la base de données avec l'ajout du rôle, il faut mettre à jou la session de l'utilisateur.
                        Pour cela, on récupère la partie depuis la base de données et on la sauvegarde dans la session de l'utilisateur.
                    */
                    $partie = $manager->getPartie($_POST["partieId"]);
                    $_SESSION["partie"] = $partie;
                    //on retourne une vue qui contient 1 pour respecter le MVC.      
                    return new AjaxView("1", "text");
                }
            }            
        }
        return new AjaxView("0", "text");
    }

    //cette fonction retourne un tableau qui indique pour chaque rôle s'il est libre ou non et si c'est l'utilisateur en cours qui détient le rôle de la partie
    public static function getRoles()
    {
        //l'identifiant de la partie est envoyé en post
        if(isset($_POST["partieId"]) && !empty($_POST["partieId"]))
        {
            $partieId = $_POST["partieId"];
            $manager = new PartieManager();
            $partie = $manager->getPartie($partieId);
            if($partie)
            {        
                foreach($partie->getRoles() as $role => $userID)
                {
                    //si l'utilisateur qui a le rôle l'utilisateur qui est connecté alors on met "choisi"
                    $user = parent::getUser();
                    if($userID == $user->getId())
                        $roles[$role] = "choisi";
                    //s'il s'agit d'un autre utilisateur non nul on met "indisponible"
                    else if($userID != NULL)
                        $roles[$role] = "indisponible";
                    //si l'utilisateur est nul on met "libre"
                    else
                        $roles[$role] = "libre";
                }
                    
                return new AjaxView(json_encode($roles), "json");
            }
        }
        return new AjaxView("12", "text");
    }

    //permet de renvoyer une réponse Ajax aux clients pour savoir si le maître du jeu a lancé la partie
    public static function isPartieLancee()
    {
        if(!parent::isConnected())
        {
            $dReponse["title"] = "Page introuvable";
            return new View("Error/404.html", $dReponse);
        }
        $manager = new PartieManager();
        $partie = $manager->getPartie($_SESSION["partie"]->getId());
        return new AjaxView($partie->isEnCours(), "text");
    }

    public static function lancerPartie()
    {
        if(!parent::isConnected())
        {
            $dReponse["title"] = "Page introuvable";
            return new View("Error/404.html", $dReponse);
        }

        $user = parent::getUser();
        if(parent::isInPartie() && $_SESSION["partie"]->getMaitre() == $user->getId())
        {
            $manager = new PartieManager();
            $result = $manager->lancerPartie();
            if($result)
            {
                $_SESSION["partie"]->setEnCours(true);
                return new AjaxView("1", "text");
            }
        }
        return new AjaxView("0", "text");
    }
}