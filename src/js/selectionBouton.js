var urlRoles = "http://localhost/PROJETWEB/jouer/selectRole";

$(function(){
    $("button").click(function(){
        var ok = confirm("Voulez-vous valider ce choix ?");
        if(ok == true)
        {
            $.ajax({
                url: urlRoles,
                type: "POST",
                data: "role=" + $( this ).text(),
                success: function(result){
                    alert(result)
                }
            });
        }        
    });         
});