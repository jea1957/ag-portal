<?php

require_once __DIR__ . '/src/require_all.php';
require_once __DIR__ . '/src/apartments.php';

$apartments = new Apartments($pdo);

try {
    switch($_SERVER["REQUEST_METHOD"]) {
        case "GET":
            $result = $apartments->getFiltered(array(
                "apartmentid" => clean_input($_GET["apartmentid"]),
                "number"      => clean_input($_GET["number"]),
                "floor"       => clean_input($_GET["floor"]),
                "side"        => clean_input($_GET["side"]),
                "type"        => clean_input($_GET["type"]),
                "size"        => clean_input($_GET["size"]),
                "reduction"   => clean_input($_GET["reduction"]),
                "tapshares"   => clean_input($_GET["tapshares"]),
                "shafts"      => clean_input($_GET["shafts"]),
                "owner"       => (clean_input($_GET["owner"]) == "true") ? 1 : 0,
                "extern"      => (clean_input($_GET["extern"]) == "true") ? 1 : 0,
                "tenant"      => (clean_input($_GET["tenant"]) == "true") ? 1 : 0,
                "historical"  => (clean_input($_GET["historical"]) == "true") ? 1 : 0
            ));
            break;

        case "POST":
            $result = $apartments->insert(array(
                "apartmentid" => intval(clean_input($_POST["apartmentid"])),
                "number"      => clean_input($_POST["number"]),
                "floor"       => clean_input($_POST["floor"]),
                "side"        => clean_input($_POST["side"]),
                "type"        => clean_input($_POST["type"]),
                "size"        => clean_input($_POST["size"]),
                "reduction"   => clean_input($_POST["reduction"]),
                "tapshares"   => clean_input($_POST["tapshares"]),
                "shafts"      => clean_input($_POST["shafts"])
            ));
            break;

        case "PUT":
            parse_str(file_get_contents("php://input"), $_PUT);

            $result = $apartments->update(array(
                "apartmentid" => intval(clean_input($_PUT["apartmentid"])),
                "number"      => clean_input($_PUT["number"]),
                "floor"       => clean_input($_PUT["floor"]),
                "side"        => clean_input($_PUT["side"]),
                "type"        => clean_input($_PUT["type"]),
                "size"        => clean_input($_PUT["size"]),
                "reduction"   => clean_input($_PUT["reduction"]),
                "tapshares"   => clean_input($_PUT["tapshares"]),
                "shafts"      => clean_input($_PUT["shafts"])
            ));
            break;

        case "DELETE":
            parse_str(file_get_contents("php://input"), $_DELETE);

            $result = $apartments->remove(intval($_DELETE["apartmentid"]));
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
