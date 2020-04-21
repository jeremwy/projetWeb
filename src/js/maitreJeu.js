var urlSite = "http://localhost/PROJETWEB/";
var urlPartieEvent = urlSite + "PartieEvent/";
var urlHorloge = urlPartieEvent + "horloge";

var vitesse = 1;

//pour changer l'intervalle d'une fonction "setInterval", il faut appeler "clearInterval" sur une varibale.
//Pour cela, la fonction "setInterval" doit être stockée dans une variable :
var horlogeintervalleFunction;

function horloge() {
    horlogeintervalleFunction =  
    setInterval( function(){
        if(vitesse != 0)
        {
            $.ajax({
                url: urlHorloge,
                type: "POST",
                data: "vitesse=" + vitesse,
                success: function(result){
                    console.log(result);
                }
            });
        }
    }, 1000);
}

function changeTime() {
    let boutonTemps =  $("#bouton_temps");
    let temps = boutonTemps.html();
    switch(temps) {
        case("x0"):
            vitesse = 1;
            boutonTemps.html("x1");
            break;
        case("x1"):
            vitesse = 2;
            boutonTemps.html("x2");
            break;
        case("x2"):
            vitesse = 4;
            boutonTemps.html("x4");
            break
        default:
            vitesse = 0;
            boutonTemps.html("x0");
            break;
    }
    //on efface l'intervalle de la fonction horloge afin de le remplacer
    clearInterval(horlogeintervalleFunction);
    horloge();
}

$( document ).ready(function(){
    horloge();
});