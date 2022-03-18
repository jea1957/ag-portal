<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');
ini_set('log_errors', '1');
ini_set('error_log', "/tmp/php.log");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . "/phpmailer/src/Exception.php";
require_once __DIR__ . "/phpmailer/src/PHPMailer.php";

function clean_input($data) {
    if (!isset($data)) {
        return NULL;
    }
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function send_email($from_email, $from_name, $to_email, $to_name, $subject, $body_html, $body_text) {
    $mail = new PHPMailer(true); // Passing `true` enables exceptions
    try {
        $mail->CharSet = "UTF-8";
        $mail->setFrom($from_email, $from_name);
        $mail->addAddress($to_email, $to_name);
        $mail->isHTML(true);
        $mail->Subject = $subject;
        if (isset($body_html)) {
            $mail->Body = $body_html;
        }
        if (isset($body_text)) {
            $mail->AltBody = $body_text;
        }
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
