<?php
declare(strict_types=1);

$pageTitle = 'Home';
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/header.php';
?>

<section class="hero">
    <div class="container hero-content">
        <p class="eyebrow">Welcome to King Manu Tech Hub</p>
        <h1>Build a smarter future with technology that works for you.</h1>
        <p class="hero-copy">We help people and businesses make confident technology choices, solve everyday challenges, and turn fresh ideas into practical results.</p>
        <a class="button" href="services.php">Explore our services</a>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="section-heading">
            <p class="eyebrow">Why King Manu</p>
            <h2>Good technology should feel straightforward.</h2>
        </div>
        <div class="card-grid">
            <article class="card">
                <span class="card-number">01</span>
                <h3>People first</h3>
                <p>We listen before recommending, and explain every step in plain language.</p>
            </article>
            <article class="card">
                <span class="card-number">02</span>
                <h3>Practical solutions</h3>
                <p>Our recommendations focus on what you need today and where you want to go next.</p>
            </article>
            <article class="card">
                <span class="card-number">03</span>
                <h3>Here to help</h3>
                <p>From your first question to ongoing support, we make it easy to get assistance.</p>
            </article>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
