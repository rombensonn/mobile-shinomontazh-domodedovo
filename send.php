<?php
declare(strict_types=1);

session_start();
require __DIR__ . '/config.php';

ini_set('display_errors', '0');
error_reporting(E_ALL);

function clean_field(string $value, int $limit = 500): string
{
    $value = trim(strip_tags($value));
    $value = mb_substr($value, 0, $limit, 'UTF-8');
    return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function fail(string $message): never
{
    http_response_code(400);
    echo '<!doctype html><html lang="ru"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Ошибка отправки</title><link rel="stylesheet" href="assets/css/style.css"></head><body><main class="system-page"><div class="system-card"><h1>Заявка не отправлена</h1><p>' . htmlspecialchars($message, ENT_QUOTES, 'UTF-8') . '</p><a class="btn btn-primary" href="index.php#lead-form">Вернуться к форме</a></div></main></body></html>';
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

if (!empty($_POST['website'] ?? '')) {
    fail('Похоже на автоматическую отправку. Попробуйте ещё раз.');
}

$csrf = (string)($_POST['csrf_token'] ?? '');
if (empty($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $csrf)) {
    fail('Сессия формы устарела. Обновите страницу и попробуйте снова.');
}

$now = time();
if (!empty($_SESSION['last_submit_at']) && ($now - (int)$_SESSION['last_submit_at']) < $rateLimitSeconds) {
    fail('Заявки отправляются слишком часто. Подождите немного и повторите.');
}

$name = clean_field((string)($_POST['name'] ?? ''), 80);
$phone = clean_field((string)($_POST['phone'] ?? ''), 40);
$location = clean_field((string)($_POST['location'] ?? ''), 180);
$problem = clean_field((string)($_POST['problem'] ?? ''), 180);
$urgency = clean_field((string)($_POST['urgency'] ?? ''), 80);
$comment = clean_field((string)($_POST['comment'] ?? ''), 800);
$sourceForm = clean_field((string)($_POST['source_form'] ?? 'Форма сайта'), 120);
$pageUrl = clean_field((string)($_POST['page_url'] ?? ($_SERVER['HTTP_REFERER'] ?? '')), 300);
$agree = isset($_POST['privacy']);

$phoneDigits = preg_replace('/\D+/', '', $phone);
if (!$phone || strlen((string)$phoneDigits) < 10) {
    fail('Укажите корректный телефон для связи.');
}

if (!$agree) {
    fail('Нужно согласиться с политикой конфиденциальности.');
}

$lead = [
    'date' => date('Y-m-d H:i:s'),
    'name' => html_entity_decode($name, ENT_QUOTES, 'UTF-8'),
    'phone' => html_entity_decode($phone, ENT_QUOTES, 'UTF-8'),
    'location' => html_entity_decode($location, ENT_QUOTES, 'UTF-8'),
    'problem' => html_entity_decode($problem, ENT_QUOTES, 'UTF-8'),
    'urgency' => html_entity_decode($urgency, ENT_QUOTES, 'UTF-8'),
    'comment' => html_entity_decode($comment, ENT_QUOTES, 'UTF-8'),
    'source_form' => html_entity_decode($sourceForm, ENT_QUOTES, 'UTF-8'),
    'ip' => $_SERVER['REMOTE_ADDR'] ?? '',
    'user_agent' => mb_substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 300, 'UTF-8'),
    'page' => html_entity_decode($pageUrl, ENT_QUOTES, 'UTF-8'),
];

$messageLines = [
    "Новая заявка с сайта: {$siteName}",
    "Дата: {$lead['date']}",
    "Имя: {$lead['name']}",
    "Телефон: {$lead['phone']}",
    "Где авто: {$lead['location']}",
    "Проблема: {$lead['problem']}",
    "Срочность: {$lead['urgency']}",
    "Комментарий: {$lead['comment']}",
    "Источник формы: {$lead['source_form']}",
    "IP: {$lead['ip']}",
    "User-Agent: {$lead['user_agent']}",
    "Страница: {$lead['page']}",
];
$message = implode("\n", $messageLines);

if (!empty($adminEmail) && filter_var($adminEmail, FILTER_VALIDATE_EMAIL)) {
    $headers = [
        'MIME-Version: 1.0',
        'Content-Type: text/plain; charset=UTF-8',
        'From: ' . $siteName . ' <no-reply@' . ($_SERVER['HTTP_HOST'] ?? 'localhost') . '>',
    ];
    @mail($adminEmail, '=?UTF-8?B?' . base64_encode($mailSubject) . '?=', $message, implode("\r\n", $headers));
}

if ($telegramEnabled && $telegramBotToken !== '' && $telegramChatId !== '' && function_exists('curl_init')) {
    $ch = curl_init('https://api.telegram.org/bot' . $telegramBotToken . '/sendMessage');
    curl_setopt_array($ch, [
        CURLOPT_POST => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 8,
        CURLOPT_POSTFIELDS => [
            'chat_id' => $telegramChatId,
            'text' => $message,
            'disable_web_page_preview' => true,
        ],
    ]);
    @curl_exec($ch);
    @curl_close($ch);
}

if ($csvEnabled) {
    $isNewFile = !file_exists($csvPath);
    $fp = @fopen($csvPath, 'ab');
    if ($fp) {
        if (flock($fp, LOCK_EX)) {
            if ($isNewFile) {
                fputcsv($fp, array_keys($lead), ';');
            }
            fputcsv($fp, array_values($lead), ';');
            flock($fp, LOCK_UN);
        }
        fclose($fp);
    }
}

$_SESSION['last_submit_at'] = $now;
unset($_SESSION['csrf_token']);

header('Location: thank-you.php');
exit;
