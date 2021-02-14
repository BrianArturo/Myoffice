jQuery( document  ).on( "click","#save_options", function(event) {
    jQuery('#mywidgetframe').toggleClass('openwidgetframe','2000');
    var caso_id     =   $("#caso_id").val();
    var tags        =   $("#tags").val();
    var valor       =   $("#valor").val()
    var formato     =   $("#formato option:selected").val();
    $.ajax({
        type: 'POST',
        url: 'ajax_options.php',
        data: {
            'function':      'create_option',
            'caso_id':       caso_id,
            'formato': formato,
            'tags':   tags ,
            'valor':  valor
        },
        success: function(response){
            console.log(response);
            $("#tags").val("");
            $("#valor").val("");
            var htmlinfo    =   '<div class="col-md-3  ">'+ 
                                    '<div class="form-group" data-toggle="tooltip" data-placement="top" title="'+ tags +'">'+
                                        '<i class="fa '+ response +'" aria-hidden="true"></i> '+
                                        valor+
                                    '</div>'+
                                '</div>';

            $("#infobox").append(htmlinfo);
                
        }
    }); 
});

$(document).ready(function() {
    jQuery( ".option_box" ).on( "taphold", function( event ) {  

        $(event.target).hide(); 
     } )




    $(".option_box").pressAndHold({
        holdTime: 1000,
  progressIndicatorRemoveDelay: 900,
  progressIndicatorColor: "blue",
  progressIndicatorOpacity: 0.3
    });

    $(".option_box").on('start.pressAndHold', function(event) {
        console.log("start"); 
    });
    $(".option_box").on('complete.pressAndHold', function(event) {
        console.log("complete");
        $(event.target).hide(); 
        var caso_id     =   $("#caso_id").val();
        var option_id   =   $(event.target).attr("option_id");
        
        $.ajax({
            type: 'POST',
            url: 'ajax_options.php',
            data: {
                'function':      'delete_option',
                'caso_id':       caso_id,
                'option_id':        option_id
            },
            success: function(response){
                console.log(response);
            }
        }); 




    });
    $(".option_box").on('end.pressAndHold', function(event) {
        console.log("end");
    });
});

