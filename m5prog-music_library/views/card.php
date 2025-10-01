<?php
$s = $single;
?>
<div class="col">
  <div class="card h-100">
    <img src="<?= htmlspecialchars($s['track_image']) ?>" class="card-img-top" alt="<?= htmlspecialchars($s['title']) ?>">
    <div class="card-body">
      <h5 class="card-title mb-1">
        <a href="/single/<?= htmlspecialchars($s['slug']) ?>"><?= htmlspecialchars($s['title']) ?></a>
      </h5>
      <p class="mb-2">
        <a href="/artist/<?= htmlspecialchars($s['artist_slug']) ?>" class="link-secondary text-decoration-none"><?= htmlspecialchars($s['artist_name']) ?></a>
        —
        <a href="/genre/<?= htmlspecialchars($s['genre_slug']) ?>" class="link-secondary text-decoration-none"><?= htmlspecialchars($s['genre_name']) ?></a>
      </p>
      <p class="text-muted mb-0">Year <?= (int)$s['release_year'] ?> · <?= (int)$s['duration_seconds'] ?>s</p>
    </div>
  </div>
</div>
