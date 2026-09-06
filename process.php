<?php
declare(strict_types=1);

require_once __DIR__ . '/inc/helpers.php';

const DEV_MODE = true;

if (DEV_MODE) {
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', '0');
    ini_set('log_errors', '1');
    error_reporting(E_ALL);
}

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    header('Location: index.html', true, 303);
    exit;
}

$name    = trim((string) ($_POST['name']    ?? ''));
$type    = trim((string) ($_POST['type']    ?? ''));
$details = trim((string) ($_POST['details'] ?? ''));

$errors = [];

if ($name === '') {
    $errors[] = 'الاسم مطلوب.';
} elseif (mb_strlen($name) > 60) {
    $errors[] = 'الاسم يتجاوز ٦٠ حرفًا.';
}

if (!in_array($type, allowed_types(), true)) {
    $errors[] = 'اختر نوع طلب من القائمة.';
}

if ($details === '') {
    $errors[] = 'التفاصيل مطلوبة.';
} elseif (mb_strlen($details) > 1000) {
    $errors[] = 'التفاصيل تتجاوز ١٠٠٠ حرف.';
}

$reference = '';
$createdAt = date('Y-m-d H:i');

if (!$errors) {
    do {
        $reference = 'REQ-' . date('Ymd') . '-' . strtoupper(bin2hex(random_bytes(2)));
    } while (find_request($reference) !== null);

    $saved = save_request([
        'reference'  => $reference,
        'name'       => $name,
        'type'       => $type,
        'details'    => $details,
        'status'     => 'pending',
        'created_at' => $createdAt,
    ]);

    if (!$saved) {
        $errors[] = 'تعذّر حفظ الطلب. تحقّق من صلاحية مجلد data ثم أعد المحاولة.';
        $reference = '';
    }
}

http_response_code($errors ? 422 : 200);
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= $errors ? 'تعذّر الإرسال' : 'تم استلام الطلب' ?></title>
<style>
  :root{
    --ink:#1d1d1f; --sub:#6e6e73; --line:#d2d2d7;
    --grey:#f5f5f7; --blue:#0071e3; --blue-hover:#0077ed; --red:#bf4800;
  }
  *{box-sizing:border-box}
  body{
    margin:0;background:#fff;color:var(--ink);
    font-family:-apple-system,BlinkMacSystemFont,"SF Pro Display","SF Arabic",
                "Helvetica Neue","Segoe UI",Tahoma,sans-serif;
    font-size:17px;line-height:1.47;letter-spacing:-.01em;
    -webkit-font-smoothing:antialiased;
  }
  .globalnav{
    position:sticky;top:0;z-index:20;height:48px;
    background:rgba(255,255,255,.72);
    backdrop-filter:saturate(180%) blur(20px);
    -webkit-backdrop-filter:saturate(180%) blur(20px);
    border-bottom:1px solid rgba(0,0,0,.08);
  }
  .globalnav .inner{
    max-width:1024px;margin:0 auto;height:100%;
    display:flex;align-items:center;gap:28px;padding:0 22px;font-size:12px;
  }
  .globalnav a{color:inherit;text-decoration:none;opacity:.82}
  .globalnav a:hover{opacity:1}
  .globalnav .brand{font-weight:600;opacity:1}

  .hero{text-align:center;padding:72px 22px 52px}
  .hero h1{
    font-size:clamp(36px,6.4vw,56px);line-height:1.07;
    font-weight:600;letter-spacing:-.022em;margin:0 0 14px;
  }
  .hero p{
    font-size:clamp(18px,2.2vw,22px);line-height:1.4;color:var(--sub);
    margin:0 auto;max-width:38ch;
  }
  .hero .ref{
    display:inline-block;margin-top:26px;
    background:var(--grey);border-radius:14px;
    padding:16px 28px;
    font-size:24px;font-weight:600;letter-spacing:.01em;
  }
  .hero .ref span{
    display:block;font-size:13px;font-weight:400;
    color:var(--sub);letter-spacing:-.01em;margin-top:4px;
  }

  .panel{background:var(--grey);padding:56px 22px 76px}
  .card{
    max-width:660px;margin:0 auto;
    background:#fff;border-radius:18px;
    padding:clamp(26px,5vw,40px);
  }
  .card h2{font-size:22px;font-weight:600;letter-spacing:-.016em;margin:0 0 22px}
  dl{margin:0}
  .row{
    display:flex;gap:20px;justify-content:space-between;
    padding:16px 0;border-top:1px solid var(--line);
  }
  .row dt{color:var(--sub);font-size:15px;flex:0 0 8.5rem}
  .row dd{margin:0;flex:1;white-space:pre-wrap;text-align:start}

  ul.errors{margin:0 0 8px;padding-inline-start:20px;color:var(--red)}
  ul.errors li{margin-bottom:8px}

  .actions{text-align:center;margin-top:34px}
  .cta{
    display:inline-block;font-size:17px;font-weight:500;
    background:var(--blue);color:#fff;text-decoration:none;
    border-radius:980px;padding:13px 34px;transition:background .2s;
  }
  .cta:hover{background:var(--blue-hover)}
  .cta:focus-visible{outline:4px solid rgba(0,113,227,.35);outline-offset:2px}
  .link{color:var(--blue);text-decoration:none;font-size:15px;display:inline-block;margin-top:16px}
  .link:hover{text-decoration:underline}

  footer{
    background:var(--grey);border-top:1px solid var(--line);
    color:var(--sub);font-size:12px;padding:20px 22px;
  }
  footer .inner{max-width:1024px;margin:0 auto}
</style>
</head>
<body>

  <nav class="globalnav">
    <div class="inner">
      <span class="brand">الخدمات الإدارية</span>
      <a href="index.html">تقديم طلب</a>
      <a href="status.php">حالة الطلب</a>
      <a href="help.php">المساعدة</a>
    </div>
  </nav>

<?php if ($errors): ?>

  <section class="hero">
    <h1>تعذّر إرسال الطلب.</h1>
    <p>لم يُحفظ شيء. صحّح ما يلي وأعد المحاولة.</p>
  </section>

  <section class="panel">
    <div class="card">
      <h2>ما يحتاج إلى تصحيح</h2>
      <ul class="errors">
        <?php foreach ($errors as $error): ?>
          <li><?= e($error) ?></li>
        <?php endforeach; ?>
      </ul>
      <div class="actions">
        <a class="cta" href="index.html">العودة إلى النموذج</a>
      </div>
    </div>
  </section>

<?php else: ?>

  <section class="hero">
    <h1>شكرًا لك يا <?= e($name) ?>.</h1>
    <p>حُفظ طلبك وهو الآن قيد المراجعة.</p>
    <div class="ref">
      <?= e($reference) ?>
      <span>احتفظ بهذا الرقم لمتابعة حالة طلبك</span>
    </div>
  </section>

  <section class="panel">
    <div class="card">
      <h2>ملخّص الطلب</h2>
      <dl>
        <div class="row"><dt>مقدّم الطلب</dt><dd><?= e($name) ?></dd></div>
        <div class="row"><dt>نوع الطلب</dt><dd><?= e($type) ?></dd></div>
        <div class="row"><dt>التفاصيل</dt><dd><?= e($details) ?></dd></div>
        <div class="row"><dt>الحالة</dt><dd><?= e(status_label('pending')) ?></dd></div>
        <div class="row"><dt>وقت الاستلام</dt><dd><?= e($createdAt) ?></dd></div>
      </dl>
      <div class="actions">
        <a class="cta" href="status.php?ref=<?= rawurlencode($reference) ?>">متابعة الحالة</a>
        <br>
        <a class="link" href="index.html">تقديم طلب آخر</a>
      </div>
    </div>
  </section>

<?php endif; ?>

  <footer>
    <div class="inner">مكتب الخدمات الإدارية — يوم 4</div>
  </footer>

</body>
</html>
