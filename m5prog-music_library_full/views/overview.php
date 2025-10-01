<?php
require_once dirname(__DIR__) . '/source/database.php';
$pageTitle = 'Overview';
include __DIR__ . '/header.php';

$path = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
$parts = $path === '' ? [] : explode('/', $path);
$list = [];

if (count($parts) === 2 && $parts[0] === 'artist') {
  $list = fetch_singles_by_artist_slug($parts[1]);
  $pageTitle = 'Artist';
} elseif (count($parts) === 2 && $parts[0] === 'genre') {
  $list = fetch_singles_by_genre_slug($parts[1]);
  $pageTitle = 'Genre';
} else {
  $list = fetch_all_singles();
}

if (!$list) {
  echo '<p>No results.</p>';
} else {
  echo '<div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">';
  foreach ($list as $single) {
    include __DIR__ . '/card.php';
  }
  echo '</div>';
}

include __DIR__ . '/footer.php';
