var app = {
  map: null,
  init: function() {
    console.log('init');
    
    // Interception du form de connexion
    $('#formSignin').on('submit', app.formSigninSubmit);
  },
  formSigninSubmit: function(evt) {
    // On empeche l'envoi du formulaire
    evt.preventDefault();
    
    // On récupère les données du form
    var formData = $(this).serialize();
    
    // On exécute l'appel Ajax
    // L'URL à appeler est stockée dans l'attribut "action" du formulaire
    $.ajax({
      url : $(this).attr('action'),
      method : 'POST',
      dataType : 'json',
      data : formData
    }).done(function(response) {
      console.log(response);
      
      // Je stocke dans une variable la partie content pour ne pas faire pleins de recherches inutiles dans le DOM
      var $alertDiv = $('#alertSignin');
      var $content = $alertDiv.find('.content');
      
      // Si ok
      if (response.code == 1) {
        // J'ajoute la classe danger
        $alertDiv.removeClass('alert-danger').addClass('alert-success');
        // J'affiche le message de succès
        $content.html('Connexion réussie');
        // J'affiche la div
        $alertDiv.show();
        
        
        var urlToRedirect = response.redirect;
        
        // Je redirige après 2 secondes
        window.setTimeout(function() {
          location.href = urlToRedirect;
        }, 2000);
      }
      // Sinon, il y a des erreurs à afficher
      else {
        // J'ajoute la classe danger
        $alertDiv.addClass('alert-danger');
        // Je vide le contenu (anciennes erreurs)
        $content.html("");
        // Je parcours les erreurs retournées par l'appel Ajax
        $.each(response.errorList, function(index, value) {
          // Je crée un élément "div" avec du code html à l'intérieur
          var $currenterrorDiv = $('<div>', {
            html: value
          });
          // J'ajoute cet élément au DOM
          $content.append($currenterrorDiv);
        });
        // J'affiche la div
        $alertDiv.show();
      }
      
    }).fail(function() {
      alert('ajax failed');
    });
  },
  initMap: function() {
    $.ajax({
      url : "https://maps.googleapis.com/maps/api/geocode/json",
      method: 'get',
      dataType: 'json',
      data: {
        apikey: "AIzaSyAXyv9VfmYc7DfpoC-vxXgtSo5sYt2LnuA",
        address: eventAddress
      }
    }).done(function(response) {
      console.log(response);

      // Active la map
      app.map = new google.maps.Map(document.getElementById('map'), {
        center: response.results["0"].geometry.location,
        zoom: 8
      });
      // Place un marqueur sur l'adresse
      var marker = new google.maps.Marker({
        position: response.results["0"].geometry.location,
        map: app.map
      });
    });
  }
};

$(app.init);
