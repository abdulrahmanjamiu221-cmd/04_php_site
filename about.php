<?php
declare(strict_types=1);

$pageTitle = 'About';
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/data.php';
require_once __DIR__ . '/includes/header.php';
?>

<section class="page-banner">
    <div class="container">
        <p class="eyebrow">About us</p>
        <h1>Technology with a human touch.</h1>
        <p><?= e($tagline) ?></p>
    </div>
</section>

<section class="section">
    <div class="container content-layout">
        <div>
            <p class="eyebrow">Our story</p>
            <h2>Making digital possibilities easier to reach.</h2>
        </div>
        <div class="prose">
            <p>King Manu Tech Hub is a fictional technology partner built around a simple idea: technology is at its best when it helps people do more of what matters to them.</p>
            <p>We bring curiosity, clear communication, and a practical mindset to every project. Whether you are exploring a new idea or improving the tools you already use, we meet you where you are and work toward a solution that fits.</p>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="section-heading">
            <p class="eyebrow">Meet the team</p>
            <h2>People who make technology feel more human.</h2>
        </div>
        <div class="card-grid">
            <?php foreach ($team as $member): ?>
                <article class="card team-card">
                    <h3><?= e($member['name']) ?></h3>
                    <p class="team-role"><?= e($member['role']) ?></p>
                    <p><?= e($member['bio']) ?></p>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
