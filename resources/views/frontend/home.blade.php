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

    <?php setlocale(LC_MONETARY, 'es_PE'); ?>

    <script type="text/javascript" class="init">

        $(document).ready(function() {

            @if(!($user->role_id == 1 || $user->role_id == 3))

            var data = {
                labels: ["ENE", "FEB", "MAR", "ABR", "MAY", "JUN", "JUL","AGO","SEP","OCT","NOV","DIC"],
                datasets: [
                    {
                        label: "Presupuesto",
                        fillColor: "rgba(243,156,18,0.2)",
                        strokeColor: "rgba(220,220,18,1)",
                        pointColor: "rgba(220,220,18,1)",
                        pointStrokeColor: "#fff",
                        pointHighlightFill: "#fff",
                        pointHighlightStroke: "rgba(220,220,220,1)",
                        data: [
                                @foreach($budget_reports as $item)
                                {{ ($item->presupuesto == null)?0:$item->presupuesto  }},
                                @endforeach

                        ]
                    },
                    {
                        label: "Gastos",
                        fillColor: "rgba(0,187,90,0.2)",
                        strokeColor: "rgba(0,187,90,1)",
                        pointColor: "rgba(0,187,90,1)",
                        pointStrokeColor: "#fff",
                        pointHighlightFill: "#fff",
                        pointHighlightStroke: "rgba(151,187,205,1)",
                        data: [
                            @foreach($budget_reports as $item)
                               {{ ($item->gastado == null)?0:$item->gastado  }},
                            @endforeach
                    ]
                    }
                ]
            };

            var context = document.getElementById('budget').getContext('2d');
            var budgetChart = new Chart(context).Bar(data);

            @endif

            legend(document.getElementById('legendDiv'), budgetChart);


            var table_cost_center = $('#table_cost_center').dataTable({
                'createdRow': function ( row, data, index ) {

                    var gasto = data[3].replace(',', '');
                    var presupuesto = data[2].replace(',', '');


                    if ( parseFloat(gasto) > parseFloat(presupuesto)) {

                        $('td', row).eq(2).addClass('highlight_error');
                    }else{
                        $('td', row).eq(2).addClass('highlight');
                    }
                },
                'columnDefs': [
                    { "visible": false, "targets": 1 }
                ],
                'order': [[ 1, 'asc' ]],
                'displayLength': 25,
                "drawCallback": function ( settings ) {
                    var api = this.api();
                    var rows = api.rows( {page:'current'} ).nodes();
                    var last=null;

                    api.column(1, {page:'current'} ).data().each( function ( group, i ) {
                        if ( last !== group ) {
                            $(rows).eq( i ).before(
                                    '<tr class="group"><td colspan="4"><b>'+group+'</b></td></tr>'
                            );

                            last = group;
                        }
                    } );
                },
                'bInfo': false,
                'processing': false,
                'serverSide': false,
                'language': {
                    'url': '/json/i18n/Spanish.json'
                },
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
                            .column( 3 )
                            .data()
                            .reduce( function (a, b) {
                                return intVal(a) + intVal(b);
                            } );

                    // Total over this page
                    pageTotal = api
                            .column( 2, { page: 'current'} )
                            .data()
                            .reduce( function (a, b) {
                                return intVal(a) + intVal(b);
                            }, 0 );

                    pageTotal2 = api
                            .column( 3, { page: 'current'} )
                            .data()
                            .reduce( function (a, b) {
                                return intVal(a) + intVal(b);
                            }, 0 );

                    // Update footer
                    $( api.column( 2 ).footer() ).html(
                            pageTotal.toLocaleString()
                    );
                    $( api.column( 3 ).footer() ).html(
                            pageTotal2.toLocaleString()
                    );
                }
            });

            var table_cost_center_total = $('#table_cost_center_total').dataTable({
                'createdRow': function ( row, data, index ) {

                    var gasto = data[4].replace(',', '');
                    var presupuesto = data[3].replace(',', '');


                    if ( parseFloat(gasto) > parseFloat(presupuesto)) {

                        $('td', row).eq(4).addClass('highlight_error');
                    }else{
                        $('td', row).eq(4).addClass('highlight');
                    }
                },
                'bInfo': false,
                'bPaginate' : false,
                'order': [[ 1, 'asc' ]],
                'displayLength': 10,
                'language': {
                    'url': '/json/i18n/Spanish.json'
                },
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
                            .column( 4 )
                            .data()
                            .reduce( function (a, b) {
                                return intVal(a) + intVal(b);
                            } );

                    // Total over this page
                    pageTotal = api
                            .column( 3, { page: 'current'} )
                            .data()
                            .reduce( function (a, b) {
                                return intVal(a) + intVal(b);
                            }, 0 );

                    pageTotal2 = api
                            .column( 4, { page: 'current'} )
                            .data()
                            .reduce( function (a, b) {
                                return intVal(a) + intVal(b);
                            }, 0 );

                    // Update footer
                    $( api.column( 3 ).footer() ).html(
                            pageTotal.toLocaleString()
                    );
                    $( api.column( 4 ).footer() ).html(
                            pageTotal2.toLocaleString()
                    );
                }
            });

            // Order by the grouping
            $('#table_cost_center tbody').on( 'click', 'tr.group', function () {
                table_cost_center.fnFilter(this.value,2);

            } );

            var table = $('#example').dataTable({
                'createdRow': function ( row, data, index ) {

                    var gasto = data[3].replace(',', '');
                    var presupuesto = data[2].replace(',', '');


                    if ( parseFloat(gasto) > parseFloat(presupuesto)) {
                        $('td', row).eq(3).addClass('highlight_error');
                    }else{
                        $('td', row).eq(3).addClass('highlight');
                    }
                },
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
                            .column( 3 )
                            .data()
                            .reduce( function (a, b) {
                                return intVal(a) + intVal(b);
                            } );

                    // Total over this page
                    pageTotal = api
                            .column( 2, { page: 'current'} )
                            .data()
                            .reduce( function (a, b) {
                                return intVal(a) + intVal(b);
                            }, 0 );

                    pageTotal2 = api
                            .column( 3, { page: 'current'} )
                            .data()
                            .reduce( function (a, b) {
                                return intVal(a) + intVal(b);
                            }, 0 );

                    // Update footer
                    $( api.column( 2 ).footer() ).html(
                            pageTotal.toLocaleString()
                    );
                    $( api.column( 3 ).footer() ).html(
                            pageTotal2.toLocaleString()
                    );
                },
                'bInfo': false,
                'bPaginate' : false,
                'processing': false,
                'serverSide': false,
                'language': {
                    'url': '/json/i18n/Spanish.json'
                },
                "iDisplayLength": 20,
                'order': [[2, 'asc']]
            });

            var table_role = $('#example_role').dataTable({

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
                            .column( 2 )
                            .data()
                            .reduce( function (a, b) {
                                return intVal(a) + intVal(b);
                            } );

                    // Total over this page
                    pageTotal = api
                            .column( 2, { page: 'current'} )
                            .data()
                            .reduce( function (a, b) {
                                return intVal(a) + intVal(b);
                            }, 0 );



                    // Update footer
                    $( api.column( 2 ).footer() ).html(
                            pageTotal.toLocaleString()
                    );
                },
                'bInfo': false,
                'bPaginate' : false,
                'language': {
                    'url': '/json/i18n/Spanish.json'
                },
                'order': [[2, 'asc']]
            });

            $('#filter_division').change( function() {
                table.fnFilter(this.value,0);
                table_role.fnFilter(this.value,0);
                table_cost_center_total.fnFilter(this.value, 0);
                //table_cost_center.fnFilter(this.value, 0);
            });

            $('#filter_book_account').change( function() {
                table.fnFilter(this.value,1);
                //table_cost_center.fnFilter(this.value,1);
            });

            $('#filter_cycle').change( function() {
                window.location.href = '/frontend/home?cycle_code='+this.value;
            });

            @if($user->role_id == 4 || $user->role_id == 5 || $user->role_id == 6)

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

            @endif


        });
    </script>
    @stop


@section('header')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Tablero de Control
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
                                        <option value="{{ $division->name }}">{{ $division->name }}</option>
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
                                        <option value="{{ $book_account->name }}">{{ $book_account->code.' - '.$book_account->name }}</option>
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
        @if(!($user->role_id == 1 || $user->role_id == 3))
        <div class="col-xs-12">

            <!-- Split button -->
            <div class="box-tools pull-right">
                <div class="box-body">
                    <button id="aprobar" class="btn btn-primary"
                            data-toggle="tooltip" data-placement="left" data-original-title="Esta opción aprueba todos los gastos masivamente">Aprobar</button>
                    <button id="desaprobar" class="btn btn-primary">Desaprobar</button>
                </div>

            </div>
        </div>

        @endif

        </div>
    <div class="row">
        <div class="col-xs-6">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-money"></i> Presupuesto {{ $cycle_code }}
                    </h3>
                    <div class="pull-right box-tools">
                        <button class="btn btn-info btn-sm" data-widget="collapse" data-toggle="tooltip" title="" data-original-title="Colapsar"><i class="fa fa-minus"></i>
                        </button>
                    </div>

                </div>
                <div class="box-body table-responsive">
                    <table id="example" class="display table table-bordered table-striped center-table">
                        <thead>
                        <tr>
                            <th>División</th>
                            <th>Cuenta</th>
                            <th>Pres(S/.)</th>
                            <th>Gast(S/.)</th>
                        </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th colspan="2"></th>
                                <th></th>
                                <th></th>
                            </tr>
                        </tfoot>
                        <tbody>
                        @foreach($reports as $report)
                            <tr>
                                <td>{{ $report->division }}</td>
                                <td>{{ $report->cuenta }}</td>
                                <td>{{ number_format($report->presupuesto,2,".",",") }}</td>
                                <td>{{ number_format($report->gastado,2,".",",") }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div>

        <div class="col-xs-6">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-users"></i> Gastos Solicitados por Usuario</h3>

                    <div class="pull-right box-tools">
                        <button class="btn btn-info btn-sm" data-widget="collapse" data-toggle="tooltip" title="" data-original-title="Colapsar"><i class="fa fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="box-body table-responsive">
                    <table id="example_role" class="display table table-bordered table-striped center-table">
                        <thead>
                        <tr>
                            <th>División</th>
                            <th>Usuario</th>
                            <th>Sol (S/.)</th>
                        </tr>
                        </thead>
                        <tfoot>
                        <tr>
                            <th colspan="2"></th>
                            <th></th>
                        </tr>
                        </tfoot>
                        <tbody>
                        @foreach($report_role as $report)
                            <tr>
                                <td>{{ $report->division }}</td>
                                <td>{{ $report->rol }}</td>
                                <td>{{ number_format($report->gastado,2,".",",") }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div>
    </div>

    @if(!($user->role_id == 1 || $user->role_id == 3))

    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-bar-chart-o"></i> Presupuesto (Gráfico)</h3>

                    <div class="pull-right box-tools">
                        <button class="btn btn-info btn-sm" data-widget="collapse" data-toggle="tooltip" title="" data-original-title="Colapsar"><i class="fa fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="box-body table-responsive">
                    <div id="legendDiv"></div>
                    <canvas id="budget" width="1000" height="400"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
            <div class="col-xs-6">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-list"></i> Centro de Costos - Ciclo: {{ $cycle_code }}</h3>

                        <div class="pull-right box-tools">
                            <button class="btn btn-info btn-sm" data-widget="collapse" data-toggle="tooltip" title="" data-original-title="Colapsar"><i class="fa fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="box-body table-responsive">
                        <table id="table_cost_center_total" class="display table table-bordered table-striped center-table">
                            <thead>
                            <tr>
                                <th>División</th>
                                <th>Código</th>
                                <th>Centro Costo</th>
                                <th>Presup(S/.)</th>
                                <th>Gastos(S/.)</th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th colspan="3"></th>
                                <th></th>
                                <th></th>
                            </tr>
                            </tfoot>
                            <tbody>
                            @foreach($report_cost_center_total as $report)
                                <tr>
                                    <td>{{ $report->division }}</td>
                                    <td>{{ $report->cod_centro }}</td>
                                    <td>{{ $report->centro }}</td>
                                    <td>{{ number_format($report->presupuesto,2,".",",") }}</td>
                                    <td>{{ number_format($report->gastado,2,".",",") }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>

            <div class="col-xs-6">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-outdent"></i> Centro de Costos Detallado - Ciclo: {{ $cycle_code }}</h3>

                        <div class="pull-right box-tools">
                            <button class="btn btn-info btn-sm" data-widget="collapse" data-toggle="tooltip" title="" data-original-title="Colapsar"><i class="fa fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="box-body table-responsive">
                        <table id="table_cost_center" class="display table table-bordered table-striped center-table">
                            <thead>
                            <tr>
                                <th>Centro de Costo</th>
                                <th>Detalle</th>
                                <th>Presup(S/.)</th>
                                <th>Gastos(S/.)</th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th colspan="2"></th>
                                <th></th>
                                <th></th>
                            </tr>
                            </tfoot>
                            <tbody>
                            @foreach($report_cost_center as $report)
                                <tr>
                                    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; - {{ $report->cuenta }}</td>
                                    <td>{{ $report->cod_centro }} - {{ $report->centro }}</td>
                                    <td>{{ number_format($report->presupuesto,2,".",",") }}</td>
                                    <td>{{ number_format($report->gastado,2,".",",") }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>

    @endif

@endsection
