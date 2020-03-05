<?php
    require_once("loader.php");
    session_start();    

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

    /*
        Si l'on passe des informations dans l'url (via GET) alors l'url va être de la forme : "Controller/method?var1=var1&var2=var2".
        Si l'on ignore pas la partie pour les informations dans l'url alors notre système de routage va alors tenter d'appeler la méthode 
        "method?var1=var1&var2=var2" du contrôler "Controller".
        Ci-dessous, on parcourt le tableau contenant la route ($path) et on nettoie les variables qui pourrait contenir des informations pour GET.
    */
    if(isset($_GET) && !empty($_GET))
    {
        /*
           Si la dernière case du tableau $path contient des informations pour GET (infomations dans l'url) alors on "explode" ("découpe") le contenu de la case
           avec pour séparateur "?" ainsi, la première case retourner par explode sera le nom attendu sans les informations pour GET.

           Ex: si $path[1] = "method?var1=toto" alors explode("?", $path[1]) retourne le tableau ["method", "var1=toto"]. Ilsuffit de récupérer la première case
           de ce tableau pour avoir le nom de la métthode.

           (Il n'y a besoin de verifier que la dernière case uniquement car les informations sont toujours passées en fin d'URL)
        */
        $i = sizeof($path);
        $path[$i-1] = explode("?", $path[$i-1])[0];

    }

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
        Permet de concerver la route. Ceci est utile en cas de redirection. Par exemple après un login, on redirige l'utilisateur vers la route qu'il souhaitait atteindre
        grâce à cet objet. (Voir ci-dessous pour plus de détails sur le contrôleur et la méthode)
        On concerve en session cet objet que si la route mène quelque part.
    */
    $route = new Route($controller, $method);
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
            //on concerve la route en session pour qu'elle soit accessible par les autres scripts
            //on ne garde pas les route de login et de logout car on ne veut pas être redirigé vers elles
            if($route->getRoute() != "User/login" && $route->getRoute() != "User/logout")
            {
                //on sauvegarde dans le tableau de session sous la clef "redirectRoute" car on accèdera à cette route uniquement si on redirige.
                //Ainsi, la route vers laquelle sera redirigé l'utilisateur sera la route (page) qu'il a visité juste avant
                $_SESSION["redirectRoute"] = serialize($route);
            }
                
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
