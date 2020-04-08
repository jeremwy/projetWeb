var urlSite = "http://localhost/PROJETWEB/";

var urlChatEnvoi = urlSite + "chat/envoiMessage";
var urlChatGetMessage = urlSite + "chat/getLastMessage";
var lastId = 0;
var messageNonLu = 0;

$( document ).ready(function(){
    //au début on cache les notifications (on est sur qu'il n'y en a aucune).
    $("#notifications").hide();

    charger();  //appel de la fonction qui permet de récupérer les derniers messages

    $("#envoi").click(function() {
        var message = $('#saisieMessage').val();
        if(message != ""){ // on vérifie que le message ne soit pas vide
            $.ajax({
                url: urlChatEnvoi, // on donne l'URL du fichier de traitement
                type: "POST", // la requête est de type POST
                data: "message=" + message + "&partieId=" + $( "#partieId" ).text(), // et on envoie nos données
                success : function(response){
                    console.log(response);
                    $('#saisieMessage').val("")
                }
            });
        }
        return false;
    });

    $("#toggleChatBouton").click( function() {
        $( "#chat" ).toggle();
        if($("#chat").is(":visible")){
            $("#notifications").hide();
            messageNonLu = 0;
        }
    });

    //premet de simuler un clique sur le bouton envoyer quand l'utilisateur appuie sur "entrer".
    $("#saisieMessage").keydown(function(e) {
        var key = e.which;
        if (key == 13) {
            $("#envoi").trigger("click");
        }
    });
});

function charger(){
    setInterval( function(){
        $.ajax({
            url: urlChatGetMessage,
            type: "POST",
            data: "partieId=" + $( "#partieId" ).text(),
            success: function(jsonReponse){
                if(lastId < jsonReponse.id){
                    lastId = jsonReponse.id;
                    var $tr = $("<tr>", {id: jsonReponse.id});
                    $tr.append("<td><span class='userFrom'>" + jsonReponse.nom + " " + jsonReponse.prenom + ":</span> " + jsonReponse.message + "</td>");
                    $("#messages").append($tr);
                    if($("#chat").is(":hidden")){
                        messageNonLu += 1;
                        $("#notifications").show();
                        $("#notifications").text(messageNonLu);
                    } 
                }                
            }
        });
    }, 300); // Recharge toutes les 0.3 secondes
}