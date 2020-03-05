<h1><?php echo $dReponse["title"] ?></h1>

<table id="tabParties">
    <thead>
        <tr>
            <td>Identifiant de partie</td>
            <td>Nombre de joueurs</td>
            <td></td>
        </tr>
    </thead>
    <?php
    foreach($dReponse["parties"] as $partie)
    {
        echo '<tr class="partieItem"><td class="partieInfo">' . $partie->getId() . '</td><td class="partieInfo">' . $partie->getNbJoueur() . '/7</td><td class="partieInfo"><a class="submitButton" href="' . SITE_ROOT . 'partie/loby?id=' . $partie->getId() . '">Jouer</a></td></tr>';
    }
    ?>
</table>

<div id="creerPartieDiv">
    <a class="whiteLink" href="<?php echo SITE_ROOT . "jouer/creer" ?>">Créer une partie</a>
</div>