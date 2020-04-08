var urlSite = "http://localhost/PROJETWEB/";

var urlSelectionRoles = urlSite +  "partie/selectRole";
var urlGetRoles =  urlSite + "partie/getRoles";
var urlIsPartieLancee = urlSite +  "partie/isPartieLAncee";
var urlPartie = urlSite +  "partie";
var urlLancerPartie = urlSite +  "partie/lancerPartie";
//permet de savoir si le joueur est maître du jeu ou non
var estMaitre = 0;

function isPartieLancee() {
    setInterval(function() {
        $.ajax( {
            url: urlIsPartieLancee,
            success: function(result) {
                if(result === "1")
                    window.location.replace(urlPartie);
            }
        });
    }, 500);
}

function getRoles() {
    setInterval(function() {
        $.ajax( {
            url: urlGetRoles,
            type: "POST",
            data: "partieId=" + $( "#partieId" ).text(),
            success: function(result) {
                for (let [role, valeur] of Object.entries(result)) {
                    if(role == "maitre" && valeur == "choisi" && estMaitre == 0) {
                        estMaitre = 1;
                        $( "#lancerPartie" ).replaceWith( '<div id="lancerPartie"><button id="lancerPartieBouton" class="submitButton" onclick="lancerPartie()">Lancer la partie</button></div>' );
                    }
                    $( "#"+role ).attr("class", "boutonChoix "+valeur);
                }
            }
        });
    }, 500);
}

function lancerPartie() {
    var ok = confirm("Confirmer le lancement de la partie");
    if(ok) {
        $.ajax( {
            url: urlLancerPartie,
            success: function(result) {
                console.log(result);
                if(result == "0") {
                    alert("Une erreur est survenue, impossible de lancer la partie.");
                }
            }
        });
    }
}

$(function() {
    getRoles();
    isPartieLancee();
    $(".boutonChoix").click(function() {
        boutonClasse = $( this ).attr("class");
        if(boutonClasse != "boutonChoix choisi" && boutonClasse != "boutonChoix indisponible") {
            var ok = confirm("Voulez-vous valider ce choix ?");
            if(ok)
            {
                $.ajax({
                    url: urlSelectionRoles,
                    type: "POST",
                    data: "role=" + $( this ).attr( "id" ) + "&partieId=" + $( "#partieId" ).text(),
                    success: function(result) {
                        console.log(result);
                    }
                });
            }  
        }              
    });         
});

