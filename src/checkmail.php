<?php

require_once __DIR__ . "/utils.php";
require_once __DIR__ . "/pdo.php";

$mails_sent = 8; // TODO
$info = $_SERVER['REMOTE_USER'] . ':' . $_SERVER['REMOTE_ADDR'] . ':' . $_SERVER['REMOTE_PORT'];

try {
    $sql = "UPDATE MailCheck SET MailsSent = ?, Info = ? WHERE CheckId = 1";
    $q = $pdo->prepare($sql);
    $q->execute([$mails_sent, $info]);
    $rc = $q->rowCount();
    if ($rc != 1) {
        echo 'ERROR';
    } else {
        echo 'OK';
    }
} catch (PDOException $e) {
    echo 'ERROR';
}

?>
