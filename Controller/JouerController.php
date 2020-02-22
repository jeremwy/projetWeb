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
        $dReponse["title"] = "Jouer";
        if(!parent::isConnected())
        {     
            return new View("User/login.php", $dReponse);            
        }
        else
        {
            $manager = new PartieManager();
            $dReponse["parties"] = $manager->getParties();
            return new View("Jouer/choisirPartie.php", $dReponse);
        }        
    }

    public static function creer()
    {
        if(isset($_SESSION["partie"]["id"]) && !empty($_SESSION["partie"]["id"]))
        {
            $dReponse["title"] = "Impossible de créer une partie";
            $dReponse["message"] = "Vous ne pouvez pas créer de partie car vous en avez déjà rejoint une.";
            return new View("Message.php", $dReponse);
        }
        $dReponse["title"] = "Créer une partie";
        if(!parent::isConnected())
        {            
            return new View("User/login.php", $dReponse);
        }
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
                    $partieData["maitre"] = $_SESSION["user"]->getId();
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
        if(!parent::isConnected())
        {            
            $dReponse["title"] = "Connexion";
            return new View("User/login.php", $dReponse);  
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
        //si l'utilisateur n'est pas dans une autre partie ou est dans la partie
        if(!(isset($_SESSION["partie"]["id"]) && !empty($_SESSION["partie"]["id"])) || (isset($_SESSION["partie"]["id"]) && !empty($_SESSION["partie"]["id"]) && $_SESSION["partie"]["id"] == $_POST["partieId"]))
        {
            //si un rôle a été envoyé
            if(isset($_POST["role"]) && !empty($_POST["role"]))
            {
                $role = $_POST["role"];

                switch($_POST["role"])
                {
                    case "Maître du jeu":
                        $role = "maitre";
                        break;
                    case "Pompier":
                        $role = "chefPompier";
                        break;
                    case "Policier":
                        $role = "chefPolicier";
                        break;
                    case "Médecin":
                        $role = "chefMedecin";
                        break;
                    default:
                        $role = NULL;
                }

                if($role != NULL)
                {
                    $manager = new PartieManager();
                    $result = $manager->addRole($role, $_SESSION["user"]->getId(), $_POST["partieId"]);
                    echo $result;
                    if($result)
                    {
                        $_SESSION["partie"]["id"] = $_POST["partieId"];
                        $_SESSION["partie"][$role] = $_SESSION["user"]->getId();
                        return new AjaxView("1", "text");
                    }
                }            
            }
        }
        return new AjaxView("0", "text");
    }

    public static function partie()
    {
        //pour l'instant, on ne vérifie pas si la partie existe ni si l'utilisateur en fait partie. Il faudra faire cette vérification
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
            foreach($roles as $role => $user)
            {
                //si l'utilisateur qui a le rôle l'utilisateur qui est connecté alors on met "choisi"
                if($user == $_SESSION["user"]->getId())
                    $roles[$role] = "choisi";
                //s'il s'agit d'un autre utilisateur non nul on met "indisponible"
                else if($user != NULL)
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