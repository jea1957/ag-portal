<?php

require_once __DIR__ . '/src/require_all.php';

if (clean_input($_REQUEST['account_id'])) {
    error_log('Logout: account_id '. print_r($_REQUEST['account_id'], 1));
    destroy_session();
}

?>
