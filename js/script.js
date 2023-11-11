$(function(){
    $(".navbar a, footer a").on("click", function(event){

        //c'est pour l"amimation lorsqu'on clique sur le navbar et footer. Il nous a dit qu'on n'a pas besoin de comprendre en détail. C'est du jQuery ça
        event.preventDefault(); //jQuery event.preventDefault() Method  stops the default action of an element from happening.
        var hash = this.hash;

        $("body, html").animate({scrollTop: $(hash).offset().top}, 900, function(){window.location.hash = hash;})
        //le html dans le () ci-dessus a été ajouté pour que la fonction fonctionne égalemet sur FireFox
    });

    $("#contact-form").submit(function(e){
        e.preventDefault(); //enlève le comportement par défaut lorsque je soumes un formulaire, qui est définie dans la balise input/action="" qui définit la page qui le bouton submit lance. Mais, je ne veux pas cela, je veux faire du php.
        $(".comments").empty(); //comments c'est pour les messages d'erreur, à chaque fois, on veut le partir de zéro
        var postdata = $("#contact-form").serialize(); //toutes les infos dans contact-form seront mise dans la variable postdata de façon sérialisé

        $.ajax({ //syntaxe de l'ajax [The ajax() method is used to perform an AJAX (asynchronous HTTP) request. All jQuery AJAX methods use the ajax() method. SOF]
            type: "POST", //type d'info qu'on veut transmettre type post
            url: "php/contact.php", //url vers lequel script qui nous traitera l'information, qui ensuite mettra à jour notre html avec ces infos
            data: postdata, //objet de JSON; var créé en haut
            dataType: "json", //type d'objet
            success: function(result){ //si la requête ajax est un succès, on va exécuter une fonction avec cett valeur "result"; on ferra toute la manipulation suivante

                if(result.isSuccess){ //si le paramêtre isSuccess=true du resultat de ma requête (emregistré dans resultat), ça veut dire que je n'ai pas eu des messages d'erreur. Alors, je dois éxecuter ceci. C'est une fonction de retour du PHP
                    $("#contact-form").append("<p>Your message was submitted, thank you for contacting me!</p>");
                    $("#contact-form")[0].reset(); //une fonction qui fera du resrt dans tous les éléments do contact-form. [the magic of jQuery is that everything is a set (array). Just as if you were using a regular array in jQuery [0] selects the first element. But in jQuery, it selects the actual html of the element -SOF]
                } else{
                    $("#firstname + .comments").html(result.firstnameError); // ici le result (qui est le post du contact.php) sera inclut dans le html, mais sulement le firstnameError; et ce dans .comments du #firstname.
                    $("#name + .comments").html(result.nameError);
                    $("#email + .comments").html(result.emailError);
                    $("#phone + .comments").html(result.phoneError);
                    $("#message + .comments").html(result.messageError);                    
                }               

            }

        });
    });

})