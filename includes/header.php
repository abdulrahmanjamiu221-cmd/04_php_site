<?php
declare(strict_types=1);
require_once __DIR__ . '/functions.php';
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="<?= e($tagline) ?>">
    <title><?= e($pageTitle) ?> | <?= e($siteName) ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <header class="site-header">
        <div class="container header-content">
            <a class="brand" href="index.php">
                <span class="brand-name"><?= e($siteName) ?></span>
                <span class="brand-tagline"><?= e($tagline) ?></span>
            </a>
            <nav class="site-nav" aria-label="Main navigation">
                <?php foreach ($navItems as $label => $fileName): ?>
                    <?php $isCurrentPage = basename($_SERVER['PHP_SELF']) === $fileName; ?>
                    <a href="<?= e($fileName) ?>"
                       class="<?= $isCurrentPage ? 'active' : '' ?>"
                       <?= $isCurrentPage ? 'aria-current="page"' : '' ?>>
                        <?= e($label) ?>
                    </a>
                <?php endforeach; ?>
            </nav>
        </div>
    </header>
    <main>
