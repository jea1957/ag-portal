<?php

require_once __DIR__ . '/src/require_all.php';
session_write_close(); // No write access needed anymore
require_once __DIR__ . '/src/settings.php';

$settings = new Settings($pdo);

try {
    switch($_SERVER["REQUEST_METHOD"]) {
        case "GET":
            $result = $settings->get();
            break;

        case "PUT":
            parse_str(file_get_contents("php://input"), $_PUT);

            $result = $settings->update(array(
                "smtp_username" => clean_input($_PUT["smtp_username"]),
                "smtp_password" => clean_input($_PUT["smtp_password"])
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
