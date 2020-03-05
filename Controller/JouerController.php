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

    //on aurait pu mettre cette méthode dans le contrôleur 'PartieController' mais on veut que l'url soit de la forme "jouer/creer"
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
                return new RedirectView("Message.php", SITE_ROOT . "partie/loby?id=" . $partie->getId() , $n, $dReponse);
            }
            else
            {
                $dReponse["title"] = "Erreur";
                $dReponse["message"] = "Une erreur est survenue lors de la création de la partie.";
                return new View("Message.php", $dReponse);
            }
            
        }
    }
}
?>