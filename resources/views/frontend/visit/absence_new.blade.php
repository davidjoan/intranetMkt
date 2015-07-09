@extends('frontend.app')

@section('includes.css')
    @parent
    <link href="/css/custom.css" rel="stylesheet" type="text/css" />
@stop

@section('includes.js')
    @parent

    <script src="/plugins/moment/moment.min.js" type="text/javascript"></script>
    <script src="/plugins/bootbox/bootbox.min.js" type="text/javascript"></script>

    <script type="text/javascript">

        $(document).ready(function(){
            function adjustHeight(){
                $('#lapListCont').height( ($(window).height() - $('#stopWatch').height() - $('#heightlap').height() - 110) + "px") ;
            }
            adjustHeight();
            $(window).on('resize', function(){
                adjustHeight();
            });
            //$('#time').fitText(.6, { minFontSize: '80vmin'});
            var startTime;
            var timeout;
            var pauseTime = 0;
            var mils;
            var paused = false;
            var count = 0;

            $('#time').on('click', function(){
                if(mils != undefined){
                    if(mils > 0){
                        $('#lapInst').hide();
                        var time = milToTime(mils);
                        //alert(mils);
                        time = formatTime(time);
                        count += 1;
                        $('#timeTable thead').show();
                        $('#lapList').prepend('<tr class="timeRow"><td class="lapCount muted">' + count + ".</td><td>" +time.h +':'+time.m +':'+time.s + '.' + time.mils+  '</td></tr>');
                        $('#lapListCont').animate({ scrollTop: 0 }, "fast");
                    }
                }
            });

            $('#btnPause').on('click', function(){

                bootbox.dialog({
                    message: "Estás seguro de queres guardar esta ausencia?",
                    title: "Nueva Ausencia",
                    buttons: {
                        success: {
                            label: "Ausencia",
                            className: "btn-success",
                            callback: function() {

                                $('#time').addClass('paused');
                                paused = true;
                                clearTimeout(timeout);
                                pauseTime = mils;


                                $('#btnPause').hide();

                                console.log(startTime);
                                console.log(mils);

                                var data_form = $('#visit_form').serialize();
                                var visit_start = $('#visit_start').val();
                                var uuid = $('#uuid').val();
                                var start = moment(visit_start).add(mils,'ms');

                               // console.log(data_form);
                               // console.log(visit_start);
                               // console.log(start.format('YYYY-MM-DD HH:mm:ss'));

                                data_form = data_form+'&end='+start.format('YYYY-MM-DD HH:mm:ss');

                                $("#is_supervised").prop('disabled', true);
                                $("#visit_description").prop('disabled', true);

                                if(navigator.geolocation) {
                                    navigator.geolocation.getCurrentPosition(function(position) {
                                        var latitude = position.coords.latitude;
                                        var longitude = position.coords.longitude;

                                        data_form=data_form+'&latitude='+latitude+'&longitude='+longitude;

                                        $.ajax({
                                            type: "PUT",
                                            url: "/api/visits/"+uuid,
                                            data: data_form,
                                            success: function(data) {
                                               // console.log(data);
                                                toastr.success('La ausencia se guardo correctamente!');

                                                $('#divBack').fadeIn(function(){
                                                    $('#back').removeClass("hidden");
                                                });
                                            }
                                        });
                                    }, function() {
                                        $.ajax({
                                            type: "PUT",
                                            url: "/api/visits/"+uuid,
                                            data: data_form,
                                            success: function(data) {
                                               // console.log(data);
                                                toastr.success('La ausencia se guardo correctamente!');

                                                $('#divBack').fadeIn(function(){
                                                    $('#back').removeClass("hidden");
                                                });
                                            }
                                        });
                                    });
                                } else {
                                    $.ajax({
                                        type: "PUT",
                                        url: "/api/visits/"+uuid,
                                        data: data_form,
                                        success: function(data) {
                                           // console.log(data);
                                            toastr.success('La ausencia se guardo correctamente!');

                                            $('#divBack').fadeIn(function(){
                                                $('#back').removeClass("hidden");
                                            });
                                        }
                                    });
                                }
                            }
                        },
                        danger: {
                            label: "Cancelar",
                            className: "btn-danger",
                            callback: function() {
                                paused = false;
                                $(this).html('<i class="icon-pause"></i>&nbsp;&nbsp;Fin');
                               // startTime = new Date();
                                clock();
                            }
                        }
                    }
                });


               /* if(paused == false){
                    paused = true;
                    clearTimeout(timeout);
                    pauseTime = mils;
                    $(this).html('<i class="icon-play-circle"></i>&nbsp;&nbsp;Fin');
                    $('#time').addClass('paused');
                }else{
                    paused = false;
                    $(this).html('<i class="icon-pause"></i>&nbsp;&nbsp;Fin');
                    startTime = new Date();
                    clock();
                }*/




                return false;

            });


            $('#btnStop').on('click', function(){
                $('#title').slideDown();
                $('#lapInst').hide();
                $('#time').removeClass('paused');
                paused = false;
                clearTimeout(timeout);
                pauseTime = 0;
                mils = 0;
                $('#time').html('00:00:00');
                $('#btnClear').click();
                $('#divControls').fadeOut(function(){
                    $('#divStart').fadeIn();
                    $('#btnPause').html('<i class="icon-pause"></i>&nbsp;&nbsp;Procesando');
                });
            });

            $('#btnStart').on('click', function(){
               /* $('#title').slideUp(function(){
                    adjustHeight();
                });*/
                var btn = $(this);
                startTime = new Date();
                clock();
                $('#divStart').fadeOut(function(){
                    $('#stop').removeClass("hidden");
                    $('#divControls').fadeIn();
                    $('#lapInst').fadeIn();
                    $('#lapTimes').fadeIn();
                });
            });
            function clock() {
                $('#time').removeClass('paused');
                var curTime = new Date();
                mils = (curTime - startTime) + pauseTime;
                var time = milToTime(mils);
                formatTime(time);
                var outStr = time.h +':'+time.m +':'+time.s;
                document.getElementById('time').innerHTML=outStr;
                timeout = setTimeout(clock,20);
            }

            function formatTime(time){
                for(var i in time){
                    if(i == "mils"){
                        if(time[i] < 1){
                            time[i] = "000";
                        }else
                        if(time[i] < 10){
                            time[i] = "00" + time[i];
                        }else
                        if(time[i] < 100){
                            time[i] = "0" + time[i];
                        }
                    }else if(time[i] < 10){
                        time[i] = "0" + time[i];
                    }
                }
                return time;
            }

            function milToTime(mil){
                mi = mil % 1000;
                seconds = parseInt(mil / 1000) % 60;
                minutes = parseInt(mil / 1000 / 60) % 60;
                hours = parseInt( mil / 1000 / 3600);
                return {s: seconds, m: minutes, h:hours, mils:mi};
            }

            $('#btnClear').on('click', function(){
                $('#lapList, #btnClear').fadeOut(function(){
                    $('#lapList').html('').fadeIn();
                    $('#timeTable thead').hide();
                    //$('#lapInst').fadeIn();
                    count = 0;
                });
            });
        });
    </script>
    @stop


@section('header')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Ausencia
            <small>{{ $visit->client->closeup_name }}</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ url('/') }}"><i class="fa fa-dashboard"></i> Inicio</a></li>
            <li><a href="{{ url('/frontend/target') }}"><i class="fa fa-user-md"></i> Target</a></li>
            <li><a href="{{ url('/frontend/target/'.$visit->target_id) }}"><i class="fa fa-user"></i> Perfil</a></li>
            <li class="active">
                <i class="fa fa-medkit"></i> Ausencia
            </li>
        </ol>
    </section>
@stop

@section('content')

        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title">Nueva Ausencia</h3>
                </div><!-- /.box-header -->
                <div class="box-body">


    <form id="visit_form">
        <input type="hidden" name="uuid" id="uuid" value="{{ $visit->uuid }}" />
        <input type="hidden" name="visit_type_id" value="1" />
        <input type="hidden" name="visit_status_id" value="3" />
        <input type="hidden" name="zone_id" value="{{ $visit->zone_id }}" />
        <input type="hidden" name="user_id" value="{{ $visit->user_id }}" />
        <input type="hidden" name="campaign_id" value="{{ $visit->campaign_id }}" />
        <input type="hidden" name="target_id" value="{{ $visit->target_id }}" />
        <input type="hidden" name="specialty_id" value="{{ $visit->client->specialty_base_id }}" />
        <input type="hidden" name="client_id" value="{{ $visit->client_id }}" />
        <input type="hidden" name="start" id="visit_start" value="{{ $start }}" />
        <input type="hidden" name="cmp" value="{{ $visit->client->code }}" />
        <input type="hidden" name="firstname" value="{{ $visit->client->firstname }}" />
        <input type="hidden" name="lastname" value="{{ $visit->client->lastname }}" />
        <input type="hidden" name="is_from_mobile" value="0" />
        <input type="hidden" name="active" value="1" />

        <div class="center">
            <div id="stopWatch">
                <h1 id="title">Nueva Ausencia<br><small>{{ $visit->client->closeup_name }}</small></h1>
                <div class="">
                    <div class="span12">
                        <div id="time" class="pointable" style="font-weight:bold; width:100%; display:block;">00:00:00</div>
                    </div>
                </div>
                <div class="">
                    <div class="span4 center">
                        <div class="form-group">
                            <select id="is_supervised" name="is_supervised" class="form-control center">
                                <option value="0">Sin Supervisión</option>
                                <option value="1">Con supervisión</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <select id="reason_id" name="reason_id" class="form-control center">
                                @foreach($reasons as $reason)
                                    <option value="{{ $reason->id }}">{{ $reason->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <input id="description" name="description" placeholder="Comentario" class="form-control input-lg" value="{{ $visit->client->description }}"/>
                        </div>
                    </div>
                </div>
                <div class="">
                    <div id="divStart" class="span4 center">
                        <button id="btnStart" class="btn btn-large btn-block btn-primary" style="font-weight:bold;" data-do="start" type="button">Inicio</button>
                    </div>
                </div>
                <div class="" id="divControls">
                    <div class="center">
                        <div class="hidden" id="stop">
                            <span class="span2">
								<button id="btnPause" class="btn btn-large btn-block" type="button">
                                    <i class="icon-pause"></i>
                                        &nbsp;&nbsp;Fin
                                </button>
							</span>
                        </div>
                    </div>
                </div>
                <div class="" id="divBack">
                    <div class="center">
                        <div class="hidden" id="back">
                            <span class="span2">
								<a id="btnBack" class="btn btn-large btn-block" type="button" href="{{ url('frontend/target/'.$visit->target_id) }}">
                                    &nbsp;&nbsp;Regresar
                                </a>
							</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="clear"></div>
            <div id="lapTimes" class="hidden row center">
                <div class="heightlap" style="margin-top:10px;">
                    <button class="btn hidden" id="btnClear" type="button">Clear</button>
                    <p id="lapInst">Tap the time to record a lap.</p>
                </div>
                <div class="span12 center scrollable" id="lapListCont" style="height:0px;">
                    <div class="row">
                        <div class="span4 center" style="margin-top:5px;">
                            <table id="timeTable" class="table table-striped" style="width:70%; margin:auto;">
                                <thead class="hidden">
                                <tr>
                                    <th>#</th>
                                    <th>Time</th>
                                </tr>
                                </thead>
                                <tbody id="lapList"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </form>
</div><!-- /.box-body -->
</div><!-- /.box -->
@endsection
