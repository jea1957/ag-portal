<?php

// Edit the following variables to match your project
// Rename this file to pdo.php

$db_url     = 'https://my_page.example.com';
$db_contact = 'my_contact@example.com';
$db_cname   = 'Contact name for website';
$db_owner   = 'Association name';
$db_author  = 'Author of website';

$db_host    = 'localhost';
$db_user    = 'my_db_user';
$db_passwd  = 'my_db_password';
$db_name    = 'my_db_name';

$db_charset = 'utf8mb4';
$db_dsn     = "mysql:host=$db_host;dbname=$db_name;charset=$db_charset";
$db_options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($db_dsn, $db_user, $db_passwd, $db_options);
    $pdo->query("SET lc_messages = 'da_DK'");
} catch (PDOException $e) {
    echo 'ERROR: Database connection failed. ' . $e->getMessage();
    die();
}

?>
