
<?php
require_once("Model/UserManager.php");
require_once("Model/Class/User.php");
class UserController
{
    public static function signup()
    {
        //si l'utilisateur n'a pas encore rempli de formulaire d'inscription alors on affiche la vue permettant l'inscription
        if(!isset($_POST) || empty($_POST))
        {
            $dReponse["title"] = "Inscription";
            $dReponse["js"][0] = "signupCheck.js";
            return new View("User/signup.php", $dReponse);
        }
        else{
            //on vérifie si le formaulaire est corecte (c'est-à_dire qu'il ne manque pas de champs et qu'aucun n'autre n'a été ajouté)
            if(count($_POST) != 4)
            {
                //s'il manque un champs, on retourne le formulaire vide pour que l'utilisateur puisse le remplir de nouveua
                $dReponse["title"] = "Inscription";
                return new View("User/signup.php", $dReponse);
            }
            //on vérifie si tous les champs sont remplis et si le même mot de passe a été renseigné deux fois
            //on nettoie les variables valeurs rentrées dans le formualire (suppression des espaces) avec trim (sauf s'il s'agit d'un mot de passe).
            $messageCount = 0;
            foreach($_POST as $key => $var)
            {
                if($var === "")
                {
                    $dReponse["form"]["emptyField"] = 1;   //on met la variable "emptyField" à 1 du sous tableau "form. La vue va ainsi savoir qu'un champs n'a pas été rempli et qu'il faudra afficher un message
                }
                //si la varibale n'est pas vide on la nettoie
                else if($key != "password" && $key != "passwordRepeat")
                {
                    $var = trim($var);
                    //on conserve les informations renseignées par l'utilisateur. Cela permettra de les réaffihcer dans le formulaire s'il doit $etre réaffiché
                    $dReponse["form"]["data"][$key] = htmlspecialchars($var);
                }
                $userData[$key] = $var;
            }
            if(isset($dReponse["form"]["emptyField"]) && $dReponse["form"]["emptyField"] === 1)
            {
                //on retourne la vue pour que l'utilisateur corrige l'erreur.
                $dReponse["title"] = "Inscription";
                $dReponse["js"][0] = "signupCheck.js";
                return new View("User/signup.php", $dReponse); 
            }
            if($_POST["password"] != $_POST["passwordRepeat"])
            {
                $dReponse["form"]["message"][$messageCount++] = "Les mots de passe ne correspondent pas.";
                //on retourne la vue pour que l'utilisateur corrige l'erreur.
                $dReponse["title"] = "Inscription";
                $dReponse["js"][0] = "signupCheck.js";
                return new View("User/signup.php", $dReponse);
            }

            //si on arrive a ce stade, cela signifie que tous les tests ont été passés. Il faut donc enregistrer l'utilisateur dans la base de données.
            $user = new User($userData["nom"], $userData["prenom"], $userData["password"]);
            $userManager = new UserManager($user);
            $result = $userManager->saveUser();
            if($result == 1)
            {
                $dReponse["title"] = "Inscription réussi";
                $dReponse["message"] = "Inscription réussi.";
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
        echo "ok";
        die(); //arrête le programme
    }
}
?>