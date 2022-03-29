<?php

require_once __DIR__ . '/src/require_all.php';
session_write_close(); // No write access needed anymore
require_once __DIR__ . '/src/persons.php';

$persons = new Persons($pdo);

try {
    switch($_SERVER["REQUEST_METHOD"]) {
        case "GET":
            if (isset($_GET["nomails"])) {
                $nomails = (clean_input($_GET["nomails"]) == "true") ? true : false;
            } else {
                $nomails = null; // wildcard
            }
            $result = $persons->getFiltered(array(
                "name"       => clean_input($_GET["name"]),
                "address"    => clean_input($_GET["address"]),
                "email"      => clean_input($_GET["email"]),
                "phone"      => clean_input($_GET["phone"]),
                "nomails"    => $nomails
            ));
            break;

        case "POST":
            $result = $persons->insert(array(
                "name"    => clean_input($_POST["name"]),
                "address" => clean_input($_POST["address"]),
                "email"   => clean_input($_POST["email"]),
                "phone"   => clean_input($_POST["phone"]),
                "nomails" => (clean_input($_POST["nomails"]) == "true") ? true : false
            ));
            break;

        case "PUT":
            parse_str(file_get_contents("php://input"), $_PUT);

            $result = $persons->update(array(
                "personid" => intval(clean_input($_PUT["personid"])),
                "name"     => clean_input($_PUT["name"]),
                "address"  => clean_input($_PUT["address"]),
                "email"    => clean_input($_PUT["email"]),
                "phone"    => clean_input($_PUT["phone"]),
                "nomails" => (clean_input($_PUT["nomails"]) == "true") ? true : false
            ));
            break;

        case "DELETE":
            parse_str(file_get_contents("php://input"), $_DELETE);

            $result = $persons->remove(intval($_DELETE["personid"]));
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
