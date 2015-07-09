@extends('frontend.app')

@section('includes.css')
    @parent
    <link href="/plugins/fullcalendar/fullcalendar.css" rel="stylesheet" type="text/css" />
    <link href="/plugins/fullcalendar/fullcalendar.print.css" rel="stylesheet" type="text/css" media='print' />
@stop

@section('includes.js')
    @parent
    <script src="/plugins/jQueryUI/jquery-ui-1.10.3.min.js" type="text/javascript"></script>
    <script src="/plugins/moment/moment.min.js" type="text/javascript"></script>
    <script src="/plugins/bootbox/bootbox.min.js" type="text/javascript"></script>
    <script src="/plugins/fullcalendar/fullcalendar.min.js" type="text/javascript"></script>
    <script src="/plugins/fullcalendar/lang-all.js" type="text/javascript"></script>
    <script src="/plugins/fullcalendar/gcal.js" type="text/javascript"></script>
    <script src="/plugins/typeahead/typeahead.js" type="text/javascript"></script>
    <script src="/plugins/typeahead/bootstrap3-typeahead.js" type="text/javascript"></script>

    <!-- Page specific script -->
    <script type="text/javascript">

        var zone_id = {{ $zone->id }};
        var user_id = {{ $user->id }};
        var campaign_id = {{ $campaign->id }};


        $(function() {

            /* initialize the external events
             -----------------------------------------------------------------*/
            function ini_events(ele) {
                ele.each(function() {

                    // create an Event Object (http://arshaw.com/fullcalendar/docs/event_data/Event_Object/)
                    // it doesn't need to have a start or end
                    var eventObject = {
                        title: $.trim($(this).text()) // use the element's text as the event title
                    };

                    // store the Event Object in the DOM element so we can get to it later
                    $(this).data('eventObject', eventObject);

                    // make the event draggable using jQuery UI
                    $(this).draggable({
                        zIndex: 1070,
                        revert: true, // will cause the event to go back to its
                        revertDuration: 0  //  original position after the drag
                    });

                });
            }
            ini_events($('#external-events div.external-event'));

            /* initialize the calendar
             -----------------------------------------------------------------*/

            $('#calendar').fullCalendar({
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'month,agendaWeek,agendaDay'
                },
                //Random default events
                events: '/frontend/rutas/calendar?zone_id='+zone_id+'&campaign_id='+campaign_id+'&user_id='+user_id,
                windowResize: function(view) {
                    if ($(window).width() < 614){
                        $('#calendar').fullCalendar( 'changeView', 'agendaDay' );
                    } else {
                        $('#calendar').fullCalendar( 'changeView', 'agendaWeek' );
                    }
                },
                editable: true,
                droppable: true, // this allows things to be dropped onto the calendar !!!
                lang: 'es',
                defaultView: 'agendaWeek',
                weekends : false,
                allDaySlot: false,
                workDayStart: '8:00',
                minTime: '07:00',
                maxTime: '22:00',
                defaultEventMinutes: 30,
                slotDuration: '00:15:00',
                defaultTimedEventDuration: '00:30:00',
                eventDrop: function( event, delta,revertFunc,jsEvent, ui, view ) {
                    console.log(event);


                    var start = moment(event._start).format('YYYY-MM-DD HH:mm').toString();
                    var end = moment(event._end).format('YYYY-MM-DD HH:mm').toString();

                    $.ajax({
                        type: "PUT",
                        url: "/api/routes/"+event.uuid,
                        data: 'start='+start+'&end='+end,
                        success: function(data) {
                            toastr.success('Se actualizó la fecha', event.title);
                        }
                    });
                },
                eventResize: function(event, delta, revertFunc) {
                    var start = moment(event._start).format('YYYY-MM-DD HH:mm').toString();
                    var end = moment(event._end).format('YYYY-MM-DD HH:mm').toString();

                    $.ajax({
                        type: "PUT",
                        url: "/api/routes/"+event.uuid,
                        data: 'start='+start+'&end='+end,
                        success: function(data) {
                            toastr.success('Se actualizó la hora', event.title);
                        }
                    });
                },
                eventClick: function(event){
                    console.log(event);

                        bootbox.dialog({
                            message: '<b>Programada para el dia:</b> '+
                            moment(event.start).lang('es').format('LLLL')+
                            ((event.address)?'<br><b>Dirección:</b>'+event.address:'')+
                            '<br><b>Tipo de Ruta:</b>'+((event.color == '#F4543C')?'Punto de Contacto':'Ruta Normal'),
                            title: event.title,
                            buttons: {
                                success: {
                                    label: "Perfil",
                                    className: "btn-success",
                                    callback: function() {
                                        window.location = '/frontend/target/'+event.target_id;
                                    }
                                },
                                danger: {
                                    label: "Eliminar",
                                    className: "btn-danger",
                                    callback: function() {
                                        $.ajax({
                                            type: "DELETE",
                                            url: "/api/routes/"+event.uuid,
                                            success: function(data) {
                                                $('#calendar').fullCalendar('removeEvents', event._id);
                                                toastr.success('Se eliminó correctamente!', event.title);
                                            }
                                        });
                                    }
                                },
                                main: {
                                    label: "Cancelar",
                                    className: "btn-info",
                                    callback: function() {
                                    }
                                }
                            }
                        });


                    return false;

                },
                drop: function(date, allDay) { // this function is called when something is dropped

                    var start = moment(date).format('YYYY-MM-DD HH:mm').toString();
                    var end = moment(date).add('minutes',30).format('YYYY-MM-DD HH:mm').toString();

                    var time = moment(date).format('HH:mm').toString();

                    if(time == '00:00')
                    {
                        start = moment(date).add('hours',8).format('YYYY-MM-DD HH:mm').toString();
                        end = moment(date).add('hours',8).add('minutes',30).format('YYYY-MM-DD HH:mm').toString();
                        date = moment(date).add('hours',8).format("YYYY-MM-DD HH:mm");
                    }

                    // retrieve the dropped element's stored Event Object
                    var originalEventObject = $(this).data('eventObject');

                    // we need to copy it, so that multiple events don't have a reference to the same object
                    var copiedEventObject = $.extend({}, originalEventObject);

                    // assign it the date that was reported
                    copiedEventObject.start = start;
                    copiedEventObject.end = end;
                    copiedEventObject.allDay = false;
                    copiedEventObject.backgroundColor = $(this).css("background-color");
                    copiedEventObject.borderColor = $(this).css("border-color");
                    copiedEventObject.target_id = $(this).data('target_id');
                    copiedEventObject.point_of_contact = $(this).data('point_of_contact');



                    var data_form = $('#form_route').serialize();
                    console.log(data_form);
                    data_form= data_form+'&target_id='+$(this).data('target_id')+'&point_of_contact='+$(this).data('point_of_contact');

                    data_form = data_form+'&start='+start+'&end='+end;

                    //console.log(data_form);

                    $.ajax({
                        type: "POST",
                        url: "/api/routes",
                        data: data_form,
                        success: function(data) {
                            //console.log(data);

                            toastr.success('Se creó la ruta correctamente!', copiedEventObject.title);
                            copiedEventObject.uuid = data.uuid;
                            copiedEventObject.id = data.uuid;
                            originalEventObject.uuid = data.uuid;
                            originalEventObject.id = data.uuid;
                            $('#calendar').fullCalendar('renderEvent', copiedEventObject, true);
                        }
                    });

                    // is the "remove after drop" checkbox checked?
                    if ($('#drop-remove').is(':checked')) {
                        // if so, remove the element from the "Draggable Events" list
                        console.log($(this));
                        $(this).remove();
                    }



                }
            });

            /* ADDING EVENTS */
            var currColor = "#f56954"; //Red by default
            var pointOfContact = "1"; //Red by default
            $("point_of_contact").val(pointOfContact);
            //Color chooser button
            var colorChooser = $("#color-chooser-btn");
            $("#color-chooser > li > a").click(function(e) {
                e.preventDefault();
                //Save color
                currColor = $(this).css("color");

                pointOfContact = $(this).data("point");
                console.log("point_of_contact: "+pointOfContact);
                $("point_of_contact").val(pointOfContact);
                //Add color effect to button
                colorChooser
                        .css({"background-color": currColor, "border-color": currColor})
                        .html($(this).text()+' <span class="caret"></span>');

            });
            $("#add-new-event").click(function(e) {
                e.preventDefault();
                //Get value and make sure it is not null
                var val = $("#new-event").val();
                if (val.length == 0) {
                    return;
                }

                console.log(val);
                console.log(map[val]);

                //Create event
                var event = $("<div />");

                event.css({"background-color": currColor, "border-color": currColor, "color": "#fff"}).addClass("external-event");
                event.html(val);
                event.attr('data-target_id', map[val]);
                event.attr('data-point_of_contact', pointOfContact);
                console.log(event);
                $('#external-events').prepend(event);

                //Add draggable funtionality
                ini_events(event);

                //Remove event from text input
                $("#new-event").val("");
            });
        });

        var map;

        $(".typeahead").typeahead({
            source: function (query, process) {
              //  console.log(query);
               // var $this = this; //get a reference to the typeahead object
                return $.get('/api/targets?zone_id='+zone_id+'&campaign_id='+campaign_id+'&user_id='+user_id+'&query='+query,
                        function(data){
                            //console.log(data);
                            var options = [];
                            map = {}; //replace any existing map attr with an empty object
                            for(i=0;i<data.length;i++) {
                                console.log(data[i]);
                                options.push(data[i].client.closeup_name);
                                map[data[i].client.closeup_name] = data[i].id;
                            }

                    return process(options);
                },'json');
            }
        });
    </script>
@stop

@section('header')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Rutas
            <small>programación de puntos de contacto</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ url('/') }}"><i class="fa fa-dashboard"></i> Inicio</a></li>
            <li class="active"><i class="fa fa-calendar"></i> Rutas</li>
        </ol>
    </section>
@stop

@section('content')

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-3">
                <div class="box box-primary">
                    <div class="box-header">
                        <h4 class="box-title">Programar</h4>
                    </div>
                    <div class="box-body">
                        <!-- the events -->
                        <div id='external-events'>
                            <p>
                                <input type='checkbox' id='drop-remove' /> <label for='drop-remove'>Eliminar despues</label>
                            </p>
                        </div>
                    </div><!-- /.box-body -->
                </div><!-- /. box -->
                <div class="box box-primary">
                    <form id="form_route" action="#">
                        <div class="box-header">
                            <h3 class="box-title">Crear Ruta</h3>
                        </div>
                        <div class="box-body">
                            <div class="btn-group" style="width: 100%; margin-bottom: 10px;">
                                <button type="button" id="color-chooser-btn" class="btn btn-danger btn-block btn-sm dropdown-toggle" data-toggle="dropdown">Punto de Contacto<span class="caret"></span></button>
                                <ul class="dropdown-menu" id="color-chooser">
                                    <li><a class="text-red" data-point="1" href="#"><i class="fa fa-square"></i> Punto de Contacto</a></li><!--1-->
                                    <li><a class="text-blue" href="#" data-point="0"><i class="fa fa-square"></i> Ruta Normal</a></li><!--0-->
                                </ul>
                            </div><!-- /btn-group -->
                            <div class="input-group">
                                <input id="new-event" type="text" data-provide="typeahead" class="form-control typeahead" placeholder="Médico">
                                <input name="zone_id" id="zone_id" type="hidden" value="{{ $zone->id }}"/>
                                <input name="campaign_id" id="campaign_id" type="hidden" value="{{ $campaign->id }}"/>
                                <input name="user_id" id="user_id" type="hidden" value="{{ $user->id }}" />
                                <input name="is_from_mobile" id="is_from_mobile" type="hidden" value="0" />
                                <div class="input-group-btn">
                                    <button id="add-new-event" type="button" class="btn btn-default btn-flat">Agregar</button>
                                </div><!-- /btn-group -->
                            </div><!-- /input-group -->
                        </div>
                    </form>
                </div>

                <div class="box box-primary">
                    <div class="box-header">
                        <h4 class="box-title">Exportar</h4>
                    </div>
                    <div class="box-body">
                        <!-- the events -->
                        <a class="btn btn-primary" href="/frontend/rutas/exportar?zone_id={{ $zone->id }}&campaign_id={{ $campaign->id}}&user_id={{ $user->id }}">
                            <i class="fa fa-download"></i>

                            Exportar Excel</a>
                    </div><!-- /.box-body -->
                </div><!-- /. box -->
            </div><!-- /.col -->
            <div class="col-md-9">
                <div class="box box-primary">
                    <div class="box-body no-padding">
                        <!-- THE CALENDAR -->
                        <div id="calendar"></div>
                    </div><!-- /.box-body -->
                </div><!-- /. box -->
            </div><!-- /.col -->
        </div><!-- /.row -->
    </section><!-- /.content -->

@endsection
