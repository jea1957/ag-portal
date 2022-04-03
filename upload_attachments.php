<?php

require_once __DIR__ . '/src/require_all.php';
session_write_close(); // No write access needed anymore
require_once __DIR__ . '/src/mails.php';

$mails = new Mails($pdo);

try {
    switch($_SERVER["REQUEST_METHOD"]) {
        case "GET":
            error_log("GET". print_r($_GET, 1));
            $result = 1;
            break;

        case "POST":
            $mailid = intval(clean_input($_POST["mailid"]));
            error_log("mailid: $mailid");
            $num_files = count($_FILES['file']['name']);
            for ($i = 0; $i < $num_files; $i++) {
                error_log("name: " . $_FILES['file']['name'][$i] . " type: " . $_FILES['file']['type'][$i] . " tmp_name: " . $_FILES['file']['tmp_name'][$i] .
                " error: " . $_FILES['file']['error'][$i] . " size: " . $_FILES['file']['size'][$i]);
            }
            
            //error_log("POST". print_r($_POST, 1));
            //error_log("FILES". print_r($_FILES, 1));
            $result = 2;
            break;

        case "DELETE":
            parse_str(file_get_contents("php://input"), $_DELETE);
            error_log("DELETE". print_r($_DELETE, 1));
            $result = 3;
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
