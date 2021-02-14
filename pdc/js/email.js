jQuery( document  ).on( "click",".guestaccess", function(event) {
    idobj = event.target.id
    var caso_id = $("#caso_id").val();
    var contact_id = $(this).attr("contact-id");
    console.log("Manda una invitaccion a nombre de: "+ contact_id);

            $.ajax({
                type: 'POST',
                url: 'ajax_email.php',
                data: {
                    'function':      'mail_invitation',
                    'caso_id':          caso_id,
                    'contac_id': contact_id

                },
                success: function(response){
                    console.log(idobj);
                    $("#"+idobj).addClass("text-success");
                    $("#"+idobj).effect("pulsate", { times:20 });
                    console.log(response);
                }
            });   

});
