<?php
    /*
        Tableau de commandes du maître du jeu
    */
?>

<div class="TableauBord">
    <div class="commands_board">
        <p>Vitesse : <button id="bouton_temps" onclick="changeTime()">x1</button> (cliquez pour changer la vitesse).</p>
    </div>
    <div class="victimesSection">
        <h2>Victimes : </h2>
        <div class="tableauVictimes">
            <?php
                foreach($dReponse["victimes"] as $victime)
                {
                    echo '<div class="carteVictime">';
                    echo '<p>' . $victime->getNom() . " "  . $victime->getPrenom() . '</p>';
                    echo '<p>Blessures : ' . $victime->getBlessures() . '</p>';
                    echo '<p>État : ';
                    switch($victime->getEtat())
                    {
                        case 0: echo "Sauf"; break;
                        case 1: echo "Blessé légé."; break;
                        case 2: echo "Blessé moyen."; break;
                        case 3: echo "Blessé grave."; break;
                    }
                    echo '</p>';
                    echo '</div>';
                }
            ?>
        </div>
    </div>
</div>