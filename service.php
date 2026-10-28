<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/data.php';

$slug = $_GET['slug'] ?? '';
$service = null;

if (is_string($slug)) {
    foreach ($services as $item) {
        if ($item['slug'] === $slug) {
            $service = $item;
            break;
        }
    }
}

if ($service === null) {
    http_response_code(404);
    $pageTitle = 'Service not found';
} else {
    $pageTitle = $service['title'];
}

require_once __DIR__ . '/includes/header.php';
?>

<section class="page-banner">
    <div class="container">
        <p class="eyebrow">Our services</p>
        <?php if ($service === null): ?>
            <h1>We couldn’t find that service.</h1>
            <p>Please check the link or browse our available services.</p>
        <?php else: ?>
            <h1><?= e($service['title']) ?></h1>
            <p><?= e($service['description']) ?></p>
        <?php endif; ?>
    </div>
</section>

<?php if ($service !== null): ?>
    <section class="section">
        <div class="container content-layout service-detail">
            <div class="service-detail-icon" aria-hidden="true"><?= e($service['icon']) ?></div>
            <div class="prose">
                <p class="eyebrow">Service details</p>
                <h2><?= e($service['title']) ?></h2>
                <p><?= e($service['description']) ?></p>
                <p class="service-price">Starting at <?= e(formatPrice((float) $service['price'])) ?></p>
                <a class="button" href="contact.php">Ask us about this service</a>
            </div>
        </div>
    </section>
<?php else: ?>
    <section class="section">
        <div class="container">
            <a class="button" href="services.php">Browse our services</a>
        </div>
    </section>
<?php endif; ?>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
