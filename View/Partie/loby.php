<h1 id="partieNom"><?php echo $dReponse["title"]; ?></h1>
<p>Choisir un rôle :</p>
<div class="roles">
    <div class="boutonChoix" id="maitre">
        <img src="<?php echo SITE_ROOT . "src/img/"; ?>maitre.jpg" alt="Maître du jeu">
        <label for="maitre">Maitre du jeu</label>
    </div>
    
    <div class="boutonChoix" id="chefPolicier">
        <img src="<?php echo SITE_ROOT . "src/img/"; ?>policier.jpeg" alt="Policier">
        <label for="maitre">Policier</label>
    </div>

    <div class="boutonChoix" id="chefMedecin">
        <img src="<?php echo SITE_ROOT . "src/img/"; ?>medecin.jpg" alt="Médecin">
        <label for="maitre">Médecin</label>
    </div>

    <div class="boutonChoix" id="chefPompier">
        <img src="<?php echo SITE_ROOT . "src/img/"; ?>pompier.jpg" alt="Pompier">
        <label for="maitre">Pompier</label>
    </div>
</div>

<div id="lancerPartie"></div>

<aside id="asideChat">
    <?php include("View/Chat/chat.php"); ?>
</aside>

<p id="partieId" class="hidden"><?php echo $dReponse["partieId"]; ?></p>