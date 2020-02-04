<form method="post" action="<?php echo SITE_ROOT ?>User/Login" class="form">
  <ul>
    <li>
      <h1 id="labelConnexion">Connexion</h1>
    </li>
    <li>
      <input type="text" name="nom" placeholder="Nom" class="inputTexte"/>
    </li>
    <li>
      <input type="text" name="prenom" placeholder="Prénom" class="inputTexte"/>
    </li>
    <li>
      <input type="password" name="password" placeholder="Mot de passe" class="inputTexte"/>
    </li>
    <li>
      <input type="submit" value="JOUER" class="submitButton"/>
    </li>
    <li>
      <a href="<?php echo SITE_ROOT ?>User/signup" id="labelPas">Pas de compte ? Créez-en un nouveau.</a>
    </li>
  </ul>
</form>
