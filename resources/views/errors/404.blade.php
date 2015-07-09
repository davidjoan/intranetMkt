@extends('frontend.app')

@section('header')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Pagina no encontrada
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
            <h2 class="headline text-yellow"> 404</h2>
            <div class="error-content">
                <h3><i class="fa fa-warning text-yellow"></i> Pagina no encontrada.</h3>
                <p>
                    No pudimos encontrar la página que estabas buscando.
                    Mientras tanto, puedes <a href='{{ url('/') }}'>regresar al inicio</a>.
                </p>
            </div><!-- /.error-content -->
        </div><!-- /.error-page -->
    </section><!-- /.content -->

@endsection