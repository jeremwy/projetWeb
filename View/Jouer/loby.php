<h1 id="partieId"><?php echo $dReponse["title"]; ?></h1>
<p>Choisir un rôle :</p>
<table id="choixRole">
    <tr>
        <td></td>
        <td><button  class="boutonChoix" id="maitre">Maître du jeu</button></td>
        <td></td>
    </tr>
    <tr>
        <td><button class="boutonChoix" id="chefPompier">Pompier</button></td>
        <td><button class="boutonChoix" id="chefPolicier">Policier</button></td>
        <td><button class="boutonChoix" id="chefMedecin">Médecin</button></td>
    </tr>    
</table>

<p id="reponseAjax"></p>