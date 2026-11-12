<?php
require_once __DIR__ . '/validation.php';
$basePath = $basePath ?? '';
$pageTitle = $pageTitle ?? 'Gift Cards';
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle) ?> | Air Asia</title>
    <link rel="stylesheet" href="<?= e($basePath) ?>css/styles.css">
  </head>
  <body>
    <a class="skip-link" href="#main">Skip to content</a>
    <header class="site-header">
      <div class="header-inner">
        <a class="brand" href="<?= e($basePath) ?>card-list.php"
          aria-label="Air Asia Rewards gift cards">
          <span class="brand-mark">AirAsia</span>
          <span>
            <strong>Air Asia Rewards</strong>
            <small>Gift Card Redemption</small>
          </span>
        </a>
