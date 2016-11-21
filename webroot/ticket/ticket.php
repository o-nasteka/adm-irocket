<?php
session_start();
// Soft pdf crater include

require 'class.phpmailer.php'; // phpmailer script
require ('vendor/autoload.php');
use Dompdf\Dompdf;
use Dompdf\Options;
// Soft pdf crater include END


// html -> pdf verst
function html_invoice($number_inv){

    $html = '<html><meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <head>
        <style>
            body { font-family: DejaVu Sans;  }


             .head_logo {
            text-align: center;
        }

        .table_centr {
            margin-top: 20px;

        }

        .table_centr table {
            margin: auto;
            width: 100%;

            padding: 10px;

        }

        .table_centr table td {
            border: 1px solid black;
        }
        .table_centr .text-1 {
            background-color: #002A53;
            color: #ffffff;
            text-align: center;
            padding: 1px;
        }

        .td_1 {
        padding: 0;
        }

        .table_centr .text-1 h2 {
            margin: 15px 0;
        }

        .text_tick table {
            margin: auto;
            width: 100%;
        }
        .text_tick {

        }

        .head_text table{
            margin:  100px auto 0 auto;
            width: 100%;
        }

        </style>




        </head>
        <body>

        <div class="head_logo">
        <img src="level_up.logo.jpg">
    </div>


    <div class="table_centr">

                <table class="table-1" >
                    <tr>
                        <td class="td_1" colspan="2">
                            <div class="text-1">
                                <h2>Квиток на Ukraine Level UP</h2>
                            </div>


                        </td>
                    </tr>
                    <tr>
                        <td widt ch="50%">
                            <p>Дата</p>
                        </td>
                        <td>
                            <p>23.11.2016 09:00</p>
                        </td>
                    </tr>
                    <tr>
                        <td width="50%">
                            № Заказа
                        </td>
                        <td>
                            <p>'.$number_inv.'</p>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <p>
                                Данный билет дает право на посещение бизнес форума Ukraine Level Up 2016.<br>
                                Билет необходимо распечатать и предьявить на входе<br>
                                Данный билет действителен на одного человека.<br>
                                После регистрации на входе билет гасится и становиться недействительным!<br>
                                Не передавайте уникальный код третьим лицам!<br>
                                Электронный билет обмену и возврату не подлежит.<br>
                                Киев, Парковая дорога 16а, Конгресс-холл "Парковый". Тел. (044) 337 29 94<br>
                            </p>
                        </td>
                    </tr>
                </table>

    </div>';

    return $html;

}
// html -> pdf verst END



if(!$_SESSION['email_check']){
    exit;
}


$arr_info = $_SESSION['email_check'];

unset($_SESSION['email_check']);

foreach($arr_info as $k=> $v) {

    $options = new Options();
    $options->set('isRemoteEnabled', TRUE);
    $options->set('isFontSubsettingEnabled', true);
    $options->set('dpi', 130);
    $dompdf = new Dompdf($options);

    // Verst invoice
    $html = html_invoice($v['number']);
    // Verst invoice END
    $dompdf->load_html(urldecode($html), 'UTF-8');
    $dompdf->render();

    $output = $dompdf->output();

    // Create file to server
    file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/webroot/uploads/ticket-pdf/' . $v['number'] . '.pdf', $output);
    // Create file to server end
    // Create pdf END

    unset($options);
    unset($dompdf);

    $inv_attch = $_SERVER['DOCUMENT_ROOT'].'/webroot/uploads/ticket-pdf/'.$v['number'].'.pdf';
    $str = "
    <p>
        Добрый день!<br>
        Вам вложены ваши билеты на Ukraine Level up.<br>
        Вам необходимо распечатать их и предьявить на входе.
    </p>
";

    $mail = new PHPMailer();

    $mail->From = 'Events@icf.ua';      // from email
    $mail->FromName = 'Info';   // from name
    // $mail->AddAddress('oleg.nasteka@gmail.com', 'Oleg'); // to, name
    $mail->AddAddress($v['email'], ''); // to, name
    //$mail->AddAddress('info@irocket.me', 'Oleg'); // to, name
    //$mail->AddAddress('Events@icf.ua', 'Icf'); // to, name
    //$mail->AddAddress('soa@ltis-uk.com', 'ltis-uk.com'); // to, name
    $mail->IsHTML(true);        // email format HTML
    $mail->Subject = "Билет Ukraine Level Up 2016";  // subject

    $mail->Body = $str;
    $mail->AddAttachment($inv_attch);
    // send mail
    if (!$mail->Send()){
        unset($mail) ;
        die ('Mailer Error: ' . $mail->ErrorInfo);
    } else {
            unset($mail);
            echo('Your message has been sent!');

    }

}

header("Location: http://adm2.irocket.me/admin/order/");
