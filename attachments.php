<?php

require_once __DIR__ . '/src/require_all.php';
session_write_close(); // No write access needed anymore
require_once __DIR__ . '/src/mails.php';

$mails = new Mails($pdo);

try {
    switch($_SERVER["REQUEST_METHOD"]) {
        case "GET":
            $mailid = intval(clean_input($_POST["mailid"]));
            $result = $mails->getAttachments($mailid);
            break;

        case "POST":
            $mailid = intval(clean_input($_POST["mailid"]));
            $num_files = count($_FILES['file']['name']);
            for ($i = 0; $i < $num_files; $i++) {
                $error = $_FILES['file']['error'][$i];
                if ($error != 0) {
                    // https://www.php.net/manual/en/features.file-upload.errors.php
                    error_log("Error $error in \"" . $_FILES['file']['name'][$i] . "\"");
                    break;
                }
                $result = $mails->addAttachment(array(
                    "mailid" => $mailid,
                    "name"   => $_FILES['file']['name'][$i],
                    "type"   => $_FILES['file']['type'][$i],
                    "file"   => $_FILES['file']['tmp_name'][$i],
                    "size"   => $_FILES['file']['size'][$i]
                ));
            }
            
            $result = $mails->getAttachments($mailid);
            break;

        case "DELETE":
            parse_str(file_get_contents("php://input"), $_DELETE);
            $mailid = intval(clean_input($_DELETE["mailid"]));
            $id = intval(clean_input($_DELETE["id"]));
            $mails->delAttachment($id);
            $result = $mails->getAttachments($mailid);
            break;
    }

    header("Content-Type: application/json");
    echo json_encode($result);

} catch (PDOException $e) {
    http_response_code(500);
    header("Content-Type: application/json");
    echo json_encode($e->getMessage());
}

?>
