<?php

require_once __DIR__ . '/src/require_all.php';
session_write_close(); // No write access needed anymore
require_once __DIR__ . '/src/mails.php';

$mails = new Mails($pdo);

if ($_SERVER["REQUEST_METHOD"] === 'GET' && isset($_GET["id"])) {
    $id = intval(clean_input($_GET["id"]));
    $result = $mails->getAttachmentFile($id);
    if ($result) {
        header('Content-Type: ' . $result->type); 
        header('Content-Disposition: inline; filename="' . $result->name . '"');
        header('Content-Transfer-Encoding: binary');
        header('Accept-Ranges: bytes');
        echo $result->file;
        exit(0);
    }
}

http_response_code(404);
require_once __DIR__ . '/src/404.php';

?>
