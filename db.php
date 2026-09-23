<?php
declare(strict_types=1);

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

function db(): mysqli
{
    static $connection = null;
    if ($connection instanceof mysqli) {
        return $connection;
    }

    $connection = new mysqli(
        getenv('ESAS_DB_HOST') ?: '127.0.0.1',
        getenv('ESAS_DB_USER') ?: 'root',
        getenv('ESAS_DB_PASSWORD') ?: '',
        getenv('ESAS_DB_NAME') ?: 'esas'
    );
    $connection->set_charset('utf8mb4');
    return $connection;
}
