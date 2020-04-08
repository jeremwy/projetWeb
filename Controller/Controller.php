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
            return $_SESSION["user"];
        }
        return false;
    }

    //indique si l'utilisateur est dans une partie
    protected static function isInPartie()
    {
        return isset($_SESSION["partie"]) && !empty($_SESSION["partie"]) && $_SESSION["partie"]->getId() != null;
    }

    /*
        Cette méthoode vérifie si l'utilisateur est dans une partie qui existe.
        Si ce n'est pas le cas, la session de l'utilisateur est nettoyée (suppression de la partie sauvegardée en session)
    */
    protected static function cleanPartie()
    {
        $manager = new PartieManager();   

        if(self::isInPartie())
        {
            $partie = $manager->getPartie($_SESSION["partie"]->getId());
            if(!$partie)
            {
                unset($_SESSION["partie"]);
            }
        }
    }
}
?>