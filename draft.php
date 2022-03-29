<?php

require_once __DIR__ . '/src/require_all.php';
session_write_close(); // No write access needed anymore
require_once __DIR__ . '/src/mails.php';

$mails = new Mails($pdo);

try {
    switch($_SERVER["REQUEST_METHOD"]) {
        case "GET":
            $result = $mails->getDraft(intval(clean_input($_GET["accountid"])));
            break;

        case "POST":
            $result = $mails->prepareDraft(array(
                "mailid"    => intval(clean_input($_POST["mailid"])),
                "accountid" => intval(clean_input($_POST["accountid"])),
                "subject"   => clean_input($_POST["subject"]),
                "body"      => trim($_POST["body"]) // Keep html content
            ));
            break;

        case "PUT":
            parse_str(file_get_contents("php://input"), $_PUT);
            $result = $mails->updDraft(array(
                "mailid"    => intval(clean_input($_PUT["mailid"])),
                "accountid" => intval(clean_input($_PUT["accountid"])),
                "subject"   => clean_input($_PUT["subject"]),
                "body"      => trim($_PUT["body"]) // Keep html content
            ));
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
