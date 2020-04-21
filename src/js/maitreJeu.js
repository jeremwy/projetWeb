var urlSite = "http://localhost/PROJETWEB/";
var urlPartieEvent = urlSite + "PartieEvent/";
var urlHorloge = urlPartieEvent + "horloge";

var tempsAppel = 1000; //en secondes

//pour changer l'intervalle d'une fonction "setInterval", il faut appeler "clearInterval" sur une varibale.
//Pour cela, la fonction "setInterval" doit être stockée dans une variable :
var horlogeintervalleFunction;

function horloge() {
    horlogeintervalleFunction =  setInterval( function(){
                                    $.ajax({
                                        url: urlHorloge,
                                        type: "POST"
                                    });
                                }, tempsAppel);
}

function changeTime() {
    let boutonTemps =  $("#bouton_temps");
    let temps = boutonTemps.html();
    switch(temps) {
        case("x1"):
            tempsAppel = 500;
            boutonTemps.html("x2");
            break;
        case("x2"):
            tempsAppel = 250;
            boutonTemps.html("x4");
            break
        default:
            tempsAppel = 1000;
            boutonTemps.html("x1");
            break;
    }
    //on efface l'intervalle de la fonction horloge afin de le remplacer
    clearInterval(horlogeintervalleFunction);
    horloge();
}

$( document ).ready(function(){
    horloge();
});