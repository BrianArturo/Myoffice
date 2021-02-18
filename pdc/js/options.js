jQuery(document).on("click", ".option_delete_box", function (event) {
    var caso_id = $("#caso_id").val();
    var option_id = $(this).attr("option_id");
    console.log("Option id: " + option_id);
    if (confirm("En verdad quiere borrar esto?")) {
        $('div[option_id="' + option_id + '"]').hide('slow');
        $.ajax({
            type: 'POST',
            url: 'ajax_options.php',
            data: {
                'function': 'delete_option',
                'caso_id': caso_id,
                'option_id': option_id
            },
            success: function (response) {
                console.log(response);

            }
        });
    } else {

    }



});

jQuery(document).on("click", "#save_options", function (event) {
    jQuery('#mywidgetframe').toggleClass('openwidgetframe', '2000');
    var caso_id = $("#caso_id").val();
    var tags = $("#tags").val();
    var valor = $("#valor").val()
    var formato = $("#formato option:selected").val();
    $.ajax({
        type: 'POST',
        url: 'ajax_options.php',
        data: {
            'function': 'create_option',
            'caso_id': caso_id,
            'formato': formato,
            'tags': tags,
            'valor': valor
        },
        success: function (response) {
            console.log(response);
            $("#tags").val("");
            $("#valor").val("");

            var htmlinfo = '<div class="col-md-3  ">' +
                '<div class="form-group" data-toggle="tooltip" data-placement="top" title="' + tags + '">' +
                '<span class="  hiddeondesktop option_delete_box text-right" >' +
                '<i class="fa fa-check fa-2x ml-3 mr-3" aria-hidden="true"></i> ' +
                '</span>' +
                '<i class="fa fa-2x ' + response + '" aria-hidden="true"></i> ' +
                valor +
                '</div>' +
                '</div>';

            $("#infobox").append(htmlinfo);
            window.location.reload(false);
        }
    });
});

jQuery(document).on("click", "#save_pago", function (event) {
    jQuery('#mywidgetframe').toggleClass('openwidgetframe', '2000');
    var caso_id = $("#caso_id").val();
    var tags = $("#Fecha").val();
    var valor = $("#valorPago").val();
    var formato = $("#tipo_pago option:selected").val();
    var descripcion = $("#description").val();
    $.ajax({
        type: 'POST',
        url: 'ajax_options.php',
        data: {
            'function': 'save_pago',
            'caso_id': caso_id,
            'formato': formato,
            'tags': tags,
            'descripcion': descripcion,
            'valor': valor
        },
        success: function (response) {
            console.log(response);
            $("#Fecha").val("");
            $("#valorPago").val("");
            window.location.reload(false);
        },

    });
});

$(document).ready(function () {
    jQuery(".option_box").on("taphold", function (event) {

        $(event.target).hide();
    })




    $(".option_box").pressAndHold({
        holdTime: 1000,
        progressIndicatorRemoveDelay: 900,
        progressIndicatorColor: "blue",
        progressIndicatorOpacity: 0.3
    });

    $(".option_box").on('start.pressAndHold', function (event) {
        console.log("start");
    });
    $(".option_box").on('complete.pressAndHold', function (event) {
        console.log("complete");
        $(event.target).hide();
        var caso_id = $("#caso_id").val();
        var option_id = $(event.target).attr("option_id");



        $.ajax({
            type: 'POST',
            url: 'ajax_options.php',
            data: {
                'function': 'delete_option',
                'caso_id': caso_id,
                'option_id': option_id
            },
            success: function (response) {
                console.log(response);
            }
        });




    });
    $(".option_box").on('end.pressAndHold', function (event) {
        console.log("end");
    });
});