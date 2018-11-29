
/******   il arrive que je puisse cliquer sur 3 cartes d'affilés je dois avoir
un souci d'algo mais j'ai pas trouver et je suis pas arriver a finir la gestion
des highscores  ***************************************************************/



var app = {

  init: function(){

    console.log("Initialized");

    /**************gestion de l'acceuil***************************************/
    $(".left").on("click",function(){//quand on clique sur soft
      $("#acceuil").hide();
      creationPlateau(14,1.66);
    });
    $(".right").on("click",function(){//quand on clique sur hard
      $("#acceuil").hide();
      creationPlateau(18,1.11);
    });
    $(".little").on("click",function(){//quand on click sur little on doit reset les highscore
      location.reload();
    });
    /**************************************************************************/

    function creationPlateau(nbPaire,timerSpeed){

      $("#plateau").empty();//on vide le plateau
      /***********on crée les cartes*******************/
      var imageCarte = [];
      for (i=0;i<nbPaire;i++){
        imageCarte.push("0 -" + (i*100)+"px");
        imageCarte.push("0 -" + (i*100)+"px");
      }
      /***********on les mélange*******************/
      for(i=0; i<(nbPaire*2); i++){//on assigne un nombre au pif a chaque index
        var hasard = NbAleatoire((nbPaire*2)-1);
        var sauve=imageCarte[i];
        imageCarte[i]=imageCarte[hasard];
        imageCarte[hasard]=sauve;
      }
      function NbAleatoire(max){
        return Math.floor(Math.random()*(max-0+1))+0;
      }
      /****************on les pose sur le plateau*************/
      /*************on adapte la taille au nb de carte*********/
      if(nbPaire===14){
        $("#plateau").css("width","70%");
      }
      else{
        $("#plateau").css("width","90%");
      }
      var carte =[];
      var cpt = 0;
      var nbPairesTrouver = 0;
      var premiereCarte = 0;
      var deuxiemeCarte = 0;
      /***************la boucle qui crée le plateau************/
      for( i = 0 ; i < (nbPaire*2) ; i++){

        var div = document.createElement("div");
        carte.push(div);
        carte[i].style.backgroundPosition = imageCarte[i];
        document.getElementById("plateau").appendChild(div).className="carte cache";
        carte[i].addEventListener("click",function(i){//on ecoute le click
          if(cpt===1){
            deuxiemeCarte = i.target;
            deuxiemeCarte.className="carte image";
            cpt++;
            if(premiereCarte.style.backgroundPosition === deuxiemeCarte.style.backgroundPosition){//si les deux images sont identique
              nbPairesTrouver++;
              setTimeout(function(){
                cpt=0;
              },10);
              if(nbPairesTrouver===nbPaire){
                setTimeout(function(){//on laisse le temps a la derniere carte de se retourner
                    clearTimeout(Horloge);
                  alert("Gagné");
                  switch(highscore){
                    case timer<highscore[0]:
                    highscore[0]=timer;
                    break;
                    case timer<highscore[1]:
                    highscore[1]=timer;
                    break;
                    case timer<highscore[2]:
                    highscore[2]=timer;
                    break;
                  }
                  $("#gagne").show()
                  $("#gagne").on("click",function(){
                    $("#gagne").hide();
                    $("#plateau").empty();
                    $("#acceuil").show();
                  });

                },100);
              }
            }
            else{
              setTimeout(function(){
                premiereCarte.className="carte cache";
                deuxiemeCarte.className="carte cache";
                cpt=0;
              },500);
            }

          }
          if(cpt===0){
            premiereCarte = i.target;
            premiereCarte.className="carte image";
            cpt++;
          }
        });//fin de l'écoute du click
      }//fin de la boucle de creation
      /**************High Score**********************************/
      var highscore=[localStorage.getItem("score1"),localStorage.getItem("score2"),localStorage.getItem("score3")];
      /***************TIMER***********************************/
      var timer = 0;
      $( "#progressbar" ).progressbar({//on met en place la progressbar grace a jqueryui
        value: timer
      });
      var Horloge = setInterval(function(){//mise a jour de la barre de progression
        timer = timer + timerSpeed;
        $( "#progressbar" ).progressbar({
          value: timer
        });
        //gestion des couleurs
        if(timer<50){$( ".ui-progressbar-value" )[0].style.backgroundColor = "green";}
        if(timer>50&&timer<75){$( ".ui-progressbar-value" )[0].style.backgroundColor = "orange";}
        if(timer>75){$( ".ui-progressbar-value" )[0].style.backgroundColor = "red";}
        if(timer>100){//lorsque le temps est depassé
          clearTimeout(Horloge);
          timer=0;
            $("#perdu").show()
            $("#perdu").on("click",function(){
              $("#perdu").hide();
                  $("#plateau").empty();
                  $("#acceuil").show();
            });
        }
      },1000);
    }//fin creationPlateau

  }//fin de init

}

$(app.init);
