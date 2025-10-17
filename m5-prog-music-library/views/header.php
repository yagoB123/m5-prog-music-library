<?php
$baseTitle = 'Music Library';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= isset($pageTitle) ? htmlspecialchars($pageTitle) . ' | ' . $baseTitle : $baseTitle ?></title>
  <link rel="stylesheet" href="/css/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
  <header class="header">
    <div class="container">
      <a href="/" class="logo">
        <i class="fas fa-music"></i>
        <span>Music Library</span>
      </a>
      
      <div class="search">
        <i class="fas fa-search search__icon"></i>
        <form method="get" action="/search.php" class="search__form">
          <input 
            type="search" 
            name="searchquery" 
            class="search__input" 
            placeholder="Search songs, artists, albums..." 
            value="<?= isset($_GET['searchquery']) ? htmlspecialchars($_GET['searchquery']) : '' ?>"
            aria-label="Search"
          >
        </form>
      </div>
      
      <nav class="nav">
        <a href="/" class="nav__link <?= (!isset($pageTitle) || $pageTitle === 'Overview') ? 'nav__link--active' : '' ?>">
          <i class="fas fa-home"></i>
          <span>Home</span>
        </a>
        <a href="/about.php" class="nav__link <?= (isset($pageTitle) && $pageTitle === 'About') ? 'nav__link--active' : '' ?>">
          <i class="fas fa-info-circle"></i>
          <span>About</span>
        </a>
        <a href="/library" class="nav__link">
          <i class="fas fa-music"></i>
          <span>Library</span>
        </a>
      </nav>
    </div>
  </header>
  
  <main class="main-content">
<main class="py-4">
  <div class="container">
