<html>
    <head>
        <title>{{ $invoice->number }}-{{ $invoice->clientAddressBilling->name }}</title>

        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

        <style type="text/css">
        {!! strip_tags($css) !!}
        </style>
    </head>

    <body>
        <div id="logo"></div>

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
                <span class="line number">{{ $invoice->serie->name ?? '' }} Nº</span>
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

        <table id="client">
            <tr class="line">
                <th class="header name">Cliente</th>
                <td class="data name">{{ $invoice->billing_name }}</td>
            </tr>

            <tr class="line">
                <th class="header address">Dirección</th>
                <td class="data address">{{ $invoice->billing_address }}</td>
            </tr>

            <tr class="line">
                <th class="header city">Localidad</th>
                <td class="data city">{{ $invoice->billing_city }}</td>
            </tr>

            @if ($invoice->billing_phone)

            <tr class="line">
                <th class="header phone">Teléfono</th>
                <td class="data phone">{{ $invoice->billing_phone }}</td>
            </tr>

            @endif

            @if ($invoice->billing_email)

            <tr class="line">
                <th class="header email">Email</th>
                <td class="data email">{{ $invoice->billing_email }}</td>
            </tr>

            @endif

            <tr class="line">
                <th class="header tax_number">CIF/NIF</th>
                <td class="data tax_number">{{ $invoice->billing_tax_number }}</td>
            </tr>
        </table>

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
                        {{ (int)$item->percent_discount }}%
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

                @if ($invoice->amount_shipping)
                <p class="line shipping-subtotal">{{ $invoice->shipping->name ?? 'Envío' }}</p>
                <p class="line shipping-tax">IVA Envío</p>
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

                @if ($invoice->amount_shipping)

                <p class="line shipping-subtotal">{{ money($invoice->amount_shipping_subtotal) }}</p>
                <p class="line shipping-tax">
                    {{ $invoice->amount_shipping_tax_percent }}%
                    - {{ money($invoice->amount_shipping_tax_amount) }}
                </p>

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
