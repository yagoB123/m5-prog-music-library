<?php
$path = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
if ($path === '' || $path === 'overview') {
  require_once dirname(__DIR__) . '/views/overview.php';
  exit;
}
$parts = explode('/', $path);
if ($parts[0] === 'single' && isset($parts[1])) {
  require_once dirname(__DIR__) . '/views/single.php';
  exit;
}
if ($parts[0] === 'artist' || $parts[0] === 'genre') {
  require_once dirname(__DIR__) . '/views/overview.php';
  exit;
}
http_response_code(404);
$pageTitle = 'Not Found';
include dirname(__DIR__) . '/views/header.php';
echo '<p>Page not found.</p>';
include dirname(__DIR__) . '/views/footer.php';
