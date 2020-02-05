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

    /*
        Permet d'enregister l'utilisateur dans la base de données.
        Si l'utilisateur est déjà présent alors -1 est retourné.
        En cas d'erreur false (0) est retourné.
        En cas de succès 1 est retouné.
    */
    public function saveUser()
    {
        //si la fonction ne retourne pas false, cela signifie que l'utilisateur existe et donc il faut retourner -1
        if($this->getUser($this->user->nom, $this->user->prenom) != false)
        {
            return -1;
        }
        //afin d'insérer un timestamp UNIX dans une base de donées mysql, il faut utiliser le fonction FROM_UNIXTIME qui permet de dire "la valeur est un timestamp unix" à la base de données
        $stmt = $this->db->prepare("INSERT INTO Utilisateur VALUES (?, ?, ?, FROM_UNIXTIME(?))");
        $stmt->bindValue(1, $this->user->getNom(), PDO::PARAM_STR);
        $stmt->bindValue(2, $this->user->getPrenom(), PDO::PARAM_STR);
        /*
            On hash le mot de passe afin qu'il ne soit pas stocké en clair dans la base de données.
            Pour vérifier le mot de passe, il faudra utiliser "password_verify".
        */
        $stmt->bindValue(3, password_hash($this->user->getMotDePasse(), PASSWORD_DEFAULT), PDO::PARAM_STR);
        $stmt->bindValue(4, $this->user->getInscription());

        return $stmt->execute();
    }

    public function login()
    {
        $userTmp = $this->getUser($this->user->getNom(), $this->user->getPrenom());
        if($userTmp != false)
        {
            if(password_verify($this->user->getMotDePasse(), $userTmp->getMotDePasse()) == true)
            {
                //on saubegarde l'utilisateur en session
                $_SESSION["user"] = $userTmp;
                return 1;
            }
            else
            {
                return -1;
            }
        }
        //si l'utilisateur est à faux et qu'aucune erreur n'a eu lieu lors de l'exécution de la requète (code erreur = 00000) alors c'est que l'utilisateur n'existe pas
        else if($userTmp == false && $this->db->errorInfo()[0] === "00000")
        {
            return -1;
        }
        //si l'utilisateur est faux alors il y a eu une erreur
        else if($userTmp == false)
        {
            return 0;
        }
    }

    //retourne l'utilisateur s'il existe retourne false sinon ou en cas d'erreur
    public function getUser($nom, $prenom)
    {
        $stmt = $this->db->prepare("SELECT * 
                                    FROM Utilisateur
                                    WHERE Nom=? AND Prenom=?");
        $stmt->bindValue(1, $nom);
        $stmt->bindValue(2, $prenom);
        $stmt->execute();
        /*
            On indique ici qu'il faut retourner une nouvelle instance de 'User' lors du parcourt des données.
            'PDO::FETCH_PROPS_LATE' permet de ne pas appeler le constructeur de la classe une fois les données parcourues.
            Cela aurait pour effet d'appeler le constructeur avec les paramètres pour chaque attribut.
        */
        $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'User');
        $user = $stmt->fetch();

        return $user;
    }
}
?>