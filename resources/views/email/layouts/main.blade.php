<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width" />

        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:100,300,400" type="text/css" />

        <style>
            @include('email.assets.css.styles')
        </style>

        <!--[if mso]>
        <style type="text/css">
            body,
            table,
            td {
                font-family: Arial, Helvetica, sans-serif !important;
            }
        </style>
        <![endif]-->
    </head>

    <body>
        <table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#eeeeee">
            <tr>
                <td align="center" valign="top" width="100%">
                    <table align="center" border="0" cellpadding="0" cellspacing="0" width="600" class="full_wrapper">
                        <tr>
                            <td height="40">&nbsp;</td>
                        </tr>

                        <tr>
                            <td align="center" valign="top">
                                <table border="0" cellpadding="0" cellspacing="0" width="600" class="full_width">
                                    <tr>
                                        <td align="center" valign="top" bgcolor="#ffffff" class="full_width_text">
                                            @yield('body')
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>

                        <tr>
                            <td align="center" valign="top">
                                <table border="0" cellpadding="0" cellspacing="0" width="600" class="full_width">
                                    <tr>
                                        <td height="40">&nbsp;</td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </body>
</html>
