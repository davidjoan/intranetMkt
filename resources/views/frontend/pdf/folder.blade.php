<html>
<head>
    <meta name="viewport" content="initial-scale=1.0; maximum-scale=1.0; minimum-scale=1.0; user-scalable=no; width=device-width" />
    <link href="/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <style>

        body {
            font-family: Arial, Helvetica, sans-serif;
            padding:0px;
            margin:0px;
            color:#333;
            min-height:100%;
        }

        .header {
            text-align:center;
            position:fixed;
            height:30px;
            left:0px;
            right:0px;
            background:#FFF;
            padding:25px 0px 5px 0px;
            border-bottom:5px solid #CCC;
            font-size:20px;
            font-weight:bold;
        }

        .content {
            position:fixed;
            top:62px;
            bottom:0px;
            left:0px;
            right:0px;
            overflow:scroll;
            -webkit-overflow-scrolling:touch;
            margin-bottom:30px;
        }

        .footer {
            position:fixed;
            height:22px;
            left:0px;
            right:0px;
            bottom:0px;
            background:#FFF;
            color:888;
            text-align:center;
            padding:5px 0px 0px 0px;
            border-top:5px solid #CCC;
            font-size:12px;
        }

        .float-left .card {
            float:left;
            width:300px;
            height:270px;
        }

        .multi-column {
            columns:300px 3;
            -webkit-columns:300px 3;
        }

        .card {
            background:#FFF;
            border:2px solid #AAA;
            border-bottom:3px solid #BBB;
            padding:0px;
            margin:15px;
            overflow:hidden;
        }

        .card h1 {
            margin:0px;
            padding:10px;
            padding-bottom:0px;
        }

        .card p {
            margin:0px;
            padding:10px;
        }

        .card-image {
            width:100%;
            height:200px;
            padding:0px;
            margin:0px;
            background-position:center;
            background-repeat:no-repeat;
            position:relative;
            overflow:hidden;
        }

        .card-image .banner {
            height:50px;
            width:50px;
            top:0px;
            right:0px;
            position:absolute;
        }

        .card-image h1,
        .card-image h2,
        .card-image h3,
        .card-image h4,
        .card-image h5,
        .card-image h6 {
            position:absolute;
            bottom:0px;
            width:100%;
            color:white;
            background:rgba(0,0,0,0.65);
            margin:0px;
            padding:6px;
            border:none;
        }

        .rightColumn,
        .leftColumn {
            display:inline-block;
            width:49%;
            vertical-align:top;
        }

        .column {
            display:inline-block;
            vertical-align:top;
        }
        .qr {

            top: 5px;
            left: 5px;

        }
    </style>
</head>
<body>

<div class="content">


    <div class="card">
            <table>
                <tr>
                    <td width="20%">
                        <img src="data:image/png;base64,{{ base64_encode(QrCode::format('png')->size(150)->generate($expense->code)) }}"
                             class="qr"/>
                    </td>
                    <td width="80%">
                        <table class="table">
                            <tr>
                                <td><b>Nombre: </b></td>
                                <td>{{{ $expense->name }}}</td>
                            </tr>
                            <tr>
                                <td><b>Descripción: </b></td>
                                <td>{{{ $expense->description }}}</td>
                            </tr>
                            <tr>
                                <td><b>Tipo: </b></td>
                                <td>{{{ $expense->expense_type->name }}}</td>
                            </tr>
                            <tr>
                                <td><b>Usuario: </b></td>
                                <td>{{{ $expense->user->name }}}</td>
                            </tr>
                            <tr>
                                <td><b>División: </b></td>
                                <td>{{{ $expense->division->name }}}</td>
                            </tr>
                            <tr>
                                <td><b>Fecha: </b></td>
                                <td>{{{ $expense->application_date }}}</td>
                            </tr>
                            <tr>
                                <td><b>Codigo: </b></td>
                                <td>{{{ $expense->code }}}</td>
                            </tr>

                            <tr>
                                <td><b>Visto Bueno:</b></td>
                                <td>
                                    <table>
                                        <tr>
                                            <td><b>Gerente División:</b></td>
                                            <td  style="border: 1px solid black;">@if ($expense->approval_1 == '1')
                                                    OK @else &nbsp;&nbsp;
                                                @endif</td>
                                        </tr>
                                        <tr>
                                            <td><b>Control de Gestión:</b></td>
                                            <td  style="border: 1px solid black;">@if ($expense->approval_2 == '1')
                                                    OK @else &nbsp;&nbsp;
                                                @endif</td>
                                        </tr>
                                        <tr>
                                            <td><b>Gerencia General:</b></td>
                                            <td  style="border: 1px solid black;">@if ($expense->approval_3 == '1')
                                                    OK @else &nbsp;&nbsp;
                                                @endif</td>
                                        </tr>

                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td><b>Formatos:</b></td>
                                <td>
                                    <ul>
                                        @foreach ($expense->expense_type->file_formats as $file_format)
                                            <li>{{ $file_format->name }}</li>
                                        @endforeach
                                    </ul>
                                </td>
                            </tr>

                            <tr>
                                <td><b>Monto: </b></td>
                                <td>S/. {{{ $expense->total_amount }}}</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
</div>


<div class="header">
    {{ $expense->expense_type->name }} - #{{{ $expense->code }}}
</div>

<div class="footer">
    copyright 2015 <a href="#" target="_blank">Intranet MKT</a>.
</div>
</body>
</html>