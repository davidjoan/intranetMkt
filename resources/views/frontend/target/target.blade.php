@extends('frontend.app')

@section('includes.css')
    @parent
    <link href="/plugins/datatables/dataTables.bootstrap.css" rel="stylesheet" type="text/css" />
    <link href="/plugins/datatables/extensions/TableTools/css/dataTables.tableTools.css" rel="stylesheet" type="text/css" />
@stop

@section('includes.js')
    @parent

    <script src="/plugins/moment/moment.min.js" type="text/javascript"></script>
    <script src="/plugins/datatables/jquery.dataTables.js" type="text/javascript"></script>
    <script src="/plugins/datatables/extensions/TableTools/js/dataTables.tableTools.js" type="text/javascript"></script>
    <script src="/plugins/datatables/dataTables.bootstrap.js" type="text/javascript"></script>
    <script type="text/javascript" class="init">

        $(document).ready(function() {

            var table = $('#example').dataTable({
                'processing': false,
                'serverSide': false,
                'ajax': {
                    'url' : '/api/targets?zone_id={{ $zone->id }}&campaign_id={{ $campaign->id }}&user_id={{ $user->id }}',
                    'dataSrc': '',
                    'type' : 'get'
                },
                'language': {
                    'url': '/json/i18n/Spanish.json'
                },
                'columns': [
                    { 'data': 'client.photo',
                        'mRender' : function(data,type,full){
                            return "<img src='/frontend/image/client/"+full.client.code+"'  class='img-circle' style='width: 80px; height: 90px;' />";
                        }
                    },
                    { 'data': 'client.code',
                        'mRender' : function(data,type,full){
                            return ('0000'+full.client.code).slice(-5);
                        }
                    },
                    { 'data': 'client.closeup_name',
                        'mRender' : function(data,type,full){
                            return "<a href='/frontend/target/"+full.id+"'>"+data+"</a>";
                        }
                    },
                    { 'data': 'client.address' },
                    { 'data': 'client.location.name',
                        'mRender' : function(data,type,full){ return (data == null)?' ':data } },
                    { 'data': 'client.category.code', "class": "text-center " },
                    { 'data': 'client.place.code', "class": "text-center " }
                   /* { 'data': 'id', "class": "text-center ",
                        'mRender' : function(data,type,full){
                            //TODO: fix the color
                            //console.log(full);
                            var color = '';

                            switch(full.client.category.code) {
                                case 'V' : color ='btn-danger';break;
                                case 'B' : color ='btn-warning';break;
                                case 'A' : color ='btn-warning';break;
                            }

                            return "<a class='btn "+color+
                                    " btn-lg' href='/frontend/target/"+full.id+"'> </a>"; } }*/
                ],
                "iDisplayLength": 20,
                'order': [[3, 'asc']]
            });

            $('#filter_category').change( function() {
                table.fnFilter(this.value,5);
            });

            $('#filter_place').change( function() {
                table.fnFilter(this.value,6);
            });
            console.log('finish process');
        });

    </script>
@stop

@section('header')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Target Médico
            <small>Lista de médicos asignados a tu zona.</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ url('/') }}"><i class="fa fa-dashboard"></i> Inicio</a></li>
            <li class="active">
                <i class="fa fa-user-md"></i> Target Médico
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
                                <label>categoría</label>
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
                                <label>Taréa</label>
                                <select class="form-control filter_grid" id="filter_place">
                                    <option value="">todos</option>
                                    <option value="AM">AM</option>
                                    <option value="CO">CO</option>
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
                    <h3 class="box-title"><i class="fa fa-user-md"></i> Lista de Doctores</h3>
                </div>
                <div class="box-body table-responsive">
                    <table id="example" class="display table table-bordered table-striped center-table">
                        <thead>
                        <tr>
                            <th>Foto</th>
                            <th>Cmp</th>
                            <th>Médico</th>
                            <th>Dirección</th>
                            <th>Localidad</th>
                            <th>Cat.</th>
                            <th>Tar.</th>
                           <!-- <th>Estado</th>-->
                        </tr>
                        </thead>
                    </table>
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div>
    </div>
@endsection