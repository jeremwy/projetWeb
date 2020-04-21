<?php

//cette classe va permettre de centraliser la connexion/déconnexion à la base de données
class Manager
{
    protected $db;

    public function __construct()
    {
        $this->db = new PDO(DB_DSN, DB_USER, DB_PASSWORD,
            array(
                PDO::ATTR_PERSISTENT => true,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ));
        $this->db->query("SET NAMES UTF8"); //permet d'indiquer à PDO d'utiiser l'encodage UTF-8
    }
}
?>