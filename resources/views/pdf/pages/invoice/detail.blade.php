<html>
    <head>
        <title>{{ $invoice->number }}-{{ $invoice->clientAddressBilling->name }}</title>

        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

        <style type="text/css">
        {{ include base_path('resources/views/pdf/pages/invoice/detail.css') }}
        </style>
    </head>

    <body>
        <div id="company">
            <p class="line name">{{ $invoice->company_name }}</p>
            <p class="line tax_number">{{ $invoice->company_tax_number }}</p>
            <p class="line address">{{ $invoice->company_address }}</p>
            <p class="line city">{{ $invoice->company_postal_code }} - {{ $invoice->company_city }}</p>
            <p class="line phone">{{ $invoice->company_phone }}</p>
            <p class="line email">{{ $invoice->company_email }}</p>
        </div>

        <div class="clear"></div>

        <div id="info">
            <div class="header">
                <span class="line number">Factura Nº</span>
                <span class="line date">Fecha</span>
                <span class="line payment">Método de pago</span>
            </div>

            <div class="data">
                <span class="line number">{{ $invoice->number }}</span>
                <span class="line date">{{ $invoice->date_at }}</span>
                <span class="line payment">{{ $invoice->payment->name ?? '-' }}</span>
            </div>
        </div>

        <div class="clear"></div>

        <div id="client">
            <div class="billing {{ $invoice->clientAddressShipping ? '' : 'no-shipping' }}">
                <div class="title">Facturación</div>

                <div class="header">
                    <p class="line name">Cliente</p>
                    <p class="line address">Dirección</p>
                    <p class="line city">Localidad</p>

                    @if ($invoice->clientAddressBilling->phone)
                    <p class="line phone">Teléfono</p>
                    @endif

                    @if ($invoice->clientAddressBilling->email)
                    <p class="line email">Email</p>
                    @endif

                    <p class="line tax_number">CIF</p>
                </div>

                <div class="data">
                    <p class="line name">{{ $invoice->clientAddressBilling->name }}</p>
                    <p class="line address">{{ $invoice->clientAddressBilling->address }}</p>
                    <p class="line city">{{ $invoice->clientAddressBilling->city }}</p>

                    @if ($invoice->clientAddressBilling->phone)
                    <p class="line phone">{{ $invoice->clientAddressBilling->phone }}</p>
                    @endif

                    @if ($invoice->clientAddressBilling->email)
                    <p class="line email">{{ $invoice->clientAddressBilling->email }}</p>
                    @endif

                    <p class="line tax_number">{{ $invoice->clientAddressBilling->tax_number }}</p>
                </div>
            </div>

            @if ($invoice->clientAddressShipping)
            <div class="shipping">
                <div class="title">Envío</div>

                <div class="header">
                    <p class="line name">Cliente</p>
                    <p class="line address">Dirección</p>
                    <p class="line city">Localidad</p>

                    @if ($invoice->clientAddressShipping->phone)
                    <p class="line phone">Teléfono</p>
                    @endif

                    @if ($invoice->clientAddressShipping->email)
                    <p class="line email">Email</p>
                    @endif
                </div>

                <div class="data">
                    <p class="line name">{{ $invoice->clientAddressShipping->name }}</p>
                    <p class="line address">{{ $invoice->clientAddressShipping->address }}</p>
                    <p class="line city">{{ $invoice->clientAddressShipping->city }}</p>

                    @if ($invoice->clientAddressShipping->phone)
                    <p class="line phone">{{ $invoice->clientAddressShipping->phone }}</p>
                    @endif

                    @if ($invoice->clientAddressShipping->email)
                    <p class="line email">{{ $invoice->clientAddressShipping->email }}</p>
                    @endif
                </div>
            </div>
            @endif
        </div>

        <div class="clear"></div>

        <div id="items">
            <table class="list">
                <tr class="header">
                    <th class="line reference">Ref.</th>
                    <th class="line description">Descripción</th>
                    <th class="line amount">Importe</th>
                    <th class="line quantity">Cant.</th>
                    <th class="line discount">Dto.</th>
                    <th class="line total">Total</th>
                </tr>

                @foreach ($invoice->items as $item)
                <tr class="data">
                    <td class="line reference">{{ $item->reference ?: '-' }}</td>
                    <td class="line description">{{ $item->description }}</td>
                    <td class="line amount">{{ money($item->amount_price) }}</td>
                    <td class="line quantity">{{ number($item->quantity) }}</td>
                    <td class="line discount">
                        @if ($item->percent_discount)
                        {{ $item->percent_discount }}%
                        @else
                        -
                        @endif
                    </td>
                    <td class="line total">{{ money($item->amount_subtotal) }}</td>
                </tr>
                @endforeach
            </table>
        </div>

        <div class="clear"></div>

        <div id="total">
            <div class="header">
                <p class="line subtotal">Subtotal</p>

                @if ($invoice->discount)
                <p class="line discount">{{ $invoice->discount->name }}</p>
                @endif

                @if ($invoice->tax)
                <p class="line tax">{{ $invoice->tax->name }}</p>
                @endif

                <p class="line total">Total</p>
            </div>

            <div class="data">
                <p class="line subtotal">{{ money($invoice->amount_subtotal) }}</p>

                @if ($invoice->discount)
                <p class="line discount">{{ money($invoice->amount_discount) }}</p>
                @endif

                @if ($invoice->tax)
                <p class="line tax">{{ money($invoice->amount_tax) }}</p>
                @endif

                <p class="line total">{{ money($invoice->amount_total) }}</p>
            </div>
        </div>

        <div class="clear"></div>

        <div id="comment">
            <p class="title">Notas / Comentarios</p>
            <div class="text">{!! nl2br($invoice->comment_public) !!}</div>
        </div>
    </body>
</html>
