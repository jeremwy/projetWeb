<?php
require_once("Model/PartieManager.php");
require_once("Model/Class/Partie.php");
class JouerController extends Controller
{

    public static function index()
    {
        $dReponse["title"] = "Jouer";
        if(parent::isConnected())
        {            
            $manager = new PartieManager();
            $dReponse["parties"] = $manager->getParties();
            return new View("Jouer/choisirPartie.php", $dReponse);
        }
        else
        {
            return new View("User/login.php", $dReponse);
        }        
    }

    public static function creer()
    {
        //si aucun formualire n'a été envoyé
        if(!isset($_POST) || empty($_POST))
        {
            $dReponse["js"][0] = "creerPartieCheck.js";
            $dReponse["title"] = "Créer une partie";
            return new View("Jouer/creerPartie.php", $dReponse);
        }
        else
        {  
            //s'il manque des champs dans le formulaire, on le renvoit
            if(count($_POST) != 2)
            {
                $dReponse["js"][0] = "creerPartieCheck.js";
                $dReponse["title"] = "Créer une partie";
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
                $dReponse["title"] = "Créer une partie";
                return new View("Jouer/creerPartie.php", $dReponse);
            }
            
            //on vérifie si le nom de partie n'est pas déjà pris
            $manager = new PartieManager();

            if($manager->isIdUsed($partieData["nomPartie"]))
            {
                $dReponse["js"][0] = "creerPartieCheck.js";
                $dReponse["title"] = "Créer une partie";
                $dReponse["form"]["message"][0] = "Le nom de partie est déjà utilisé.";
                return new View("Jouer/creerPartie.php", $dReponse); 
            }

            //sinon on sauvegarde la partie            
            $partie = new Partie($partieData["nomPartie"], $partieData["maitre"]);
            $manager->setPartie($partie);
            $result = $manager->savePartie();
            if($result == 1)
            {
                $n = 5;
                $dReponse["title"] = "Partie créée";
                $dReponse["message"] = "Partie créée. Vous allez être redirigé(e) vers la pas de la partie dans " . $n . " secondes.";
                return new RedirectView("Message.php", SITE_ROOT . "jouer/loby?id=" . $partie->getId() , $n, $dReponse);
            }
            else
            {
                $dReponse["title"] = "Erreur";
                $dReponse["message"] = "Une erreur est survenue lors de la création de la partie";
                return new View("Message.php", $dReponse);
            }
            
        }
    }

    public static function loby()
    {
        
    }
}
?>