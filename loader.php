<?php
//ici se trouvent tous les fichiers qu'il faut inclure
require_once("config.php");
require_once("Vendor/loader.php");
require_once("Controller/Controller.php");
require_once("View/View.php");
require_once("View/RedirectView.php");

/*
    On inclut ces classes car leurs instances vont être stockées en session.
    Si l'on stocke une instance en session, il faut inclure la classe avant d'appeler session_start()
*/
require_once("Model/Class/User.php");
require_once("Model/Class/Partie.php");
require_once("Model/Class/Victime.php");
?>