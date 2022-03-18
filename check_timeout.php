<?php

require_once __DIR__ . '/src/require_all.php';
require_once __DIR__ . '/src/accounts.php';

$accounts = new Accounts($pdo);

try {
    if (isset($_SESSION['account_id'])) {
        $account_id = $_SESSION['account_id'];
        $time = $accounts->get_activity($account_id);
        $dtA = new DateTime($time);
        $dtA->add(new DateInterval('PT2H')); // Log out if more than 2 hours since last activity
        $dtB = new DateTime('NOW');
        if ($dtA < $dtB) {
            error_log('Activity timeout');
            destroy_session();
            header("Location: .");
            exit;
        }
    }

} catch (PDOException $e) {
    http_response_code(500);
    header("Content-Type: application/json");
    echo json_encode($e->getMessage());
}

?>
