<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');
ini_set('log_errors', '1');
ini_set('error_log', "/tmp/php.log");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . "/phpmailer/src/Exception.php";
require_once __DIR__ . "/phpmailer/src/PHPMailer.php";
require_once __DIR__ . "/phpmailer/src/SMTP.php";

use Html2Text\Html2Text;

require_once __DIR__ . "/Html2Text.php";

function clean_input($data) {
    if (!isset($data)) {
        return NULL;
    }
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Convert a local time with timezone info to UTC suitable for SQL. Example:
// localtime: 2022-07-11T00:00:00+02:00
// returns  : 2022-07-10 22:00:00
function localtime2UTC($localtime) {
    $dt = new DateTime($localtime);
    $dt->setTimezone(new DateTimeZone('UTC'));
    return $dt->format('Y-m-d H:i:s');
}

// $bcc is an optional array of objects containing 'email' and 'name'
// $att is an optional array of objects containing 'file', 'name' and 'type'
function send_email($from_email, $from_name, $to_email, $to_name, $subject, $body_html, $bcc = null, $att = null) {
    global $mail_username, $mail_password;
    $mail = new PHPMailer(true); // Passing `true` enables exceptions
    try {
        $mail->Debugoutput = 'error_log';
        $mail->SMTPDebug = 0;

        $mail->isSMTP();
        $mail->Host = 'mailout.one.com' ;
        $mail->Port = 587;
        $mail->SMTPSecure = 'tls';
        $mail->SMTPAuth = true;
        $mail->Username = $mail_username;
        $mail->Password = $mail_password;

        $mail->CharSet = "UTF-8";
        $mail->setFrom($from_email, $from_name);
        $mail->addAddress($to_email, $to_name);
        if (isset($bcc)) {
            foreach($bcc as $b) {
                $mail->addBCC($b->email, $b->name);
            }
        }
        if (isset($att)) {
            foreach($att as $a) {
                $mail->addStringAttachment($a->file, $a->name, 'base64', $a->type);
            }
        }
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $body_html;
        $h2t = new Html2Text($body_html);
        $mail->AltBody = $h2t->getText();

        if ($mail->send()) {
            $result = array("success" => 'Message has been sent.');
        } else {
            $result = array("error" => 'Error from send():' . $mail->ErrorInfo);
        }
    } catch (Exception $e) {
        $result = array("error" => $mail->ErrorInfo);
    }
    return $result;
}

function destroy_session() {
    global $cookie_samesite;
    $_SESSION = [];
    $params = session_get_cookie_params();
    setcookie(session_name(), '', [
        'expires'  => time() - 4200,
        'path'     => $params['path'],
        'domain'   => $params['domain'],
        'secure'   => $params['secure'],
        'httponly' => $params['httponly'],
        'samesite' => $cookie_samesite
    ]);
}
?>
