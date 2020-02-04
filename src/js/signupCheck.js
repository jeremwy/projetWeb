//permet d'enlever les messages d'erreur et la bordure rouge des champs non remplis
//on appelle cette function avant de faire une vérification
function init()
{
    var form = document.forms[0].elements;
    //on parcourt les 4 premiers champs (le 5 ème est le bouton pour envoyer le formulaire)
    for(var i = 0; i <= 3; i++)
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

//vérifie si tout est rempli
function checkFields()
{
    //on récupère les champs du formaulaire
    var form = document.forms[0].elements;
    var valid = true;

    //on parcourt les 4 premiers champs (le 5 ème est le bouton pour envoyer le formulaire)
    for(var i = 0; i <= 3; i++)
    {
        //si la valeur du champs est vide alors on indique que le champs n'est pas valid et on l'entour en rouge
        if(form[i].value == "")
        {
            valid = false;
            form[i].style.border = "1px solid red";
        }
    }
    
    
    if(valid == false)
    {
        addError("Erreur : Tous les champs doivent être remplis.");
    }
    return valid;
}

//vérifie si les mot de passe sont identiques
function checkPasswords()
{
    var password = document.forms[0].elements[2];
    var passwordRepeat = document.forms[0].elements[3];
    
    if(password.value != passwordRepeat.value)
    {
        addError("Erreur : Les mots de passe ne correspondent pas.");
        password.style.border = "1px solid red";
        passwordRepeat.style.border = "1px solid red";
        return false;
    }
    else
    {
        return true;
    }
}

function signupCheck()
{
    init();
    if(checkFields())
    {
        return checkPasswords();
    }    
    return false;
}