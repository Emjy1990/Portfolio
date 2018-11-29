/*********** event listener pour l'apparition disparition de l'ajout au panier **************/

 $('.btnaddCart').on('click',function(event){
   $('.addCart'+$(event.currentTarget).data("id")).css("display","block");
   $('.list').css("pointer-events", "none");
 });

 $('.close').on('click', function(){
   $('.addCart').css("display","none");
   $('.list').css("pointer-events", "initial");
 });

/**********event listener pour les update de profil ***************************************/

function updateProfil(click,close,form,div) {
  $(click).on('click',function(){
    $(div).toggle();
    $(form).toggle();
  });

  $(close).on('click',function(){
    $(div).toggle();
    $(form).toggle();
  });
};


updateProfil('#updateProfilUsernameClick','#updateProfilUsernameClose','#updateProfilUsernameForm','#updateProfilUsername');

updateProfil('#updateProfilFirstnameClick','#updateProfilFirstnameClose','#updateProfilFirstnameForm','#updateProfilFirstname');

updateProfil('#updateProfilLastnameClick','#updateProfilLastnameClose','#updateProfilLastnameForm','#updateProfilLastname');

updateProfil('#updateProfilDescriptionClick','#updateProfilDescriptionClose','#updateProfilDescriptionForm','#updateProfilDescription');

/***** Panier ****/


    // On cache e div a afficher :
    $("#basket").click(function () {
$("enplus").toggle("slow");
});

 
