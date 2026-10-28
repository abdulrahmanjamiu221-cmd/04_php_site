<?php
declare(strict_types=1);

$pageTitle = 'Contact';
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/header.php';
?>

<section class="page-banner">
    <div class="container">
        <p class="eyebrow">Get in touch</p>
        <h1>Tell us what you’re working on.</h1>
        <p>Have a question or an idea? We’d be happy to hear from you.</p>
    </div>
</section>

<section class="section">
    <div class="container contact-panel">
        <div>
            <p class="eyebrow">Start a conversation</p>
            <h2>Let’s find the right solution together.</h2>
            <p>Send us a note and tell us a little about how we can help. We’ll get back to you as soon as we can.</p>
        </div>
        <a class="contact-link" href="mailto:<?= e($contactEmail) ?>">
            <?= e($contactEmail) ?>
        </a>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
