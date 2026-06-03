<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Kütüphane Otomasyon Sistemi</title>
<style>
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:Georgia,'Times New Roman',serif;background:#f5f0e8;color:#1a2530;min-height:100vh}

/* ── NAVBAR ── */
.navbar{background:#1a2530;padding:0 60px;height:60px;display:flex;align-items:center;justify-content:space-between;position:sticky;top:0;z-index:100}
.navbar-brand{display:flex;align-items:center;gap:10px;color:#f5f0e8;font-size:16px;font-weight:700;letter-spacing:.06em}
.navbar-brand span{font-size:22px}
.navbar-links{display:flex;align-items:center;gap:8px}
.nav-btn{padding:8px 20px;font-size:13px;cursor:pointer;font-family:Georgia,serif;letter-spacing:.04em;border:none;transition:.2s}
.nav-btn-outline{background:transparent;color:rgba(255,255,255,.7);border:1px solid rgba(255,255,255,.3)}
.nav-btn-outline:hover{color:#fff;border-color:#fff}
.nav-btn-solid{background:#f5f0e8;color:#1a2530}
.nav-btn-solid:hover{background:#fff}

/* ── HERO ── */
.hero{background:#1a2530;color:#f5f0e8;padding:80px 60px;display:flex;align-items:center;justify-content:space-between;gap:40px}
.hero-left{flex:1;max-width:560px}
.hero-tag{display:inline-block;background:#8b1a1a;color:#fff;font-size:11px;padding:4px 12px;letter-spacing:.1em;text-transform:uppercase;font-family:'Segoe UI',sans-serif;margin-bottom:20px}
.hero-left h1{font-size:40px;font-weight:700;line-height:1.2;margin-bottom:16px;color:#f5f0e8}
.hero-left h1 em{color:#c8a96e;font-style:normal}
.hero-left p{font-size:15px;color:rgba(255,255,255,.65);line-height:1.8;margin-bottom:32px;font-family:'Segoe UI',sans-serif}
.hero-btns{display:flex;gap:12px;flex-wrap:wrap}
.btn-hero-primary{background:#c8a96e;color:#1a2530;padding:13px 28px;font-size:14px;font-weight:700;border:none;cursor:pointer;font-family:Georgia,serif;letter-spacing:.05em;transition:.2s}
.btn-hero-primary:hover{background:#d4b87a}
.btn-hero-outline{background:transparent;color:#f5f0e8;padding:13px 28px;font-size:14px;border:1px solid rgba(255,255,255,.4);cursor:pointer;font-family:Georgia,serif;letter-spacing:.05em;transition:.2s}
.btn-hero-outline:hover{border-color:#fff;color:#fff}
.hero-right{flex-shrink:0}
.hero-card{background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.15);padding:28px;width:280px}
.hero-card h3{font-size:13px;color:rgba(255,255,255,.5);letter-spacing:.08em;text-transform:uppercase;margin-bottom:16px;font-family:'Segoe UI',sans-serif}
.hero-stat{display:flex;align-items:center;gap:12px;padding:10px 0;border-bottom:1px solid rgba(255,255,255,.08)}
.hero-stat:last-child{border-bottom:none}
.hero-stat .ico{font-size:20px;width:36px;text-align:center}
.hero-stat-text p{font-size:20px;font-weight:700;color:#c8a96e}
.hero-stat-text span{font-size:11px;color:rgba(255,255,255,.45);font-family:'Segoe UI',sans-serif}

/* ── FEATURES ── */
.features{padding:64px 60px;background:#fff}
.section-label{text-align:center;font-size:11px;color:#8b1a1a;letter-spacing:.12em;text-transform:uppercase;font-family:'Segoe UI',sans-serif;margin-bottom:10px}
.section-title{text-align:center;font-size:28px;color:#1a2530;margin-bottom:48px}
.features-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:24px;max-width:960px;margin:0 auto}
.feature-card{border:1px solid #e5ddd0;padding:28px;background:#fdfaf5;transition:.2s}
.feature-card:hover{border-color:#c8a96e;transform:translateY(-2px)}
.feature-icon{font-size:28px;margin-bottom:14px}
.feature-card h3{font-size:15px;color:#1a2530;margin-bottom:8px}
.feature-card p{font-size:13px;color:#7c6f5e;line-height:1.7;font-family:'Segoe UI',sans-serif}


/* ── FOOTER ── */
footer{background:#1a2530;color:rgba(255,255,255,.45);text-align:center;padding:20px;font-size:12px;font-family:'Segoe UI',sans-serif;letter-spacing:.04em}

/* ── LOGIN MODAL ── */
.modal{display:none;position:fixed;inset:0;background:rgba(10,15,20,.65);z-index:200;align-items:center;justify-content:center}
.modal.open{display:flex}
.modal-box{background:#fdfaf5;border:1px solid #c8b99a;border-top:4px solid #1a2530;padding:36px 40px;width:100%;max-width:420px}
.modal-box h2{font-size:18px;margin-bottom:6px;color:#1a2530}
.modal-box p{font-size:12px;color:#7c6f5e;margin-bottom:24px;font-family:'Segoe UI',sans-serif;font-style:italic}
.modal-close{float:right;background:none;border:none;font-size:22px;cursor:pointer;color:#9b8c7a;margin-top:-4px}
.tabs{display:flex;border-bottom:2px solid #e5ddd0;margin-bottom:24px}
.tab{flex:1;padding:9px;text-align:center;font-size:13px;color:#7c6f5e;cursor:pointer;border-bottom:2px solid transparent;margin-bottom:-2px;letter-spacing:.03em;font-family:Georgia,serif}
.tab.active{color:#1a2530;border-bottom-color:#1a2530;font-weight:700}
.form-group{margin-bottom:16px}
label{display:block;font-size:11px;font-weight:700;color:#4a3f35;letter-spacing:.07em;text-transform:uppercase;margin-bottom:6px;font-family:'Segoe UI',sans-serif}
input,select{width:100%;padding:10px 12px;border:1px solid #c8b99a;background:#fff;font-size:14px;font-family:'Segoe UI',sans-serif;outline:none;color:#1a2530}
input:focus,select:focus{border-color:#1a2530}
.form-grid{display:grid;grid-template-columns:1fr 1fr;gap:10px}
.form-grid .full{grid-column:1/-1}
.btn-submit{width:100%;padding:12px;background:#1a2530;color:#f5f0e8;border:none;font-size:14px;font-family:Georgia,serif;letter-spacing:.06em;cursor:pointer;margin-top:6px;transition:.2s}
.btn-submit:hover{background:#2c3e50}
.alert{padding:10px 14px;border:1px solid;font-size:13px;margin-bottom:14px;font-family:'Segoe UI',sans-serif;display:none}
.alert-error{background:#fef2f2;border-color:#fca5a5;color:#991b1b}
.alert-success{background:#f0fdf4;border-color:#86efac;color:#166534}
.demo-box{display:grid;grid-template-columns:1fr 1fr;gap:8px;margin-top:14px}
.demo-item{background:#f5f0e8;border:1px solid #e5ddd0;padding:8px;text-align:center;font-size:12px;font-family:'Segoe UI',sans-serif;color:#4a3f35;cursor:pointer;transition:.2s}
.demo-item:hover{border-color:#1a2530}
.demo-item b{display:block;font-size:11px;color:#7c6f5e;margin-bottom:2px}
</style>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar">
  <div class="navbar-brand"><span>📚</span> KÜTÜPHANE SİSTEMİ</div>
  <div class="navbar-links">
    <button class="nav-btn nav-btn-outline" onclick="window.location.href='user_login.php'">Üye Girişi</button>
    <button class="nav-btn nav-btn-solid" onclick="window.location.href='admin_login.php'">Giriş Yap</button>
  </div>
</nav>

<!-- HERO -->
<section class="hero">
  <div class="hero-left">
    
    <h1>Kütüphane<br><em>Otomasyon</em><br>Sistemi</h1>
    <p>Kitap kataloğunu dijital ortamda yönetin. Üye kaydı, ödünç işlemleri, gecikme takibi ve ceza yönetimini tek platformdan gerçekleştirin.</p>
    <div class="hero-btns">
      <button class="btn-hero-primary" onclick="window.location.href='admin_login.php'">🔐 Yönetici Girişi</button>
      <button class="btn-hero-outline" onclick="window.location.href='user_login.php'">👤 Üye Girişi</button>
    </div>
  </div>
  <div class="hero-right">
    <div class="hero-card">
      <h3>Sistem İstatistikleri</h3>
      <div class="hero-stat"><span class="ico">📖</span><div class="hero-stat-text"><p id="hs-kitap">5</p><span>Katalogdaki kitap</span></div></div>
      <div class="hero-stat"><span class="ico">👥</span><div class="hero-stat-text"><p id="hs-uye">3</p><span>Kayıtlı üye</span></div></div>
      <div class="hero-stat"><span class="ico">⇄</span><div class="hero-stat-text"><p id="hs-odunc">0</p><span>Aktif ödünç</span></div></div>
      <div class="hero-stat"><span class="ico">🏷️</span><div class="hero-stat-text"><p>5</p><span>Kitap kategorisi</span></div></div>
    </div>
  </div>
</section>

<!-- FEATURES -->
<section class="features">
  <div class="section-label">Neler Sunuyoruz?</div>
  <h2 class="section-title">Sistem Özellikleri</h2>
  <div class="features-grid">
    <div class="feature-card"><div class="feature-icon">📚</div><h3>Kitap Kataloğu</h3><p>ISBN bazlı kitap kaydı, kategori yönetimi ve stok takibi. Arama ve filtreleme ile istediğiniz kitaba anında ulaşın.</p></div>
    <div class="feature-card"><div class="feature-icon">🔄</div><h3>Ödünç Yönetimi</h3><p>Kitap verme ve iade işlemleri otomatik stok güncellemesi ile. Tüm geçmiş kayıtlar güvenle saklanır.</p></div>
    <div class="feature-card"><div class="feature-icon">⏱</div><h3>Gecikme Takibi</h3><p>İade tarihi geçen kitaplar otomatik tespit edilir. Gecikme süresi ve tahmini ceza tutarı anlık hesaplanır.</p></div>
    <div class="feature-card"><div class="feature-icon">✉</div><h3>Talep Sistemi</h3><p>Üyeler müsait kitaplar için ödünç talebinde bulunabilir. Yönetici onay/red işlemi ile süreç yönetilir.</p></div>
    <div class="feature-card"><div class="feature-icon">💰</div><h3>Ceza Yönetimi</h3><p>Gecikmeli iadeler için otomatik ceza hesaplama. Ödeme takibi ve raporlama kolaylığı.</p></div>
    <div class="feature-card"><div class="feature-icon">🔐</div><h3>Rol Bazlı Yetki</h3><p>Yönetici ve üye rolleriyle farklı yetki seviyeleri. Güvenli oturum yönetimi ile kişisel veriler korunur.</p></div>
  </div>
</section>


<footer>© 2026 Kütüphane Otomasyon Sistemi</footer>

<!-- LOGIN / KAYIT MODAL -->
<div class="modal" id="authModal">
  <div class="modal-box">
    <button class="modal-close" onclick="closeModal()">✕</button>
    <div class="tabs">
      <div class="tab active" id="tab-giris-btn" onclick="switchTab('giris')">Giriş Yap</div>
      <div class="tab" id="tab-kayit-btn" onclick="switchTab('kayit')">Kayıt Ol</div>
    </div>
    <div id="alertBox" class="alert"></div>

    <!-- GİRİŞ -->
    <div id="tab-giris">
      <div class="form-group"><label>Kullanıcı Adı</label><input id="g_kadi" type="text" placeholder="kullanici_adi" autocomplete="username"></div>
      <div class="form-group"><label>Şifre</label><input id="g_sifre" type="password" placeholder="••••••••" autocomplete="current-password"></div>
      <button class="btn-submit" onclick="girisYap()">GİRİŞ YAP</button>
      <div style="margin-top:16px;font-size:11px;color:#9b8c7a;text-align:center;font-family:'Segoe UI',sans-serif;font-style:italic;margin-bottom:8px">Demo hesaplar:</div>
      <div class="demo-box">
        <div class="demo-item" onclick="fillDemo('admin','admin123')"><b>Yönetici</b>admin / admin123</div>
        <div class="demo-item" onclick="fillDemo('ahmet','user123')"><b>Üye</b>ahmet / user123</div>
      </div>
    </div>

    <!-- KAYIT -->
    <div id="tab-kayit" style="display:none">
      <div class="form-grid">
        <div class="form-group"><label>Ad *</label><input id="r_ad" placeholder="Adınız"></div>
        <div class="form-group"><label>Soyad *</label><input id="r_soy" placeholder="Soyadınız"></div>
        <div class="form-group full"><label>TC Kimlik No *</label><input id="r_tc" maxlength="11" placeholder="11 haneli TC"></div>
        <div class="form-group"><label>Telefon</label><input id="r_tel" placeholder="0532..."></div>
        <div class="form-group"><label>E-posta *</label><input id="r_mail" type="email" placeholder="@mail.com"></div>
        <div class="form-group full"><label>Adres</label><input id="r_adres" placeholder="İl / İlçe"></div>
        <div class="form-group"><label>Kullanıcı Adı *</label><input id="r_kadi" placeholder="benzersiz_ad"></div>
        <div class="form-group"><label>Şifre *</label><input id="r_sifre" type="password" placeholder="min. 6 karakter"></div>
      </div>
      <button class="btn-submit" onclick="kayitOl()">KAYIT OL</button>
    </div>
  </div>
</div>

<script>
const API = 'api.php';

async function api(action, data={}) {
  try {
    const fd = new FormData();
    fd.append('action', action);
    Object.entries(data).forEach(([k,v]) => fd.append(k, v));
    const r = await fetch(API, {method:'POST', body:fd});
    const text = await r.text();
    try { return JSON.parse(text); }
    catch(e) { console.error('API response:', text); return {error: 'Sunucu hatası'}; }
  } catch(e) {
    return {error: 'Bağlantı hatası: ' + e.message};
  }
}

function openModal()  { document.getElementById('authModal').classList.add('open'); }
function closeModal() { document.getElementById('authModal').classList.remove('open'); hideAlert(); }
document.getElementById('authModal').addEventListener('click', e => { if(e.target===document.getElementById('authModal')) closeModal(); });

function switchTab(t) {
  document.getElementById('tab-giris-btn').classList.toggle('active', t==='giris');
  document.getElementById('tab-kayit-btn').classList.toggle('active', t==='kayit');
  document.getElementById('tab-giris').style.display = t==='giris' ? 'block' : 'none';
  document.getElementById('tab-kayit').style.display = t==='kayit' ? 'block' : 'none';
  hideAlert();
}

function showAlert(msg, type='error') {
  const el = document.getElementById('alertBox');
  el.className = `alert alert-${type}`;
  el.textContent = msg;
  el.style.display = 'block';
}
function hideAlert() { document.getElementById('alertBox').style.display = 'none'; }

function fillDemo(kadi, sifre) {
  document.getElementById('g_kadi').value  = kadi;
  document.getElementById('g_sifre').value = sifre;
  switchTab('giris');
  openModal();
}

async function girisYap() {
  const kadi  = document.getElementById('g_kadi').value.trim();
  const sifre = document.getElementById('g_sifre').value;
  if(!kadi || !sifre) { showAlert('Lütfen tüm alanları doldurun.'); return; }
  const btn = document.querySelector('#tab-giris .btn-submit');
  btn.textContent = 'Giriş yapılıyor...'; btn.disabled = true;
  const res = await api('login', {kullanici_adi: kadi, sifre: sifre});
  btn.textContent = 'GİRİŞ YAP'; btn.disabled = false;
  if(res.ok) {
    window.location.href = res.user.rol === 'admin' ? 'admin/index.php' : 'user/index.php';
  } else {
    showAlert(res.msg || res.error || 'Giriş başarısız.');
  }
}

async function kayitOl() {
  const res = await api('kayit', {
    ad: document.getElementById('r_ad').value,
    soyad: document.getElementById('r_soy').value,
    tc_no: document.getElementById('r_tc').value,
    tel: document.getElementById('r_tel').value,
    mail: document.getElementById('r_mail').value,
    adres: document.getElementById('r_adres').value,
    kullanici_adi: document.getElementById('r_kadi').value,
    sifre: document.getElementById('r_sifre').value,
  });
  if(res.ok) { showAlert(res.msg, 'success'); setTimeout(() => switchTab('giris'), 1800); }
  else showAlert(res.msg || res.error || 'Kayıt başarısız.');
}

document.getElementById('g_sifre').addEventListener('keydown', e => { if(e.key==='Enter') girisYap(); });
</script>
</body>
</html>
