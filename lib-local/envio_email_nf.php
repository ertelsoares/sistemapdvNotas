<?php

include('./app/libraries/PHPMailer/PHPMailerAutoload.php');
include("./configuracao.php");

if($_REQUEST["para"]!=""){

    $mail = new PHPMailer;
    //$mail->SMTPDebug = 2;
    //$mail->Debugoutput = 'html';
    $mail->isSMTP();
    $mail->Host = $mailHost;
    $mail->SMTPAuth = $mailSMTPAuth; 
    $mail->Username = $mailUsername;
    $mail->Password = $mailPassword;
    // $mail->SMTPSecure = 'tls'; 
    $mail->Port = $mailPort;
    $mail->SMTPOptions = $mailSMTPOptions;

    $mail->setFrom($mailFrom, $mailNomeFrom);

    $mail->addAddress(urldecode($_REQUEST["para"]));

    $mail->Subject = 'Nota Fiscal Eletronica';

    include("envio_email_nf_modelo.php");

    $mail->isHTML(true);
    $mail->Body = $email_template;

    //Attachments
    //$mail->addStringAttachment(file_get_contents($url), 'filename');
    
    $mail->addStringAttachment(file_get_contents(urldecode($_REQUEST["danfe"])), 'Danfe-Nota-Fiscal.pdf');
    $mail->addStringAttachment(file_get_contents(urldecode($_REQUEST["xml"])), 'Nota-Fiscal.xml');


    if (!$mail->send()) {
        echo "error";
    } else {
        echo "ok";
    }

    var_dump($mail->ErrorInfo);

}