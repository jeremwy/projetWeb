<nav>
    <ul id="navList">
        <li class="navItem"><a href="<?php echo SITE_ROOT; ?>">Accueil</a></li>
        <?php
            if(!isset($_SESSION["user"]))
            {
                echo '<li class="navItem"><a href="' . SITE_ROOT . 'User/login">Connexion</a></li>';
            }
            else
            {
                echo '<li class="navItem"><a href="' . SITE_ROOT . 'User/logout">Déconnexion</a></li>';
            }
        ?>
    </ul>
</nav>