<?php
require_once("Model/PartieManager.php");
require_once("Model/Class/Partie.php");
require_once("View/AjaxView.php");
class JouerController extends Controller
{

    /*
        Dans cette classe toutes les méthodes vont d'abord vérifier si l'utilisateur est connécté.
        Si ce n'est pas le cas, la vue de login sera sera retournée
    */

    public static function index()
    {
        //force la connnexion
        $view = parent::needToConnect();
        if($view != NULL) return $view;

        $dReponse["title"] = "Jouer";
        $manager = new PartieManager();
        $dReponse["parties"] = $manager->getParties();
        return new View("Jouer/choisirPartie.php", $dReponse);     
    }

    public static function creer()
    {
        //force la connnexion
        $view = parent::needToConnect();
        if($view != NULL) return $view;

        if(isset($_SESSION["partie"]["id"]) && !empty($_SESSION["partie"]["id"]))
        {
            $dReponse["title"] = "Impossible de créer une partie";
            $dReponse["message"] = "Vous ne pouvez pas créer de partie car vous en avez déjà rejoint une.";
            return new View("Message.php", $dReponse);
        }
        $dReponse["title"] = "Créer une partie";
        //si aucun formualire n'a été envoyé
        if(!isset($_POST) || empty($_POST))
        {
            $dReponse["js"][0] = "creerPartieCheck.js";
            return new View("Jouer/creerPartie.php", $dReponse);
        }
        else
        {  
            //s'il manque des champs dans le formulaire, on le renvoit
            if(count($_POST) != 2)
            {
                $dReponse["js"][0] = "creerPartieCheck.js";
                return new View("Jouer/creerPartie.php", $dReponse);
            }
            if($_POST["nomPartie"] === "" || ($_POST["maitre"] != "oui" && $_POST["maitre"] != "non"))
            {
                $dReponse["form"]["emptyField"] = 1;   //on met la variable "emptyField" à 1 du sous tableau "form". La vue va ainsi savoir qu'un champs n'a pas été rempli et qu'il faudra afficher un message
            }
            else{
                //si la varibale n'est pas vide on la nettoie
                //on conserve les informations renseignées par l'utilisateur. Cela permettra de les réafficher dans le formulaire s'il doit etre réaffiché
                $nom = trim($_POST["nomPartie"]);
                $dReponse["form"]["data"]["nomPartie"] = htmlspecialchars($nom);
                //on concerve les données dans un tableau. Ce tableau servira pour la sauvegarde dans la base de données
                $partieData["nomPartie"] = $nom;
                $partieData["maitre"] = null;
                if($_POST["maitre"] === "oui")
                {
                    $user = parent::getUser();
                    $partieData["maitre"] = $user->getId();
                }                    
            }
            
            //si une erreur est survenue
            if(isset($dReponse["form"]["emptyField"]) && $dReponse["form"]["emptyField"] === 1)
            {
                //on retourne la vue pour que l'utilisateur corrige l'erreur.
                $dReponse["js"][0] = "creerPartieCheck.js";
                return new View("Jouer/creerPartie.php", $dReponse);
            }
            
            //on vérifie si le nom de partie n'est pas déjà pris
            $manager = new PartieManager();

            if($manager->isIdUsed($partieData["nomPartie"]))
            {
                $dReponse["js"][0] = "creerPartieCheck.js";
                $dReponse["form"]["message"][0] = "Le nom de partie est déjà utilisé.";
                return new View("Jouer/creerPartie.php", $dReponse); 
            }

            //sinon on sauvegarde la partie            
            $partie = new Partie($partieData["nomPartie"], $partieData["maitre"]);
            $manager->setPartie($partie);
            $result = $manager->savePartie();
            if($result == 1)
            {
                if($_POST["maitre"] === "oui")
                {
                    $_SESSION["partie"]["id"] = $partie->getId();
                    $_SESSION["partie"]["role"][0] = "maitre";
                }                    
                $n = 5;
                $dReponse["title"] = "Partie créée";
                $dReponse["message"] = "Partie créée. Vous allez être redirigé(e) vers la pas de la partie dans " . $n . " secondes.";
                return new RedirectView("Message.php", SITE_ROOT . "jouer/loby?id=" . $partie->getId() , $n, $dReponse);
            }
            else
            {
                $dReponse["title"] = "Erreur";
                $dReponse["message"] = "Une erreur est survenue lors de la création de la partie.";
                return new View("Message.php", $dReponse);
            }
            
        }
    }

    public static function loby()
    {
        //force la connnexion
        $view = parent::needToConnect();
        if($view != NULL) return $view;

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
            return new View("Jouer/loby.php", $dReponse);
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
            //on retourne une vue qui contient 0 (en cas d'échec) ou 1 (en cas de succès) pour respecter le MVC.      
            return new AjaxView("0", "text");
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
                    return new AjaxView("1", "text");
                }
            }            
        }
        return new AjaxView("0", "text");
    }

    public static function partie()
    {
        //force la connnexion
        $view = parent::needToConnect();
        if($view != NULL) return $view;

        $dReponse["title"] = "Jouer";
        $dReponse["js"][0] = "chat.js";
        return new View("Jouer/plateauPartie.php", $dReponse);
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
}
?>