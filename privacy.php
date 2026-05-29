<?php
declare(strict_types=1);
require __DIR__ . '/config.php';
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Политика конфиденциальности — <?= htmlspecialchars($siteName, ENT_QUOTES) ?></title>
    <meta name="robots" content="noindex, follow">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<main class="system-page policy-page">
    <article class="system-card">
        <a class="back-link" href="index.php">← На главную</a>
        <h1>Политика конфиденциальности</h1>
        <p>Настоящая политика описывает порядок обработки данных, которые пользователь передаёт через формы сайта <?= htmlspecialchars($siteName, ENT_QUOTES) ?>.</p>
        <h2>Какие данные обрабатываются</h2>
        <p>Имя, телефон, адрес или местоположение автомобиля, описание ситуации, комментарий, IP-адрес, User-Agent и страница отправки формы.</p>
        <h2>Цель обработки</h2>
        <p>Данные используются для связи с пользователем, уточнения деталей заявки, предварительного расчёта стоимости и организации выезда мастера.</p>
        <h2>Передача данных</h2>
        <p>Данные не публикуются и не передаются третьим лицам, кроме случаев, необходимых для обработки заявки или предусмотренных законодательством.</p>
        <h2>Хранение и защита</h2>
        <p>Заявки могут храниться в почте, Telegram и CSV-файле на сервере, если эти способы включены в настройках сайта. Доступ к файлам заявок ограничивается настройками сервера.</p>
        <h2>Контакты</h2>
        <p>По вопросам обработки данных можно обратиться по телефону <a href="tel:<?= $sitePhoneHref ?>"><?= htmlspecialchars($sitePhone, ENT_QUOTES) ?></a>.</p>
    </article>
</main>
</body>
</html>
