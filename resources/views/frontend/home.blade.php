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
    <script src="/plugins/chartjs/Chart.js" type="text/javascript"></script>
    <script src="/plugins/chartjs/legend.js" type="text/javascript"></script>

    <script type="text/javascript" class="init">

        $(document).ready(function() {
            //moment.locale('es-PE');

            var data = {
                labels: ["ENE", "FEB", "MAR", "ABR", "MAY", "JUN", "JUL","AGO","SEP","OCT","NOV","DIC"],
                datasets: [
                    {
                        label: "Presupuesto",
                        fillColor: "rgba(220,220,220,0.2)",
                        strokeColor: "rgba(220,220,220,1)",
                        pointColor: "rgba(220,220,220,1)",
                        pointStrokeColor: "#fff",
                        pointHighlightFill: "#fff",
                        pointHighlightStroke: "rgba(220,220,220,1)",
                        data: [
                                @foreach($budget_reports as $item)
                                {{ ($item->monto == null)?0:$item->monto  }},
                                @endforeach

                        ]
                    },
                    {
                        label: "Gastos",
                        fillColor: "rgba(151,187,205,0.2)",
                        strokeColor: "rgba(151,187,205,1)",
                        pointColor: "rgba(151,187,205,1)",
                        pointStrokeColor: "#fff",
                        pointHighlightFill: "#fff",
                        pointHighlightStroke: "rgba(151,187,205,1)",
                        data: [
                            @foreach($budget_reports as $item)
                               {{ ($item->planificado == null)?0:$item->planificado  }},
                            @endforeach
                    ]
                    }
                ]
            };

            var context = document.getElementById('budget').getContext('2d');
            var budgetChart = new Chart(context).Bar(data);

            legend(document.getElementById('legendDiv'), budgetChart);

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
                            'S/.'+pageTotal.toFixed(2)
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

            $('#filter_division').change( function() {
                table.fnFilter(this.value,0);
            });

            $('#filter_book_account').change( function() {
                table.fnFilter(this.value,1);
            });

            $('#filter_cycle').change( function() {
                table.fnFilter(this.value,3);
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
                                <label>Cuenta</label>
                                <select class="form-control filter_grid" id="filter_book_account">
                                    <option value="">Todos</option>
                                    @foreach($book_accounts as $book_account)
                                        <option value="{{ $book_account->code }}">{{ $book_account->code.' - '.$book_account->name }}</option>
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
                                        <option value="{{ $cycle->code }}">{{ $cycle->code }}</option>
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
                    <h3 class="box-title"><i class="fa fa-medkit"></i> Presupuesto</h3>
                </div>
                <div class="box-body table-responsive">
                    <table id="example" class="display table table-bordered table-striped center-table">
                        <thead>
                        <tr>
                            <th>División</th>
                            <th>Codigo</th>
                            <th>Cuenta</th>
                            <th>Ciclo</th>
                            <th>Usuario</th>
                            <th>Presupuesto</th>
                            <th>Gastos</th>
                        </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th colspan="5"></th>
                                <th style="text-align:right">Total:</th>
                                <th></th>
                            </tr>
                        </tfoot>
                        <tbody>
                        @foreach($reports as $report)
                            <tr>
                                <td>{{ $report->division }}</td>
                                <td>{{ $report->cod_cuenta }}</td>
                                <td>{{ $report->cuenta }}</td>
                                <td>{{ $report->ciclo }}</td>
                                <td>{{ $report->usuario }}</td>
                                <td>{{ $report->monto }}</td>
                                <td>{{ $report->planificado }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-bar-chart-o"></i> Presupuesto</h3>
                </div>
                <div class="box-body table-responsive">
                    <div id="legendDiv"></div>
                    <canvas id="budget" width="1100" height="400"></canvas>
                </div>

            </div>
        </div>
    </div>

@endsection
