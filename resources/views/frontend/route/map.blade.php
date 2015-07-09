@extends('frontend.app')

@section('includes.css')
    @parent

    <!-- jvectormap -->
    <link href="/plugins/jvectormap/jquery-jvectormap-1.2.2.css" rel="stylesheet" type="text/css" />
    <!-- fullCalendar -->
    <link href="/plugins/fullcalendar/fullcalendar.css" rel="stylesheet" type="text/css" />
    <!-- Daterange picker -->
    <link href="/plugins/daterangepicker/daterangepicker-bs3.css" rel="stylesheet" type="text/css" />

@stop

@section('includes.js')
    @parent

    <script src="https://maps.googleapis.com/maps/api/js?v=3.exp"></script>
    <!-- daterangepicker -->
    <script src="/plugins/daterangepicker/daterangepicker.js" type="text/javascript"></script>

    <script type="text/javascript">

        var user_id = {{ $user->id }};
        var zone_id = {{ $zone->id }};
        var campaign_id = {{ $campaign->id }};



        function loadMapOfTheDay(start, end){

            $("#table_routes").empty();
            $.getJSON("/api/routes?zone_id="+zone_id+
                    "&campaign_id="+campaign_id+
                    "&start="+start+
                    "&end="+end, function(json1) {
                        if(json1.total > 0)
                        {
                            var path = new Array();

                            $.each(json1.data, function(key, data) {
                                var latLng = new google.maps.LatLng(data.client.latitude, data.client.longitude);

                                path[key] = latLng;
                                var marker = new google.maps.Marker({
                                    position: latLng,
                                    title: data.client.closeup_name,
                                    icon: 'http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld='+(key+1)+'|FF0000|000000'
                                });
                                //console.log(data);

                                var infowindow = new google.maps.InfoWindow({
                                    content: data.client.closeup_name+
                                    "<br>"+data.client.address+"</b>"
                                 //   ((data.client.location == null)?'':' - '+data.client.location.name)+
                                  //  "<br>"+((data.point_of_contact == "1")?'Punto de Contacto':'Ruta nomal')+
                                  //  " <b>"+moment(data.start,'YYYY-MM-DD H:mm:ss').fromNow()+"</b>"

                                });
                                google.maps.event.addListener(marker, 'click', function() {
                                    infowindow.open(map,marker);
                                });
                                marker.setMap(map);

                                var itemHtml = [
                                    "<tr>",
                                        "<td>"+(key+1)+"</td>",
                                    "<td><a href='/frontend/target/"+data.target_id+"'>"+data.client.closeup_name+"</a></td>",
                                    "<td>"+data.client.address+"</td>",
                                    "<td>"+moment(data.start).format("D/M/YY")+"</td>",
                                    "<td>"+moment(data.start).format("HH:mm")+"</td>",
                                 //   "<td>"+data.client.category.name+"</td>",
                                  //  "<td>"+data.client.place.name+"</td>",
                                    "<td>"+((data.point_of_contact == "1")?'Si':'No')+"</td>",
                                    "</tr>"
                                ].join("\n");

                                $("#table_routes").append(itemHtml);
                            });

                            var line = new google.maps.Polyline({
                                path: path,
                                map: map
                            });
                        }else{
                            var itemHtml = [
                                "<tr>",
                                "<td>Nada programado para este día.</td><td></td><td></td><td></td><td></td><td></td><td></td>",
                                "</tr>"
                            ].join("\n");

                            $("#table_routes").append(itemHtml);
                        }
                    }
            );
        }

        $('.daterange').daterangepicker(
                {
                    ranges: {
                        'Hoy': [moment(), moment()],
                        'Ayer': [moment().subtract('days', 1), moment().subtract('days', 1)],
                        'Mañana': [moment().add('days', 1), moment().add('days', 1)]
                    },
                    locale: {
                        applyLabel: 'Aplicar',
                        cancelLabel: 'Cancelar',
                        fromLabel: 'Desde',
                        toLabel: 'Hasta',
                        customRangeLabel: 'Rango de Fechas',
                        daysOfWeek: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi','Sa'],
                        monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
                        firstDay: 1
                    },
                    startDate: moment().subtract('days', 29),
                    endDate: moment()
                },
                function(start, end) {
                    myLatLong = new google.maps.LatLng(-12.089257, -77.037898);
                    var mapOptions = {
                        zoom: 11,
                        center: myLatLong
                    };
                    map = new google.maps.Map(document.getElementById('world-map'), mapOptions);

                    if(navigator.geolocation) {
                        navigator.geolocation.getCurrentPosition(function(position) {
                            var pos = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);

                            var marker = new google.maps.Marker({
                                position: pos,
                                icon: 'http://www.robotwoods.com/dev/misc/bluecircle.png',
                                title: 'Tu estás Aqui!.'
                            });
                            marker.setMap(map);
                            map.setCenter(pos);
                        }, function() {
                            handleNoGeolocation(true);
                        });
                    } else {
                        handleNoGeolocation(false);
                    }

                    loadMapOfTheDay(start.startOf("day").format("YYYY-MM-DD H:mm:ss"), end.endOf("day").format("YYYY-MM-DD H:mm:ss"));
                    // alert("You chose: " + start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
                });

        //Map widget
        var map;
        var myLatLong;

        function initialize() {
            myLatLong = new google.maps.LatLng(-12.089257, -77.037898);
            var mapOptions = {
                zoom: 11,
                center: myLatLong
            };
            map = new google.maps.Map(document.getElementById('world-map'), mapOptions);

            if(navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    var pos = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);

                    var marker = new google.maps.Marker({
                        position: pos,
                        icon: 'http://www.robotwoods.com/dev/misc/bluecircle.png',
                        title: 'Tu estás Aqui!.'
                    });
                    marker.setMap(map);
                    map.setCenter(pos);
                }, function() {
                    handleNoGeolocation(true);
                });
            } else {
                handleNoGeolocation(false);
            }

            loadMapOfTheDay(moment().startOf("day").format("YYYY-MM-DD H:mm:ss"), moment().endOf("day").format("YYYY-MM-DD H:mm:ss"));
        }


        function handleNoGeolocation(errorFlag) {
            if (errorFlag) {
                var content = 'Error: El servicio de geolocalización a fallado.';
            } else {
                var content = 'Error: Tu navegador no soporta geolocalización.';
            }

            var options = {
                map: map,
                position: new google.maps.LatLng(-12.089257, -77.037898),
                content: content
            };

            var infowindow = new google.maps.InfoWindow(options);
            map.setCenter(options.position);
        }

        google.maps.event.addDomListener(window, 'load', initialize);



    </script>

    @stop

    @section('header')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Mapa del Dia
            <small>Ubicación Geográfica del rutero por día.</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ url('/') }}"><i class="fa fa-dashboard"></i> Inicio</a></li>
            <li class="active">
                <i class="fa fa-map-marker"></i> Mapa del Día
            </li>
        </ol>
    </section>
@stop

@section('content')

    <div class="row">
        <div class="col-lg-12">
            <div class="alert alert-info alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <i class="fa fa-info-circle"></i>
                    <strong>Bienvenido a Reflex 360º?</strong>
                    Esta es una version de prueba, sientete libre de realizar los cambios que sean necesarios.
            </div>
        </div>
    </div>
    <!-- /.row -->
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-map-marker"></i> Mapa del Día</h3>

                    <div class="pull-right box-tools">
                        <button class="btn btn-primary btn-sm daterange pull-right" data-toggle="tooltip" title="Rango de días"><i class="fa fa-calendar"></i></button>
                        <button class="btn btn-primary btn-sm pull-right" data-widget='collapse' data-toggle="tooltip" title="Collapse" style="margin-right: 5px;"><i class="fa fa-minus"></i></button>
                    </div><!-- /. tools -->

                </div>
                <div class="box-body table-responsive">
                    <div id="world-map" style="width: 100%; height: 450px;"></div>

                    <div class="table-responsive">
                        <!-- .table - Uses sparkline charts-->
                        <table class="table table-striped" >
                            <thead>
                            <th>Id</th>
                            <th>Médico</th>
                            <th>Dirección</th>
                            <th>Fecha</th>
                            <th>Hora</th>
                            <th>PC</th>
                            </thead>
                            <tbody id="table_routes" />
                        </table><!-- /.table -->
                    </div>
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div>
    </div>
@endsection
