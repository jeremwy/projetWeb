<?php
mb_internal_encoding("UTF-8");  //on définie l'encodage sur UTF-8

define("SITE_ROOT", "http://localhost/projetweb/");
define("FOLDER_ROOT", __DIR__);
define("SEPARATOR", DIRECTORY_SEPARATOR );
define("DB_DSN", "mysql:host=localhost;dbname=projetweb");
define("DB_USER", "root");
define("DB_PASSWORD", "");
?>