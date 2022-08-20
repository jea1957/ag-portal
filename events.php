<?php

require_once __DIR__ . '/src/require_all.php';
session_write_close(); // No write access needed anymore
require_once __DIR__ . '/src/events.php';

$events = new Events($pdo);

try {
    switch($_SERVER["REQUEST_METHOD"]) {
        case "GET":
            //error_log("./events.php GET: " . print_r($_GET, 1));
            if (isset($_GET["start"]) && isset($_GET["end"])) {
                $utc_start = localtime2UTC($_GET["start"]);
                $utc_end = localtime2UTC($_GET["end"]);
                $result = $events->getRange($utc_start, $utc_end);
                //error_log("result: " . print_r($result, 1));
            } else {
                $result["error"] = "Wrong parameter";
                //error_log("res: " . print_r($result, 1));
            }
            break;

        case "POST":
            //error_log("./events.php POST: " . print_r($_POST, 1));
            $result = $events->insertEvent(array(
                "type"        => $_POST["type"],
                "title"       => $_POST["title"],
                "note"        => $_POST["note"],
                "start"       => localtime2UTC($_POST["start"]),
                "end"         => localtime2UTC($_POST["end"]),
                "duration"    => $_POST["duration"],
                "isallday"    => ($_POST["isallday"] == "true") ? true : false,
                "isrecurring" => ($_POST["isrecurring"] == "true") ? true : false,
                "rrule"       => $_POST["rrule"]
            ));
            break;

        case "PUT":
            parse_str(file_get_contents("php://input"), $_PUT);

            //error_log("./events.php PUT: " . print_r($_PUT, 1));
            $result = $events->updateEvent(array(
                "eventid"     => $_PUT["eventid"],
                "type"        => $_PUT["type"],
                "title"       => $_PUT["title"],
                "note"        => $_PUT["note"],
                "start"       => localtime2UTC($_PUT["start"]),
                "end"         => localtime2UTC($_PUT["end"]),
                "duration"    => $_PUT["duration"],
                "isallday"    => ($_PUT["isallday"] == "true") ? true : false,
                "isrecurring" => ($_PUT["isrecurring"] == "true") ? true : false,
                "rrule"       => $_PUT["rrule"]
            ));
            break;

        case "DELETE":
            parse_str(file_get_contents("php://input"), $_DELETE);

            $result = $events->removeEvent(intval($_DELETE["eventid"]));
            //error_log("remove: " . $_DELETE["eventid"] . ", count: " . $result ) ;
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
