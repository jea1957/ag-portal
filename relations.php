<?php

require_once __DIR__ . '/src/require_all.php';
require_once __DIR__ . '/src/relations.php';

$rel = new Relations($pdo, $table);

try {
    switch($_SERVER["REQUEST_METHOD"]) {
        case "GET":
            $result = $rel->getAll(array(
                "personid"   => clean_input($_GET["personid"]),
                "id"         => clean_input($_GET["id"]),
                "historical" => (clean_input($_GET["historical"]) == "true") ? 1 : 0
            ));
            break;

        case "POST":
            $result = $rel->insert(array(
                "personid" => intval(clean_input($_POST["personid"])),
                "id"       => intval(clean_input($_POST["id"])),
                "relation" => empty($_POST["relation"]) ? 3 : intval(clean_input($_POST["relation"])),
                "started"  => clean_input($_POST["started"]),
                "stopped"  => clean_input($_POST["stopped"])
            ));
            break;

        case "PUT":
            parse_str(file_get_contents("php://input"), $_PUT);

            $result = $rel->update(array(
                "personid" => intval(clean_input($_PUT["personid"])),
                "id"       => intval(clean_input($_PUT["id"])),
                "relation" => empty($_PUT["relation"]) ? 3 : intval(clean_input($_PUT["relation"])),
                "started"  => clean_input($_PUT["started"]),
                "stopped"  => clean_input($_PUT["stopped"])
            ));
            break;

        case "DELETE":
            parse_str(file_get_contents("php://input"), $_DELETE);

            $result = $rel->remove(array(
                "personid" => intval(clean_input($_DELETE["personid"])),
                "id"       => intval(clean_input($_DELETE["id"]))
            ));
            break;
    }

    header("Content-Type: application/json");
    echo json_encode($result);

} catch (PDOException $e) {
    http_response_code(500);
    header("Content-Type: application/json");
    $result = $e->getMessage();
    echo json_encode($result);
}

?>
