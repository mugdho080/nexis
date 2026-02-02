<?php
$DB_HOST = getenv('DB_HOST') ?: '127.0.0.1';
$DB_USER = getenv('DB_USER') ?: 'root';
$DB_PASS = getenv('DB_PASS') ?: '';
$DB_NAME = getenv('DB_NAME') ?: 'ndis_plan_manager';
$DB_PORT = getenv('DB_PORT') ?: 3306;

$mysqli = @new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME, (int) $DB_PORT);
if ($mysqli->connect_errno) {
    $mysqli = null;
}
?>
