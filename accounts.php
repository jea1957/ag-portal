<?php

require_once __DIR__ . '/src/require_all.php';
session_write_close(); // No write access needed anymore
require_once __DIR__ . '/src/accounts.php';

$accounts = new Accounts($pdo);

try {
    switch($_SERVER["REQUEST_METHOD"]) {
        case "GET":
            $result = $accounts->getAll();
            break;

        case "POST":
            $otp = random_password(8);
            $result = $accounts->insert(array(
                "name"      => clean_input($_POST["name"]),
                "email"     => clean_input($_POST["email"]),
                "otp"       => $otp,
                "state"     => 1, // New users are always created as 'FirstLogin'
                "role"      => intval(clean_input($_POST["role"])),
                "lang"      => intval(clean_input($_POST["lang"]))
            ));
            break;

        case "PUT":
            parse_str(file_get_contents("php://input"), $_PUT);

            $accountid = intval($_PUT["accountid"]);
            $state     = intval($_PUT["state"]);
            if (($accountid == 1) && ($state != 2)) {
                http_response_code(500);
                $result = _L("put_account_1");
            } else {
                $result = $accounts->update(array(
                    "accountid" => intval(clean_input($_PUT["accountid"])),
                    "name"      => clean_input($_PUT["name"]),
                    "state"     => intval(clean_input($_PUT["state"])),
                    "role"      => intval(clean_input($_PUT["role"])),
                    "lang"      => intval(clean_input($_PUT["lang"]))
                ));
            }
            break;

        case "DELETE":
            parse_str(file_get_contents("php://input"), $_DELETE);
            $accountid = intval($_DELETE["accountid"]);
            if ($accountid == 1) {
                http_response_code(500);
                $result = _L("del_account_1");
            } else {
                $result = $accounts->remove(intval($_DELETE["accountid"]));
            }
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
