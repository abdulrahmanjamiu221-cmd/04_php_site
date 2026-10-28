<?php
declare(strict_types=1);

$pageTitle = 'Services';
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/data.php';
require_once __DIR__ . '/includes/header.php';
?>

<section class="page-banner">
    <div class="container">
        <p class="eyebrow">What we do</p>
        <h1>Technology services for your next step.</h1>
        <p>Flexible, friendly support designed around real-world needs.</p>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="card-grid">
            <?php foreach ($services as $service): ?>
                <?= renderServiceCard($service) ?>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
