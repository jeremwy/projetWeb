<?php
require_once("Controller.php");
require_once("Model/Class/Message.php");
require_once("Model/ChatManager.php");
require_once("View/AjaxView.php");

class  ChatController extends Controller{
    public static function envoiMessage()
    {
        // si on a envoyé des données avec le formulaire et si l'utilisateur est dans une partie
        if(isset($_POST['message']) && !empty($_POST["message"]) && isset($_SESSION["partie"]) && !empty($_SESSION["partie"])){
            //on prend l'ID et le message de l'utilisateur
            $message = new Message($_POST["message"], $_SESSION["user"]->getId(), $_SESSION["partie"]["id"]);
            $manager = new ChatManager($message);
            $result = $manager->envoiMessage();
            //il faudra gérer les cas où il y a une erreur  
        }
        die(); // POUR L'INSTANT ON ARRETE LE SCRIPT. IL FAUDRA CHANGER çA PARCEQUE C'EST DE LA MERDE DE FAIRE COMME çA ...
    }

    public static function getLastMessage()
    {
        //retourne le dernier message d'un partie (le joueur doit d'abord être connecté)
        if(isset($_SESSION["partie"]) && !empty($_SESSION["partie"])){
            $manager = new ChatManager();
            $message = $manager->getLastMessage($_SESSION["partie"]["id"]);
            return new AjaxView(json_encode($message), "json");
        }
    }
}
?>