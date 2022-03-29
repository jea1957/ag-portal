<?php

require_once __DIR__ . '/src/require_all.php';
session_write_close(); // No write access needed anymore
require_once __DIR__ . '/src/depots.php';

$depots = new Depots($pdo);


try {
    switch($_SERVER["REQUEST_METHOD"]) {
        case "GET":
            $result = $depots->getFiltered(array(
                "depotid"    => clean_input($_GET["depotid"]),
                "number"     => clean_input($_GET["number"])
            ));
            break;

        case "POST":
            $result = $depots->insert(array(
                "depotid" => intval(clean_input($_POST["depotid"])),
                "number"  => clean_input($_POST["number"])
            ));
            break;

        case "PUT":
            parse_str(file_get_contents("php://input"), $_PUT);

            $result = $depots->update(array(
                "depotid" => intval(clean_input($_PUT["depotid"])),
                "number"  => clean_input($_PUT["number"])
            ));
            break;

        case "DELETE":
            parse_str(file_get_contents("php://input"), $_DELETE);

            $result = $depots->remove(intval($_DELETE["depotid"]));
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
