<?php
declare(strict_types=1);

function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function formatPrice(float $amount): string
{
    return '₦' . number_format($amount, 2);
}

function renderServiceCard(array $service): string
{
    $slug = e(rawurlencode($service['slug']));
    $title = e($service['title']);
    $description = e($service['description']);
    $icon = e($service['icon']);
    $price = e(formatPrice((float) $service['price']));

    return '<article class="card service-card">'
        . '<span class="service-card-icon" aria-hidden="true">' . $icon . '</span>'
        . '<h2><a href="service.php?slug=' . $slug . '">' . $title . '</a></h2>'
        . '<p>' . $description . '</p>'
        . '<p class="service-price">From ' . $price . '</p>'
        . '<a class="service-card-link" href="service.php?slug=' . $slug . '">View service</a>'
        . '</article>';
}
