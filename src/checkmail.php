<?php

// This is supposed to be called via an external cron job
require_once __DIR__ . '/utils.php';
require_once __DIR__ . '/lang_da.php';
require_once __DIR__ . '/pdo.php';
require_once __DIR__ . '/mails.php';

$mails = new Mails($pdo);

try {
    $result = $mails->sendMail();
    echo 'STATUS: '. $result;
} catch (PDOException $e) {
    echo 'ERROR';
}

?>
