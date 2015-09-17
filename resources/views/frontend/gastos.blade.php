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
                    'url' : '/api/expenses?user_id='+user_id+'&cycle_id='+cycle_id,
                    'dataSrc': '',
                    'type' : 'get'
                },
                'columns': [
                    { 'data' : 'cycle.code' },
                    { 'data' : 'expense_type.description' },
                    { 'data' : 'code' },
                    { 'data' : 'division.code' },
                    { 'data': 'application_date', "class": "text-center ",
                        'mRender' : function(data,type,full){
                            return moment(full.application_date,'YYYY-MM-DD HH:mm:ss').format("DD/MM/YYYY");
                        } },
                    { 'data': 'name',
                        'mRender' : function(data,type,full){
                            return "<a href='/frontend/detalle/"+full.id+"'>"+data+"</a>";
                        }
                    },
                    { 'data': 'user.name',
                        'mRender' : function(data,type,full){
                            return getWords(full.user.name);
                        }
                    },
                    { 'data': 'total_amount',
                        'mRender' : function(data,type,full){

                            var intVal = function ( i ) {
                                return typeof i === 'string' ?
                                i.replace(/[\$,]/g, '')*1 :
                                        typeof i === 'number' ?
                                                i : 0;
                            };
                            return intVal(full.total_amount).toLocaleString();

                        }
                    },
                    { 'data': 'approval_1',
                        'mRender' : function(data,type,full){
                            return ((full.approval_1) == 1)?"<i class='fa fa-fw fa-check-circle-o'></i>":"<i class='fa fa-fw fa-circle-o'></i></i>";
                        }
                    },
                    { 'data': 'approval_2' ,
                        'mRender' : function(data,type,full){
                            return ((full.approval_2) == 1)?"<i class='fa fa-fw fa-check-circle-o'></i>":"<i class='fa fa-fw fa-circle-o'></i></i>";
                        }
                    },
                    { 'data': 'approval_3' ,
                        'mRender' : function(data,type,full){
                            return ((full.approval_3) == 1)?"<i class='fa fa-fw fa-check-circle-o'></i>":"<i class='fa fa-fw fa-circle-o'></i></i>";
                        }
                    },
                    { 'data': 'approval_4' ,
                        'mRender' : function(data,type,full){
                            return ((full.approval_4) == 1)?"<i class='fa fa-fw fa-check-circle-o'></i>":"<i class='fa fa-fw fa-circle-o'></i></i>";
                        }
                    }

                ],
                "iDisplayLength": 20,
                'order': [[2, 'asc']]
            });

            // table.fnFilter("Visitado",8);

            $('#filter_division').change( function() {
                table.fnFilter(this.value,3);
            });

            $('#filter_expense_type').change( function() {
                table.fnFilter(this.value,1);
            });

            $('#filter_cycle').change( function() {
                window.location.href = '/frontend/gastos?cycle_code='+this.value;
            });

            $('#example tbody').on( 'click', 'tr', function () {
                $(this).toggleClass('selected');
            } );

            $('#aprobar').click( function () {
                $( ".selected" ).each(function( index ) {
                    console.log($( this ).children().eq(2).text() );

                    $.ajax({
                        type: "GET",
                        url: "/frontend/aprobar/"+$( this ).children().eq(2).text(),
                        success: function(data) {
                            console.log(data);
                            if(data == 'ok'){
                                toastr.success('Su Gasto '+$( this ).children().eq(2).text()+'se aprobo correctamente!');
                            }else{
                                toastr.success('Su Gasto '+$( this ).children().eq(2).text()+'no se aprobo porque no tiene V.V. anterior!');
                            }
                        }
                    });
                });

                setTimeout(function(){
                    window.location.href = '/frontend/gastos';
                    return false;
                },3000);

            } );

            $('#desaprobar').click( function () {
                $( ".selected" ).each(function( index ) {
                    console.log($( this ).children().eq(2).text() );

                    $.ajax({
                        type: "GET",
                        url: "/frontend/desaprobar/"+$( this ).children().eq(2).text(),
                        success: function(data) {
                             console.log(data);
                            if(data == 'ok'){
                                toastr.success ('Su Gasto '+$( this ).children().eq(2).text()+'se desaprobo correctamente!');
                            }else{
                                toastr.error('Su Gasto '+$( this ).children().eq(2).text()+'no se desaprobo porque ya tiene V.V. posterior!');
                            }

                        }
                    });
                });

                setTimeout(function(){
                    window.location.href = '/frontend/gastos';
                    return false;
                },3000);

            } );
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
            <li><a href="{{ url('/frontend/home') }}"><i class="fa fa-dashboard"></i> Inicio</a></li>
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
                                <select class="form-control filter_grid" id="filter_division">
                                    <option value="">Todos</option>
                                    @foreach($user->divisions as $division)
                                        <option value="{{ $division->code }}">{{ $division->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label>Tipo de Gastos</label>
                                <select class="form-control filter_grid" id="filter_expense_type">
                                    <option value="">Todos</option>
                                    @foreach($expense_types as $expense_type)
                                        <option value="{{ $expense_type->description }}">{{ $expense_type->name }}</option>
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

            <!-- Split button -->
            <div class="box-tools pull-right">
                <div class="box-body">
                    @if(in_array($user->role_id, array(1,2,3)))
                    <button id="nuevo_gasto" class="btn btn-info">Nuevo Gasto</button>
                    @endif
                    <button id="aprobar" class="btn btn-primary">Aprobar</button>
                        <button id="desaprobar" class="btn btn-primary">Desaprobar</button>
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
                            <th>Ciclo</th>
                            <th>Tipo</th>
                            <th>Código</th>
                            <th>Divisón</th>
                            <th>Fecha</th>
                            <th>Motivo</th>
                            <th>Usuario</th>
                            <th>Monto(S/.)</th>
                            <th>SOL</th>
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