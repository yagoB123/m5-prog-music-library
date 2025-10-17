<?php
require_once dirname(__DIR__) . '/source/database.php';

// Get search query and clean it up
$q = isset($_GET['searchquery']) ? trim($_GET['searchquery']) : '';
$pageTitle = 'Search';
$suggestedTerm = null;

include dirname(__DIR__) . '/views/header.php';

echo '<div class="search-results">';

if ($q === '') {
    echo '<div class= "search-empty">
            <div class="search-empty__icon">
                <i class="fas fa-search"></i>
            </div>
            <h2>Search for music</h2>
            <p>Enter an artist, song, or album to get started</p>
          </div>';
    include dirname(__DIR__) . '/views/footer.php';
    exit;
}

// Search with fuzzy matching
$results = search_singles($q);

// If no results, try with a lower threshold
if (empty($results)) {
    $results = search_singles($q, 0.3);
    
    // If still no results, try to find similar terms
    if (empty($results)) {
        $suggestedTerm = get_similar_term($q);
    }
}

// Display search results
echo '<div class="search-header">';
echo '<h1>Search Results</h1>';
echo '<p class="search-query">' . htmlspecialchars($q) . '</p>';

echo '<div class="search-filters">';
echo '<span class="search-count">' . count($results) . ' results found</span>';
echo '</div>';
echo '</div>'; // .search-header

if (empty($results)) {
    echo '<div class="search-no-results">
            <i class="fas fa-music"></i>
            <h2>No results found</h2>
            <p>We couldn\'t find any matches for <strong>' . htmlspecialchars($q) . '</strong>.</p>';
    
    if ($suggestedTerm) {
        echo '<p>Did you mean: <a href="/search.php?searchquery=' . urlencode($suggestedTerm) . '" class="suggestion-link">' . 
             htmlspecialchars($suggestedTerm) . '</a>?</p>';
    }
    
    echo '<div class="search-tips">
            <h4>Search Tips:</h4>
            <ul>
                <li>Try different keywords or more general terms</li>
                <li>Check your spelling</li>
                <li>Search for an artist, song, or album</li>
            </ul>
          </div>
          </div>';
} else {
    // Group results by type (artist, song, album in a real implementation)
    echo '<div class="search-results-grid">';
    
    // Calculate max relevance for normalizing scores
    $maxRelevance = 0;
    foreach ($results as $result) {
        if (isset($result['relevance']) && $result['relevance'] > $maxRelevance) {
            $maxRelevance = $result['relevance'];
        }
    }
    
    foreach ($results as $single) {
        // Calculate match quality (0-100%)
        $relevancePercent = isset($single['relevance']) 
            ? round(($single['relevance'] / $maxRelevance) * 100) 
            : 100;
            
        // Add relevance to the single data for the card
        $single['relevance_percent'] = $relevancePercent;
        
        // Include the card template
        include dirname(__DIR__) . '/views/card.php';
    }
    
    echo '</div>'; // .search-results-grid
}

echo '</div>'; // .search-results

include dirname(__DIR__) . '/views/footer.php';

/**
 * Try to find a similar term in the database
 */
function get_similar_term($term) {
    // In a real implementation, you might query your database for similar terms
    // For now, we'll just try some simple transformations
    
    // Remove common suffixes/endings
    $variations = [
        $term,
        preg_replace('/s$/', '', $term),  // try singular/plural
        preg_replace('/ing$/', 'e', $term), // running -> runne
        preg_replace('/ed$/', '', $term),  // played -> play
    ];
    
    // Try each variation
    foreach (array_unique($variations) as $variation) {
        if (!empty($variation)) {
            $results = search_singles($variation, 0.4, 1);
            if (!empty($results)) {
                return $variation;
            }
        }
    }
    
    return null;
}
