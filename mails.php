<?php

require_once __DIR__ . '/src/require_all.php';
require_once __DIR__ . '/src/mails.php';

$mails = new Mails($pdo);

try {
    switch($_SERVER["REQUEST_METHOD"]) {
        case "GET":
            $result = $mails->getDrafts(intval(clean_input($_GET["accountid"])));
            break;

        case "POST":
            $accountid = intval(clean_input($_POST["accountid"])),
            $result = $mails->newDraft($accountid);
            break;

        case "PUT":
            parse_str(file_get_contents("php://input"), $_PUT);
            $result = $mails->updDraft(array(
                "mailid"    => intval(clean_input($_PUT["mailid"])),
                "accountid" => intval(clean_input($_PUT["accountid"])),
                "subject"   => clean_input($_PUT["subject"]),
                "body"      => clean_input($_PUT["body"])
            ));
            break;

        case "DELETE":
            parse_str(file_get_contents("php://input"), $_DELETE);
            $mailid    = intval($_DELETE["mailid"]);
            $accountid = intval($_DELETE["accountid"]);
            $result    = $mails->delDraft($mailid, $accountid);
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
