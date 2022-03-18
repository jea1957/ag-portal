<?php

require_once __DIR__ . '/src/require_all.php';
require_once __DIR__ . '/src/accounts.php';

$accounts = new Accounts($pdo);

try {
    switch($_SERVER["REQUEST_METHOD"]) {
        case "PUT":
            parse_str(file_get_contents("php://input"), $_PUT);
            $account_id = intval(clean_input($_PUT["account_id"]));
            $accounts->update_activity($account_id);
            $result = "OK";
    }

    header("Content-Type: application/json");
    echo json_encode($result);

} catch (PDOException $e) {
    http_response_code(500);
    header("Content-Type: application/json");
    echo json_encode($e->getMessage());
}

?>
