<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'src/phpmailer/src/Exception.php';
require 'src/phpmailer/src/PHPMailer.php';

$mail = new PHPMailer(true); // Passing `true` enables exceptions
try {
    //Recipients
    $mail->setFrom('bestyrelsen@admiralensgaard.com', 'Bestyrelsen i AG');
    $mail->addAddress('jea@hvedevej22.dk', 'Joergen Andreasen');
    $mail->addReplyTo('bestyrelsen@admiralensgaard.com', 'Reply');
    //$mail->addCC('cc@example.com');
    //$mail->addBCC('jea@hvedevej22.dk', 'Joergen Andreasen');

    //Attachments
    //$mail->addAttachment('/var/tmp/file.tar.gz');
    //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');

    //Content
    $mail->isHTML(true);
    $mail->Subject = 'Dette er en mail fra Admiralens Gaard';
    $mail->Body    = 'Body text <b>goes</b> here. Danish letters: åæøÅÆØ';
    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    if ($mail->send()) {
        echo 'Message has been sent.';
    } else {
        echo 'Error from send().' . $mail->ErrorInfo;
    }
} catch (Exception $e) {
    echo 'Message could not be sent.';
    echo 'Mailer Error: ' . $mail->ErrorInfo;
}
?>
