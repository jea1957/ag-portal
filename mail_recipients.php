<?php

require_once __DIR__ . '/src/require_all.php';
session_write_close(); // No write access needed anymore
require_once __DIR__ . '/src/mails.php';

$mails = new Mails($pdo);

try {
    switch($_SERVER["REQUEST_METHOD"]) {
        case "GET":
            $result = $mails->getMailRecipients(intval(clean_input($_GET["mailid"])));
            break;
        case "POST":
            $data = [
                "mailid"    => intval(clean_input($_POST["mailid"])),
                "accountid" => intval(clean_input($_POST["accountid"]))
            ];
            if (isset($_POST["apartments"])) {
                $p = $_POST["apartments"];
                $data["apartments"] = [
                    "apartmentid" => clean_input($p["apartmentid"]),
                    "number"      => clean_input($p["number"]),
                    "floor"       => clean_input($p["floor"]),
                    "side"        => clean_input($p["side"]),
                    "type"        => clean_input($p["type"]),
                    "size"        => clean_input($p["size"]),
                    "reduction"   => clean_input($p["reduction"]),
                    "tapshares"   => clean_input($p["tapshares"]),
                    "shafts"      => clean_input($p["shafts"]),
                    "owner"       => (clean_input($p["owner"]) == "true") ? 1 : 0,
                    "extern"      => (clean_input($p["extern"]) == "true") ? 1 : 0,
                    "tenant"      => (clean_input($p["tenant"]) == "true") ? 1 : 0,
                    "historical"  => (clean_input($p["historical"]) == "true") ? 1 : 0
                ];
            }
            if (isset($_POST["parkings"])) {
                $p = $_POST["parkings"];
                if (isset($p["depot"])) {
                    $depot = (clean_input($p["depot"]) == "true") ? true : false;
                } else {
                    $depot = null; // wildcard
                }
                if (isset($p["charger"])) {
                    $charger = (clean_input($p["charger"]) == "true") ? true : false;
                } else {
                    $charger = null; // wildcard
                }
                if (isset($p["ccharger"])) {
                    $ccharger = (clean_input($p["ccharger"]) == "true") ? true : false;
                } else {
                    $ccharger = null; // wildcard
                }
                $data["parkings"] = [
                    "parkingid"  => clean_input($p["parkingid"]),
                    "depot"      => $depot,
                    "charger"    => $charger,
                    "ccharger"   => $ccharger,
                    "owner"      => (clean_input($p["owner"]) == "true") ? 1 : 0,
                    "extern"     => (clean_input($p["extern"]) == "true") ? 1 : 0,
                    "tenant"     => (clean_input($p["tenant"]) == "true") ? 1 : 0,
                    "historical" => (clean_input($p["historical"]) == "true") ? 1 : 0
                ];
            }
            if (isset($_POST["depots"])) {
                $p = $_POST["depots"];
                $data["depots"] = [
                    "depotid"    => clean_input($p["depotid"]),
                    "number"     => clean_input($p["number"]),
                    "historical" => (clean_input($p["historical"]) == "true") ? 1 : 0
                ];
            }
            if (isset($_POST["persons"])) {
                $p = $_POST["persons"];
                if (isset($p["nomails"])) {
                    $nomails = (clean_input($p["nomails"]) == "true") ? true : false;
                } else {
                    $nomails = null; // wildcard
                }
                $data["persons"] = [
                    "name"       => clean_input($p["name"]),
                    "address"    => clean_input($p["address"]),
                    "email"      => clean_input($p["email"]),
                    "phone"      => clean_input($p["phone"]),
                    "nomails"    => $nomails
                ];
            }
            if (isset($_POST["board"])) {
                $data["board"] = 1;
            }
            if (isset($_POST["caretaker"])) {
                $data["caretaker"] = 1;
            }
            if (isset($_POST["administrator"])) {
                $data["administrator"] = 1;
            }
            $result = $mails->setMailRecipients($data);
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
