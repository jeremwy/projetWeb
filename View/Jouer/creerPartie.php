<form action="<?php echo SITE_ROOT . "jouer/creer"; ?>" method="post" class="form" onsubmit="return creerPartieCheck()">
    <ul>
        <li class="formField">
            <h1 id="labelConnexion"><?php echo $dReponse["title"]; ?></h1>
        </li>
        <li class="formField">
            <input type="text" class="inputTexte" name="nomPartie" placeholder="Nom de la partie"  value="<?php echo isset($dReponse["form"]["data"]["nomPartie"]) ? $dReponse["form"]["data"]["nomPartie"] : ""; ?>"/>
        </li>
        <li class="formField">
            <label for="oui">
                <input type="radio" name="maitre" id="oui" value="oui">Je veux être le maître du jeu.
            </label>
        </li>
        <li class="formField">
            <label for="non">
                <input type="radio" name="maitre" id="non" value="non">Je ne veux pas être le maître du jeu.
            </label>
        </li>
        <li class="formField">
            <input type="submit" value="CREER" class="submitButton"/>
        </li>
    </ul>
    <div id="errors">
    <?php
      if(isset($dReponse["form"]["emptyField"]) && $dReponse["form"]["emptyField"] == 1)
      {
        echo '<p class="error">Erreur : Tous les champs doivent être remplis.</p>';
      }
      //s'il y a des messages à afficher
      if(isset($dReponse["form"]["message"]))
      {
        foreach($dReponse["form"]["message"] as $message)
        {
          echo '<p class="error">Erreur : ' . $message . '</p>';
        }
      }        
    ?>
  </div>
</form>