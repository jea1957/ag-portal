<?php

require_once __DIR__ . '/src/require_all.php';
require_once __DIR__ . '/src/parkings.php';

$parkings = new Parkings($pdo);


try {
    switch($_SERVER["REQUEST_METHOD"]) {
        case "GET":
            if (isset($_GET["depot"])) {
                $depot = (clean_input($_GET["depot"]) == "true") ? true : false;
            } else {
                $depot = null; // wildcard
            }
            if (isset($_GET["charger"])) {
                $charger = (clean_input($_GET["charger"]) == "true") ? true : false;
            } else {
                $charger = null; // wildcard
            }
            $result = $parkings->getFiltered(array(
                "parkingid"  => clean_input($_GET["parkingid"]),
                "depot"      => $depot,
                "charger"    => $charger,
                "owner"      => (clean_input($_GET["owner"]) == "true") ? 1 : 0,
                "extern"     => (clean_input($_GET["extern"]) == "true") ? 1 : 0,
                "tenant"     => (clean_input($_GET["tenant"]) == "true") ? 1 : 0,
                "historical" => (clean_input($_GET["historical"]) == "true") ? 1 : 0
            ));
            break;

        case "POST":
            $result = $parkings->insert(array(
                "parkingid" => intval(clean_input($_POST["parkingid"])),
                "depot"     => (clean_input($_POST["depot"]) == "true") ? true : false,
                "charger"   => (clean_input($_POST["charger"]) == "true") ? true : false
            ));
            break;

        case "PUT":
            parse_str(file_get_contents("php://input"), $_PUT);

            $result = $parkings->update(array(
                "parkingid" => intval(clean_input($_PUT["parkingid"])),
                "depot"     => (clean_input($_PUT["depot"]) == "true") ? true : false,
                "charger"   => (clean_input($_PUT["charger"]) == "true") ? true : false
            ));
            break;

        case "DELETE":
            parse_str(file_get_contents("php://input"), $_DELETE);

            $result = $parkings->remove(intval($_DELETE["parkingid"]));
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
