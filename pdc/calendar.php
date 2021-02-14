<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
include("inc/config.php");
session_start();
if (!isset($_SESSION["auth_id"])) {
    header("Location:index.php");
}
if ($_GET['logout'] == "logout") {
    unset($_SESSION);
}
$_ID = auyama_decrypt(base64_decode(rawurldecode($_GET["id"])));
?>
<!DOCTYPE HTML>

<head>
    <title><?php echo $TITULO ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <?php include("inc/header.php"); ?>

    <link rel='stylesheet' href='js/fullcalendar/dist/fullcalendar.css' />
    <script src='js/moment/min/moment.min.js'></script>
    <script src='js/fullcalendar/dist/fullcalendar.min.js'></script>
    <script src='js/fullcalendar/dist/locale-all.js'></script>
    <script>
        $(document).ready(function() {

            var calendar = $('#calendar').fullCalendar({
                locale: 'es',
                editable: true,
                defaultView: 'listWeek',
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'listWeek,month,agendaWeek,agendaDay'
                },
                slotLabelFormat: [
                    'hh(:mm) a', // top level of text
                    'hh(:mm) a' // lower level of text
                ],
                views: {
                    day: {
                        timeFormat: 'hh:mm'
                    },
                    week: {
                        timeFormat: 'hh:mm'
                    }
                },
                events: 'ical-load.php',
                selectable: true,
                selectHelper: true,
                select: function(start, end, allDay) {
                    /* var start = $.fullCalendar.formatDate(start, "Y-MM-DD HH:mm:ss");
        var end = $.fullCalendar.formatDate(end, "Y-MM-DD HH:mm:ss"); 
      var title = prompt("Agregar una descripciòn");
      */
                    $('#icalendar').modal('show');
                    $('#start').val($.fullCalendar.formatDate(start, "Y-MM-DD HH:mm:ss"));
                    $('#end').val($.fullCalendar.formatDate(end, "Y-MM-DD HH:mm:ss"));

                    console.log("event:" + start);

                    jQuery(document).off('click').on("click", "#createevent", function(event, start, end) {
                        var title = $('#event').val();
                        var start = $('#start').val();
                        var end = $('#end').val();
                        var public;
                        if ($('#public').prop('checked')) {
                            public = "on";
                        } else {
                            public = "off";
                        }
                        var color = $("#color option:selected").val();
                        var caso_id = $("#caso option:selected").val();
                        $('#icalendar').modal('hide');

                        $.ajax({
                            url: "ical-insert.php",
                            type: "POST",
                            data: {
                                title: title,
                                start: start,
                                end: end,
                                color: color,
                                public: public,
                                caso_id: caso_id
                            },
                            success: function() {

                                calendar.fullCalendar('refetchEvents');
                                console.log("Added Successfully");

                            }
                        });
                        $('#event').val("");

                    });


                    /* 
      if(title)
      {
 
       $.ajax({
        url:"ical-insert.php",
        type:"POST",
        data:{title:title, start:start, end:end},
        success:function()
        {
         calendar.fullCalendar('refetchEvents');
         console.log("Added Successfully");
        }
       })
      } */
                },
                editable: true,
                eventRender: function(event, element) {
                    var ww = $('#calendar').fullCalendar('getView');

                    if ("listWeek" == ww.name) {
                        var title = element.find('.fc-title, .fc-list-item-title');
                        if (event.caso_id != "Z%2FhVTmy1NdyLbEVecUwLnA%3D%3D") {
                            title.prepend('<span class="casoon mr-3 ml-3"><a href="view_caso.php?id=' + event.caso_id + '"><i class="fa fa-2x fa-archive " aria-hidden="true"></i></a> </span> ');
                        }
                        title.prepend(' <span class="closeon "><i class="fa fa-2x fa-trash  " aria-hidden="true"></i></span> ');
                        title.find(".closeon").click(function() {
                            $('#calendar').fullCalendar('removeEvents', event._id);
                            console.log('Delete event id: ' + event.id);
                            $.ajax({
                                url: "ical-delete.php",
                                type: "POST",
                                data: {
                                    id: event.id
                                },
                                success: function() {
                                    calendar.fullCalendar('refetchEvents');
                                    console.log("Event Removed");
                                }
                            })
                        });
                    }
                },
                eventResize: function(event) {
                    var start = $.fullCalendar.formatDate(event.start, "Y-MM-DD HH:mm:ss");
                    var end = $.fullCalendar.formatDate(event.end, "Y-MM-DD HH:mm:ss");
                    var title = event.title;
                    var id = event.id;
                    $.ajax({
                        url: "ical-update.php",
                        type: "POST",
                        data: {
                            title: title,
                            start: start,
                            end: end,
                            id: id
                        },
                        success: function() {
                            calendar.fullCalendar('refetchEvents');
                            console.log('Event Update');
                        }
                    })
                },

                eventDrop: function(event) {
                    var start = $.fullCalendar.formatDate(event.start, "Y-MM-DD HH:mm:ss");
                    var end = $.fullCalendar.formatDate(event.end, "Y-MM-DD HH:mm:ss");
                    var title = event.title;
                    var id = event.id;
                    $.ajax({
                        url: "ical-update.php",
                        type: "POST",
                        data: {
                            title: title,
                            start: start,
                            end: end,
                            id: id
                        },
                        success: function() {
                            calendar.fullCalendar('refetchEvents');
                            console.log("Event Updated");
                        }
                    });
                },

                eventClick: function(event) {
                    /*if(confirm("estas seguro de querer borrar este evento?"))
                    {
                     var id = event.id;
                     $.ajax({
                      url:"delete.php",
                      type:"POST",
                      data:{id:id},
                      success:function()
                      {
                       calendar.fullCalendar('refetchEvents');
                       console.log("Event Removed");
                      }
                     })
                    }*/
                },

            });
        });
    </script>
</head>

<body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">



    <?php include("inc/menu.php"); ?>

    <?php
    if ($_SESSION["type"] != "guest") { ?>
        <div class="container-fluid  ">

            <div id="content" class="wrapper">
                <div class="row">
                    <div class="col-md-8 mt-3 ml-auto mr-auto">

                        <div id="calendar"> </div>

                    </div>
                </div>
            </div>
        </div>

        <style>
            select option {
                margin: 40px;
                background: rgba(0, 0, 0, 0.3);
                color: #fff;
                text-shadow: 0 1px 0 rgba(0, 0, 0, 0.4);
            }

            select option[value="#007bff"] {
                background: #007bff;
            }

            select option[value="#6610f2"] {
                background: #6610f2;
            }

            select option[value="#6f42c1"] {
                background: #6f42c1;
            }

            select option[value="#e83e8c"] {
                background: #e83e8c;
            }

            select option[value="#dc3545"] {
                background: #dc3545;
            }

            select option[value="#fd7e14"] {
                background: #fd7e14;
            }

            select option[value="#ffc107"] {
                background: #ffc107;
            }

            select option[value="#28a745"] {
                background: #28a745;
            }

            select option[value="#20c997"] {
                background: #20c997;
            }

            select option[value="#17a2b8"] {
                background: #17a2b8;
            }

            select option[value="#6c757d"] {
                background: #6c757d;
            }

            select option[value="#28a745"] {
                background: #28a745;
            }

            select option[value="#17a2b8"] {
                background: #17a2b8;
            }

            select option[value="#ffc107"] {
                background: #ffc107;
            }
        </style>


        <div class="modal fade" id="icalendar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Crea Evento</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group mb-md-3">
                            <input type="text" class="form-control" id="event" name="event" placeholder="Evento">
                            <input type="hidden" class="form-control" id="start" name="start">
                            <input type="hidden" class="form-control" id="end" name="end">
                        </div>
                        <!-- <div class="form-check mb-2 ">
                <input type="checkbox" class="form-check-input position-static" id="public" name="public">
                <label class="form-check-label" for="public">Evento publico:</label>
            </div> -->
                        <div class="form-group mt-md-3">
                            <select class="form-control" id="color" name="color">
                                <option value="">Tipo de evento</option>
                                <option value="#007bff">Llamada</option>
                                <option value="#6610f2">Cita</option>
                                <option value="#6f42c1">Video llamada</option>
                                <option value="#e83e8c">Recordatorio</option>
                                <option value="#dc3545">Revisión</option>
                                <option value="#fd7e14">Importante</option>
                                <option value="#ffc107">Encuentro</option>
                                <option value="#28a745">Pagar</option>
                                <option value="#20c997">Cobrar</option>
                                <option value="#17a2b8">Otros</option>
                                <option value="#6c757d">Otros 11</option>
                                <option value="#28a745">Otros 12</option>
                                <option value="#17a2b8">Otros 13</option>
                                <option value="#ffc107">Otros 14</option>
                            </select>
                        </div>
                        <div class="form-group mt-md-3">
                            <select class="form-control" id="caso" name="caso">
                                <option value="">Selecciona caso</option>
                                <?php
                                if ($_SESSION["type"] == "admin") {
                                    $query = "SELECT * from casos where created_by='" . $_SESSION["auth_id"] . "' order by caso_id desc ";
                                } else {
                                    $query = "SELECT casos.*, user_casos.user_id from casos left  join user_casos on  ( user_casos.caso_id = casos.caso_id ) where user_id =" . $_SESSION["auth_id"] . " and status>0 order by caso_id desc ";
                                }



                                $mysqli2->real_query($query);
                                $res = $mysqli2->use_result();

                                while ($linea = $res->fetch_assoc()) {
                                    echo ' <option value="' . $linea["caso_id"] . '">' . $linea["name"] . ' Id:' . $linea["caso_id"] . '</option>';
                                }
                                ?>

                            </select>
                        </div>
                    </div>
                    <div class="modal-footer mt-md-3">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="createevent">Save changes</button>
                    </div>
                </div>
            </div>
        </div>

    <?php } else {
        ydhatta();
    }
    ?>

    <?php
    if ($_SESSION["type"] != "guest") {
        include("inc/footer.php");
    } ?>
    <script src="js/jQuery-File-Upload-master/js/vendor/jquery.ui.widget.js"></script>
    <script src="js/jQuery-File-Upload-master/js/jquery.iframe-transport.js"></script>
    <script src="js/jQuery-File-Upload-master/js/jquery.fileupload.js"></script>
    <script type="text/javascript" src="js/script.js"></script>
</body>

</html>