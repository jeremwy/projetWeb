urlRequeteServeur = "http://aragon.iem/~jt798633/index.php/maj";

function requete() {
    setInterval(() => {
        $.ajax({
            url : urlRequeteServeur, // on donne l'URL du fichier de traitement
            success : function(response){
                alert(response);
            }
        });
    }, 1000);
}

$( document ).ready(function(){
    requete();
});