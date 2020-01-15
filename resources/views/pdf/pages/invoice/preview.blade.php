<html>
    <head>
        <title>2019-0723-Herrero y Granado SA</title>

        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

        <style type="text/css">
        {!! strip_tags($css) !!}
        </style>
    </head>

    <body>
        <div id="logo"></div>

        <div id="company">
            <p class="line name">Viajes Valero-Madrid</p>
            <p class="line tax_number">N01333688</p>
            <p class="line address">Avinguda Santiago, 0, 1º C</p>
            <p class="line city">36490 - Os Pascual de las Torres</p>
            <p class="line phone">+34 972 08 2269</p>
            <p class="line email">angela61@emiliode.com</p>
        </div>

        <div class="clear"></div>

        <div id="info">
            <div class="header">
                <span class="line number">{{ $serie->name ?? '' }} Nº</span>
                <span class="line date">Fecha</span>
                <span class="line payment">Método de pago</span>
            </div>

            <div class="data">
                <span class="line number">{{ $serie ? ($serie->number_prefix.$serie->number_next) : 1 }}</span>
                <span class="line date">{{ date('Y-m-d') }}</span>
                <span class="line payment">Transferencia Bancaria</span>
            </div>
        </div>

        <div class="clear"></div>

        <table id="client">
            <tr class="line">
                <th class="header name">Cliente</th>
                <td class="data name">Herrero y Granado SA</td>
            </tr>

            <tr class="line">
                <th class="header address">Dirección</th>
                <td class="data address">Rúa Diana, 0, 79º A</td>
            </tr>

            <tr class="line">
                <th class="header city">Localidad</th>
                <td class="data city">La Sarabia</td>
            </tr>

            <tr class="line">
                <th class="header phone">Teléfono</th>
                <td class="data phone">+34 997 46 8842</td>
            </tr>

            <tr class="line">
                <th class="header email">Email</th>
                <td class="data email">ismael.arias@yahoo.com</td>
            </tr>

            <tr class="line">
                <th class="header tax_number">CIF/NIF</th>
                <td class="data tax_number">B96671260</td>
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

                <tr class="data">
                    <td class="line reference">DOMAIN-01</td>
                    <td class="line description">Dominio .com (1 año)</td>
                    <td class="line amount">90,00€</td>
                    <td class="line quantity">3,00</td>
                    <td class="line discount">1%</td>
                    <td class="line total">267,30€</td>
                </tr>

                <tr class="data">
                    <td class="line reference">WEB-01</td>
                    <td class="line description">Plantilla WordPress</td>
                    <td class="line amount">200,00€</td>
                    <td class="line quantity">1,00</td>
                    <td class="line discount">3%</td>
                    <td class="line total">194,00€</td>
                </tr>

                <tr class="data">
                    <td class="line reference">WEB-01</td>
                    <td class="line description">Plantilla WordPress</td>
                    <td class="line amount">200,00€</td>
                    <td class="line quantity">1,00</td>
                    <td class="line discount">44%</td>
                    <td class="line total">112,00€</td>
                </tr>

                <tr class="data">
                    <td class="line reference">HOSTING-01</td>
                    <td class="line description">Alojamiento Web Básico (1 año)</td>
                    <td class="line amount">60,00€</td>
                    <td class="line quantity">3,00</td>
                    <td class="line discount">22%</td>
                    <td class="line total">140,40€</td>
                </tr>
            </table>
        </div>

        <div class="clear"></div>

        <div id="total">
            <div class="header">
                <p class="line subtotal">Subtotal</p>
                <p class="line discount">IRPF 19%</p>
                <p class="line tax">IVA 10%</p>
                <p class="line shipping-subtotal">SEUR 24</p>
                <p class="line shipping-tax">IVA Envío</p>
                <p class="line total">Total</p>
            </div>

            <div class="data">
                <p class="line subtotal">713,70€</p>
                <p class="line discount">135,60€</p>
                <p class="line tax">71,37€</p>
                <p class="line shipping-subtotal">10,00€</p>
                <p class="line shipping-tax">21% - 2,10€</p>
                <p class="line total">659,47€</p>
            </div>
        </div>

        <div class="clear"></div>

        <div id="comment">
            <p class="title">Notas / Comentarios</p>
            <div class="text">Realizar ingreso en la siguiente cuenta bancaria indicando el número de factura.</div>
        </div>
    </body>
</html>
