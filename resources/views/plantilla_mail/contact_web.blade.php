<!DOCTYPE html>
<html>
    <body bgcolor="#ecf0f5">
        <table style="font-family: &quot;Helvetica Neue&quot;, Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; width: 100%; margin: 0;">
            <tr>
                <td valign="top">
                    <div style="max-width: 700px; margin: 0 auto; padding: 20px;">
                        <table class="main" width="100%" cellpadding="0" cellspacing="0" itemprop="action" bgcolor="#fff">
                            <tr >
                                <td class="content-wrap" style="margin: 0; padding: 20px;padding-top: 0px;" valign="top">
                                    <table width="100%" cellpadding="0" cellspacing="0" style="">
                                        <tr>
                                            <td class="content-block" style="font-size: 18px; margin-top: 10px; padding: 0 0 20px;" valign="top">
                                                <center><h1 style="background: #A32121; color:white;">Hola, {{$empresa->razon_social??""}}</h1></center>

                                                <table width="100%" style="font-size: 15px;padding-top: 10px;">
                                                    <tr>
                                                        <td>
                                                            Asunto:
                                                            <b>{{($asunto??'Sin asunto')}}</b>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td colspan="2"> {{($mensaje??'')}}</td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>
                                                Muchas gracias,
                                                <br>
                                                Saludos,
                                                <br>
                                                <b>{{$remitente}}</b>
                                                <br>
                                                <i>Teléfono: </i> <b>{{$celular??""}}</b>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </div>
                </td>
            </tr>
        </table>
    </body>
 </html>
