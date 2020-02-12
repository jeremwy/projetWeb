<?php
class JouerController extends Controller
{

    public static function index()
    {
        $dReponse["title"] = "Jouer";
        if(parent::isConnected())
        {            
            $dReponse["message"] = "ok";
            return new View("Message.php", $dReponse);
        }
        else
        {
            return new View("User/login.php", $dReponse);
        }        
    }
}
?>