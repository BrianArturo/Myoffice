/*
    salvar el nombre del caso 
    salvar EL codigo del caso
*/ 

jQuery( document  ).on( "click","#cuantia_caso", function(event) {
    jQuery("#cuantia_caso").toggle( "slow" );
    jQuery("#cuantia_caso_container").toggle( "slow" );
   // jQuery("#cuantia_caso_field_save").toggle( "slow" );  
});
$("#cuantia_caso_field_save").on("click",function() {
    console.log("Guardo este valor: "+ jQuery("#cuantia_caso_field").val());
    var caso_id = $("#caso_id").val();
    $.ajax({
        type: 'POST',
        url: 'ajax_caso.php',
        data: {
            'function': 'update_caso_cuantia',
            'caso_id': caso_id,
            'value': $("#cuantia_caso_field").val(),
        },
        success: function(msg){
            console.log( msg);
            $("#cuantia_caso_field").empty();
            $("#cuantia_caso").html(msg);
            $("#cuantia_caso").effect("pulsate", { times:5 });
           

        }
    });
    jQuery("#cuantia_caso").toggle( "slow" );
    jQuery("#cuantia_caso_conta" +
                 "iner").toggle( "slow" );

});




jQuery( document  ).on( "click","#caso_name", function(event) {
    jQuery("#caso_name").toggle( "slow" );
    jQuery("#caso_name_field").toggle( "slow" );
    jQuery("#caso_name_field_save").toggle( "slow" );  
});
$("#caso_name_field_save").on("click",function() {
    console.log("Guardo este valor: "+ jQuery("#caso_name_field").val());
    var caso_id = $("#caso_id").val();
    $.ajax({
        type: 'POST',
        url: 'ajax_caso.php',
        data: {
            'function': 'update_caso_name',
            'caso_id': caso_id,
            'value': $("#caso_name_field").val(),
        },
        success: function(msg){
            console.log( msg);
            $("#caso_name_field").empty();
            $("#caso_name").html(msg);
            $("#caso_name").effect("pulsate", { times:5 });
           

        }
    });
    jQuery("#caso_name").toggle( "slow" );
    jQuery("#caso_name_field").toggle( "slow" );
    jQuery("#caso_name_field_save").toggle( "slow" );

});



jQuery( document  ).on( "click","#caso_code", function(event) {
    jQuery("#caso_code").toggle( "slow" );
    jQuery("#caso_code_field").toggle( "slow" );
    jQuery("#caso_code_field_save").toggle( "slow" );     
    jQuery("#caso_code_container").toggle( "slow" );     
});
$("#caso_code_field_save").on("click",function() {
    console.log("Guardo este valor: "+ jQuery("#caso_code_field").val());
    var caso_id = $("#caso_id").val();
    $.ajax({
        type: 'POST',
        url: 'ajax_caso.php',
        data: {
            'function': 'update_caso_code',
            'caso_id': caso_id,
            'value': $("#caso_code_field").val(),
        },
        success: function(msg){
            console.log( msg);
            $("#caso_code_field").empty();
            $("#caso_code").html(msg);
            $("#caso_code").effect("pulsate", { times:5 });
        }
    });
    jQuery("#caso_code").toggle( "slow" );
    jQuery("#caso_code_field").toggle( "slow" );
    jQuery("#caso_code_field_save").toggle( "slow" );
    jQuery("#caso_code_container").toggle( "slow" );

});

jQuery( document  ).on( "click","#anularCaso", function(event) {
    var caso_id = $("#caso_id").val();
    //var valorPago = $(".valorPago").html();
    var tr = $(this).closest("tr");
    var valorPago = tr.find(".valorPago").html();
    var pagoId = tr.find("#pagoId").val();
    var tipoPago = tr.find("#tipoPago").html();
    console.log(tipoPago);
    $.ajax({
        type: 'POST',
        url: 'ajax_caso.php',
        data: {
            'function': 'anular_pago',
            'caso_id': caso_id,
            'valorPago':valorPago,
            'pagoId':pagoId,
            'tipoPago':tipoPago,
        },
        success: function(msg){
            console.log( msg);
            console.log("Pago Anulado")
            window.location.reload(false); 
        }
    });
});