<div id="header-container">
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
                    if(isset($_SESSION["partie"]) && $_SESSION["partie"]->getId() != null)
                    {
                        echo '<li class="navItem"><a href="' . SITE_ROOT . 'partie/loby?id=' . $_SESSION["partie"]->getId() .'">Ma partie</a></li>';
                    }
                }
            ?>
        </ul>
    </nav>
    <div id="switch-theme-container">
       <button id="switch-theme-button" onclick="switch_theme()">Thème blanc</button>
    </div>
</div>
