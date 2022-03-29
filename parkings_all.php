<?php

require_once __DIR__ . '/src/require_all.php';
session_write_close(); // No write access needed anymore
require_once __DIR__ . '/src/parkings.php';

$parkings = new Parkings($pdo);


try {
    switch($_SERVER["REQUEST_METHOD"]) {
        case "GET":
            $result = $parkings->getAll();
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
