<html>
<body>


        <table width="100%">
            <tr>
                <td colspan="13" style="text-align: center;"><h3>{{ $expense->name }} - #{{{ $expense->code }}}</h3> </td>
            </tr>
            <tr>


                <td colspan="13" style="text-align: center;"><h3>SOLICITUD DE ORDEN DE COMPRA</h3> </td>
            </tr>
            <tr>
                <td width="5%"></td>
                <td width="25%" colspan="2"><b>Solicitante:</b></td>
                <td width="25%" colspan="2">&nbsp;</td>
                <td width="25%" colspan="2" style="text-align: right;"><b>Fecha:</b> {{{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$expense->application_date)->format('d/m/Y') }}}</td>
                <td width="20%" colspan="2" style="text-align: right;"><b>Nro. Sol.:</b>{{ $expense->code }}</td>
            </tr>
            <tr>
                <td width="5%"></td>
                <td width="25%" colspan="2">{{ $expense->user->name }}</td>
                <td width="25%" colspan="2"></td>
                <td width="25%" colspan="2" style="text-align: right;"></td>
                <td width="20%" colspan="2" style="text-align: right;"></td>
            </tr>
        </table>
        <br>
        <table class="table">
            <tr>
                <th style="width: 10px">#</th>
                <th>Codigo</th>
                <th>C. C.</th>
                <th>C. Contable</th>
                <th>Act</th>
                <th>Gas</th>
                <th>Inv</th>
                <th>Can</th>
                <th>Precio</th>
                <th>Desc</th>
                <th>Total</th>
                <th>Destino</th>
                <th>Fecha</th>
            </tr>
            @foreach($expense->buy_orders as $key => $buy_order)

            <tr>
                <td>
                    {{ $key+1 }}
                </td>
                <td>
                    {{ $buy_order->code }}
                </td>
                <td>
                    {{ $buy_order->cost_center }}
                </td>
                <td>
                    {{ $buy_order->book_account }}
                </td>
                <td>
                    @if($buy_order->active == '1')
                        OK
                    @endif
                </td>
                <td>
                    @if($buy_order->expenditure == '1')
                        OK
                    @endif
                </td>
                <td>
                    @if($buy_order->inventory == '1')
                        OK
                    @endif
                </td>
                <td>
                    {{ $buy_order->quantity }}
                </td>
                <td>
                    {{ $buy_order->price_unit }}
                </td>
                <td width="30">
                    {{ $buy_order->description }}
                </td>
                <td width="10">
                    {{ $buy_order->estimated_value }}
                </td>
                <td width="10">
                    {{ $buy_order->destination }}
                </td>
                <td>
                    {{{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$buy_order->delivery_date)->format('d/m/Y') }}}
                </td>
            </tr>
                @endforeach
            <tr>
                <td>
                </td>
                <td>
                </td>
                <td>
                </td>
                <td>
                </td>
                <td>
                </td>
                <td>
                </td>
                <td>
                </td>
                <td>
                </td>
                <td>
                </td>
                <td style="text-align: right;">
                    <b>TOTAL S/.</b>

                </td>
                <td>
                    {{ $expense->total_amount }}
                </td>
                <td>
                </td>
                <td>
                </td>
            </tr>
        </table>

<br>
        <br>
        <table width="100%">
            <tr>
                <td width="5%"></td>
                <td><b>Observaciones:</b></td></tr>
            <tr>
                <td width="5%"></td>
                <td>
                    {{ $expense->description }}
                </td>
            </tr>
        </table>



        <br>
        <br>
        <br>
        <br>
        <table width="100%" style="text-align: center;">
            <tr>
                <td width="5%"></td>
                <td  colspan="2"><b>Solicitado Por</b></td>
                <td  colspan="2"><b>Autorizado Por</b></td>
                <td  colspan="2"><b>VoBo Gcia. General</b></td>
                <td  colspan="2"><b>Recibido por</b></td>
            </tr>

        </table>



</body>
</html>