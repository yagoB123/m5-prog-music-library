<?php
require_once dirname(__DIR__) . '/source/database.php';

// Use a default user ID since we're not using authentication
$default_user_id = 1;
$pageTitle = 'Music Library';

// Handle adding/removing from library
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && isset($_POST['single_id'])) {
    $single_id = (int)$_POST['single_id'];
    
    if ($_POST['action'] === 'add') {
        // Add to library
        $sql = "INSERT IGNORE INTO user_library (user_id, single_id) VALUES (?, ?)";
        $stmt = db()->prepare($sql);
        $stmt->bind_param('ii', $default_user_id, $single_id);
        $stmt->execute();
    } elseif ($_POST['action'] === 'remove') {
        // Remove from library
        $sql = "DELETE FROM user_library WHERE user_id = ? AND single_id = ?";
        $stmt = db()->prepare($sql);
        $stmt->bind_param('ii', $default_user_id, $single_id);
        $stmt->execute();
    }
    
    // Redirect to avoid form resubmission
    header('Location: ' . $_SERVER['REQUEST_URI']);
    exit();
}

// Get library tracks for the default user
$sql = "SELECT s.*, a.name AS artist_name, a.slug AS artist_slug, 
               g.name AS genre_name, g.slug AS genre_slug,
               TRUE AS in_library
        FROM user_library ul
        JOIN singles s ON ul.single_id = s.id
        JOIN artists a ON s.artist_id = a.id
        JOIN genres g ON s.genre_id = g.id
        WHERE ul.user_id = ?
        ORDER BY ul.added_at DESC";
$stmt = db()->prepare($sql);
$stmt->bind_param('i', $default_user_id);
$stmt->execute();
$library_tracks = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

include dirname(__DIR__) . '/views/header.php';
?>

<div class="library-container">
    <div class="library-header">
        <h1>Music Library</h1>
        <?php if (empty($library_tracks)): ?>
            <p class="empty-library">Your library is empty. Start adding tracks to see them here!</p>
        <?php endif; ?>
    </div>

    <div class="search-results-grid">
        <?php foreach ($library_tracks as $track): ?>
            <div class="track-card">
                <div class="track-card__no-image">
                    <i class="fas fa-music"></i>
                </div>
                <div class="track-card__info">
                    <h3 class="track-card__title">
                        <a href="/single/<?= htmlspecialchars($track['slug']) ?>">
                            <?= htmlspecialchars($track['title']) ?>
                        </a>
                    </h3>
                    <div class="track-card__meta">
                        <a href="/artist/<?= htmlspecialchars($track['artist_slug']) ?>" class="track-card__artist">
                            <?= htmlspecialchars($track['artist_name']) ?>
                        </a>
                        <span class="track-card__divider">•</span>
                        <span class="track-card__year"><?= (int)$track['release_year'] ?></span>
                    </div>
                    <button class="track-card__play-button" aria-label="Play <?= htmlspecialchars($track['title']) ?>">
                        <i class="fas fa-play"></i>
                    </button>
                </div>
                <form method="post" class="library-action-form">
                    <input type="hidden" name="single_id" value="<?= $track['id'] ?>">
                    <button type="submit" 
                            name="action" 
                            value="remove" 
                            class="btn--icon library-action-button in-library"
                            aria-label="Remove from library" 
                            title="Remove from library">
                        <i class="fas fa-heart"></i>
                    </button>
                </form>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php include dirname(__DIR__) . '/views/footer.php'; ?>
