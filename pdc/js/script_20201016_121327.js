var table;
var page;
var scrollTimer;



$(document).ready(function() {




    $("#contact-spool").on("click",'.delete-contact',function(event) {
        var caso_id = $("#caso_id").val();
        //alert("DELETE CONTACT:" +  $(this).attr("contact-id") + " CASO:" + caso_id);

        $.ajax({
            type: 'POST',
            url: 'ajax_caso.php',
            data: {
                'function': 'delete_client',
                'caso_id': caso_id,
                'client_id': $(this).attr("contact-id"),
            },
            success: function(msg){
                console.log( msg);
                $("#contact-spool").empty();
                $("#contact-spool").html(msg);
            }
        });


    });

    jQuery('#add-contact').click(function() {
        $("#contact-form").toggle( "slow" );
    });
    $( function() {


        /*  AUTO COMPLETE */
        if($("#contact_name").length  ){
            $( "#contact_name" ).autocomplete({
                source: "search.php",
                minLength: 3,
                select: function( event, ui ) {
                    var caso_id = $("#caso_id").val();
                    $.ajax({
                        type: 'POST',
                        url: 'ajax_caso.php',
                        data: {
                            'function': 'add_client',
                            'caso_id': caso_id,
                            'client_id': ui.item.id,
                        },
                        success: function(msg){
                            console.log( msg);
                            $("#contact_name").val("");
                            $("#contact-spool").empty();
                            $("#contact-spool").html(msg);
                        }
                    });
                }
            });
        }
        /* END AUTO COMPLETE */
    } );

    /*jQuery(".popup,.gallery-item a").fancybox();
    jQuery(".popupajax").fancybox({type: 'ajax'});*/
    /* SCROLL TO THE TOP */
    jQuery(window).scroll(function(){
        clearTimeout(scrollTimer);
        scrollTimer = setTimeout(function() {
            var scrollY = jQuery(window).scrollTop();

            if (scrollY > 150){
                jQuery('#back-top').fadeIn();
                //jQuery( "#menu" ).addClass( "sticky" );
                //jQuery( ".minimal-menu" ).addClass( "sticky-menu" );
            }else{
                jQuery('#back-top').fadeOut();
                //jQuery( "#menu" ).removeClass( "sticky" );
                //jQuery( ".minimal-menu" ).removeClass( "sticky-menu" );
            }
        }, 100);
    }).trigger('scroll');


    jQuery('#back-top').click(function() {
        jQuery('body,html').animate({scrollTop: 0}, 1000);
    });

    /*END SCROLL TO THE TOP */

    /* SLIDER TOOLS */
    jQuery('#opener').click(function(){
        jQuery('#opener i').toggleClass('rotation','2000');
        jQuery('#mywidgetframe').toggleClass('openwidgetframe','2000');
    })

    jQuery('#opener i').toggleClass('rotation','2000');
    /* ENSSLIDER TOOLS */




    $('[data-toggle="tooltip"]').tooltip()
    $('.notes, .textareas').summernote({minHeight: 300,
            toolbar: [
                // [groupName, [list of button]]
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['font', ['strikethrough', 'superscript', 'subscript']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['link', ['linkDialogShow', 'unlink']],
                ['para', ['ul', 'ol', 'paragraph']] ,
                ['codeview']
            ]
        }
    );
//$('.notes').summernote({minHeight: 150,z});

    table =    $('#contatti').DataTable({	
		"pagingType": "simple_numbers",
		responsive: {details: false},
            language: { url: 'js/Spanish.json'}, 
		dom: "lfrtBip",//'<"top"lB>rt<"bottom"ip>',//"Bfrlip", 
		buttons:[
			{
				//Botón para Excel
				extend: 'excelHtml5',
				titleAttr: 'Excel',
				text: '<i class="fa fa-file-excel"></i> EXCEL',
				"className": 'btn btn-success'
			}, 
			{
				extend: 'pdfHtml5',
				titleAttr: 'PDF',
				text: '<i class="far fa-file-pdf"></i> PDF',
				"className": 'btn btn-danger'
			}
		] 
		
        }
    );
	
});

$.fn.DataTable.ext.pager.numbers_length = 4;






$('#lang').on('change', function() {
    //alert( $(this).find(":selected").val() );
    $( "#load-lang" ).submit();
});

$('#contatti').on( 'length.dt', function ( e, settings, len ) {
    console.log( 'New page length: '+len );

    $.ajax({
        type: 'POST',
        url: 'settings.php',
        data: {
            'val': len,
            'var': 'len',
        },
        success: function(msg){
            console.log( msg);
        }
    });


} );


$('#contatti').on( 'order.dt', function () {

    var order = table.order();
    console.log( 'Ordering on column '+order[0][0]+'  '+order[0][1]+' ' );

    $.ajax({
        type: 'POST',
        url: 'settings.php',
        data: {
            'order': order[0][1],
            'col': order[0][0],
            'val': order[0][0]+','+order[0][1],
            'var': 'order',
        },
        success: function(msg){
            console.log(msg);
        }
    });



} );


$('#contatti').on( 'page.dt', function () {
    var info = table.page.info();
    console.log( 'Showing page: '+info.page+' of '+info.pages );
    //https://datatables.net/reference/api/page()

    $.ajax({
        type: 'POST',
        url: 'settings.php',
        data: {
            'val': info.page,
            'var': 'page',

        },
        success: function(msg){
            console.log(msg);
        }
    });


} );




jQuery( document  ).on( "click","#send", function(event) {

    var form = jQuery('#quote-form');

    if(form[0].checkValidity()===false){
        //alert("non inviare");
    }else{
        //alert("invia");
        event.preventDefault();
        jQuery( "#quote-box" ).hide();
        jQuery("#form-save").show();
        var dataform = jQuery("#quote-form").serializeArray();

        dataform.push({name: 'action', value: 'send_auyama_form'});
        jQuery.ajax({
            url : 'send-quota.php',
            type : 'post',
            data : dataform ,
            success : function( response ) {

                jQuery("#form-save").hide();
                jQuery( "#quote-box" ).show();
                jQuery( "#quote-box" ).empty();
                jQuery("#quote-box").html(response);

            }
        });
    }


});


jQuery( document  ).on( "click","#update_contact", function(event) {
    var form = jQuery('#contact_form');
    if(form[0].checkValidity()===false){
        //alert("non inviare");
    }else{
        //alert("invia");
        event.preventDefault();

        jQuery("#spinner").toggle( "slow" );
        var dataform = jQuery("#contact_form").serializeArray();

        dataform.push({name: 'function', value: 'update_contact'});
        jQuery.ajax({
            url : 'ajax_contact.php',
            type : 'post',
            data : dataform ,
            success : function( response ) {

                jQuery("#spinner").toggle( "slow" );
                jQuery( "#log" ).toggle( "slow" );
                jQuery( "#log" ).empty();
                jQuery("#log").html(response);
                jQuery( "#log" ).delay(2000).toggle( "slow" );
            }
        });
    }

});

jQuery( document  ).on( "click","#create_contact", function(event) {
    var form = jQuery('#contact_form');
    if(form[0].checkValidity()===false){
        //alert("non inviare");
    }else{
        //alert("invia");
        event.preventDefault();
        $("#create_contact").attr("disabled", true);

        jQuery("#spinner").toggle( "slow" );
        var dataform = jQuery("#contact_form").serializeArray();

        dataform.push({name: 'function', value: 'create_contact'});
        jQuery.ajax({
            url : 'ajax_contact.php',
            type : 'post',
            data : dataform ,
            success : function( response ) {

                jQuery("#spinner").toggle( "slow" );
                jQuery( "#log" ).toggle( "slow" );
                jQuery( "#log" ).empty();
                jQuery("#log").html(response);
                jQuery( "#log" ).delay(2000).toggle( "slow" );
                setTimeout(function(){
                    $(window.location).attr('href', 'contactos.php');
                }, 4000);

            }
        });
    }

});

jQuery( document  ).on( "click","#save_caso", function(event) {
    $("#save_caso").attr("disabled", true);
    event.preventDefault();
    jQuery("#spinner").toggle( "slow" );
    var caso_id     = $("#caso_id").val();
    var nota_caso   = $("#notes").val();

    $.ajax({
        type: 'POST',
        url: 'ajax_caso.php',
        data: {
            'function':  'save_caso',
            'caso_id':   caso_id,
            'nota_caso': nota_caso
        },
        success: function(response){

            jQuery("#spinner").toggle( "slow" );
            jQuery("#log" ).toggle( "slow" );
            jQuery("#log" ).empty();
            jQuery("#log").html(response);
            jQuery("#log" ).delay(3000).toggle( "slow" );
            setTimeout(function(){
                $("#save_caso").attr("disabled", false);
            }, 3000);
        }
    });
});






jQuery( document  ).on( "click","#new_caso", function(event) {
    var form = jQuery('#contact_form');
    if(form[0].checkValidity()===false){
        //alert("non inviare");
    }else{
        //alert("invia");
        event.preventDefault();
        //$("#new_caso").attr("disabled", true);

        jQuery("#spinner").toggle( "slow" );
        var dataform = jQuery("#contact_form").serializeArray();

        dataform.push({name: 'function', value: 'new_caso'});
        jQuery.ajax({
            url : 'ajax_caso.php',
            type : 'post',
            data : dataform ,
            success : function( response ) {

                jQuery("#spinner").toggle( "slow" );
                jQuery( "#log" ).toggle( "slow" );
                jQuery( "#log" ).empty();
                var res = response.split("@");

                jQuery("#log").html(res[0]);
                jQuery( "#log" ).delay(2000).toggle( "slow" );
                setTimeout(function(){
                    $(window.location).attr('href', 'view_caso.php?id=' + res[1]);
                }, 4000);

            }
        });
    }

});



jQuery( document  ).on( "click", ".delete_caso ,.delete_contact, .delete_note", function(event) {

    if(confirm("En verdad quiere borrar esto?")){
        console.log("borrado " + $(this).attr("object") + " - "+ $(this).attr("item"));

        $(this).removeClass("fa-trash");
        $(this).addClass("fa-check");

        $.ajax({
            type: 'POST',
            url: 'ajax_caso.php',
            data: {
                'function':      'delete_item',
                'item_id':       $(this).attr("item"),
                'object_name': $(this).attr("object")
            },
            success: function(response){
                console.log(response);
            }
        });

    }else{
        console.log("estava bromiando " + $(this).attr("object") +" - "+   $(this).attr("item"));
    }
});


jQuery('#add_note').click(function() {
    $("#note-form").toggle( "slow" );
});







jQuery( document  ).on( "click","#save_status_caso", function(event) {
    var caso_id     = $("#caso_id").val();
    var jquery_caso_id  =   $("#save_status_caso i" ) ;
    jquery_caso_id.removeClass("fa-floppy-o");
    jquery_caso_id.addClass("fa-spinner fa-spin   fa-fw");

    $.ajax({
        type: 'POST',
        url: 'ajax_caso.php',
        data: {
            'function':      'save_status_caso',
            'caso_id':       caso_id,
            'status': $("#status option:selected").val()
        },
        success: function(response){
            console.log(response);
            jquery_caso_id.removeClass("fa-spinner fa-spin  fa-fw ");
             jquery_caso_id.addClass("fa-floppy-o");
        }
    });
});


jQuery( document  ).on( "click","#save_contact_type", function(event) {
    // alert("Guarda el tipo di contacto" + $(this).attr("selectctl"));
    //alert(" contact id: " + $(this).attr("selectctl") + " status" +$("#"+ $(this).attr("selectctl") +" option:selected").val());

    var jquery_contact_id  =   $(this) ;
    jquery_contact_id.removeClass("fa-floppy-o");
    jquery_contact_id.addClass("fa-spinner fa-spin fa-3x fa-fw");

    var caso_id     = $("#caso_id").val();
    console.log("caso_id "+caso_id);

    var contact_id  =   $(this).attr("selectctl");
    contact_id  =   contact_id.replace("contact_type", "");
    console.log("contact_id "+contact_id);

    var contact_type = $("#"+ $(this).attr("selectctl") +" option:selected").val()
    console.log("status "+contact_type);

    $.ajax({
        type: 'POST',
        url: 'ajax_caso.php',
        data: {
            'function'      : 'save_contact_type',
            'caso_id'       : caso_id,
            'contact_id'    : contact_id,
            'contact_type'  : contact_type
        },
        success: function(response){
            console.log(response);
            jquery_contact_id.removeClass("fa-spinner fa-spin fa-3x fa-fw ");
            jquery_contact_id.addClass("fa-floppy-o");
        }
    });




});



jQuery( document  ).on( "click",".delete-file", function(event) {
    if(confirm("En verdad quiere borrar este archivo?")){
        var file_id = $(this).attr("file-id");
        var caso_id = $("#caso_id").val();
        $.ajax({
            type: 'POST',
            url: 'ajax_document_list.php',
            data: {
                'function':      'delete_document',
                'file_id':          file_id,
                'caso_id':          caso_id
            },
            success: function(response){
                console.log("Borra:" +file_id );
                console.log("Delete Response: "+response);
                $("h3").text("Documentos-Cantidad: "+response);
                $.ajax({
                    type: 'POST',
                    url: 'ajax_document_list.php',
                    data: {
                        'function':     'list_document',
                        'caso_id':		caso_id
                    },
                    success: function(response){
                        $("#filespool").empty();
                        $("#filespool").html(response);
                        update_icons();

                    }
                });


            }
        });
    }else{
        console.log("No borra nada");
    }
});

jQuery( document  ).on( "click","#save_note", function(event) {

    event.preventDefault();
    jQuery("#spinner-note").toggle( "slow" );
    jQuery("#note-spool").toggle( "slow" );
    jQuery("#note-spool" ).empty();
    var caso_id = $("#caso_id").val();
    var note_category = $("#note_category").val();
    var note_text = $("#note_text").val();
    $("#note_text").empty();
    $('#note_text').summernote('code', '');
    $("#note_category").val("");
    $("#note-form").toggle( "slow" );
    $.ajax({
        type: 'POST',
        url: 'ajax_caso.php',
        data: {
            'function':      'add_note',
            'caso_id':       caso_id,
            'note_category': note_category,
            'note_text':     note_text
        },
        success: function(response){
            jQuery("#note-spool").html(response);
            jQuery("#spinner-note").toggle( "slow" );
            jQuery("#note-spool").toggle( "slow" );

        }
    });


});



function update_icons(){
    $(".doc, .docx, .odf, .txt, .rtf").addClass("fa-file-word-o ");
    $(".pptx").addClass("fa-file-powerpoint-o ");
    $(".flv").addClass("fa-file-video-o ");
    $(".xls, .xlsx, .ods").addClass("fa-file-excel-o");
    $(".mp4, .3gp").addClass("fa-file-video-o");
    $(".pdf").addClass("fa-file-pdf-o");
    //$(".png, .jpg, .jpeg, .gif").addClass("fa-file-image-o");
    $(".mp3, .wav").addClass("fa-file-audio-o");
    $(".zip").addClass("fa-file-archive-o");
    $('.fancyjpg, .fancypng, .fancygif, .fancyjpeg').fancybox();
}







jQuery( document  ).on( "click","#create_add_client", function(event) {

    event.preventDefault();
    $("#create_add_client").attr("disabled", true);
    $("#create_add_client i"). addClass(" fa-spin  fa-fw ");


    var caso_id = $("#caso_id").val();
    var contact_name_new = $("#contact_name_new").val();

    $.ajax({
        type: 'POST',
        url: 'ajax_caso.php',
        data: {
            'function':      'create_add_client',
            'caso_id':          caso_id,
            'contact_name_new': contact_name_new

        },
        success: function(response){
            $("#create_add_client").attr("disabled", false);
            $("#create_add_client i"). removeClass(" fa-spin  fa-fw ");
            $("#contact_name_new").val("");
            $("#contact-spool").empty();
            $("#contact-spool").html(response);
        }
    });


});


// Jquery Dependency

$("input[data-type='currency']").on({
    keyup: function() {
        formatCurrency($(this));
    },
    blur: function() {
        formatCurrency($(this), "blur");
    }
});


function formatNumber(n) {
    // format number 1000000 to 1,234,567
    return n.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",")
}


function formatCurrency(input, blur) {
    // appends $ to value, validates decimal side
    // and puts cursor back in right position.

    // get input value
    var input_val = input.val();

    // don't validate empty input
    if (input_val === "") { return; }

    // original length
    var original_len = input_val.length;

    // initial caret position
    var caret_pos = input.prop("selectionStart");

    // check for decimal
    if (input_val.indexOf(".") >= 0) {

        // get position of first decimal
        // this prevents multiple decimals from
        // being entered
        var decimal_pos = input_val.indexOf(".");

        // split number by decimal point
        var left_side = input_val.substring(0, decimal_pos);
        var right_side = input_val.substring(decimal_pos);

        // add commas to left side of number
        left_side = formatNumber(left_side);

        // validate right side
        right_side = formatNumber(right_side);

        // On blur make sure 2 numbers after decimal
        if (blur === "blur") {
            right_side += "00";
        }

        // Limit decimal to only 2 digits
        right_side = right_side.substring(0, 2);

        // join number by .
        input_val = "$" + left_side + "." + right_side;

    } else {
        // no decimal entered
        // add commas to number
        // remove all non-digits
        input_val = formatNumber(input_val);
        input_val = "$" + input_val;

        // final formatting
        /*
        if (blur === "blur") {
          input_val += ".00";
        }*/
    }

    // send updated string to input
    input.val(input_val);

    // put caret back in the right position
    var updated_len = input_val.length;
    caret_pos = updated_len - original_len + caret_pos;
    input[0].setSelectionRange(caret_pos, caret_pos);
}