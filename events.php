<?php

require_once __DIR__ . '/src/require_all.php';
session_write_close(); // No write access needed anymore
require_once __DIR__ . '/src/events.php';

$events = new Events($pdo);

try {
    switch($_SERVER["REQUEST_METHOD"]) {
        case "GET":
            error_log("./events.php GET: " . print_r($_GET, 1));
            if (isset($_GET["start"]) && isset($_GET["end"])) {
                $utc_start = localtime2UTC($_GET["start"]);
                $utc_end = localtime2UTC($_GET["end"]);
                $res = $events->getRange($utc_start, $utc_end);
                error_log("res: " . print_r($res, 1));
                $result = [];
                foreach($res as $r) {
                    $result[] = [
                        'id'    => strval($r->eventid),
                        'title' => $r->title,
                        'start' => str_replace(' ', 'T', $r->start).'Z', // Convert to ISO 8601 UTC format
                        'end'   => str_replace(' ', 'T', $r->end).'Z'    // E.g.: '2022-06-22T12:00:00Z'
                    ];
                }
            } else {
                $result["error"] = "Wrong parameter";
                error_log("res: " . print_r($result, 1));
            }
            break;

        case "POST":
            break;

        case "PUT":
            break;

        case "DELETE":
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
