<?php
require_once("Manager.php");
require_once("Class/Message.php");

class ChatManager extends Manager
{

    private $message;

    public function __construct($message = null)
    {
        parent::__construct();
        if($message === null)
        {
            $this->message = new Message();
        }
        else
        {
            $this->message = $message;
        }
    }

    //méthode appellée par JouerController
    public function envoiMessage()
    {
        if($this->message->getMessage() == "")
        {
            return false;
        }

        //l'identifiant est un identifiant généré par mysql ainsi, il faut laisser la première valeur à NULL (le sgbd s'en occupe)
        $stmt = $this->db->prepare('INSERT INTO chat VALUES(NULL, :user_id, :partie, :message)');
        $stmt->bindValue(":user_id", $this->message->getUser_id());
        $stmt->bindValue(":partie", $this->message->getPartie());
        $stmt->bindValue(":message", $this->message->getMessage());
        return $stmt->execute();
    }

    public function getLastMessage($partieId)
    {
        $stmt = $this->db->prepare('SELECT chat.*, utilisateur.nom, utilisateur.prenom
                                    FROM chat, utilisateur
                                    WHERE partie=:partieId AND utilisateur.id=chat.user_id AND chat.id=(SELECT MAX(id)
                                                                                                          FROM chat
                                                                                                          WHERE partie=:partieId2)');
        $stmt->bindValue(":partieId", $partieId);
        $stmt->bindValue(":partieId2", $partieId);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Message');
        $message = $stmt->fetch();
        return $message;
    }

    public function clearMessage($partieId)
    {
        $stmt = $this->db->prepare('DELETE FROM chat
                                    WHERE partie=:partieId');
        $stmt->bindValue(":partieId", $partieId);
        $stmt->execute();
    }
}
?>