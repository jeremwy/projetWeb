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
            return new View("Partie/ChoisirPartie.php", $dReponse);
        }
        else
        {
            return new View("User/login.php", $dReponse);
        }        
    }
}
?>