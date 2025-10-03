<?php
$baseTitle = 'Music Library';
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= isset($pageTitle) ? htmlspecialchars($pageTitle) . ' | ' . $baseTitle : $baseTitle ?></title>
  <link rel="stylesheet" href="/dist/main.css">
</head>
<body>
<nav class="navbar navbar-expand-lg bg-body-tertiary">
  <div class="container">
    <a class="navbar-brand" href="/">Music Library</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div id="nav" class="collapse navbar-collapse">
      <ul class="navbar-nav me-auto">
        <li class="nav-item"><a class="nav-link" href="/">Overview</a></li>
        <li class="nav-item"><a class="nav-link" href="/about.php">About</a></li>
      </ul>
      <form class="d-flex" role="search" method="get" action="/search.php">
        <input class="form-control me-2" type="search" name="searchquery" placeholder="Search title" value="<?= isset($_GET['searchquery']) ? htmlspecialchars($_GET['searchquery']) : '' ?>">
        <button class="btn btn-primary" type="submit">Search</button>
      </form>
    </div>
  </div>
</nav>
<main class="py-4">
  <div class="container">
