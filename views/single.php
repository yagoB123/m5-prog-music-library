<?php
require_once dirname(__DIR__) . '/source/database.php';
$path = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
$parts = $path === '' ? [] : explode('/', $path);
$slug = isset($parts[1]) ? $parts[1] : null;
$single = $slug ? fetch_single_by_slug($slug) : null;
$pageTitle = $single ? $single['title'] : 'Single';
include __DIR__ . '/header.php';

if (!$single) {
  echo '<p>Single not found.</p>';
  include __DIR__ . '/footer.php';
  exit;
}
?>
<div class="row">
  <div class="col-md-5">
    <img src="<?= htmlspecialchars($single['track_image']) ?>" alt="<?= htmlspecialchars($single['title']) ?>" class="img-fluid rounded shadow-sm">
  </div>
  <div class="col-md-7">
    <h1 class="h3"><?= htmlspecialchars($single['title']) ?></h1>
    <p class="mb-2">
      <a href="/artist/<?= htmlspecialchars($single['artist_slug']) ?>" class="link-secondary text-decoration-none"><?= htmlspecialchars($single['artist_name']) ?></a>
      —
      <a href="/genre/<?= htmlspecialchars($single['genre_slug']) ?>" class="link-secondary text-decoration-none"><?= htmlspecialchars($single['genre_name']) ?></a>
    </p>
    <ul class="list-unstyled">
      <li>Release year: <?= (int)$single['release_year'] ?></li>
      <li>Duration: <?= (int)$single['duration_seconds'] ?> seconds</li>
    </ul>
    <img src="<?= htmlspecialchars($single['artist_image']) ?>" alt="<?= htmlspecialchars($single['artist_name']) ?>" class="img-fluid rounded" style="max-width:260px">
  </div>
</div>
<?php include __DIR__ . '/footer.php'; ?> 
