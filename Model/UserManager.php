<?php
require_once("Manager.php");
require_once("Class/User.php");

//classe qui permet de gérer les échanges avec la base de données concernant les utilisateurs
class UserManager extends Manager
{
    private $user;

    public function __construct($user = NULL)
    {
        parent::__construct();
        $this->user = $user;
    }

    public function saveUser()
    {
        if($this->userExists() == true)
        {
            return -1;
        }
        //afin d'insérer un timestamp UNIX dans une base de donées mysql, il faut utiliser le fonction FROM_UNIXTIME qui permet de dire "la valeur est un timestamp unix" à la base de données
        $stmt = $this->db->prepare("INSERT INTO Utilisateur VALUES (?, ?, ?, FROM_UNIXTIME(?))");
        $stmt->bindParam(1, $this->user->nom, PDO::PARAM_STR);
        $stmt->bindParam(2, $this->user->prenom, PDO::PARAM_STR);
        $stmt->bindParam(3, $this->user->motDePasse, PDO::PARAM_STR);
        $stmt->bindParam(4, $this->user->inscription);

        return $stmt->execute();
    }

    private function userExists()
    {
        $stmt = $this->db->prepare("SELECT * 
                                    FROM Utilisateur
                                    WHERE Nom=? AND Prenom=?");
        $stmt->bindValue(1, $this->user->nom);
        $stmt->bindValue(2, $this->user->prenom);
        $stmt->execute();
        $result = $stmt->fetch();
        
        if($result != false)
            return true;
        return false;
    }
}
?>