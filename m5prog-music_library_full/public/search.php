<?php
require_once dirname(__DIR__) . '/source/database.php';
$q = isset($_GET['searchquery']) ? trim($_GET['searchquery']) : '';
$pageTitle = 'Search';
include dirname(__DIR__) . '/views/header.php';

if ($q === '') {
  echo '<p>Please enter a search term.</p>';
  include dirname(__DIR__) . '/views/footer.php';
  exit;
}

$results = search_singles($q);
if (!$results) {
  echo '<p>No results for <strong>' . htmlspecialchars($q) . '</strong>.</p>';
} else {
  echo '<p>Results for <strong>' . htmlspecialchars($q) . '</strong>:</p>';
  echo '<div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">';
  foreach ($results as $single) {
    include dirname(__DIR__) . '/views/card.php';
  }
  echo '</div>';
}
include dirname(__DIR__) . '/views/footer.php';
