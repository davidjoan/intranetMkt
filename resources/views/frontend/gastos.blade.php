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

        $(document).ready(function() {
            moment.locale('es-PE');

            $('#nuevo_gasto').click(function() {
                window.location.href = '/frontend/nuevo_gasto';
                return false;
            });

            var table = $('#example').dataTable({
                'processing': true,
                'serverSide': false,
                'language': {
                    'url': '/json/i18n/Spanish.json'
                },
                'ajax': {
                    'url' : '/api/expenses?user_id='+user_id,
                    'dataSrc': '',
                    'type' : 'get'
                },
                'columns': [
                    { 'data' : 'expense_type.name' },
                    { 'data': 'application_date', "class": "text-center ",
                        'mRender' : function(data,type,full){
                            return moment(full.application_date,'YYYY-MM-DD HH:mm:ss').format("DD/MM/YYYY");
                        } },
                    { 'data': 'name',
                        'mRender' : function(data,type,full){
                            return "<a href='/frontend/detalle/"+full.id+"'>"+data+"</a>";
                        }
                    },
                    { 'data': 'user.name' },
                    { 'data': 'total_amount' },
                    { 'data': 'approval_1' },
                    { 'data': 'approval_2' },
                    { 'data': 'approval_3' },
                ],
                "iDisplayLength": 20,
                'order': [[2, 'asc']]
            });

            // table.fnFilter("Visitado",8);

            $('#filter_category').change( function() {
                table.fnFilter(this.value,4);
            });

            $('#filter_place').change( function() {
                table.fnFilter(this.value,5);
            });

            $('#filter_status').change( function() {
                table.fnFilter(this.value,9);
            });
            //  console.log('finish process');
        });

    </script>
@stop

@section('header')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Gastos
            <small>Lista completa de gastos por división.</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ url('/') }}"><i class="fa fa-dashboard"></i> Inicio</a></li>
            <li class="active">
                <i class="fa fa-medkit"></i> Gastos
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
                                <select class="form-control filter_grid" id="filter_category">
                                    <option value="">todos</option>
                                    <option value="V">VIP</option>
                                    <option value="A">A</option>
                                    <option value="B">B</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label>Centro de Costo</label>
                                <select class="form-control filter_grid" id="filter_place">
                                    <option value="">todos</option>
                                    <option value="AM">AM</option>
                                    <option value="CO">CO</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label>Estado</label>
                                <select class="form-control filter_grid" id="filter_status">
                                    <option value="2">Aprobado GD</option>
                                    <option value="1">Aprobado CG</option>
                                    <option value="3">aPROBADO GG</option>
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

            <!-- Split button -->
            <div class="box-tools pull-right">
                <div class="box-body">
            <button id="nuevo_gasto" class="btn btn-primary">Nuevo Gasto</button>
            </div>

        </div>
</div>

    </div>
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-medkit"></i> Lista de Gastos</h3>
                </div>
                <div class="box-body table-responsive">
                    <table id="example" class="display table table-bordered table-striped center-table">
                        <thead>
                        <tr>
                            <th>Tipo</th>
                            <th>Fecha</th>
                            <th>Motivo</th>
                            <th>Usuario</th>
                            <th>Monto</th>
                            <th>VV1</th>
                            <th>VV2</th>
                            <th>VV3</th>
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