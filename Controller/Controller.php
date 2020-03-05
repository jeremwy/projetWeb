<?php
class Controller
{
    protected static function isConnected()
    {
        if(isset($_SESSION["user"]))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    //si l'utilisateur n'est pas connecté alors on retoune automatiquelent la vue de connexion
    protected static function needToConnect()
    {
        if(!isset($_SESSION["user"]))
        {
            $dReponse["title"] = "Connexion";
            return new View("User/login.php", $dReponse);
        }
    }

    protected static function getUser()
    {
        if(self::isConnected())
        {
            return unserialize($_SESSION["user"]);
        }
        return false;
    }
}
?>