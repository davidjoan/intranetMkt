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

        $(document).ready(function() {
            //moment.locale('es-PE');

            var table = $('#example').dataTable({
                'footerCallback': function ( row, data, start, end, display ) {
                    var api = this.api(), data;
                    console.log(api);

                    // Remove the formatting to get integer data for summation
                    var intVal = function ( i ) {
                        return typeof i === 'string' ?
                        i.replace(/[\$,]/g, '')*1 :
                                typeof i === 'number' ?
                                        i : 0;
                    };

                    // Total over all pages
                    total = api
                            .column( 5 )
                            .data()
                            .reduce( function (a, b) {
                                return intVal(a) + intVal(b);
                            } );

                    // Total over this page
                    pageTotal = api
                            .column( 5, { page: 'current'} )
                            .data()
                            .reduce( function (a, b) {
                                return intVal(a) + intVal(b);
                            }, 0 );

                    // Update footer
                    $( api.column( 5 ).footer() ).html(
                            'S/.'+pageTotal
                    );
                },
                'processing': false,
                'serverSide': false,
                'language': {
                    'url': '/json/i18n/Spanish.json'
                },
                "iDisplayLength": 20,
                'order': [[2, 'asc']]
            });

            // table.fnFilter("Visitado",8);

            $('#filter_category').change( function() {
                table.fnFilter(this.value,4);
            });

            $('#filter_expense_type').change( function() {
                table.fnFilter(this.value,0);
            });

            $('#example tbody').on( 'click', 'tr', function () {
                $(this).toggleClass('selected');
            } );

            $('#aprobar').click( function () {
                $( ".selected" ).each(function( index ) {
                    console.log($( this ).children().eq(1).text() );

                    $.ajax({
                        type: "GET",
                        url: "/frontend/aprobar/"+$( this ).children().eq(1).text(),
                        success: function(data) {
                            console.log(data);
                            if(data == 'ok'){
                                toastr.success('Su Gasto '+$( this ).children().eq(1).text()+'se aprobo correctamente!');
                            }else{
                                toastr.success('Su Gasto '+$( this ).children().eq(1).text()+'no se aprobo porque no tiene V.V. anterior!');

                            }


                        }
                    });
                });

                setTimeout(function(){
                    window.location.href = '/frontend/home';
                    return false;
                },3000);

            } );

            $('#desaprobar').click( function () {
                $( ".selected" ).each(function( index ) {
                    console.log($( this ).children().eq(1).text() );

                    $.ajax({
                        type: "GET",
                        url: "/frontend/desaprobar/"+$( this ).children().eq(1).text(),
                        success: function(data) {
                            console.log(data);
                            if(data == 'ok'){
                                toastr.success ('Su Gasto '+$( this ).children().eq(1).text()+'se desaprobo correctamente!');
                            }else{
                                toastr.error('Su Gasto '+$( this ).children().eq(1).text()+'no se desaprobo porque ya tiene V.V. posterior!');

                            }

                        }
                    });
                });

                setTimeout(function(){
                    window.location.href = '/frontend/home';
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
            Dashboard
            <small></small>
        </h1>
        <ol class="breadcrumb">
            <li class="active">
                <i class="fa fa-dashboard"></i> Inicio
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

    <!-- Small boxes (Stat box) -->
    <div class="row">
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-green">
                <div class="inner">
                    <h3><sup style="font-size: 20px">S/.</sup> 12,000</h3>
                    <p>Presupuesto</p>
                </div>
                <div class="icon">
                    <i class="ion ion-stats-bars"></i>
                </div>
                <a href="{{ url('frontend/visitas') }}" class="small-box-footer">Mas Info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div><!-- ./col -->
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-aqua">
                <div class="inner">
                    <h3><sup style="font-size: 20px">S/.</sup> {{ $total_amount }} </h3>
                    <p>Gastos</p>
                </div>
                <div class="icon">
                    <i class="ion ion-bag"></i>
                </div>
                <a href="{{ url('frontend/visitas') }}" class="small-box-footer">Mas Info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div><!-- ./col -->
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-yellow">
                <div class="inner">
                    <h3>{{ $total_expenses }}</h3>
                    <p># Gastos</p>
                </div>
                <div class="icon">
                    <i class="ion ion-person-add"></i>
                </div>
                <a href="{{ url('frontend/target') }}" class="small-box-footer">Mas Info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div><!-- ./col -->
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-red">
                <div class="inner">
                    <h3>10</h3>
                    <p>Pendientes</p>
                </div>
                <div class="icon">
                    <i class="ion ion-pie-graph"></i>
                </div>
                <a href="{{ url('frontend/visitas') }}" class="small-box-footer">Mas Info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div><!-- ./col -->
    </div><!-- /.row -->


    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-medkit"></i> Resumen de Gastos</h3>
                </div>
                <div class="box-body table-responsive">
                    <table id="example" class="display table table-bordered table-striped center-table">
                        <thead>
                        <tr>
                            <th>Mes</th>
                            <th>Rol</th>
                            <th>Usuario</th>
                            <th>Cuenta</th>
                            <th>Tipo</th>
                            <th>Monto</th>
                            <th>Presupuesto</th>
                            <th>Diferencia</th>
                        </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th colspan="5"></th>
                                <th style="text-align:right">Total:</th>
                                <th colspan="2"></th>
                            </tr>
                        </tfoot>
                        <tbody>
                        @foreach($reports as $report)
                            <tr>
                                <td>{{ date('F', mktime(0, 0, 0, $report->mes, 10)) }}</td>
                                <td>{{ $report->rol }}</td>
                                <td>{{ $report->usuario }}</td>
                                <td>{{ $report->cuenta }}</td>
                                <td>{{ $report->tipo }}</td>
                                <td>{{ $report->estimado }}</td>
                                <td>{{ $report->presupuesto }}</td>
                                <td>{{ $report->diferencia }}</td>

                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div>
    </div>

@endsection
