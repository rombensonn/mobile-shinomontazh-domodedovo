<?php
declare(strict_types=1);

$siteName = 'Мобильный шиномонтаж Москва и МО';
$siteUrl = 'https://rombensonn.github.io/mobile-shinomontazh-domodedovo';
$sitePhone = '+7 (926) 023-73-44';
$sitePhoneHref = '+79260237344';
$siteAddress = 'Станционная ул., 3, Домодедово, Московская область';
$yandexMapsUrl = 'https://yandex.ru/maps/-/CPHvqZmq';
$yandexMapEmbedUrl = 'https://yandex.ru/map-widget/v1/?mode=search&text=' . rawurlencode($siteAddress) . '&z=16';

$adminEmail = 'example@example.com';
$mailSubject = 'Новая заявка с сайта мобильного шиномонтажа';

$telegramEnabled = false;
$telegramBotToken = '';
$telegramChatId = '';

$csvEnabled = true;
$csvPath = __DIR__ . '/data/leads.csv';

$rateLimitSeconds = 30;
$timezone = 'Europe/Moscow';

date_default_timezone_set($timezone);
