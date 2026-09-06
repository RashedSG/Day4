<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>المساعدة</title>
<style>
  :root{
    --ink:#1d1d1f; --sub:#6e6e73; --line:#d2d2d7;
    --grey:#f5f5f7; --blue:#0071e3; --blue-hover:#0077ed;
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
  .hero p{font-size:clamp(18px,2.2vw,22px);line-height:1.4;color:var(--sub);margin:0 auto;max-width:38ch}
  .panel{background:var(--grey);padding:56px 22px 76px}
  .card{
    max-width:660px;margin:0 auto;
    background:#fff;border-radius:18px;
    padding:clamp(26px,5vw,40px);
  }
  .card h2{font-size:20px;font-weight:600;margin:0 0 10px}
  .card p,.card li{color:var(--sub)}
  .card ol{padding-inline-start:22px}
  .card li{margin-bottom:8px}
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
    <h1>المساعدة</h1>
    <p>كيف تقدّم طلبًا وتتابع حالته.</p>
  </section>

  <section class="panel">
    <div class="card">
      <h2>خطوات التقديم</h2>
      <ol>
        <li>أدخل اسمك كما هو مسجّل رسميًا.</li>
        <li>اختر نوع الطلب ثم اكتب التفاصيل.</li>
        <li>بعد الإرسال يظهر رقم مرجعي ويُحفظ الطلب في الملف.</li>
        <li>استخدم الرقم في صفحة «حالة الطلب» للمتابعة.</li>
      </ol>
      <p>تُراجع الطلبات خلال يومي عمل. الحالات: قيد المراجعة، مقبول، مرفوض.</p>
    </div>
  </section>

  <footer>
    <div class="inner">مكتب الخدمات الإدارية — يوم 4</div>
  </footer>
</body>
</html>
