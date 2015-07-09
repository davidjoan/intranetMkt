@extends('clean')

@section('content')
    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <strong>Upss!</strong> Hubo algunos problemas con su ingreso.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <p class="login-box-msg">Iniciar Sesión</p>
    <form class="form-horizontal" role="form" method="POST" action="{{ url('/auth/login') }}">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">

        <div class="form-group has-feedback">
            <input type="email" class="form-control" placeholder="Correo Electrónico" name="email" value="{{ old('email') }}">
            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
        </div>
        <div class="form-group has-feedback">
            <input type="password" class="form-control" name="password" placeholder="Contraseña"/>
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
        </div>
        <div class="row">
            <div class="col-xs-8">
                <div class="checkbox icheck">
                    <label>
                        <input type="checkbox" name="remember"> Recuerdame
                    </label>
                </div>
            </div><!-- /.col -->
            <div class="col-xs-4">
                <button type="submit" class="btn btn-primary btn-block btn-flat">Ingresar</button>
            </div><!-- /.col -->
        </div>
    </form>

    <a class="btn btn-link" href="{{ url('/password/email') }}">¿Olvidó su contraseña?</a>
    <br>
    <a href="#" class="text-center">Si eres nuevo en la Intranet, Registrate</a>


@endsection
