<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$envPath = dirname(__DIR__) . '/.env';
$env = [];
if (file_exists($envPath)) {
  $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
  foreach ($lines as $line) {
    if (str_starts_with(trim($line), '#')) continue;
    $parts = explode('=', $line, 2);
    if (count($parts) === 2) {
      $env[trim($parts[0])] = trim($parts[1]);
    }
  }
}

define('DB_HOST', $env['DB_HOST'] ?? 'db');
define('DB_PORT', (int)($env['DB_PORT'] ?? 3306));
define('DB_NAME', $env['DB_NAME'] ?? 'music_library');
define('DB_USERNAME', $env['DB_USERNAME'] ?? 'app');
define('DB_PASSWORD', $env['DB_PASSWORD'] ?? 'app');
