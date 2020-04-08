<?php
class Message
{
    public $id;
    public $message;
    public $user_id;
    public $partie;

    //ces deux attributs ne sont renseignés que lorsque que l'on envoi un objet message au client
    public $nom;
    public $prenom;

    public function __construct($message = "", $user_id = "", $partie = "")
    {
        $this->message = $message;
        $this->user_id = $user_id;
        $this->partie = $partie;
    }

    public function getMessage()
    {
        return trim($this->message);
    }

    public function getUser_id()
    {
        return $this->user_id;
    }

    public function getPartie()
    {
        return $this->partie;
    }

    public function setMessage($message)
    {
        $this->message = $message;
    }

    public function setUser_id($user_id)
    {
        $this->message = $user_id;
    }

    public function setPartie($partie)
    {
        $this->message = $partie;
    }

    public function getNom()
    {
        return $this->nom;
    }

    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom()
    {
        return $this->prenom;
    }

    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;

        return $this;
    }
}
?>