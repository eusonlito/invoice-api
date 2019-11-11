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
            <p><strong>{{ $invoice->company_name }}</strong></p>
            <p>{{ $invoice->company_tax_number }}</p>
            <p>{{ $invoice->company_address }}</p>
            <p>{{ $invoice->company_city }}</p>
            <p>{{ $invoice->company_phone }}</p>
            <p>{{ $invoice->company_email }}</p>
        </div>

        <br class="clear" />

        <div id="info">
            <div class="header">
                <span>Factura Nº</span>
                <span>Fecha</span>
                <span>Método de pago</span>
            </div>

            <div class="data">
                <span><strong>{{ $invoice->number }}</strong></span>
                <span>{{ $invoice->date_at }}</span>
                <span>{{ $invoice->payment->name ?? '-' }}</span>
            </div>
        </div>

        <div id="client">
            <div class="header">
                <p>Cliente</p>
                <p>CIF</p>
                <p>Dirección</p>
                <p>Localidad</p>

                @if ($invoice->clientAddressBilling->phone)
                <p>Teléfono</p>
                @endif

                @if ($invoice->clientAddressBilling->email)
                <p>Email</p>
                @endif
            </div>

            <div class="data">
                <p><strong>{{ $invoice->clientAddressBilling->name }}</strong></p>
                <p>{{ $invoice->clientAddressBilling->tax_number }}</p>
                <p>{{ $invoice->clientAddressBilling->address }}</p>
                <p>{{ $invoice->clientAddressBilling->city }}</p>

                @if ($invoice->clientAddressBilling->phone)
                <p>{{ $invoice->clientAddressBilling->phone }}</p>
                @endif

                @if ($invoice->clientAddressBilling->email)
                <p>{{ $invoice->clientAddressBilling->email }}</p>
                @endif
            </div>
        </div>

        <br class="clear" />

        <div id="items">
            <table>
                <tr>
                    <th class="description">Descripción</th>
                    <th class="amount">Importe</th>
                    <th class="quantity">Cantidad</th>
                    <th class="discount">Descuento</th>
                    <th class="total">Total</th>
                </tr>

                @foreach ($invoice->items as $item)
                <tr>
                    <td class="description">{{ $item->description }}</td>
                    <td class="amount">{{ money($item->amount_price) }}</td>
                    <td class="quantity">{{ number($item->quantity) }}</td>
                    <td class="discount">
                        @if ($item->percent_discount)
                        {{ $item->percent_discount }}%
                        @else
                        -
                        @endif
                    </td>
                    <td class="total">{{ money($item->amount_subtotal) }}</td>
                </tr>
                @endforeach
            </table>
        </div>

        <div id="total">
            <div class="header">
                <p>Subtotal</p>

                @if ($invoice->discount)
                <p>{{ $invoice->discount->name }}</p>
                @endif

                @if ($invoice->tax)
                <p>{{ $invoice->tax->name }}</p>
                @endif

                <p><strong>Total</strong></p>
            </div>

            <div class="data">
                <p>{{ money($invoice->amount_subtotal) }}</p>

                @if ($invoice->discount)
                <p>{{ money($invoice->amount_discount) }}</p>
                @endif

                @if ($invoice->tax)
                <p>{{ money($invoice->amount_tax) }}</p>
                @endif

                <p><strong>{{ money($invoice->amount_total) }}</strong></p>
            </div>
        </div>

        <br class="clear" />

        <div id="comment">
            <p class="title">Notas / Comentarios</p>

            <div>{!! nl2br($invoice->comment_public) !!}</div>
        </div>
    </body>
</html>
