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

/**
 * Calculate the similarity between two strings using Levenshtein distance
 */
function calculate_similarity($str1, $str2) {
    $str1 = mb_strtolower($str1);
    $str2 = mb_strtolower($str2);
    $len1 = mb_strlen($str1);
    $len2 = mb_strlen($str2);
    
    // If one string is empty, return 0 (no similarity)
    if ($len1 === 0 || $len2 === 0) {
        return 0;
    }
    
    // If strings are identical, return 1.0 (100% match)
    if ($str1 === $str2) {
        return 1.0;
    }
    
    // Calculate Levenshtein distance
    $distance = levenshtein($str1, $str2);
    
    // Calculate maximum possible distance
    $maxLen = max($len1, $len2);
    
    // Return similarity as a float between 0 and 1
    return 1 - ($distance / $maxLen);
}

/**
 * Search for singles with fuzzy matching
 * @param string $term Search term
 * @param float $threshold Minimum similarity threshold (0.0 to 1.0)
 * @param int $limit Maximum number of results to return
 * @return array Search results with relevance scores
 */
function search_singles($term, $threshold = 0.5, $limit = 50) {
    // First, try an exact or partial match
    $like = '%' . $term . '%';
    $sql = "SELECT s.id, s.title, s.slug, s.duration_seconds, s.release_year, s.track_image,
                   a.name AS artist_name, a.slug AS artist_slug,
                   g.name AS genre_name, g.slug AS genre_slug
            FROM singles s
            JOIN artists a ON a.id = s.artist_id
            JOIN genres g ON g.id = s.genre_id
            WHERE s.title LIKE ? OR a.name LIKE ?
            ORDER BY s.title ASC";
    
    $stmt = db()->prepare($sql);
    $stmt->bind_param('ss', $like, $like);
    $stmt->execute();
    $exactResults = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    
    // If we have exact matches, return them
    if (count($exactResults) > 0) {
        return $exactResults;
    }
    
    // If no exact matches, fetch all singles for fuzzy matching
    $allSingles = fetch_all_singles();
    $results = [];
    
    // Split search term into words
    $searchTerms = preg_split('/\s+/', $term);
    
    foreach ($allSingles as $single) {
        // Combine title and artist for better matching
        $textToSearch = $single['title'] . ' ' . $single['artist_name'];
        $bestScore = 0;
        
        // Check each search term against the combined text
        foreach ($searchTerms as $searchTerm) {
            $searchTerm = trim($searchTerm);
            if (empty($searchTerm)) continue;
            
            // Split the text into words
            $words = preg_split('/\s+/', $textToSearch);
            
            // Check each word in the text
            foreach ($words as $word) {
                $similarity = calculate_similarity($searchTerm, $word);
                if ($similarity > $bestScore) {
                    $bestScore = $similarity;
                }
                
                // If we have a good match, no need to check further
                if ($bestScore >= 0.9) break 2;
            }
        }
        
        // If the best score meets the threshold, add to results
        if ($bestScore >= $threshold) {
            $single['relevance'] = $bestScore;
            $results[] = $single;
        }
    }
    
    // Sort results by relevance (highest first)
    usort($results, function($a, $b) {
        return $b['relevance'] <=> $a['relevance'];
    });
    
    // Limit the number of results
    return array_slice($results, 0, $limit);
}
