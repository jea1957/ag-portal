<?php

require_once __DIR__ . '/src/require_all.php';
session_write_close(); // No write access needed anymore
require_once __DIR__ . '/src/mails.php';

$mails = new Mails($pdo);

try {
    switch($_SERVER["REQUEST_METHOD"]) {
        case "GET":
            if (isset($_GET["state"])) {
                $state = intval(clean_input($_GET["state"]));
            } else {
                $state = null; // wildcard
            }
            $result = $mails->getMails($state);
            break;

        case "DELETE":
            parse_str(file_get_contents("php://input"), $_DELETE);
            $mailid    = intval($_DELETE["mailid"]);
            $result    = $mails->delMail($mailid);
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
