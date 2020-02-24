<nav>
    <ul id="navList">
        <li class="navItem"><a href="<?php echo SITE_ROOT; ?>">Accueil</a></li>
        <?php
            if(!isset($_SESSION["user"]))
            {
                echo '<li class="navItem"><a href="' . SITE_ROOT . 'user/login">Connexion</a></li>';
            }
            else
            {
                echo '<li class="navItem"><a href="' . SITE_ROOT . 'jouer">Jouer</a></li>';
                echo '<li class="navItem"><a href="' . SITE_ROOT . 'user/logout">Déconnexion</a></li>';
                if(isset($_SESSION["partie"]["id"]) && !empty($_SESSION["partie"]["id"]))
                {
                    echo '<li class="navItem"><a href="' . SITE_ROOT . 'jouer/loby?id=' . $_SESSION["partie"]["id"] .'">Ma partie</a></li>';
                }
            }
        ?>
    </ul>
</nav>