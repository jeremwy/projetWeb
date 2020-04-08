<?php
require_once("Controller.php");
require_once("Model/Class/Message.php");
require_once("Model/Class/Partie.php");
require_once("Model/PartieManager.php");
require_once("Model/ChatManager.php");
require_once("View/AjaxView.php");

class ChatController extends Controller{
    public static function envoiMessage()
    {
        /*
            Pour le chat, on fonctionne de la manière suivante :
            -si la partie n'est pas en cours alors tout le monde peut parler dans le chat
            -si la partie est en cour alors seuls les joueurs de la partie peuvent parler
        */
        if(isset($_POST["partieId"]) && !empty($_POST["partieId"]) && parent::isConnected())
        {
            $partieId = $_POST["partieId"];
            $partieManager = new PartieManager();
            $partie = $partieManager->getPartie($partieId);
            if($partie) //la partie existe
            {                
                if($partie->isEnCours() && $_SESSION["partie"]->getId() == $partie->getId())    //la partie est lancée et le joueur est dans cette partie
                {
                    if(isset($_POST['message']) && !empty($_POST["message"]))
                    {
                        //ajout du message
                        return self::sauvegarderMessage($_POST["message"], $_SESSION["partie"]->getId());
                    }
                }
                //si la partie n'est pas lancée, on se fiche de savoir si le joueur est dans la partie
                else
                {
                    if(isset($_POST['message']) && !empty($_POST["message"]))
                    {
                        return self::sauvegarderMessage($_POST["message"], $partie->getId());
                    }
                }
            }
            return new AjaxView("0", "text");
        }
        $dReponse["title"] = "Page introuvable";
        return new View("Error/404.html", $dReponse);
    }

    //permet de suavegarder un message dans la base de données
    private static function sauvegarderMessage($message, $partieId)
    {
        $user = parent::getUser();
        $message = new Message($_POST["message"], $user->getId(),$partieId);
        $manager = new ChatManager($message);
        if($manager->envoiMessage())
            return new AjaxView("1", "text");
        return new AjaxView("0", "text");
    }

    public static function getLastMessage()
    {
        /*
            Comme pour l'envoi d'un message :
            -si la partie n'est pas lancée, tous les joueurs peuvent voir les messages
            -si la partie est lancée, seuls les joueurs de la partie peuvent voir les messages
        */
        if(isset($_POST["partieId"]) && !empty($_POST["partieId"]) && parent::isConnected())
        {
            $partieId = $_POST["partieId"];
            $partieManager = new PartieManager();
            $partie = $partieManager->getPartie($partieId);
            if($partie) //la partie existe
            {
                $chatManager = new ChatManager();
                $message;
                if($partie->isEnCours() && $_SESSION["partie"]->getId() == $partie->getId())    //la partie est lancée et le joueur est dans cette partie
                {
                    $message = $chatManager->getLastMessage($partieId);
                }
                //si la partie n'est pas lancée, on se fiche de savoir si le joueur est dans la partie
                else
                {
                    $message = $chatManager->getLastMessage($partieId);
                }
                return new AjaxView(json_encode($message), "json");
            }
            return new AjaxView("0", "text");
        }
        $dReponse["title"] = "Page introuvable";
        return new View("Error/404.html", $dReponse);
    }
}
?>