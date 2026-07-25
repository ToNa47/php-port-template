<?php
declare(strict_types=1);

/**
 * Database credentials come from environment variables so nothing
 * sensitive is committed to source control. Set these on your server
 * (see .env.example) — the values below are only local-dev fallbacks.
 */
$host     = getenv('DB_HOST') ?: 'localhost';
$user     = getenv('DB_USER') ?: 'root';
$password = getenv('DB_PASS') ?: '';
$database = getenv('DB_NAME') ?: 'my';

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $con = mysqli_connect($host, $user, $password, $database);
    $con->set_charset('utf8mb4');
} catch (mysqli_sql_exception $e) {
    // Log the real error for the developer, but never show DB internals to visitors.
    error_log('Database connection failed: ' . $e->getMessage());
    http_response_code(500);
    die('Maaf, sedang ada gangguan pada server. Silakan coba lagi nanti.');
}
