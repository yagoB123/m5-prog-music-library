<?php
require_once __DIR__ . '/config.php';

function db() {
  static $mysqli = null;
  if ($mysqli === null) {
    $mysqli = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME, DB_PORT);
    if ($mysqli->connect_errno) {
      http_response_code(500);
      die('Database connection failed');
    }
    $mysqli->set_charset('utf8mb4');
  }
  return $mysqli;
}

function fetch_all_singles() {
  $sql = "SELECT s.id, s.title, s.slug, s.duration_seconds, s.release_year, s.track_image,
                 a.name AS artist_name, a.slug AS artist_slug,
                 g.name AS genre_name, g.slug AS genre_slug
          FROM singles s
          JOIN artists a ON a.id = s.artist_id
          JOIN genres g ON g.id = s.genre_id
          ORDER BY s.title ASC";
  $stmt = db()->prepare($sql);
  $stmt->execute();
  $res = $stmt->get_result();
  return $res->fetch_all(MYSQLI_ASSOC);
}

function fetch_single_by_slug($slug) {
  $sql = "SELECT s.id, s.title, s.slug, s.duration_seconds, s.release_year, s.track_image,
                 a.id AS artist_id, a.name AS artist_name, a.slug AS artist_slug, a.image AS artist_image,
                 g.id AS genre_id, g.name AS genre_name, g.slug AS genre_slug
          FROM singles s
          JOIN artists a ON a.id = s.artist_id
          JOIN genres g ON g.id = s.genre_id
          WHERE s.slug = ?
          LIMIT 1";
  $stmt = db()->prepare($sql);
  $stmt->bind_param('s', $slug);
  $stmt->execute();
  $res = $stmt->get_result();
  return $res->fetch_assoc();
}

function fetch_singles_by_artist_slug($artistSlug) {
  $sql = "SELECT s.id, s.title, s.slug, s.duration_seconds, s.release_year, s.track_image,
                 a.name AS artist_name, a.slug AS artist_slug,
                 g.name AS genre_name, g.slug AS genre_slug
          FROM singles s
          JOIN artists a ON a.id = s.artist_id
          JOIN genres g ON g.id = s.genre_id
          WHERE a.slug = ?
          ORDER BY s.title ASC";
  $stmt = db()->prepare($sql);
  $stmt->bind_param('s', $artistSlug);
  $stmt->execute();
  $res = $stmt->get_result();
  return $res->fetch_all(MYSQLI_ASSOC);
}

function fetch_singles_by_genre_slug($genreSlug) {
  $sql = "SELECT s.id, s.title, s.slug, s.duration_seconds, s.release_year, s.track_image,
                 a.name AS artist_name, a.slug AS artist_slug,
                 g.name AS genre_name, g.slug AS genre_slug
          FROM singles s
          JOIN artists a ON a.id = s.artist_id
          JOIN genres g ON g.id = s.genre_id
          WHERE g.slug = ?
          ORDER BY s.title ASC";
  $stmt = db()->prepare($sql);
  $stmt->bind_param('s', $genreSlug);
  $stmt->execute();
  $res = $stmt->get_result();
  return $res->fetch_all(MYSQLI_ASSOC);
}

function search_singles($term) {
  $like = '%' . $term . '%';
  $sql = "SELECT s.id, s.title, s.slug, s.duration_seconds, s.release_year, s.track_image,
                 a.name AS artist_name, a.slug AS artist_slug,
                 g.name AS genre_name, g.slug AS genre_slug
          FROM singles s
          JOIN artists a ON a.id = s.artist_id
          JOIN genres g ON g.id = s.genre_id
          WHERE s.title LIKE ?
          ORDER BY s.title ASC";
  $stmt = db()->prepare($sql);
  $stmt->bind_param('s', $like);
  $stmt->execute();
  $res = $stmt->get_result();
  return $res->fetch_all(MYSQLI_ASSOC);
}
