@extends("frontend.app")

@section("includes.css")
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

@section("includes.js")
    @parent
    <script src="/plugins/jQueryUI/jquery-ui-1.10.3.min.js" type="text/javascript"></script>
    <script src="/plugins/moment/moment.min.js" type="text/javascript"></script>
    <script src="/plugins/bootbox/bootbox.min.js" type="text/javascript"></script>
    <script src="/plugins/fullcalendar/fullcalendar.min.js" type="text/javascript"></script>
    <script src="/plugins/fullcalendar/lang-all.js" type="text/javascript"></script>
    <script src="/plugins/fullcalendar/gcal.js" type="text/javascript"></script>
    <script src="/plugins/iCheck/icheck.min.js" type="text/javascript"></script>
    <script src="/plugins/timepicker/bootstrap-timepicker.min.js" type="text/javascript"></script>
    <script src="/js/validator.js" type="text/javascript"></script>
    <script src="/plugins/typeahead/typeahead.js" type="text/javascript"></script>
    <script src="/plugins/typeahead/bootstrap3-typeahead.js" type="text/javascript"></script>

    <script type="text/javascript">
        var expense_id = {{ $expense->id }};
        var division_id = {{ $expense->division_id }};


        $(function() {
            var map;
            var selectioned;

            $("#add_cost_center").click(function(e) {
                console.log(map);
                console.log(map[selectioned]);




            });
            $(".typeahead2").typeahead({
                updater: function(selection){

                    $.ajax({
                        type: "GET",
                        url: "/frontend/cost_center/add/"+expense_id+"/"+map[selection],
                        success: function(data) {
                            console.log(data);
                        }
                    });

                    return selection;

                },

                source: function (query, process) {
                    // var $this = this; //get a reference to the typeahead object
                    return $.get('/frontend/cost_centers/' + division_id+'?query='+query,
                            function (data) {
                                //console.log(data);
                                var options = [];
                                map = {}; //replace any existing map attr with an empty object
                                for (i = 0; i < data.length; i++) {
                                    console.log(data[i]);
                                    options.push(data[i].name);
                                    map[data[i].name] = data[i].id;
                                }
                                return process(options);
                            }, 'json');
                }
            });

            $(".typeahead").typeahead({
                updater: function(selection){

                    $.ajax({
                        type: "GET",
                        url: "/frontend/cost_center/add/"+expense_id+"/"+map[selection],
                        success: function(data) {
                            console.log(data);
                            toastr.success('Su agrego el centro de costo correctamente!');

                            setTimeout(function(){
                                window.location.href = '/frontend/detalle/'+expense_id;
                                return false;
                            },2000);

                        }
                    });

                },

                source: function (query, process) {
                    // var $this = this; //get a reference to the typeahead object
                    return $.get('/frontend/cost_centers/' + division_id+'?query='+query,
                            function (data) {
                                //console.log(data);
                                var options = [];
                                map = {}; //replace any existing map attr with an empty object
                                for (i = 0; i < data.length; i++) {
                                    console.log(data[i]);
                                    options.push(data[i].name);
                                    map[data[i].name] = data[i].id;
                                }
                                return process(options);
                            }, 'json');
                }
            });
        });
    </script>
    <script src="/js/target_show.js" type="text/javascript"></script>

    @if (in_array($expense->expense_type->id, array(1,2,3,4)))
        <script>
            loadNotes(1);
        </script>
    @endif

    @if (in_array($expense->expense_type->id, array(5,6,8)))
        <script>
            loadEntertainments(1);
        </script>
    @endif

    @if (in_array($expense->expense_type->id, array(7,9,15)))
        <script>
            loadCampaigns(1);
        </script>
    @endif

    @if (in_array($expense->expense_type->id, array(16,17)))
        <script>
            loadAttentions(1);
        </script>
    @endif

@stop


@section("header")
    <!-- Content Header (Page header)-->
    <section class="content-header">
        <h1>
            {{ $expense->name }}
            <small>Detalle de Gasto</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ url('/frontend/home') }}"><i class="fa fa-dashboard"></i> Inicio</a></li>
            <li><a href="{{ url('/frontend/gastos') }}"><i class="fa fa-medkit"></i> Gastos</a></li>
            <li class="active"><i class="fa fa-calendar"></i> Detalle {{ $expense->name }}</li>
        </ol>
    </section>
@stop

@section("content")
    <!-- Main content -->
    <section class="content">


        <div class="row">
            <div class="col-lg-12">
                <div class="alert alert-info alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <i class="fa fa-info-circle"></i>
                    <strong>Bienvenido a Intranet MKT?</strong>
                    Esta es una version de prueba, sientete libre de realizar los cambios que sean necesarios.
                </div>
            </div>
        </div>
        @if(Session::has('message'))
            <div class="row">
                <div class="col-lg-12">
                    <div class="alert alert-success alert-dismissable">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        {{ Session::get('message')  }}
                    </div>
                </div>
            </div>

        @endif



        <!-- top row -->
        <div class="row">
            <section class="col-lg-12">

            <div class="btn-group pull-right" style="padding-bottom: 5px;">

                <button type="button" class="btn btn-primary btn-flat" href="#" id="eliminar_gasto">Eliminar</button>

            </div>
                </section>

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

                        <h3 class="box-title">Registro de Gasto</h3>
                    </div>

                    <div class="box-body">

                        <table class="table">
                            <tr>
                                <td><b>Tipo: </b></td>
                                <td>{{{ $expense->expense_type->name }}}</td>
                            </tr>
                            <tr>
                                <td><b>Usuario: </b></td>
                                <td>{{{ $expense->user->name }}}</td>
                            </tr>
                            <tr>
                                <td><b>División: </b></td>
                                <td>{{{ $expense->division->name }}}</td>
                            </tr>
                            <tr>
                                <td><b>Fecha: </b></td>
                                <td>{{{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$expense->application_date)->format('d/m/Y') }}}</td>
                            </tr>
                            <tr>
                                <td><b>Codigo: </b></td>
                                <td>{{{ $expense->code }}}</td>
                            </tr>
                            <tr>
                                <td><b>Nombre: </b></td>
                                <td>{{{ $expense->name }}}</td>
                            </tr>
                            <tr>
                                <td><b>description: </b></td>
                                <td>{{{ $expense->description }}}</td>
                            </tr>


                            <tr>
                                <td><b>Visto Bueno:</b></td>
                                <td>
                                    <ul>
                                        <li><b>Gerente División:</b>
                                            @if ($expense->approval_2 == '1')
                                                <i class="fa fa-fw fa-check-circle-o"></i>
                                            @else
                                                <i class="fa fa-fw fa-circle-o"></i></i>
                                            @endif
                                        </li>
                                        <li><b>Control de Gestión:</b>
                                            @if ($expense->approval_3 == '1')
                                                <i class="fa fa-fw fa-check-circle-o"></i>
                                            @else
                                                <i class="fa fa-fw fa-circle-o"></i></i>
                                            @endif
                                        </li>
                                        <li><b>Gerencia General:</b>
                                            @if ($expense->approval_4 == '1')
                                                <i class="fa fa-fw fa-check-circle-o"></i>
                                            @else
                                                <i class="fa fa-fw fa-circle-o"></i></i>
                                            @endif
                                        </li>
                                    </ul>
                                </td>
                            </tr>
                            <tr>
                                <td><b>Formatos:</b></td>
                                <td>
                                    <ul>
                                        @foreach ($expense->expense_type->file_formats as $file_format)
                                            <li>{{ $file_format->name }}</li>
                                        @endforeach
                                    </ul>
                                </td>
                            </tr>

                            <tr>
                                <td><b>Monto: </b></td>
                                <td>S/. {{{ $expense->total_amount }}}</td>
                            </tr>
                            <tr>
                                <td><b>Estimado: </b></td>
                                <td>S/. {{{ $expense->estimated_amount }}}</td>
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


            </section>
            <!-- /.Left col -->

            <!-- right col (We are only adding the ID to make the widgets sortable)-->
            <section class="col-lg-6 connectedSortable">



                <!-- Informac List -->
                <div class="box box-primary">
                    <div class="box-header">
                        <i class="ion ion-clipboard"></i>
                        <h3 class="box-title">Información de Formatos</h3>
                        <div class="box-tools pull-right">
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">

                        <table class="table">
                            <tr>
                                <td><b>Portada </b></td>
                                <td><a class="btn btn-primary" href="/frontend/gastos/portada/{{ $expense->id }}">
                                        <i class="fa fa-download"></i> Descargar Portada</a></td>
                            </tr>

                            <!--
                            <tr>
                                <td><b>Reporte </b></td>
                                <td><a class="btn btn-primary" href="/frontend/gastos/reporte/{{ $expense->id }}">
                                        <i class="fa fa-download"></i> Reporte</a></td>
                            </tr>
-->

                            @if (in_array($expense->expense_type->id, array(10,11,12,13,14)))
                                @foreach($expense->expense_type->file_formats as $file_format)
                                    <tr>
                                        <td><b>{{ $file_format->name }} Original </b></td>
                                        <td><a class="btn btn-primary" href="/uploads/{{ $file_format->file }}" target="_blank">
                                                <i class="fa fa-download"></i> Descargar {{ $file_format->name }}</a></td>
                                    </tr>

                                @endforeach

                            @endif

                            @if (in_array($expense->expense_type->id, array(16,17)))
                                @foreach($expense->expense_type->file_formats as $file_format)
                                    <tr>
                                        <td><b>{{ $file_format->name }} </b></td>
                                        <td><a class="btn btn-primary" href="/frontend/gastos/exportar_atencion_xls/{{ $expense->id }}/{{ $file_format->id }}">
                                                <i class="fa fa-download"></i> Descargar {{ $file_format->name }}</a></td>
                                    </tr>
                                @endforeach
                            @endif
                            @if (in_array($expense->expense_type->id, array(7,9,15)))
                                @foreach($expense->expense_type->file_formats as $file_format)
                                    <tr>
                                        <td><b>{{ $file_format->name }} </b></td>
                                        <td><a class="btn btn-primary" href="/frontend/gastos/exportar_campana_xls/{{ $expense->id }}/{{ $file_format->id }}">
                                                <i class="fa fa-download"></i> Descargar {{ $file_format->name }}</a></td>
                                    </tr>

                                @endforeach
                            @endif

                            @if (in_array($expense->expense_type->id, array(1,2,3,4)))
                                @foreach($expense->expense_type->file_formats as $file_format)
                                    <tr>
                                        <td><b>{{ $file_format->name }} </b></td>
                                        <td><a class="btn btn-primary" href="/frontend/gastos/exportar_xls/{{ $expense->id }}/{{ $file_format->id }}">
                                                <i class="fa fa-download"></i> Descargar {{ $file_format->name }}</a></td>
                                    </tr>

                                @endforeach
                            @endif

                            @if (in_array($expense->expense_type->id, array(5,6,8)))
                                @foreach($expense->expense_type->file_formats as $file_format)
                                    <tr>
                                        <td><b>{{ $file_format->name }} </b></td>
                                        <td><a class="btn btn-primary" href="/frontend/gastos/exportar_entretenimiento_xls/{{ $expense->id }}/{{ $file_format->id }}">
                                                <i class="fa fa-download"></i> Descargar {{ $file_format->name }}</a></td>
                                    </tr>

                                @endforeach
                            @endif

                            @foreach($expense->expense_details as $expense_detail)
                                <tr>
                                    <td><b>{{ $expense_detail->file_format->name }} escaneado </b></td>
                                    <td><a class="btn btn-primary" href="/uploads/expense_details/{{ $expense_detail->filename }}" target="_blank">
                                            <i class="fa fa-download"></i> Descargar {{ $expense_detail->file_format->name }}</a></td>
                                </tr>
                            @endforeach




                        </table>

                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer clearfix no-border">
                                           </div>
                </div>
                <!-- /.box -->

                <div class="box box-primary">
                    <div class="box-header">
                        <i class="ion ion-clipboard"></i>
                        <h3 class="box-title">Información de Centro de Costo</h3>
                        <div class="box-tools pull-right">
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">

                        <div class="box-header with-border">
                            <input type="text" class="form-control typeahead"
                                   data-provide="typeahead" STYLE=""
                                   placeholder="Ingresa el Centro de Costo">
                        </div>
                        <br>


                        <table class="table table-bordered table-striped">
                            <tbody><tr>
                                <th style="width: 10px">Código</th>
                                <th>Centro de Costo</th>
                                <th>Distribución</th>
                                <th style="width: 40px">Porcentaje</th>
                                <th></th>
                            </tr>
                            <?php $acomulado = 0 ?>
                            @foreach($expense->expense_amounts as $key => $expense_amount)

                            <tr>
                                <td>
                                    <?php  $acomulado += $expense_amount->percent ?>
                                        {{$expense_amount->cost_center->code }}</td>
                                <td>{{ $expense_amount->cost_center->name }}</td>
                                <td>
                                    <div class="progress progress-xs">
                                        <div class="progress-bar progress-bar-success" style="width: {{ $acomulado }}%"></div>
                                    </div>
                                </td>
                                <td><span class="badge bg-success">{{ $expense_amount->percent }}%</span></td>
                                <td><div class='tools'>
                                        <i class='fa fa-trash-o' onClick='deleteExpenseAmount({{ $expense_amount->id }},{{ $expense_amount->expense_id }})'></i>
                                        </div>
                                </td>
                            </tr>
                            @endforeach
                            </tbody></table>
                    </div>
                </div>


    </section>


            </div>



        @if (in_array($expense->expense_type->id, array(16,17)))
            @foreach($expense->expense_type->file_formats as $file_format)

                <div class="row">
                    <!-- right col (We are only adding the ID to make the widgets sortable)-->
                    <section class="col-lg-12 connectedSortable">

                        <!-- TO DO List -->
                        <div class="box box-primary">
                            <div class="box-header">
                                <i class="ion ion-clipboard"></i>
                                <h3 class="box-title">{{ $file_format->name }}</h3>
                                <div class="box-tools pull-right">

                                    <ul class="pagination pagination-sm inline" id="note_pagination">
                                    </ul>
                                </div>
                            </div>
                            <!-- /.box-header -->
                            <div class="box-body">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th style="width: 10px">#</th>
                                        <th>Promotora</th>
                                        <th>Cod Cliente</th>
                                        <th>Cliente</th>
                                        <th>Descripción</th>
                                        <th>Cost Unit.</th>
                                        <th>Cantidad</th>
                                        <th>Total</th>
                                        <th>Motivo</th>
                                        <th>&nbsp;</th>
                                    </tr>
                                    </thead>
                                    <tbody id="list_note">

                                    </tbody>
                                </table>
                            </div>
                            <!-- /.box-body -->
                            <div class="box-footer clearfix no-border">
                                <button class="btn btn-default pull-right" data-toggle="modal" data-target="#attention-modal"><i class="fa fa-plus"></i> Agregar Detalle</button>
                            </div>
                        </div>
                        <!-- /.box -->
                    </section>
                </div>


                <div class="modal fade" id="attention-modal" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                <h4 class="modal-title"><i class="ion ion-clipboard"></i> Detalle {{{ $expense->expense_type->name }}}</h4>
                                {{{ $expense->name }}}
                            </div>
                            <form action="#" method="post" id="form_attention">

                                <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                                <input name="file_format_id" type="hidden" value="{{ $file_format->id }}" />
                                <input name="expense_id" type="hidden" value="{{{ $expense->id }}}" />

                                <div class="modal-body">

                                    <div class="form-group">
                                        <label for="promotora">promotora</label>
                                        <input type="text" class="form-control" name="promotora" id="promotora" required />
                                    </div>

                                    <div class="form-group">
                                        <label for="client_code">Cod Cliente</label>
                                        <input type="text" class="form-control " name="client_code" id="client_code" required/>
                                    </div>

                                    <div class="form-group">
                                        <label for="client">Cliente</label>
                                        <input type="text" class="form-control " name="client" id="client" required/>
                                    </div>

                                    <div class="form-group">
                                        <label for="description">Descripción</label>
                                        <input type="text" class="form-control " name="description" id="description" required/>
                                    </div>
                                    <div class="form-group">
                                        <label>Costo Unitario S/.</label>
                                        <div class="input-group">
                                            <span class="input-group-addon">S/.</span>
                                            <input type="text" class="form-control" name="price_unit" id="price_unit_attention" required />
                                            <span class="input-group-addon">.00</span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="quantity">Cantidad</label>
                                        <input type="text" class="form-control" name="quantity" id="quantity_attention" required/>
                                    </div>
                                    <div class="form-group">
                                        <label>Valor Estimado S/.</label>
                                        <div class="input-group">
                                            <span class="input-group-addon">S/.</span>
                                            <input type="text" class="form-control" name="estimated_value" id="estimated_value_attention" required readonly />
                                            <span class="input-group-addon">.00</span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Motivo</label>
                                        <input type="text" class="form-control " name="reason" id="reason" required />
                                    </div>


                                </div>
                                <div class="modal-footer clearfix">
                                    <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Cancelar</button>

                                    <button id="submit_attention" type="submit" class="btn btn-primary pull-left"><i class="fa fa-save"></i> Grabar</button>

                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            @endforeach
        @endif

        @if (in_array($expense->expense_type->id, array(7,9,15)))
            @foreach($expense->expense_type->file_formats as $file_format)
                <div class="row">
                    <!-- right col (We are only adding the ID to make the widgets sortable)-->
                    <section class="col-lg-12 connectedSortable">

                        <!-- TO DO List -->
                        <div class="box box-primary">
                            <div class="box-header">
                                <i class="ion ion-clipboard"></i>
                                <h3 class="box-title">{{ $file_format->name }}</h3>
                                <div class="box-tools pull-right">

                                    <ul class="pagination pagination-sm inline" id="note_pagination">
                                    </ul>
                                </div>
                            </div>
                            <!-- /.box-header -->
                            <div class="box-body">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th style="width: 10px">#</th>
                                        <th>Consultor</th>
                                        <th>Fecha</th>
                                        <th>Lugar</th>
                                        <th>CMP</th>
                                        <th>Medico</th>
                                        <th>Monto</th>
                                        <th>&nbsp;</th>
                                    </tr>
                                    </thead>
                                    <tbody id="list_note">

                                    </tbody>
                                </table>
                            </div>
                            <!-- /.box-body -->
                            <div class="box-footer clearfix no-border">
                                <button class="btn btn-default pull-right" data-toggle="modal" data-target="#campaign-modal"><i class="fa fa-plus"></i> Agregar Detalle</button>
                            </div>
                        </div>
                        <!-- /.box -->
                    </section>
                </div>

                <!-- /.content -->

                <div class="modal fade" id="campaign-modal" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                <h4 class="modal-title"><i class="ion ion-clipboard"></i> Detalle {{{ $expense->expense_type->name }}}</h4>
                                {{{ $expense->name }}}
                            </div>
                            <form action="#" method="post" id="form_campaign">

                                <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                                <input name="file_format_id" type="hidden" value="{{ $file_format->id }}" />
                                <input name="expense_id" type="hidden" value="{{{ $expense->id }}}" />

                                <div class="modal-body">

                                    <div class="form-group">
                                        <label for="type_entertainment">Consultor</label>
                                        <input type="text" class="form-control" name="consultor" id="consultor" required />
                                    </div>
                                    <!--

                                    <div class="form-group">
                                        <label for="entertainment_type">Tipo Actividad</label>
                                        <input type="text" class="form-control " name="entertainment_type" id="entertainment_type" required/>
                                    </div>-->

                                    <div class="form-group">
                                        <label>Fecha</label>
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                            <input type="text" class="form-control datepicker" name="delivery_date" id="delivery_date" required/>
                                        </div>
                                    </div>


                                    <div class="form-group">
                                        <label for="place">Lugar</label>
                                        <input type="text" class="form-control " name="place" id="place" required/>
                                    </div>


                                    <div class="form-group">
                                        <label for="cmp">CMP</label>
                                        <input type="text" class="form-control" name="cmp" id="cmp" required/>
                                    </div>
                                    <div class="form-group">
                                        <label for="doctor">Doctor</label>
                                        <input type="text" class="form-control" name="doctor" id="doctor" required/>
                                    </div>
                                    <div class="form-group">
                                        <label>Valor Estimado S/.</label>
                                        <div class="input-group">
                                            <span class="input-group-addon">S/.</span>
                                            <input type="text" class="form-control" name="estimated_value" id="estimated_value" required />
                                            <span class="input-group-addon">.00</span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Descripción</label>

                                        <input type="text" class="form-control " name="description" id="description" required />

                                    </div>


                                </div>
                                <div class="modal-footer clearfix">
                                    <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Cancelar</button>

                                    <button id="submit_campaign" type="submit" class="btn btn-primary pull-left"><i class="fa fa-save"></i> Grabar</button>

                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            @endforeach

        @endif

        @if (in_array($expense->expense_type->id, array(5,6,8)))

            @foreach($expense->expense_type->file_formats as $file_format)

                <div class="row">
                    <!-- right col (We are only adding the ID to make the widgets sortable)-->
                    <section class="col-lg-12 connectedSortable">

                        <!-- TO DO List -->
                        <div class="box box-primary">
                            <div class="box-header">
                                <i class="ion ion-clipboard"></i>
                                <h3 class="box-title">{{ $file_format->name }}</h3>
                                <div class="box-tools pull-right">

                                    <ul class="pagination pagination-sm inline" id="note_pagination">
                                    </ul>
                                </div>
                            </div>
                            <!-- /.box-header -->
                            <div class="box-body">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th style="width: 10px">#</th>
                                        <th>Consultor</th>
                                        <th>Tipo Actividad</th>
                                        <th>Fecha</th>
                                        <th>Lugar</th>
                                        <th># HCP</th>
                                        <th>Monto</th>
                                        <th>Costo Unit.</th>
                                        <th>Monto Max.</th>
                                        <th>Aprobado</th>
                                        <th>&nbsp;</th>
                                    </tr>
                                    </thead>
                                    <tbody id="list_note">

                                    </tbody>
                                </table>


                            </div>
                            <!-- /.box-body -->
                            <div class="box-footer clearfix no-border">
                                <button class="btn btn-default pull-right" data-toggle="modal" data-target="#entertainment-modal"><i class="fa fa-plus"></i> Agregar Detalle</button>
                            </div>
                        </div>
                        <!-- /.box -->
                    </section>
                </div>

                <!-- /.content -->
                <!-- ENTERTAINMENT MODAL-->
                <div class="modal fade" id="entertainment-modal" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                <h4 class="modal-title"><i class="ion ion-clipboard"></i> Detalle {{{ $expense->expense_type->name }}}</h4>
                                {{{ $expense->name }}}
                            </div>
                            <form action="#" method="post" id="form_entertainment">

                                <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                                <input name="file_format_id" type="hidden" value="{{ $file_format->id }}" />
                                <input name="expense_id" type="hidden" value="{{{ $expense->id }}}" />

                                <div class="modal-body">

                                    <div class="form-group">
                                        <label for="type_entertainment">Consultor</label>
                                        <input type="text" class="form-control" name="consultor" id="consultor" required />
                                    </div>

                                    <div class="form-group">
                                        <label for="entertainment_type">Tipo Actividad</label>
                                        @if($expense->expense_type_id == 5)
                                            <select class="form-control" name="entertainment_type" id="entertainment_type" required="">
                                                <option value="Refrigerio">Refrigerio</option>
                                                <option value="Desayuno">Desayuno</option>
                                                <option value="Almuerzo">Almuerzo</option>
                                                <option value="Cena">Cena</option>
                                            </select>
                                        @else
                                            <input type="text" class="form-control " name="entertainment_type" id="entertainment_type" required/>
                                        @endif
                                    </div>

                                    <div class="form-group">
                                        <label>Fecha</label>
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                            <input type="text" class="form-control datepicker" name="delivery_date" id="delivery_date" required/>
                                        </div>
                                    </div>


                                    <div class="form-group">
                                        <label for="place">Lugar</label>
                                        <input type="text" class="form-control " name="place" id="place" required/>
                                    </div>


                                    <div class="form-group">
                                        <label for="qty_doctors">Cantidad</label>
                                        <input type="text" class="form-control" name="qty_doctors" id="qty_doctors" required/>
                                    </div>
                                    <div class="form-group">
                                        <label>Valor Estimado S/.</label>
                                        <div class="input-group">
                                            <span class="input-group-addon">S/.</span>
                                            <input type="text" class="form-control" name="estimated_value" id="estimated_value" required />
                                            <span class="input-group-addon">.00</span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Descripción</label>

                                        <input type="text" class="form-control " name="description" id="description" required />

                                    </div>


                                </div>
                                <div class="modal-footer clearfix">
                                    <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Cancelar</button>

                                    <button id="submit_entertainment" type="submit" class="btn btn-primary pull-left"><i class="fa fa-save"></i> Grabar</button>

                                </div>
                            </form>
                        </div>
                    </div>
                </div>


            @endforeach
        @endif


        @if (in_array($expense->expense_type->id, array(1,2,3,4)))

            @foreach($expense->expense_type->file_formats as $file_format)

            <div class="row">
                <!-- right col (We are only adding the ID to make the widgets sortable)-->
                <section class="col-lg-12 connectedSortable">

                    <!-- TO DO List -->
                    <div class="box box-primary">
                        <div class="box-header">
                            <i class="ion ion-clipboard"></i>
                            <h3 class="box-title">{{ $file_format->name }}</h3>
                            <div class="box-tools pull-right">

                                <ul class="pagination pagination-sm inline" id="note_pagination">
                                </ul>
                            </div>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th style="width: 10px">#</th>
                                    <th>Codigo</th>
                                    <th>Centro de Costo</th>
                                    <th>Cuenta Contable</th>
                                    <th>Activo</th>
                                    <th>Gasto</th>
                                    <th>Inventario</th>
                                    <th>Cant</th>
                                    <th>Precio Unidad</th>
                                    <th>Desc</th>
                                    <th>Total</th>
                                    <th>Destino</th>
                                    <th>Fecha</th>
                                    <th>&nbsp;</th>
                                </tr>
                                </thead>
                                <tbody id="list_note">

                                </tbody>
                            </table>


                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer clearfix no-border">
                            <button class="btn btn-default pull-right" data-toggle="modal" data-target="#note-modal"><i class="fa fa-plus"></i> Agregar Detalle</button>
                        </div>
                    </div>
                    <!-- /.box -->
                </section>
            </div>
                <!-- /.content -->
                <!-- BUY ORDER MODAL-->
                <div class="modal fade" id="note-modal" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                <h4 class="modal-title"><i class="ion ion-clipboard"></i> Detalle {{{ $expense->expense_type->name }}}</h4>
                                {{{ $expense->name }}}
                            </div>
                            <form action="#" method="post" id="form_note">

                                <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                                <input name="file_format_id" type="hidden" value="{{ $file_format->id }}" />
                                <input name="expense_id" type="hidden" value="{{ $expense->id }}" />

                                <div class="modal-body">

                                    <div class="form-group">
                                        <label for="code">Código</label>
                                            <input type="text" class="form-control " name="code" id="code" required />

                                    </div>

                                    <div class="form-group">
                                        <label for="cost_center">Centro de Costo</label>
                                        <input type="text" class="form-control typeahead2" name="cost_center" id="cost_center" required/>
                                    </div>

<!--
                                    <div class="form-group">
                                        <label for="book_account">Cuenta Contable</label>
                                            <input type="text" class="form-control " name="book_account" id="book_account" required/>
                                    </div>-->

                                    <div class="checkbox">
                                        <label for="active">
                                            <input type="checkbox" id="active" name="active"> Activo
                                        </label>
                                    </div>

                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" id="expenditure" name="expenditure"> Gasto
                                        </label>
                                    </div>

                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" id="inventory" name="inventory"> Inventario
                                        </label>
                                    </div>

                                    <div class="form-group">
                                        <label for="quantity">Cantidad</label>
                                            <input type="text" class="form-control" name="quantity" id="quantity" required/>
                                    </div>
                                    <div class="form-group">
                                        <label>Precio Unidad S/.</label>
                                        <div class="input-group">
                                            <span class="input-group-addon">S/.</span>
                                            <input type="text" class="form-control" name="price_unit" id="price_unit" required>
                                            <span class="input-group-addon">.00</span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Descripción de lo solicitado</label>

                                        <input type="text" class="form-control " name="description" id="description" required />

                                    </div>
                                    <div class="form-group">
                                        <label>Valor Estimado S/.</label>
                                        <div class="input-group">
                                            <span class="input-group-addon">S/.</span>
                                            <input type="text" class="form-control" name="estimated_value" id="estimated_value" required readonly />
                                            <span class="input-group-addon">.00</span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="destination">Destino</label>
                                            <input type="text" class="form-control " name="destination" id="destination" required/>
                                    </div>

                                    <div class="form-group">
                                        <label>Fecha de entrega solicitada</label>
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                            <input type="text" class="form-control datepicker" name="delivery_date" id="delivery_date" required/>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer clearfix">
                                    <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Cancelar</button>

                                    <button id="submit_detail" type="submit" class="btn btn-primary pull-left"><i class="fa fa-save"></i> Grabar</button>

                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            @endforeach

    <!-- /.row (main row) -->


    @endif



                        <div class="row">
                            @foreach($expense->expense_type->file_formats as $file_format)

                                <div class="col-md-6">

                                    <div class="box box-primary">
                                        <div class="box-header with-border">
                                            <h3 class="box-title">Subir {{ $file_format->name }} Escaneado</h3>
                                        </div><!-- /.box-header -->
                                        <!-- form start -->
                                        {!!  Form::open(array("url"=>"/frontend/upload_format","files"=>true,"role" => "form")) !!}

                                        <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                                        <input name="file_format_id" type="hidden" value="{{ $file_format->id }}" />
                                        <input name="expense_id" type="hidden" value="{{{ $expense->id }}}" />

                                        <div class="box-body">
                                            <div class="form-group">
                                                <label for="exampleInputFile">Formato Escaneado Imagen/PDF</label>
                                                {!!  Form::file("file") !!}

                                                <p class="help-block">Ejemplo formato11.pdf.</p>
                                            </div>

                                        </div><!-- /.box-body -->

                                        <div class="box-footer">
                                            <button type="submit" class="btn btn-primary">Subir</button>
                                        </div>
                                        {!!  Form::close() !!}

                                    </div><!-- /.box -->
                                </div>

                            @endforeach
                        </div>


    </section>




    <!-- EDIT EXPENSE MODAL -->
    <div class="modal fade" id="marketing-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"><i class="ion ion-clipboard"></i> Modificar Gasto</h4>
                    {{{ $expense->name }}}
                </div>
                <form action="#" method="post" id="edit_expense">
                    <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                    <div class="modal-body clearfix">

                        <div class="col-md-12 col-sm-12">

                            <div class="form-group">
                                <label>Fecha</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" class="form-control datepicker" name="application_date"
                                           id="application_date"
                                           value="{{{ \Carbon\Carbon::createFromFormat("Y-m-d H:i:s",$expense->application_date)->format("d/m/Y") }}}"
                                           required />
                                </div>
                            </div>

                            <div class="form-group required">
                                <label for="name">Nombre</label>
                                    <input type="text" class="form-control" name="name" value="{{ $expense->name }}" required />
                            </div>

                            <div class="form-group required">
                                <label for="description">Descripción</label>
                                    <input type="text" class="form-control" name="description"
                                           value="{{ $expense->description }}" />
                            </div>
                            <div class="form-group">
                                <label for="estimated_amount">Monto Estimado S/.</label>
                                <div class="input-group">
                                    <span class="input-group-addon">S/.</span>
                                    <input type="text" class="form-control" name="estimated_amount" id="estimated_amount"
                                           required
                                           value="{{ $expense->estimated_amount }}"
                                           placeholder="Monto estimado"
                                           data-error="Monto estimado Requerido."
                                            />
                                    <span class="input-group-addon">.00</span>
                                </div>
                            </div>


                        </div>

                    <div class="modal-footer clearfix">
                        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Cancelar</button>

                        <button id="" type="submit" class="btn btn-primary pull-left"><i class="fa fa-save"></i> Grabar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop