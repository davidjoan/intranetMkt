@extends('frontend.app')

@section('header')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Formatos
            <small>Lista completa de todos los foramtos segun tipo de gasto</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ url('/') }}"><i class="fa fa-dashboard"></i> Inicio</a></li>
            <li class="active">
                <i class="fa fa-bar-chart-o"></i> Reportes
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
        <div class="col-md-3">
            <div class="box box-warning">
                <div class="box-header with-border">
                    <h3 class="box-title">Formato # 1</h3>
                    <div class="box-tools pull-right">
                        <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    </div><!-- /.box-tools -->
                </div><!-- /.box-header -->
                <div class="box-body">
                    Listado completo de visitas correspondiente al ciclo actual.
                    <a class="btn btn-primary" href="#">
                        <i class="fa fa-download"></i>
                        Descargar
                    </a>
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div><!-- /.col -->
        <div class="col-md-3">
            <div class="box box-warning">
                <div class="box-header with-border">
                    <h3 class="box-title">Formato # 2</h3>
                    <div class="box-tools pull-right">
                        <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    </div><!-- /.box-tools -->
                </div><!-- /.box-header -->
                <div class="box-body">
                    Listado completo de rutas correspondiente al ciclo actual.
                    <a class="btn btn-primary" href="#">
                        <i class="fa fa-download"></i>
                        Descargar
                    </a>
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div><!-- /.col -->
        <div class="col-md-3">
            <div class="box box-warning">
                <div class="box-header with-border">
                    <h3 class="box-title">Formato # 3</h3>
                    <div class="box-tools pull-right">
                        <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    </div><!-- /.box-tools -->
                </div><!-- /.box-header -->
                <div class="box-body">
                    Listado completo de ausencias correspondiente al ciclo actual.
                    <a class="btn btn-primary" href="#">
                        <i class="fa fa-download"></i>
                        Descargar
                    </a>
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div><!-- /.col -->
        <div class="col-md-3">
            <div class="box box-warning">
                <div class="box-header with-border">
                    <h3 class="box-title">Formato # 4</h3>
                    <div class="box-tools pull-right">
                        <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    </div><!-- /.box-tools -->
                </div><!-- /.box-header -->
                <div class="box-body">
                    Reporte de cobertura a la fecha, correspondiente al ciclo actual.
                    <a href="{{ url('/frontend/cobertura/exportar') }}" class="btn btn-primary">Descargar
                    </a>
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div><!-- /.col -->
        </div>

        <!-- /.row -->
        <div class="row">
            <div class="col-md-3">
                <div class="box box-warning">
                    <div class="box-header with-border">
                        <h3 class="box-title">Formato # 5</h3>
                        <div class="box-tools pull-right">
                            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        </div><!-- /.box-tools -->
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        Target Médico completo, nombre direccion, cat, tarea y esp.
                        <a class="btn btn-primary" href="#">
                            <i class="fa fa-download"></i>
                            Descargar
                        </a>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div><!-- /.col -->
        </div>


@endsection
