<?php

require_once __DIR__ . '/src/require_all.php';
session_write_close(); // No write access needed anymore
require_once __DIR__ . '/src/depots.php';

$depots = new Depots($pdo);

try {
    switch($_SERVER["REQUEST_METHOD"]) {
        case "GET":
            $result = $depots->getWait();
            break;

        case "POST":
            $result = $depots->addWait(intval(clean_input($_POST["personid"])));
            break;

        case "DELETE":
            parse_str(file_get_contents("php://input"), $_DELETE);
            $result = $depots->delWait(intval($_DELETE["waitid"]));
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
