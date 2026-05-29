<?php
declare(strict_types=1);
require __DIR__ . '/config.php';
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Заявка отправлена — <?= htmlspecialchars($siteName, ENT_QUOTES) ?></title>
    <meta name="robots" content="noindex, follow">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<main class="system-page">
    <section class="system-card">
        <p class="eyebrow">Спасибо</p>
        <h1>Заявка отправлена</h1>
        <p>Мы свяжемся с вами для уточнения деталей, возможности выезда и ориентировочной стоимости.</p>
        <div class="hero-actions">
            <a class="btn btn-primary" href="tel:<?= $sitePhoneHref ?>">Позвонить сейчас</a>
            <a class="btn btn-secondary" href="index.php">Вернуться на сайт</a>
        </div>
    </section>
</main>
</body>
</html>
