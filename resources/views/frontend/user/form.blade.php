@extends('frontend.app')

@section('header')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Mi Perfil
            <small>Edita tus datos, cambia tu contraseña o agrega tu foto.</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ url('/') }}"><i class="fa fa-dashboard"></i> Inicio</a></li>
            <li class="active">
                <i class="fa fa-edit"></i> Perfil
            </li>
        </ol>
    </section>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-user"></i> Editar mi perfil</h3>
                </div>
                <div class="box-body">
                    {!! $form !!}
                </div>
            </div>
        </div>
    </div>
@stop