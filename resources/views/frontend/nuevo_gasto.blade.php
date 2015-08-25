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
    <script src="/js/validator.js" type="text/javascript"></script>

    <script type="text/javascript">


        $(document).ready(function() {

            $('.datepicker').datepicker({
                startDate: 'now',
                language: 'es',
                format: 'dd/mm/yyyy',
                daysOfWeekDisabled: '0'
            });

            $('#cancelar').click(function () {
                window.location.href = '/frontend/gastos';
                return false;
            });

            $('#gasto').validator().on('submit', function (e) {
                if (e.isDefaultPrevented()) {
                    // handle the invalid form...
                } else {
                    var data_form = $('#gasto').serialize();
                    console.log(data_form);

                    $.ajax({
                        type: "POST",
                        url: "/api/expenses",
                        data: data_form,
                        success: function(data) {
                            // console.log(data);
                            toastr.success('Su Gasto se guardo correctamente!');

                            setTimeout(function(){
                                window.location.href = '/frontend/gastos';
                                return false;
                            },20);

                        }
                    });
                }

                return false;
            });

        });

    </script>


@stop


@section('header')
    <!-- Content Header (Page header)

    'expense_type_id', 'user_id','division_id','application_date','code','name',
                            'description','approval_a','approval_b','approval_c','approval_d','approval_e',
                            'total_amount','active'

    -->
    <section class="content-header">
        <h1>
            Nuevo Gasto
            <small>Detalle de gasto</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ url('/frontend/home') }}"><i class="fa fa-dashboard"></i> Inicio</a></li>
            <li><a href="{{ url('/frontend/gastos') }}"><i class="fa fa-medkit"></i> Gastos</a></li>
            <li class="active"><i class="fa fa-calendar"></i> Nuevo Gasto</li>
        </ol>
    </section>
@stop

@section('content')
    <!-- Main content -->
    <section class="content">

        <!-- top row -->
        <div class="row">
            <div class="btn-group pull-right" style="padding-bottom: 5px">
<!--
                <a type="button" class="btn btn-primary btn-flat" href="#" style="margin-right: 5px;">Visitar </a>
                <a type="button" class="btn btn-primary btn-flat" href="{{ url('/frontend/ausencia') }}" style="margin-right: 5px;">Ausencia</a>
                <a type="button" class="btn btn-primary btn-flat" href="#{{ url('/frontend/rutas') }}" style="margin-right: 5px;">Ruta</a>
-->
            </div>

        </div>
        <!-- /.row -->

        <!-- Main row -->
        <div class="row">
            <!-- Left col -->
            <section class="col-lg-6 connectedSortable">
                <!-- Box (with bar chart) -->
                <div class="box box-info" id="loading-example">
                    <div class="box-header">

                        <!-- tools box -->
                        <div class="pull-right box-tools">

                        </div>
                        <!-- /. tools -->
                        <i class="fa fa-user-md"></i>

                        <h3 class="box-title">Nuevo Gasto</h3>
                    </div>


                    <form id="gasto" action="#" data-toggle="validator">
                    <div class="box-body">

                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" id="user_id" name="user_id" value="{{ $user->id }}"/>

                        <div class="form-group">
                            <label for="cycle_id">Ciclo</label>
                            <select id="cycle_id" name="cycle_id" class="form-control center">
                                @foreach ($cycles as $cycle)
                                    <option value="{{ $cycle->id }}">{{ $cycle->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="expense_type_id">Tipo Gasto</label>
                            <select id="expense_type_id" name="expense_type_id" class="form-control center">
                                @foreach ($expense_types as $expense_type)
                                    <option value="{{ $expense_type->id }}">{{ $expense_type->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="expense_type_id">División</label>
                            <select id="expense_type_id" name="division_id" class="form-control center">
                                @foreach ($user->divisions as $division)
                                    <option value="{{ $division->id }}">{{ $division->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group required">
                            <label for="application_date">Fecha</label>
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text" class="form-control datepicker" name="application_date" id="application_date"
                                       required data-error="fecha requerida." />
                            </div>
                            <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group">
                            <label for="name">Nombre</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Nombre Gasto" required=""
                                   data-error="Nombre requerido."/>
                            <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group">
                            <label for="description">Descripción</label>
                            <input type="text" class="form-control" id="description" name="description"
                                   placeholder="Descripción del Gasto"  data-error="Descripción Requerida."/>
                            <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group">
                            <label for="estimated_amount">Monto Estimado S/.</label>
                            <div class="input-group">
                                <span class="input-group-addon">S/.</span>
                                <input type="text" class="form-control" name="estimated_amount" id="estimated_amount"
                                       required
                                       placeholder="Monto estimado"
                                       data-error="Monto estimado Requerido."
                                        />
                                <span class="input-group-addon">.00</span>
                            </div>
                        </div>

                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">

                            <button type="button" id="cancelar" class="btn btn-default">Cancelar</button>
                            <button type="submit" id="grabar" class="btn btn-info pull-right">Guardar</button>

                        <!-- /.row -->
                    </div>
                    <!-- /.box-footer -->
                </div>
                </form>
                <!-- /.box -->


            </section>
            <!-- /.Left col -->

            <!-- right col (We are only adding the ID to make the widgets sortable)-->
            <section class="col-lg-6 connectedSortable">

                <!-- TO DO List -->
                <div class="box box-primary">
                    <div class="box-header">
                        <i class="ion ion-clipboard"></i>
                        <h3 class="box-title">Indicaciones</h3>
                        <div class="box-tools pull-right">


                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <ul>
                            <li>Cada gasto va al centro de costo de una división.</li>
                            <li>Elije correctamente el tipo de gasto, usa la siguiente tabla.</li>
                            <li>El monto se calcula en base al detalle.</li>
                        </ul>
                        <br>

                        <h4 class="box-title">Tabla detallada de tipos de gastos</h4>
                        <table class="table table-bordered table-striped center-table">
                            <tr>
                                <td>Tipo de Gasto</td>
                                <td>Cuenta Contable</td>
                                <td>Descripción Contable</span></td>
                                <td>Formato</td>
                            </tr>
                            <tr>
                                <td>Materiales promocionales
                                    impresos</td>
                                <td>6371010</td>
                                <td>Material promocional - impreso</td>
                                <td>Formato Nro 11</td>
                            </tr>
                            <tr>
                                <td>Suministro de
                                    recordatorios de la marca</td>
                                <td>6371010</td>
                                <td>Material promocional - impreso</td>
                                <td>Formato Nro 12</td>
                            </tr>
                            <tr>
                                <td>Artículos de utilidad
                                    médica</td>
                                <td>6371010</td>
                                <td>Material promocional - impreso</td>
                                <td>Formato Nro 13</td>
                            </tr>
                            <tr>
                                <td>Cortesías culturales</td>
                                <td>6371010</td>
                                <td>Material promocional - impreso</td>
                                <td>Formato Nro 14</td>
                            </tr>
                            <tr>
                                <td>Comidas y refrigerios</td>
                                <td>6371020</td>
                                <td>Gastos de Promoción</td>
                                <td>Formato Nro 15</td>
                            </tr>
                            <tr>
                                <td>Entretenimiento</td>
                                <td>6371020</td>
                                <td>Gastos de Promoción</td>
                                <td>Formato Nro 16</td>
                            </tr>
                            <tr>
                                <td>Recorridos de
                                    planta y visitas al sitio</td>
                                <td>6371020</td>
                                <td>Gastos de Promoción</td>
                                <td>Formato Nro 17</td>
                            </tr>
                            <tr>
                                <td>Interacción con
                                    pacientes y organizaciones de pacientes</td>
                                <td>6371020</td>
                                <td>Gastos de Promoción</td>
                                <td>Formato Nro 16</td>
                            </tr>
                            <tr>
                                <td>Campañas Medicas</td>
                                <td>6371020</td>
                                <td>Gastos de Promoción</td>
                                <td>Formato Nro 17</td>
                            </tr>
                            <tr>
                                <td>Viajes y alojamientos
                                    razonables</td>
                                <td>6371026</td>
                                <td>Capacitación médica</td>
                                <td>Formato Nro 4</td>
                            </tr>
                            <tr>
                                <td>Acuerdos de
                                    servicios profesionales</td>
                                <td>6371026</td>
                                <td>Capacitación médica</td>
                                <td>Formato Nro 6, Formato Nro 9</td>
                            </tr>
                            <tr>
                                <td>Patrocinios: Apoyo
                                    de asistencia a conferencias educativas</td>
                                <td>6371026</td>
                                <td>Capacitación médica</td>
                                <td>Formato Nro 4, Formato Nro 3</td>
                            </tr>
                            <tr>
                                <td>Subvenciones
                                    educativas o científicas</td>
                                <td>6371026</td>
                                <td>Capacitación médica</td>
                                <td>Formato Nro 2</td>
                            </tr>
                            <tr>
                                <td>Reuniones de
                                    capacitación y educativas sobre productos organizadas por Abbott</td>
                                <td>6371026</td>
                                <td>Capacitación médica</td>
                                <td>Formato Nro 9</td>
                            </tr>
                            <tr>
                                <td>Contribuciones
                                    caritativas</td>
                                <td>6371020</td>
                                <td>Gastos de Promoción</td>
                                <td>Formato Nro 17</td>
                            </tr>
                            <tr>
                                <td>Compra de Electrodomesticos</td>
                                <td>6373008</td>
                                <td>Atenciones a clientes y/o diversos</td>
                                <td>Formato Nro 18</td>
                            </tr>
                            <tr>
                                <td>Compra de Vales</td>
                                <td>6373008</td>
                                <td>Atenciones a clientes y/o diversos</td>
                                <td>Formato Nro 19</td>
                            </tr>
                        </table>


                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer clearfix no-border">
                                           </div>
                </div>
                <!-- /.box -->


    </section>


            </div>

    </section>
    <!-- /.content -->
@endsection