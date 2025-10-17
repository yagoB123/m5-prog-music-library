<?php
$s = $single;
$is_in_library = $s['in_library'] ?? false;
?>
<div class="track-card">
  <div class="track-card__no-image">
    <i class="fas fa-music"></i>
    <?php if (isset($s['relevance_percent']) && $s['relevance_percent'] < 100): ?>
      <span class="track-card__relevance"><?= $s['relevance_percent'] ?>%</span>
    <?php endif; ?>
  </div>
  <div class="track-card__info">
    <h3 class="track-card__title">
      <a href="/single/<?= htmlspecialchars($s['slug']) ?>"><?= htmlspecialchars($s['title']) ?></a>
    </h3>
    <div class="track-card__meta">
      <a href="/artist/<?= htmlspecialchars($s['artist_slug']) ?>" class="track-card__artist">
        <?= htmlspecialchars($s['artist_name']) ?>
      </a>
      <span class="track-card__divider">•</span>
      <span class="track-card__year"><?= (int)$s['release_year'] ?></span>
    </div>
    <button class="track-card__play-button" aria-label="Play <?= htmlspecialchars($s['title']) ?>">
      <i class="fas fa-play"></i>
    </button>
  </div>
  <form method="post" action="/library.php" class="library-action-form">
    <input type="hidden" name="single_id" value="<?= $s['id'] ?>">
    <button type="submit" 
            name="action" 
            value="<?= $is_in_library ? 'remove' : 'add' ?>" 
            class="btn--icon library-action-button <?= $is_in_library ? 'in-library' : '' ?>"
            aria-label="<?= $is_in_library ? 'Remove from library' : 'Add to library' ?>"
            title="<?= $is_in_library ? 'Remove from library' : 'Add to library' ?>">
      <i class="<?= $is_in_library ? 'fas' : 'far' ?> fa-heart"></i>
    </button>
  </form>
</div>
