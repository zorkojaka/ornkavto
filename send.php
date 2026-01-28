<?php
header('Content-Type: application/json; charset=UTF-8');

function respond($ok, $error = '', $status = 200) {
    http_response_code($status);
    echo json_encode($ok ? ['ok' => true] : ['ok' => false, 'error' => $error], JSON_UNESCAPED_UNICODE);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Allow: POST');
    respond(false, 'Method not allowed', 405);
}

$honeypot = isset($_POST['website']) ? trim($_POST['website']) : '';
if ($honeypot !== '') {
    respond(true);
}

function clean($value, $max = 2000) {
    $value = is_string($value) ? trim($value) : '';
    $value = strip_tags($value);
    $value = str_replace(["\r", "\n"], ' ', $value);
    if (mb_strlen($value, 'UTF-8') > $max) {
        $value = mb_substr($value, 0, $max, 'UTF-8');
    }
    return $value;
}

$submitType = clean($_POST['submitType'] ?? 'kontakt', 20);
$ime = clean($_POST['ime'] ?? '', 200);
$telefon = clean($_POST['telefon'] ?? '', 50);
$kaj_zelis = clean($_POST['kaj_zelis'] ?? '', 200);
$namen_vozila = clean($_POST['namen_vozila'] ?? '', 200);
$budget = clean($_POST['budget'] ?? '', 100);
$povezava_oglasa = clean($_POST['povezava_oglasa'] ?? '', 500);
$opombe = clean($_POST['opombe'] ?? '', 2000);

if ($telefon === '') {
    respond(false, 'Telefon is required', 400);
}

$timestamp = date('Y-m-d H:i:s');
$ip = $_SERVER['REMOTE_ADDR'] ?? '';
$referer = $_SERVER['HTTP_REFERER'] ?? '';

$typeLabel = ($submitType === 'vse') ? 'vse' : 'kontakt';
$subject = 'Nova oddaja - OrnkAvto (' . $typeLabel . ')';

$body = "SubmitType: {$typeLabel}\r\n" .
        "Ime: {$ime}\r\n" .
        "Telefon: {$telefon}\r\n" .
        "Kaj zelis: {$kaj_zelis}\r\n" .
        "Namen vozila: {$namen_vozila}\r\n" .
        "Budget: {$budget}\r\n" .
        "Povezava do oglasa: {$povezava_oglasa}\r\n" .
        "Opombe: {$opombe}\r\n" .
        "Timestamp: {$timestamp}\r\n" .
        "IP: {$ip}\r\n" .
        "Page URL: {$referer}\r\n";

$headers = [];
$headers[] = 'MIME-Version: 1.0';
$headers[] = 'Content-Type: text/plain; charset=UTF-8';
$headers[] = 'From: OrnkAvto <no-reply@ornkavto.si>';
$headers[] = 'Reply-To: info@ornkavto.si';

$sent = mail('info@ornkavto.si', $subject, $body, implode("\r\n", $headers), '-f no-reply@ornkavto.si');

if (!$sent) {
    respond(false, 'Mail failed', 500);
}

respond(true);
?>
