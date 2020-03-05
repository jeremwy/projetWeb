
<?php
require_once("Model/UserManager.php");
require_once("Model/PartieManager.php");
require_once("Model/Class/User.php");
require_once("Vendor/Route.php");
class UserController extends Controller
{
    public static function signup()
    {
        //si l'utilisateur n'a pas encore rempli de formulaire d'inscription (rien dans POST) alors on affiche la vue permettant l'inscription
        if(!isset($_POST) || empty($_POST))
        {
            $dReponse["title"] = "Inscription";
            $dReponse["js"][0] = "signupCheck.js";
            return new View("User/signup.php", $dReponse);
        }
        else{
            //on vérifie si le formulaire est correct (c'est-à-dire qu'il ne manque pas de champs et qu'aucun n'autre n'a été ajouté)
            if(count($_POST) != 4)
            {
                //s'il manque un champs, on retourne le formulaire vide pour que l'utilisateur puisse le remplir de nouveau
                $dReponse["title"] = "Inscription";
                return new View("User/signup.php", $dReponse);
            }
            //on vérifie si tous les champs sont remplis
            //on nettoie les variables valeurs rentrées dans le formualire (suppression des espaces) avec trim (sauf s'il s'agit d'un mot de passe).
            $messageCount = 0;
            foreach($_POST as $key => $var)
            {
                if($var === "")
                {
                    $dReponse["form"]["emptyField"] = 1;   //on met la variable "emptyField" à 1 du sous tableau "form". La vue va ainsi savoir qu'un champs n'a pas été rempli et qu'il faudra afficher un message
                }
                //si la varibale n'est pas vide et s'il ne s'agit pas du mot de passe on la nettoie
                else if($key != "password" && $key != "passwordRepeat")
                {
                    $var = trim($var);
                    //on conserve les informations renseignées par l'utilisateur. Cela permettra de les réafficher dans le formulaire s'il doit etre réaffiché
                    $dReponse["form"]["data"][$key] = htmlspecialchars($var);
                }
                //on concerve les données dans un tableau. Ce tableau servira pour la sauvegarde dans la base de données
                $userData[$key] = $var;
            }
            //si une erreur est survenue
            if(isset($dReponse["form"]["emptyField"]) && $dReponse["form"]["emptyField"] === 1)
            {
                //on retourne la vue pour que l'utilisateur corrige l'erreur.
                $dReponse["title"] = "Inscription";
                $dReponse["js"][0] = "signupCheck.js";
                return new View("User/signup.php", $dReponse); 
            }
            //si les mots de passe sont différents
            if($_POST["password"] != $_POST["passwordRepeat"])
            {
                $dReponse["form"]["message"][$messageCount++] = "Les mots de passe ne correspondent pas.";
                //on retourne la vue pour que l'utilisateur corrige l'erreur.
                $dReponse["title"] = "Inscription";
                $dReponse["js"][0] = "signupCheck.js";
                return new View("User/signup.php", $dReponse);
            }

            //si on arrive a ce stade, cela signifie que tous les tests ont été passés. Il faut donc enregistrer l'utilisateur dans la base de données :
            //création d'un nouvel utilisateur
            $user = new User($userData["nom"], $userData["prenom"], $userData["password"]);
            //on passe cet utilissateur à un nouveau UserManager
            $userManager = new UserManager($user);
            //on sauvegarde l'utilisateur de l'UserManager
            $result = $userManager->saveUser();
            if($result == 1)
            {
                $dReponse["title"] = "Inscription réussie";
                $dReponse["message"] = "Inscription réussie.";
                return new View("Message.php", $dReponse);
            }
            else if($result == -1)
            {
                $dReponse["title"] = "Inscription échouée";
                $dReponse["message"] = "Inscription échouée, car un compte avec les nom et prénom indiqués existe déjà.";
                return new View("Message.php", $dReponse);
            }
            else
            {
                $dReponse["title"] = "Inscription échouée";
                $dReponse["message"] = "Inscription échouée, une erreur est survenue.";
                return new View("Message.php", $dReponse);
            }
        }       
    }

    public static function login()
    {
        //si l'utilisateur n'a pas encore rempli de formulaire d'inscription (rien dans POST) alors on affiche la vue permettant l'inscription
        if(!isset($_POST) || empty($_POST))
        {
            $dReponse["title"] = "Connexion";
            return new View("User/login.php", $dReponse);
        }
        else
        {
            if(count($_POST) != 3)
            {
                //s'il manque un champs, on retourne le formulaire vide pour que l'utilisateur puisse le remplir de nouveau
                $dReponse["title"] = "Connexion";
                return new View("User/login.php", $dReponse);
            }
            //on vérifie si tous les champs sont remplis
            $messageCount = 0;
            foreach($_POST as $key => $var)
            {
                if($var === "")
                {
                    $dReponse["form"]["emptyField"] = 1;   //on met la variable "emptyField" à 1 du sous tableau "form". La vue va ainsi savoir qu'un champs n'a pas été rempli et qu'il faudra afficher un message
                }
                else{
                    $dReponse["form"]["data"][$key] = htmlspecialchars($var);
                    $userData[$key] = $var;
                }
            }
            //si une erreur est survenue
            if(isset($dReponse["form"]["emptyField"]) && $dReponse["form"]["emptyField"] === 1)
            {
                //on retourne la vue pour que l'utilisateur corrige l'erreur.
                $dReponse["title"] = "Connexion";
                return new View("User/login.php", $dReponse);
            }
            //si on arrive a ce stade, cela signifie que tous les tests ont été passés. Il faut donc connecter l'utilisateur  :
            //création d'un nouvel utilisateur
            $user = new User($userData["nom"], $userData["prenom"], $userData["password"]);
            //on passe cet utilissateur à un nouveau UserManager
            $userManager = new UserManager($user);
            //on sauvegarde l'utilisateur de l'UserManager
            $result = $userManager->login();
            if($result == 1)
            {
                $partieManager = new PartieManager();
                $partieId = $partieManager->getUserPartieId(unserialize($_SESSION["user"])->getId());
                
                if($partieId != false)
                {
                    //attention : $partieId est un tableau qui contient une case [0] et une case  ["id"] (les deux cases ont le même contenu).
                    $_SESSION["partie"]["id"] = $partieId["id"];
                }

<<<<<<< HEAD
                $dReponse["title"] = "Connexion réussie";
=======
                $dReponse["title"] = "Connexion réussi";
>>>>>>> 5374c17ca241c72e06f846efc99cc7bdcff53bc2
                $dReponse["message"] = "Connexion réussie. Vous allez être redirigé(e)s.";
                $route = unserialize($_SESSION["redirectRoute"])->getRoute();
                return new RedirectView("Message.php", SITE_ROOT . $route, 5, $dReponse);
            }
            else if($result == -1)
            {
                $dReponse["title"] = "Connexion échouée";
                $dReponse["form"]["message"][0] = "Connexion échouée, mot de passe ou nom/prénom incorrects.";
                return new View("User/login.php", $dReponse);
            }
            else
            {
                $dReponse["title"] = "Connexion échouée";
                $dReponse["message"] = "Connexion échouée, une erreur est survenue.";
                return new View("Message.php", $dReponse);
            }
        }
    }

    public static function logout()
    {
        $partieManager = new PartieManager();
        //il faut absolument appeler cette méthode avant de tenter de supprimer la session car après, l'id de la partie stocké en session sera inaccessible
        if(isset($_SESSION["user"]) && !empty($_SESSION["user"]))
        {    
            if(isset($_SESSION["partie"]["id"]) && !empty($_SESSION["partie"]["id"]))
            {
                $maitrePartieJoueur = $partieManager->getMaitre($_SESSION["partie"]["id"]);
                $userID = parent::getUser()->getId();
                //si l'utilisateur est le maitre d'une partie alors on supprime cette partie.
                if($userID == $maitrePartieJoueur)
                {
                    $partieManager->supprimerPartie($userID);
                }
            }
            if(session_destroy())
            {
                $dReponse["title"] = "Déconnexion réussie";
                $dReponse["message"] = "Déconnexion réussie. Vous allez être redirigé(e)s.";
                return new RedirectView("Message.php", SITE_ROOT, 5, $dReponse);
            }
            else
            {
                $dReponse["title"] = "Déconnexion échouée";
                $dReponse["message"] = "Déconnexion échouée, une erreur est survenue.";
                return new View("Message.php", $dReponse);
            }
        }
        else
        {
            $dReponse["title"] = "Page introuvable";
            return new View("Error/404.html", $dReponse);
        }
    }
}
?>