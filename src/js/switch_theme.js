var url_changement_theme = "http://localhost/projetweb/user/setTheme/";
var css_root = "http://localhost/PROJETWEB/src/css/";


//au démarrage, on récupère le nom du fichier CSS afin de mettre le bon texte sur le bouton de changement de thème.
$( document ).ready(function(){
    let css =  $("#css-stylesheet-0").attr("href");
    if(css == css_root + "theme_noir.css")
        $("#switch-theme-button").html("Thème blanc");
    else if(css == css_root + "theme_blanc.css")
        $("#switch-theme-button").html("Thème noir");
});

function save_theme(theme) {
    $.ajax({
        url : url_changement_theme,
        type : "POST", // la requête est de type POST
        data : "theme=" + theme // et on envoie nos données
    });
}

function switch_theme() {
    let text = $("#switch-theme-button").html();
    if(text == "Thème blanc") {
        $("#css-stylesheet-0").attr("href", css_root + "theme_blanc.css");
        $("#switch-theme-button").html("Thème noir");

        //requête ajax pour sauvegarder le choix de l'utilisateur
        save_theme("blanc");        
    }
    if(text == "Thème noir") {
        $("#css-stylesheet-0").attr("href", css_root + "theme_noir.css");
        $("#switch-theme-button").html("Thème blanc");

        //requête ajax pour sauvegarder le choix de l'utilisateur
        save_theme("noir");    
    }
}