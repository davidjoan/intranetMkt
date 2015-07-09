@extends('frontend.app')

@section('header')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Error en la página
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ url('/') }}"><i class="fa fa-dashboard"></i> Inicio</a></li>
        </ol>
    </section>
@stop

@section('content')

    <!-- Main content -->
    <section class="content">

        <div class="error-page">
            <h2 class="headline text-yellow"> 500</h2>
            <div class="error-content">
                <h3><i class="fa fa-warning text-yellow"></i> Error en la encontrada.</h3>
                <p>
                    Vamos a trabajar en la correción de eso de inmediato.
                    Mientras tanto, puede <a href='{{ url('/') }}'>regresar al inicio</a>.
                </p>
            </div><!-- /.error-content -->
        </div><!-- /.error-page -->
    </section><!-- /.content -->

@endsection