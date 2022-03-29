<?php

require_once __DIR__ . '/src/require_all.php';
session_write_close(); // No write access needed anymore

if (empty($_POST["name"]) || empty($_POST["email"]) || empty($_POST["otp"])) {
    header("Content-Type: application/json");
    $result = array("error" => "Missing parameter(s)");
    echo json_encode($result);
    exit;
}

$name  = clean_input($_POST["name"]);
$email = clean_input($_POST["email"]);
$otp   = clean_input($_POST["otp"]);

$body_html = sprintf(_L('pwm_body'), $name, $db_url, $email, $otp);

$body_text = sprintf(_L('pwm_text'), $name, $db_url, $email, $otp);

$result = send_email($db_contact, $db_cname, $email, $name, _L('pwm_subject'), $body_html, $body_text);

header("Content-Type: application/json");
echo json_encode($result);

?>
