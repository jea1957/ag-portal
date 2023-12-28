<?php

/* Require this script first in all scripts.
 * These parameters can be set before require_once:
 * $session_bypass_login = true: Keep going if not logged in
 * $session_redirect           : Where to go if not logged in
 */

$cookie_samesite = 'Strict'; // One of  'None', 'Lax' or 'Strict'

session_name('portal-session-id');

$session_options = [
    'cookie_path' => '/',
    'cookie_secure' => true,
    'cookie_httponly' => true,
    'cookie_samesite' => $cookie_samesite
];

session_start($session_options);

if (empty($session_bypass_login)) {
    if (empty($_SESSION['authenticated'])) {
        if (isset($session_redirect)) {
            header("Location: $session_redirect");
        } else {
            http_response_code(404);
            require_once __DIR__ . '/404.php';
        }
        exit;
    }
}

/* Require everything needed for scripts */
require_once __DIR__ . '/lang_da.php';
require_once __DIR__ . '/utils.php';
require_once __DIR__ . '/pdo.php';

?>
