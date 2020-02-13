function init()
{
    var form = document.forms[0].elements;
    //on parcourt les 3 premiers champs (le 4 ème est le bouton pour envoyer le formulaire)
    for(var i = 0; i <= 2; i++)
    {
        //si la valeur du champs est vide alors on indique que le champs n'est pas valid et on l'entour en rouge
        form[i].style.border = "none";
    }
    var errorsDiv = document.querySelector("#errors");
    errorsDiv.innerHTML = "";
}

//ajoute un message d'erreur
function addError(message)
{
    var errorsDiv = document.querySelector("#errors");
    var para = document.createElement("p");
    para.appendChild(document.createTextNode(message));
    para.classList = ["error"];
    errorsDiv.appendChild(para);
}

function creerPartieCheck()
{
    init();
    var form = document.forms[0];
    var nom = form.elements[0];
    var ouiButton = form.elements[1];
    var nonButton = form.elements[2];
    
    var valid = true;

    if(nom.value == "")
    {
        nom.style.border = "solid red 1px";
        addError("Un nom doit être renseigner");
        valid = false;
    }
    if(!ouiButton.checked && !nonButton.checked)
    {
        addError("Merci de cocher une case");
        valid = false; 
    }
    return valid;
}