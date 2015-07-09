@extends('frontend.app')

@section('includes.css')
    @parent
    <link href="/plugins/jvectormap/jquery-jvectormap-1.2.2.css" rel="stylesheet" type="text/css" />
    <!-- fullCalendar -->
    <link href="/plugins/fullcalendar/fullcalendar.css" rel="stylesheet" type="text/css" />
    <link href="/plugins/fullcalendar/fullcalendar.print.css" rel="stylesheet" type="text/css" media="print" />
    <!-- daterange picker -->

    <!-- iCheck for checkboxes and radio inputs -->
    <link href="/plugins/iCheck/all.css" rel="stylesheet" type="text/css" />
    <!-- Bootstrap Color Picker -->
    <link href="/plugins/colorpicker/bootstrap-colorpicker.min.css" rel="stylesheet" />
    <!-- Bootstrap time Picker -->
    <link href="/plugins/timepicker/bootstrap-timepicker.min.css" rel="stylesheet" />
    <link href="/plugins/datepicker/datepicker3.css" rel="stylesheet" />

    <style>
        .datepicker{z-index:1151 !important;}
    </style>
@stop

@section('includes.js')
    @parent
    <script src="/plugins/jQueryUI/jquery-ui-1.10.3.min.js" type="text/javascript"></script>
    <script src="/plugins/moment/moment.min.js" type="text/javascript"></script>
    <script src="/plugins/bootbox/bootbox.min.js" type="text/javascript"></script>
    <script src="/plugins/fullcalendar/fullcalendar.min.js" type="text/javascript"></script>
    <script src="/plugins/fullcalendar/lang-all.js" type="text/javascript"></script>
    <script src="/plugins/fullcalendar/gcal.js" type="text/javascript"></script>
    <script src="/plugins/iCheck/icheck.min.js" type="text/javascript"></script>
    <script src="/plugins/timepicker/bootstrap-timepicker.min.js" type="text/javascript"></script>
    <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places"></script>

    <script type="text/javascript">
        var doctor_id = {{ $target->client->id }};
    </script>
    <script src="/js/target_show.js" type="text/javascript"></script>



    <script type="text/javascript">

        var map;
        var markers = [];
        var myLatLong;
        var client_id = {{{ $target->client_id }}};
        var zone_id = {{{ $target->zone_id }}};
        var user_id = {{{ $target->user_id }}};
        var doctor_name = '{{{ $target->client->closeup_name }}}';

        function initialize() {
            {{ $cord = ($target->client->latitude.$target->client->longitude == '')?'-12.046374,-77.0427934':$target->client->latitude.','.$target->client->longitude }}
            myLatLong = new google.maps.LatLng({{ $cord }});
            var mapOptions = {
                zoom: 15,
                center: myLatLong
            };
            map = new google.maps.Map(document.getElementById('world-map'),
                    mapOptions);
            var marker = new google.maps.Marker({
                position: myLatLong,
                draggable: true,
                map: map,
                title: ''
            });

            var input = (
                    document.getElementById('pac-input'));
            map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);


            var searchBox = new google.maps.places.SearchBox((input));

            // [START region_getplaces]
            // Listen for the event fired when the user selects an item from the
            // pick list. Retrieve the matching places for that item.
            google.maps.event.addListener(searchBox, 'places_changed', function() {
                var places = searchBox.getPlaces();

                if (places.length == 0) {
                    return;
                }
                for (var i = 0, marker; marker = markers[i]; i++) {
                    marker.setMap(null);
                }

                // For each place, get the icon, place name, and location.
                markers = [];
                var bounds = new google.maps.LatLngBounds();
                for (var i = 0, place; place = places[i]; i++) {
                    var image = {
                        url: place.icon,
                        size: new google.maps.Size(71, 71),
                        origin: new google.maps.Point(0, 0),
                        anchor: new google.maps.Point(17, 34),
                        scaledSize: new google.maps.Size(25, 25)
                    };


                    bounds.extend(place.geometry.location);
                }

                map.fitBounds(bounds);
            });
            // [END region_getplaces]

            // Bias the SearchBox results towards places that are within the bounds of the
            // current map's viewport.
            google.maps.event.addListener(map, 'bounds_changed', function() {
                var bounds = map.getBounds();
                searchBox.setBounds(bounds);
            });

            google.maps.event.addListener(marker, 'dragend', function(ev) {
                $.ajax({
                    type: "PUT",
                    url: "/api/clients/" + client_id,
                    data: 'latitude=' + marker.getPosition().lat() + '&longitude=' + marker.getPosition().lng(),
                    success: function(data) {
                        toastr.success('Se actualizó la ubicación correctamente!', doctor_name);
                    }
                });
            });
        }

        google.maps.event.addDomListener(window, 'load', initialize);


        $('#calendar').fullCalendar({
            editable: false,
            lang: 'es',
            defaultView: 'agendaWeek',
            weekends: false,
            allDaySlot: false,
            minTime: '07:00',
            maxTime: '23:00',
            buttonText: {
                today: 'hoy dia',
                month: 'mes',
                week: 'semana',
                day: 'dia',
                agendaWeek: 'semana'
            },
            eventSources: [

                '/frontend/schedule/calendar/{{{ $target->client_id }}}'
            ],
            //events: ,

            eventClick: function(event) {
                // opens events in a popup window
                // window.open(event.url, 'gcalevent', 'width=700,height=600');
                return false;
            },
            header: {
                left: '',
                center: 'title',
                right: ''
            }

        });
    </script>

@stop


@section('header')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Perfil
            <small>{{ $target->client->closeup_name }}</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ url('/') }}"><i class="fa fa-dashboard"></i> Inicio</a></li>
            <li><a href="{{ url('/frontend/target') }}"><i class="fa fa-medkit"></i> Target Médico</a></li>
            <li class="active"><i class="fa fa-calendar"></i> Perfil</li>
        </ol>
    </section>
@stop

@section('content')
    <!-- Main content -->
    <section class="content">

        <!-- top row -->
        <div class="row">
            <div class="pull-left" style="  margin: 0 0 -25 20px;">
                <img src="/frontend/image/client/{{ $target->client->code }}" class="img-circle" style='width: 80px; height: 90px;' />
            </div>
            <div class="btn-group pull-right" style="padding-bottom: 5px">
                @foreach ($visits as $key => $visit)
                    <a type="button" class="btn btn-primary btn-flat" href="{{ url('/frontend/visitar?uuid='.$visit->uuid) }}" style="margin-right: 5px;">Visitar {{ $key+1 }}</a>
                    <a type="button" class="btn btn-primary btn-flat" href="{{ url('/frontend/ausencia?uuid='.$visit->uuid) }}" style="margin-right: 5px;">Ausencia {{ $key+1 }}</a>
                @endforeach

                    <a type="button" class="btn btn-primary btn-flat" href="{{ url('/frontend/rutas') }}" style="margin-right: 5px;">Ruta</a>

            </div>

        </div>
        <!-- /.row -->

        <!-- Main row -->
        <div class="row">
            <!-- Left col -->
            <section class="col-lg-6 connectedSortable">
                <!-- Box (with bar chart) -->
                <div class="box box-danger" id="loading-example">
                    <div class="box-header">

                        <!-- tools box -->
                        <div class="pull-right box-tools">
                            <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#marketing-modal"><i class="fa fa-edit"></i>
                            </button>
                            <button class="btn btn-danger btn-sm" data-widget='collapse' data-toggle="tooltip" title="Colapsar"><i class="fa fa-minus"></i>
                            </button>
                        </div>
                        <!-- /. tools -->
                        <i class="fa fa-user-md"></i>

                        <h3 class="box-title">Médico</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">

                            <table class="table">
                                <tr>
                                    <td><b>Colegiatura: </b></td>
                                    <td>{{{ $target->client->code }}}</td>
                                </tr>
                                <tr>
                                    <td><b>Nombres: </b></td>
                                    <td>{{{ $target->client->closeup_name }}}</td>
                                </tr>
                                <tr>
                                    <td><b>Dirección: </b></td>
                                    <td>{{{ $target->client->address. ' - '. $target->client->location->name }}}</td>
                                </tr>
                                <tr>
                                    <td><b>Categoría: </b></td>
                                    <td>{{{ $target->client->category->name }}}</td>
                                </tr>
                                <tr>
                                    <td><b>Lugar: </b></td>
                                    <td>{{{ $target->client->place->name }}}</td>
                                </tr>
                                <tr>
                                    <td><b>Especialidad Base: </b></td>
                                    <td>{{{ $target->client->specialty_base->name }}}</td>
                                </tr>
                                <tr>
                                    <td><b>Especialidad Target: </b></td>
                                    <td>{{{ $target->client->specialty_target->name }}}</td>
                                </tr>
                                @if ($target->client->cmp != '')
                                <tr>
                                    <td><b>Dni: </b></td>
                                    <td>{{{ $target->client->cmp }}}</td>
                                </tr>
                                @endif
                                @if ($target->client->date_of_birth != '')
                                <tr>
                                    <td><b>Fecha de Nacimiento: </b></td>
                                    <td>{{{ $target->client->date_of_birth }}}</td>
                                </tr>
                                @endif
                                @if ($target->client->email != '')
                                <tr>
                                    <td><b>Correo Electrónico: </b></td>
                                    <td>{{{ $target->client->email }}}</td>
                                </tr>
                                @endif
                                <tr>
                                    <td><b>Marketing Info:</b></td>
                                    <td>
                                        <ul>
                                            <li><b>Cant. Pacientes:</b> {{{ $target->client->qty_patiences }}}</li>
                                            <li><b>Precio Consulta:</b> S/. {{{ $target->client->price_inquiry }}}</li>
                                            <li><b>Nivel Socio Económico:</b> {{{ $target->client->social_level_patients }}}</li>
                                            <li><b>Grupo Etario:</b> {{{ ($target->client->attends_child)?'Niños ':' ' }}}
                                                {{{ ($target->client->attends_teen)?'Adolecentes ':' ' }}}
                                                {{{ ($target->client->attends_adult)?'Adultos ':' ' }}}
                                                {{{ ($target->client->attends_old)?'Adulto Mayor ':' ' }}}
                                            </li>
                                        </ul>
                                    </td>
                                </tr>
                            </table>

                        <!-- /.row - inside box -->
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <div class="row">
                        </div>
                        <!-- /.row -->
                    </div>
                    <!-- /.box-footer -->
                </div>
                <!-- /.box -->

                <!-- Calendar -->
                <div class="box box-warning">
                    <div class="box-header">
                        <i class="fa fa-calendar"></i>
                        <div class="box-title">Horario</div>

                        <!-- tools box -->
                        <div class="pull-right box-tools">
                            <!-- button with a dropdown -->
                            <div class="btn-group">

                                <button id="schedule_refresh" class="btn btn-warning btn-sm refresh-btn" data-toggle="tooltip" title="" data-original-title="Refrescar" style="margin-right: 5px;"><i class="fa fa-refresh"></i>
                                </button>


                                <button class="btn btn-warning btn-sm dropdown-toggle" data-toggle="dropdown"><i class="fa fa-bars"></i>
                                </button>
                                <ul class="dropdown-menu pull-right" role="menu">
                                    <li><a href="#" data-toggle="modal" data-target="#schedule-modal">Modificar Horario</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <!-- /. tools -->
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body no-padding">
                        <!--The calendar -->
                        <div id="calendar"></div>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->

            </section>
            <!-- /.Left col -->
            <!-- right col (We are only adding the ID to make the widgets sortable)-->
            <section class="col-lg-6 connectedSortable">
                <!-- Map box -->
                <div class="box box-primary">
                    <div class="box-header">
                        <!-- tools box -->
                        <div class="pull-right box-tools">
                            <button type="button" class="btn btn-primary btn-sm pull-right" data-widget='remove' data-toggle="tooltip" title="Eliminar"><i class="fa fa-times"></i>
                            </button>

                            <button class="btn btn-primary btn-sm pull-right" data-widget='collapse' data-toggle="tooltip" title="Colpasar" style="margin-right: 5px;"><i class="fa fa-minus"></i>
                            </button>
                        </div>
                        <!-- /. tools -->

                        <i class="fa fa-map-marker"></i>
                        <h3 class="box-title">
                            Ubicación
                        </h3>
                    </div>
                    <div class="box-body no-padding">
                        <input id="pac-input" class="controls" type="text" placeholder="Search Box">
                        <div id="world-map" style="height: 300px;"></div>
                    </div>
                    <!-- /.box-body-->
                </div>
                <!-- /.box -->


                <!-- TO DO List -->
                <div class="box box-primary">
                    <div class="box-header">
                        <i class="ion ion-clipboard"></i>
                        <h3 class="box-title">Notas</h3>
                        <div class="box-tools pull-right">

                            <ul class="pagination pagination-sm inline" id="note_pagination">
                            </ul>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <ul class="todo-list" id="list_note">
                        </ul>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer clearfix no-border">
                        <button class="btn btn-default pull-right" data-toggle="modal" data-target="#note-modal"><i class="fa fa-plus"></i> Agregar Nota</button>
                    </div>
                </div>
                <!-- /.box -->

                <div class="row">
                    <div class="col-xs-12">
                        <div class="box">
                            <div class="box-header">
                                <i class="fa fa-fw fa-medkit"></i>
                                <h3 class="box-title">Historial de Visitas</h3>
                            </div><!-- /.box-header -->
                            <div class="box-body table-responsive no-padding">
                                <table class="table table-hover">
                                    <tr>
                                        <th>Ciclo</th>
                                        <th>Consultor</th>
                                        <th>Fecha</th>
                                        <th>Sup</th>
                                        <th>Estado</th>
                                    </tr>


                                    @foreach ($allVisits as $key => $visit)
                                        <tr>
                                            <td>{{{ $visit->campaign->code }}}</td>
                                            <td>{{{ $visit->user->lastname.' '.$visit->user->firstname }}}</td>
                                            <td>
                                                <a href="{{ url('/frontend/visita/'.$visit->uuid) }}" >{{{ $visit->start }}}</a>
                                            </td>
                                            <td class="center-block">@if ($visit->is_supervised == 1)
                                                    <i class='fa fa-user-secret center-block'></i>
                                                @endif
                                            </td>
                                            <td><img src='/images/{{{ $visit->visit_status_id }}}.png' class='img-responsive center-block' alt='Estado' /></td>
                                        </tr>
                                    @endforeach
                                </table>
                            </div><!-- /.box-body -->
                        </div><!-- /.box -->
                    </div>
                </div>

            </section>
            <!-- right col -->
        </div>
        <!-- /.row (main row) -->

    </section>
    <!-- /.content -->


    <!-- NOTE MODAL -->
    <div class="modal fade" id="note-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"><i class="ion ion-clipboard"></i> Nota</h4>
                    {{{ $target->client->closeup_name }}}
                </div>
                <form action="#" method="post" id="form_note">
                    <input name="zone_id" type="hidden" value='{{{ $target->zone_id }}}' />
                    <input name="user_id" type="hidden" value='{{{ $target->user_id }}}' />
                    <input name="campaign_id" type="hidden" value='{{{ $target->campaign_id }}}' />
                    <input name="client_id" type="hidden" value='{{{ $target->client_id }}}' />
                    <input name="target_id" type="hidden" value='{{{ $target->id }}}' />

                    <input name="is_from_mobile" type="hidden" value='0' />
                    <div class="modal-body clearfix">
                        <div id="message" class="alert alert-warning alert-dismissable hide c">
                            <i class="fa fa-warning"></i>
                            <b>Fecha y Descripción Requerida<b>
                        </div>
                        <div class="form-group">
                            <label>Tipo de Nota</label>

                            {!! Form::select('note_type_id', $note_types_options , Input::old('note_types_options'), array('class' => 'form-control','id' => 'note_type_id')) !!}


                        </div>

                        <div class="form-group required">
                            <label>Fecha</label>
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text" class="form-control datepicker" name="date" id="note_date"/>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Descripción</label>
                            <div class="input-group">
                                    <textarea class="textarea" id="note_description" name="description" required class="form-control"
                                              style="width: 560px;"
                                              spellcheck="true" rows="10" cols="50"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer clearfix">
                        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Cancelar</button>

                        <button id="submit_note" type="button" class="btn btn-primary pull-left"><i class="fa fa-save"></i> Grabar</button>

                    </div>
                </form>
            </div>
        </div>
    </div>


    <!-- MARKETING MODAL -->
    <div class="modal fade" id="marketing-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"><i class="ion ion-clipboard"></i> Info Marketing</h4>
                    {{{ $target->client->closeup_name }}}
                </div>
                <form action="#" method="post" id="form_marketing">
                    <input name="client_id" type="hidden" value='{{{ $target->client_id }}}' />

                    <div class="modal-body clearfix">

                        <div class="col-md-6 col-sm-12">

                        <div class="form-group required">
                            <label>Cant. Pacientes</label>
                            <div class="input-group">
                                <input type="text" class="form-control" name="qty_patiences" id="qty_patiences"
                                       value="{{ $target->client->qty_patiences }}" />
                            </div>
                        </div>

                        <div class="form-group required">
                            <label>Precio Consulta</label>
                            <div class="input-group">
                                <input type="text" class="form-control" name="price_inquiry" id="price_inquiry"
                                       value="{{ $target->client->price_inquiry }}"/>
                            </div>
                        </div>

                        <div class="form-group required">
                            <label>Nivel Socio Económico</label>
                            <div class="input-group">
                                <select id="social_level_patients" name="social_level_patients" class="form-control">
                                    <option value="A" {{ ($target->client->social_level_patients == 'A')?'selected':'' }}>A</option>
                                    <option value="B" {{ ($target->client->social_level_patients == 'B')?'selected':'' }}>B</option>
                                    <option value="C" {{ ($target->client->social_level_patients == 'C')?'selected':'' }}>C</option>
                                </select>
                            </div>
                        </div>

                            </div>
                        <div class="col-md-6 col-sm-12">
                        <div class="form-group required">
                            <label>Niños</label>
                            <div class="input-group">
                                <input type="checkbox" id="attends_child" name="attends_child"
                                       value="1" {{ ($target->client->attends_child == '1')?'checked':'' }}>
                            </div>
                        </div>
                        <div class="form-group required">
                            <label>Adolecentes</label>
                            <div class="input-group">
                                <input type="checkbox" id="attends_teen" name="attends_teen"
                                       value="1" {{ ($target->client->attends_teen == '1')?'checked':'' }}>
                            </div>
                        </div>
                        <div class="form-group required">
                            <label>Adultos</label>
                            <div class="input-group">
                                <input type="checkbox" id="attends_adult" name="attends_adult"
                                       value="1" {{ ($target->client->attends_adult == '1')?'checked':'' }}>
                            </div>
                        </div>
                        <div class="form-group required">
                            <label>Ancianos</label>
                            <div class="input-group">
                                <input type="checkbox" id="attends_old" name="attends_old"
                                       value="1" {{ ($target->client->attends_old == '1')?'checked':'' }}>
                            </div>
                        </div>
                            </div>
                    </div>

                    <div class="modal-footer clearfix">
                        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Cancelar</button>

                        <button id="submit_marketing" type="button" class="btn btn-primary pull-left"><i class="fa fa-save"></i> Grabar</button>

                    </div>
                </form>
            </div>
        </div>
    </div>


    <!-- SCHEDULE MODAL -->
    <div class="modal fade" id="schedule-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"><i class="fa fa-clock-o"></i> Horario</h4>
                    {{{ $target->client->closeup_name }}}
                </div>
                <form action="#" method="post">

                    <input id="zone_id" type="hidden" value='{{{ $target->zone_id }}}'/>
                    <input id="client_id" type="hidden" value='{{{ $target->client_id }}}'/>
                    <div class="modal-body clearfix">

                        <div class="col-md-3 col-sm-4">

                            <h3 class="box-title">Lunes</h3>

                            <div class="bootstrap-timepicker">
                                <div class="form-group">
                                    <label>Inicio:</label>
                                    <div class="input-group">
                                        <input id="lunes_inicio"
                                               type="text"
                                               class="form-control timepicker"
                                               value="{{{ $schedules->isEmpty()?'':$schedules[0]->start_time }}}"
                                                />
                                        <div class="input-group-addon">
                                            <i class="fa fa-clock-o"></i>
                                        </div>
                                    </div><!-- /.input group -->

                                </div>
                            </div>
                            <div class="bootstrap-timepicker">
                                <div class="form-group">
                                    <label>Fin:</label>
                                    <div class="input-group">
                                        <input id="lunes_fin"
                                               value="{{{ $schedules->isEmpty()?'':$schedules[0]->finish_time }}}"
                                               type="text" class="form-control timepicker"/>
                                        <div class="input-group-addon">
                                            <i class="fa fa-clock-o"></i>
                                        </div>
                                    </div><!-- /.input group -->
                                </div><!-- /.form group -->
                            </div>

                        </div>
                        <div class="col-md-3 col-sm-4">
                            <h3 class="box-title">Martes</h3>

                            <div class="bootstrap-timepicker">
                                <div class="form-group">
                                    <label>Inicio:</label>
                                    <div class="input-group">
                                        <input id="martes_inicio"
                                               value="{{{ $schedules->isEmpty()?'':$schedules[1]->start_time }}}"
                                               type="text" class="form-control timepicker"/>
                                        <div class="input-group-addon">
                                            <i class="fa fa-clock-o"></i>
                                        </div>
                                    </div><!-- /.input group -->
                                </div>
                            </div>
                            <div class="bootstrap-timepicker">
                                <div class="form-group">
                                    <label>Fin:</label>
                                    <div class="input-group">
                                        <input id="martes_fin"
                                               value="{{{ $schedules->isEmpty()?'':$schedules[1]->finish_time }}}"
                                               type="text" class="form-control timepicker"/>
                                        <div class="input-group-addon">
                                            <i class="fa fa-clock-o"></i>
                                        </div>
                                    </div><!-- /.input group -->
                                </div><!-- /.form group -->
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-4">
                            <h3 class="box-title">Miercoles</h3>

                            <div class="bootstrap-timepicker">
                                <div class="form-group">
                                    <label>Inicio:</label>
                                    <div class="input-group">
                                        <input id="miercoles_inicio"
                                               value="{{{ $schedules->isEmpty()?'':$schedules[2]->start_time }}}"
                                               type="text" class="form-control timepicker"/>
                                        <div class="input-group-addon">
                                            <i class="fa fa-clock-o"></i>
                                        </div>
                                    </div><!-- /.input group -->
                                </div>
                            </div>
                            <div class="bootstrap-timepicker">
                                <div class="form-group">
                                    <label>Fin:</label>
                                    <div class="input-group">
                                        <input id="miercoles_fin"
                                               value="{{{ $schedules->isEmpty()?'':$schedules[2]->finish_time }}}"
                                               type="text" class="form-control timepicker"/>
                                        <div class="input-group-addon">
                                            <i class="fa fa-clock-o"></i>
                                        </div>
                                    </div><!-- /.input group -->
                                </div><!-- /.form group -->
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-4">
                            <h3 class="box-title">Jueves</h3>

                            <div class="bootstrap-timepicker">
                                <div class="form-group">
                                    <label>Inicio:</label>
                                    <div class="input-group">
                                        <input id="jueves_inicio"
                                               value="{{{ $schedules->isEmpty()?'':$schedules[3]->start_time }}}"
                                               type="text" class="form-control timepicker"/>
                                        <div class="input-group-addon">
                                            <i class="fa fa-clock-o"></i>
                                        </div>
                                    </div><!-- /.input group -->
                                </div>
                            </div>
                            <div class="bootstrap-timepicker">
                                <div class="form-group">
                                    <label>Fin:</label>
                                    <div class="input-group">
                                        <input id="jueves_fin"
                                               value="{{{ $schedules->isEmpty()?'':$schedules[3]->finish_time }}}"
                                               type="text" class="form-control timepicker"/>
                                        <div class="input-group-addon">
                                            <i class="fa fa-clock-o"></i>
                                        </div>
                                    </div><!-- /.input group -->
                                </div><!-- /.form group -->
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-4">
                            <h3 class="box-title">Viernes</h3>

                            <div class="bootstrap-timepicker">
                                <div class="form-group">
                                    <label>Inicio:</label>
                                    <div class="input-group">
                                        <input id="viernes_inicio"
                                               value="{{{ $schedules->isEmpty()?'':$schedules[4]->start_time }}}"
                                               type="text" class="form-control timepicker"/>
                                        <div class="input-group-addon">
                                            <i class="fa fa-clock-o"></i>
                                        </div>
                                    </div><!-- /.input group -->
                                </div>
                            </div>
                            <div class="bootstrap-timepicker">
                                <div class="form-group">
                                    <label>Fin:</label>
                                    <div class="input-group">
                                        <input id="viernes_fin"
                                               min="07:00:00" max="22:00:00"
                                               value="{{{ $schedules->isEmpty()?'':$schedules[4]->finish_time }}}"
                                               type="text" class="form-control timepicker"/>
                                        <div class="input-group-addon">
                                            <i class="fa fa-clock-o"></i>
                                        </div>
                                    </div><!-- /.input group -->
                                </div><!-- /.form group -->
                            </div>
                        </div>

                    </div>

                    <div class="modal-footer clearfix">

                        <button type="button" class="btn btn-warning" id="clear_schedule" ><i class="fa fa-eraser"></i> Limpiar</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Cancelar</button>



                        <button id="submit_schedule" type="button" class="btn btn-primary pull-left"><i class="fa fa-save"></i> Grabar</button>


                    </div>
                </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
@endsection