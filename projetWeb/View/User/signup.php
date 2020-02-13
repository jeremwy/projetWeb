<form method="post" action="<?php echo SITE_ROOT ?>User/signup" class="form" onsubmit="return signupCheck()">
  <ul>
    <li class="formField">
      <h1 id="labelConnexion">Inscription</h1>
    </li>
    <li class="formField">
      <input type="text" name="nom" placeholder="Nom" class="inputTexte" value="<?php echo isset($dReponse["form"]["data"]["nom"]) ? $dReponse["form"]["data"]["nom"] : ""; ?>"/>
    </li>
    <li class="formField">
      <input type="text" name="prenom" placeholder="Prénom" class="inputTexte" value="<?php echo isset($dReponse["form"]["data"]["prenom"]) ? $dReponse["form"]["data"]["prenom"] : ""; ?>"/>
    </li>
    <li class="formField">
      <input type="password" name="password" placeholder="Mot de passe" class="inputTexte"/>
    </li>
    <li class="formField">
      <input type="password" name="passwordRepeat" placeholder="Confirmez le mot de passe" class="inputTexte"/>
    </li>
    <li class="formField">
      <input type="submit" value="S'inscrire" class="submitButton"/>
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