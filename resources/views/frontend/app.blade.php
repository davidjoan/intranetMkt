<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <meta name="description" content="Réflex 360 es una solución de negocio especialmente diseñada para la administración de fuerzas de venta en la industria farmacéutica.">
    <meta name="author" content="David Joan Tataje Mendoza">

    <title>Intranet MKT</title>


    @section("includes.css")
    <!-- Bootstrap 3.3.2 -->
    <link href="/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <!-- Font Awesome Icons -->
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <!-- Ionicons -->
    <link href="http://code.ionicframework.com/ionicons/2.0.0/css/ionicons.min.css" rel="stylesheet" type="text/css" />
    <!-- Theme style -->
    <link href="/dist/css/AdminLTE.min.css" rel="stylesheet" type="text/css" />
    <!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
    <link href="/dist/css/skins/_all-skins.min.css" rel="stylesheet" type="text/css" />

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->

    <link href="/css/toastr.css" rel="stylesheet" type="text/css" />

    <link href="/css/plugins/morris.css" rel="stylesheet" type="text/css" />

    {!! Rapyd::styles() !!}

    @show

</head>
<body class="skin-purple">
<!-- Site wrapper -->
<div class="wrapper">

    <header class="main-header">
        <a href="{{ url('/frontend/home') }}" class="logo"><b>Intranet</b> MKT</a>
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top" role="navigation">
            <!-- Sidebar toggle button-->
            <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </a>
            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">

                    <!-- User Account: style can be found in dropdown.less -->
                    <li class="dropdown user user-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <img src="{{ (Auth::user()->photo == '')?'/images/avatar.png':'/uploads/user/'.Auth::user()->photo  }}" class="user-image" alt="User Image"/>
                            <span class="hidden-xs">

                                {{ Auth::user()->name }}</span>
                        </a>
                        <ul class="dropdown-menu">
                            <!-- User image -->
                            <li class="user-header">
                                <img src="{{ (Auth::user()->photo == '')?'/images/avatar.png':'/uploads/user/'.Auth::user()->photo  }}" class="img-circle" alt="User Image" />
                                <p>
                                    {{ Auth::user()->name}} - {{ Auth::user()->role->code }}
                                    <small></small>
                                </p>
                            </li>
                            <!-- Menu Body -->
                            <!--  <li class="user-body">
                                  <div class="col-xs-4 text-center">
                                      <a href="#">Followers</a>
                                  </div>
                                  <div class="col-xs-4 text-center">
                                      <a href="#">Sales</a>
                                  </div>
                                  <div class="col-xs-4 text-center">
                                      <a href="#">Friends</a>
                                  </div>
                              </li>-->
                            <!-- Menu Footer-->
                            <li class="user-footer">
                                <div class="pull-left">
                                </div>
                                <div class="pull-right">
                                    <a href="{{ url('/auth/logout') }}" class="btn btn-default btn-flat">Cerrar Sesión</a>
                                </div>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>
    </header>

    <!-- =============================================== -->

    <!-- Left side column. contains the sidebar -->
    <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
            <!-- Sidebar user panel -->
            <div class="user-panel">
                <div class="pull-left info">
                    <p>{{ Auth::user()->name }}</p>
                    <p>{{ Auth::user()->role->name }}</p>


                    <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
                </div>
            </div>
            <!-- sidebar menu: : style can be found in sidebar.less -->
            <ul class="sidebar-menu">
                <li class="header">MENU PRINCIPAL</li>
                <li><a href="{{ url('/frontend/home') }}"><i class="fa fa-fw fa-dashboard"></i> Dashboard</a></li>
                <li><a href="{{ url('/frontend/gastos') }}"><i class="ion ion-fw ion-bag"></i> Registro de Gastos</a></li>
                <li><a href="{{ url('/frontend/presupuestos') }}"><i class="fa fa-fw fa-money"></i> Presupuestos</a></li>

            </ul>
        </section>
        <!-- /.sidebar -->
    </aside>

    <!-- =============================================== -->

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">

        @section("header")
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <h1>
                    Dashboard
                    <small></small>
                </h1>
                <ol class="breadcrumb">
                    <li><a href="#"><i class="fa fa-dashboard"></i> Inicio</a></li>
                </ol>
            </section>

            @show

                    <!-- Main content -->
            <section class="content">

                @yield("content")

            </section><!-- /.content-wrapper -->

    </div>

    <footer class="main-footer">
        <div class="pull-right hidden-xs">
            <b>Version</b> 2.0
        </div>
        <strong>Copyright &copy; 2015 <a href="{{ url('/') }}">Intranet MKT</a>.</strong> All rights reserved.
    </footer>
</div><!-- ./wrapper -->

@section("includes.js")

<script src="/plugins/jQuery/jQuery-2.1.3.min.js"></script>
<script src="/plugins/touchpunch/jquery.ui.touch-punch.min.js" type="text/javascript"></script>
<script src="/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<script src="/plugins/datepicker/bootstrap-datepicker.js" type="text/javascript"></script>
<script src="/plugins/datepicker/bootstrap-datepicker.es.js" type="text/javascript"></script>
<!--<script src="/plugins/slimScroll/jquery.slimscroll.min.js" type="text/javascript"></script>-->
<script src='/plugins/fastclick/fastclick.min.js'></script>

<script type="text/javascript">
    var user_id = {{ Auth::user()->id }};
</script>

<script src="/dist/js/app.min.js" type="text/javascript"></script>

<script src="/js/plugins/morris/raphael.min.js"></script>
<script src="/js/plugins/morris/morris.min.js"></script>

<script src="/js/toastr.min.js" type="text/javascript"></script>

{!! Rapyd::scripts() !!}

@show

</body>
</html>
