<?php
declare(strict_types=1);

/* ------------------------------------------------------------------
   يوم 4: دوال مشتركة للحفظ والقراءة والحماية من XSS.
   require_once هذا الملف من process.php و status.php
------------------------------------------------------------------ */

date_default_timezone_set('Asia/Dubai');

const DATA_FILE = __DIR__ . '/../data/requests.json';

function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function load_requests(): array
{
    if (!is_file(DATA_FILE)) {
        return [];
    }

    $raw = file_get_contents(DATA_FILE);
    if ($raw === false || trim($raw) === '') {
        return [];
    }

    $data = json_decode($raw, true);
    return is_array($data) ? $data : [];
}

function save_request(array $request): bool
{
    $dir = dirname(DATA_FILE);
    if (!is_dir($dir) && !mkdir($dir, 0775, true) && !is_dir($dir)) {
        return false;
    }

    $handle = fopen(DATA_FILE, 'c+');
    if ($handle === false) {
        return false;
    }

    try {
        if (!flock($handle, LOCK_EX)) {
            return false;
        }

        $raw = stream_get_contents($handle);
        $list = [];
        if ($raw !== false && trim($raw) !== '') {
            $decoded = json_decode($raw, true);
            if (is_array($decoded)) {
                $list = $decoded;
            }
        }

        $list[] = $request;

        rewind($handle);
        if (ftruncate($handle, 0) === false) {
            return false;
        }

        $json = json_encode($list, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        if ($json === false) {
            return false;
        }

        return fwrite($handle, $json) !== false;
    } finally {
        flock($handle, LOCK_UN);
        fclose($handle);
    }
}

function find_request(string $reference): ?array
{
    $reference = strtoupper(trim($reference));
    if ($reference === '' || !preg_match('/^REQ-\d{8}-[A-F0-9]{4}$/', $reference)) {
        return null;
    }

    foreach (load_requests() as $row) {
        if (is_array($row) && strtoupper((string) ($row['reference'] ?? '')) === $reference) {
            return $row;
        }
    }

    return null;
}

function allowed_types(): array
{
    return ['إجازة', 'شهادة', 'صيانة', 'أخرى'];
}

function status_label(string $status): string
{
    $map = [
        'pending'  => 'قيد المراجعة',
        'accepted' => 'مقبول',
        'rejected' => 'مرفوض',
    ];
    return $map[$status] ?? 'قيد المراجعة';
}
