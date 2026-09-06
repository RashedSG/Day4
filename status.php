<?php
declare(strict_types=1);

require_once __DIR__ . '/inc/helpers.php';

$ref = trim((string) ($_GET['ref'] ?? $_POST['ref'] ?? ''));
$searched = $ref !== '';
$row = $searched ? find_request($ref) : null;
$invalidFormat = $searched && !preg_match('/^REQ-\d{8}-[A-F0-9]{4}$/i', $ref);
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>حالة الطلب</title>
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

  .panel{background:var(--grey);padding:56px 22px 76px}
  form, .card{
    max-width:660px;margin:0 auto;
    background:#fff;border-radius:18px;
    padding:clamp(26px,5vw,40px);
  }
  .card{margin-top:22px}
  .card h2{font-size:22px;font-weight:600;letter-spacing:-.016em;margin:0 0 22px}
  .field{margin-bottom:26px}
  label{display:block;font-size:14px;font-weight:500;color:var(--sub);margin-bottom:8px}
  input{
    width:100%;font:inherit;color:var(--ink);background:#fff;
    border:1px solid var(--line);border-radius:12px;padding:14px 16px;
  }
  input:focus{
    outline:none;border-color:var(--blue);
    box-shadow:0 0 0 4px rgba(0,113,227,.2);
  }
  .cta{
    display:block;width:100%;font:inherit;font-size:17px;font-weight:500;
    background:var(--blue);color:#fff;border:0;border-radius:980px;
    padding:14px 26px;cursor:pointer;text-align:center;text-decoration:none;
  }
  .cta:hover{background:var(--blue-hover)}
  .msg{color:var(--red);margin:0}
  dl{margin:0}
  .row{
    display:flex;gap:20px;justify-content:space-between;
    padding:16px 0;border-top:1px solid var(--line);
  }
  .row dt{color:var(--sub);font-size:15px;flex:0 0 8.5rem}
  .row dd{margin:0;flex:1;white-space:pre-wrap;text-align:start}
  .badge{
    display:inline-block;background:var(--grey);border-radius:980px;
    padding:4px 12px;font-size:14px;
  }
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

  <section class="hero">
    <h1>حالة الطلب</h1>
    <p>أدخل الرقم المرجعي الذي وصلك بعد الإرسال.</p>
  </section>

  <section class="panel">
    <form action="status.php" method="get">
      <div class="field">
        <label for="ref">الرقم المرجعي</label>
        <input type="text" id="ref" name="ref" value="<?= e($ref) ?>" placeholder="REQ-20260907-A1B2" maxlength="20" required>
      </div>
      <button class="cta" type="submit">عرض الحالة</button>
    </form>

    <?php if ($searched && $row): ?>
      <div class="card">
        <h2>نتيجة البحث</h2>
        <dl>
          <div class="row"><dt>الرقم المرجعي</dt><dd><?= e((string) $row['reference']) ?></dd></div>
          <div class="row"><dt>مقدّم الطلب</dt><dd><?= e((string) ($row['name'] ?? '')) ?></dd></div>
          <div class="row"><dt>نوع الطلب</dt><dd><?= e((string) ($row['type'] ?? '')) ?></dd></div>
          <div class="row"><dt>التفاصيل</dt><dd><?= e((string) ($row['details'] ?? '')) ?></dd></div>
          <div class="row"><dt>الحالة</dt><dd><span class="badge"><?= e(status_label((string) ($row['status'] ?? 'pending'))) ?></span></dd></div>
          <div class="row"><dt>وقت الاستلام</dt><dd><?= e((string) ($row['created_at'] ?? '')) ?></dd></div>
        </dl>
      </div>
    <?php elseif ($searched && $invalidFormat): ?>
      <div class="card">
        <p class="msg">صيغة الرقم غير صحيحة. الشكل المتوقع: REQ-YYYYMMDD-XXXX</p>
      </div>
    <?php elseif ($searched): ?>
      <div class="card">
        <p class="msg">لا يوجد طلب بهذا الرقم.</p>
      </div>
    <?php endif; ?>
  </section>

  <footer>
    <div class="inner">مكتب الخدمات الإدارية — يوم 4</div>
  </footer>

</body>
</html>
