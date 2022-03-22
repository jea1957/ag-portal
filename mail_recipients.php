<?php

require_once __DIR__ . '/src/require_all.php';
require_once __DIR__ . '/src/mails.php';

$mails = new Mails($pdo);

try {
    switch($_SERVER["REQUEST_METHOD"]) {
        case "GET":
            $result = $mails->getMailRecipients(intval(clean_input($_GET["mailid"])));
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
