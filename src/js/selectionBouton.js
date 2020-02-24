var urlSelectionRoles = "http://localhost/PROJETWEB/jouer/selectRole";
var urlGetRoles = "http://localhost/PROJETWEB/jouer/getRoles";

function getRoles() {
    setInterval(function() {
        $.ajax( {
            url: urlGetRoles,
            success: function(result) {
                console.log(result);
                for (let [role, valeur] of Object.entries(result)) {
                    $( "#"+role ).attr("class", "boutonChoix "+valeur);
                }
            }
        });
    }, 500);
}

$(function() {
    getRoles();   
    $(".boutonChoix").click(function() {
        boutonClasse = $( this ).attr("class");
        if(boutonClasse != "boutonChoix choisi" && boutonClasse != "boutonChoix indisponible") {
            var ok = confirm("Voulez-vous valider ce choix ?");
            if(ok == true)
            {
                $.ajax({
                    url: urlSelectionRoles,
                    type: "POST",
                    data: "role=" + $( this ).attr( "id" ) + "&partieId=" + $( "#partieId" ).text()
                });
            }  
        }              
    });         
});

