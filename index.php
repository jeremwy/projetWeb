<?php
    session_start();
    require_once("loader.php");

    $request = rtrim($_SERVER["REQUEST_URI"], "/"); //supprime le caractère final "/" qui peut poser problème pour l'analyse de la requète
    $path = explode("/", $request);

    /*
        En local sur WAMP, la requète est de la forme : "/projetWeb/index".
        Ceci retournera les 3 chaînes "/", "projetWeb" et "index" or, seule la dernière chaîne nous intéresse ainsi,
        il faut enlever les deux première. Pour cela, nous utilisons le fonction array_shift qui va décaler le tableau $path d'une case sur la droite.
        Comme les deux première case du tableau contiennent "/", "projetWeb', il faire deux fois le décalage.
    */
    //Si contient "/", "projetWeb" et "index"
    array_shift($path); //contient maintenant "projetWeb" et "index"
    array_shift($path); //contient maintenant "index"

    switch (count($path))
    {
        case 2:
            $controller = ucfirst($path[0]);
            $method = $path[1];
            break;
        case 1:
            $controller = ucfirst($path[0]);
            $method ="index";
            break;
        default:    //si l'on souhaite accéder à la page d'accueil du site
            $controller = "Index";
            $method = "index";
            break;
    }

    /*
        La variable $controller contient le nom de la classe du controller qui est de la forme xxxController où xxx est passé (ou non dans l'url).
        On vérifie si le fichier contenant la classe du controller existe et si sa méthode (dont le nom est dans $method) existent. 
        Si ces deux conditions sont validées, on appelle la méthode du contrôleur.
        $controllerFile sert a indiquer le chemin relatif du fichier contenant le code du contrôleur.

        Chaque contrôleur retourne un objet View (vue du modèle MVC) qui contient le html.
    */
    
    $controller = $controller . "Controller";
    $controllerFile = "Controller/" . $controller . ".php";

    if(file_exists($controllerFile))
    {
        require_once($controllerFile);
        if(method_exists($controller, $method))
        {
            //Appelle une méthode statique et passe les arguments en tableau: cette fonction permet d'appeler une méthode d'une autre classe en donnant, dans un tableau, le nom de la classe et de la méthode. Le deuxième argument est un tableau contenant les paramètres à passer à la méthode.
            $view = forward_static_call_array(array($controller, $method), array());            
        }
        else
        {

            $dReponse["title"] = "Page introuvable";
            $view = new View("Error/404.html", $dReponse);
        }
    }
    else
    {
        $dReponse["title"] = "Page introuvable";
        $view = new View("Error/404.html", $dReponse);
    }
    $view->rend();
?>
