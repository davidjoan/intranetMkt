@extends('frontend.app')

@section('includes.css')
    @parent
    <link href="/plugins/datatables/dataTables.bootstrap.css" rel="stylesheet" type="text/css" />
    <link href="/plugins/datatables/extensions/TableTools/css/dataTables.tableTools.css" rel="stylesheet" type="text/css" />
@stop

@section('includes.js')
    @parent

    <script src="/plugins/moment/moment.js" type="text/javascript"></script>
    <script src="/plugins/datatables/jquery.dataTables.js" type="text/javascript"></script>
    <script src="/plugins/datatables/extensions/TableTools/js/dataTables.tableTools.js" type="text/javascript"></script>
    <script src="/plugins/datatables/dataTables.bootstrap.js" type="text/javascript"></script>
    <script type="text/javascript" class="init">

        var user_id = {{ $user->id }};
        var role_id = {{ $user->role_id }};
        var cycle_id = {{ $cycle_actual->id }};

        $(document).ready(function() {
            //moment.locale('es-PE');



            var table = $('#example').dataTable({
                'processing': true,
                'serverSide': false,
                'language': {
                    'url': '/json/i18n/Spanish.json'
                },
                'ajax': {
                    'url' : '/api/budgets?cycle_id='+cycle_id,
                    'dataSrc': '',
                    'type' : 'get'
                },
                'columns': [
                    { 'data' : 'cycle.code' },
                    { 'data' : 'division.name' },
                    { 'data' : 'cost_center.name' },
                    { 'data' : 'book_account.name' },
                    { 'data' : 'user.name' },
                    { 'data' : 'amount' },

                ],
                "iDisplayLength": 20,
                'order': [[2, 'asc']]
            });

            // table.fnFilter("Visitado",8);

            $('#filter_division').change( function() {
                table.fnFilter(this.value,1);
            });


            $('#filter_cycle').change( function() {
                window.location.href = '/frontend/presupuestos?cycle_code='+this.value;
            });
        });
    </script>
@stop

@section('header')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Presupuesto
            <small>Resumen completo del presupuesto por división,centro de costo Y ciclo promocional.</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ url('/frontend/home') }}"><i class="fa fa-dashboard"></i> Inicio</a></li>
            <li class="active">
                <i class="fa fa-money"></i> Presupuesto
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
                <strong>Bienvenido a Intranet MKT?</strong>
                Esta es una version de prueba, sientete libre de realizar los cambios que sean necesarios.
            </div>
        </div>
    </div>
    <!-- /.row -->
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title">Filtros</h3>
                    <div class="box-tools pull-right">
                        <button class="btn bg-teal btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    </div>
                </div><!-- /.box-header -->
                <div class="box-body">
                    <div class="row">
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label>División</label>
                                <select class="form-control filter_grid" id="filter_division">
                                    <option value="">Todos</option>
                                    @foreach($divisions as $division)
                                        <option value="{{ $division->code }}">{{ $division->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label>Ciclos</label>
                                <select class="form-control filter_grid" id="filter_cycle">
                                    <option value="">Todos</option>
                                    @foreach($cycles as $cycle)
                                        <option value="{{ $cycle->code }}"
                                                @if ($cycle->code ==$cycle_code)
                                                selected
                                        @else
                                                @endif
                                                >{{ $cycle->code }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-money"></i> Lista de Presupuesto</h3>
                </div>
                <div class="box-body table-responsive">
                    <table id="example" class="display table table-bordered table-striped center-table">
                        <thead>
                        <tr>
                            <th>Ciclo</th>
                            <th>Divisón</th>
                            <th>Centro de Costo</th>
                            <th>Cuenta</th>
                            <th>Responsable</th>
                            <th>Monto</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div>
    </div>
@endsection