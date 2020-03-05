<?php
require_once("Model/PartieManager.php");
require_once("Model/Class/Partie.php");
require_once("View/AjaxView.php");

/*
    Cette classe est utilisée pour gérer les traitement relatif à une partie (selection de rôles, lancer la partie ...).
*/
class PartieController extends Controller
{
    public static function index()
    {
        //force la connnexion
        $view = parent::needToConnect();
        if($view != NULL) return $view;

        //si le joueur n'est dans aucune partie
        if(!isset($_SESSION["partie"]["id"]) || empty($_SESSION["partie"]["id"]))
        {
            $dReponse["title"] = "Redirection sélection d'une' partie";
            $dReponse["message"] = "Vous n'êtes dans aucune partie, redirection la sélection d'une' partie.";
            return new RedirectView("Message.php", SITE_ROOT . "partie/loby", 5, $dReponse);
        }

        $manager = new PartieManager();
        if($manager->isPartieLancee())
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
        //force la connnexion
        $view = parent::needToConnect();
        if($view != NULL) return $view;

        if(!isset($_GET["id"]) || empty($_GET["id"]))
        {
            $dReponse["title"] = "Page introuvable";
            return new View("Error/404.html", $dReponse);
        }

        $idPartie = $_GET["id"];
        if(isset($_SESSION["partie"]["id"]) && !empty($_SESSION["partie"]["id"]) && $_SESSION["partie"]["id"] != $idPartie)
        {
            $dReponse["title"] = "Impossible de rejoindre cette partie";
            $dReponse["message"] = "Impossible de rejoindre cette partie car vous êtes déjà dans une autre partie.";
            return new View("Message.php", $dReponse);
        }
        else
        $manager = new PartieManager();
        
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

    public static function selectRole()
    {
        if(!parent::isConnected())
        {
            $dReponse["title"] = "Page introuvable";
            return new View("Error/404.html", $dReponse);
        }
        //si l'utilisateur est déjà dans une partie dont l'identifiant ne correspond à celui envoyé alors on retoune 0
        if(isset($_SESSION["partie"]["id"]) && $_SESSION["partie"]["id"] != $_POST["partieId"])
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
                //le test pour savoir si le rôle est libre est faire de manière automatique par le manager dans la requête SQL
                $result = $manager->addRole($roleChoisi, $user->getId(), $_POST["partieId"]);
                if($result) //si le rôle n'est pas libre, on retourne 0 (de toute façon l'utilisateur n'est pas censé pouvoir cliquer sur un bouton d'un rôle déjà séléctionné (voir JS))
                {
                    $_SESSION["partie"]["id"] = $_POST["partieId"];
                    if(!isset($_SESSION["partie"]["roles"]))
                    {
                        $_SESSION["partie"]["roles"] = array();
                    }
                    array_push($_SESSION["partie"]["roles"], $roleChoisi);
                    //on retourne une vue qui contient 0 (en cas d'échec) ou 1 (en cas de succès) pour respecter le MVC.      
                    return new AjaxView("1", "text");
                }
            }            
        }
        return new AjaxView("0", "text");
    }

    //cette fonction retourne un tableau qui indique pour chaque rôle s'il est libre ou non et si c'est l'utilisateur en cours qui détient le rôle de la partie
    public static function getRoles()
    {
        if(isset($_SESSION["partie"]["id"]) && !empty($_SESSION["partie"]["id"]))
        {
            $manager = new PartieManager();
            $roles = $manager->getRoles($_SESSION["partie"]["id"]);
            foreach($roles as $role => $userID)
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
        return new AjaxView("0", "text");
    }

    public static function isPartieLancee()
    {
        if(!parent::isConnected())
        {
            $dReponse["title"] = "Page introuvable";
            return new View("Error/404.html", $dReponse);
        }
        $manager = new PartieManager();
        $result = $manager->isPartieLancee();
        return new AjaxView($result, "text");
    }

    public static function lancerPartie()
    {
        if(!parent::isConnected())
        {
            $dReponse["title"] = "Page introuvable";
            return new View("Error/404.html", $dReponse);
        }
        $manager = new PartieManager();
        $result = $manager->lancerPartie();
        if($result) return new AjaxView("1", "text");
        return new AjaxView("0", "text");
    }
}