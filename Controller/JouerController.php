<?php
class JouerController extends Controller
{

    public static function index()
    {
        $dReponse["title"] = "Jouer";
        $dReponse["message"] = "ok";
        return new View("Message.php", $dReponse);
    }
}
?>