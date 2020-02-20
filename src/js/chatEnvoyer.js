var urlChatEnvoi = "http://localhost/projetweb/chat/envoiMessage";
var urlChatGetMessage = "http://localhost/projetweb/chat/getLastMessage";
var lastId = 0;

$( document ).ready(function(){
    $("#envoi").click(function() {
        var message = $('#champsSaisieMessage').val();
        if(message != ""){ // on vérifie que le message ne soit pas vide
            $.ajax({
                url : urlChatEnvoi, // on donne l'URL du fichier de traitement
                type : "POST", // la requête est de type POST
                data : "message=" + message, // et on envoie nos données
                success : function(response){
                    $('#champsSaisieMessage').val("")
                }
            });
        }
        return false;
    });
});

function charger(){
    setInterval( function(){
        $.ajax({
            url : urlChatGetMessage,
            success : function(jsonReponse){
                if(lastId < jsonReponse.id){
                    lastId = jsonReponse.id;
                    var $tr = $("<tr>", {id: jsonReponse.id});
                    $tr.append("<td><span class='userFrom'>" + jsonReponse.nom + " " + jsonReponse.prenom + ":</span> " + jsonReponse.message + "</td>");
                    $("#messages").append($tr);
                }                
            }
        });
    }, 300); // Recharge toutes les 0.3 secondes

}

charger();