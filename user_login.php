<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Üye Girişi — Kütüphane</title>
<style>
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:Georgia,'Times New Roman',serif;background:#f5f0e8;min-height:100vh;display:flex;flex-direction:column;align-items:center;justify-content:center}
.back-link{position:fixed;top:20px;left:24px;color:#7c6f5e;font-size:13px;text-decoration:none;font-family:'Segoe UI',sans-serif;display:flex;align-items:center;gap:6px;transition:.2s}
.back-link:hover{color:#1a2530}
.login-wrap{width:100%;max-width:420px;padding:0 20px}
.emblem{text-align:center;margin-bottom:28px}
.emblem-icon{width:60px;height:60px;border:2px solid #c8b99a;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 12px;font-size:24px;background:#fff}
.emblem h1{font-size:19px;color:#1a2530;letter-spacing:.06em}
.emblem p{font-size:12px;color:#7c6f5e;margin-top:5px;font-family:'Segoe UI',sans-serif}
.emblem .role-tag{display:inline-block;background:#1a4a2e;color:#fff;font-size:10px;padding:3px 12px;letter-spacing:.1em;text-transform:uppercase;font-family:'Segoe UI',sans-serif;margin-top:10px}
.card{background:#fff;border:1px solid #c8b99a;border-top:4px solid #1a2530;padding:28px 32px}
.tabs{display:flex;border-bottom:2px solid #e5ddd0;margin-bottom:22px}
.tab{flex:1;padding:9px;text-align:center;font-size:13px;color:#7c6f5e;cursor:pointer;border-bottom:2px solid transparent;margin-bottom:-2px;letter-spacing:.03em;font-family:Georgia,serif;transition:.15s}
.tab.active{color:#1a2530;border-bottom-color:#1a2530;font-weight:700}
.alert{padding:10px 14px;border:1px solid;font-size:13px;margin-bottom:14px;font-family:'Segoe UI',sans-serif;display:none}
.alert-error{background:#fef2f2;border-color:#fca5a5;color:#991b1b}
.alert-success{background:#f0fdf4;border-color:#86efac;color:#166534}
.form-group{margin-bottom:14px}
label{display:block;font-size:11px;font-weight:700;color:#4a3f35;text-transform:uppercase;letter-spacing:.07em;margin-bottom:5px;font-family:'Segoe UI',sans-serif}
input{width:100%;padding:10px 12px;border:1px solid #c8b99a;background:#fdfaf5;font-size:13px;font-family:'Segoe UI',sans-serif;outline:none;color:#1a2530;transition:.2s}
input:focus{border-color:#1a2530;background:#fff}
.form-grid{display:grid;grid-template-columns:1fr 1fr;gap:10px}
.form-grid .full{grid-column:1/-1}
.btn-submit{width:100%;padding:12px;background:#1a2530;color:#f5f0e8;border:none;font-size:14px;font-family:Georgia,serif;letter-spacing:.06em;cursor:pointer;margin-top:4px;transition:.2s}
.btn-submit:hover{background:#2c3e50}
.btn-submit:disabled{opacity:.6;cursor:not-allowed}
.divider{text-align:center;font-size:11px;color:#9b8c7a;margin:14px 0;font-style:italic}
.demo-box{background:#f5f0e8;border:1px solid #e5ddd0;padding:10px 14px;text-align:center;cursor:pointer;transition:.2s;font-family:'Segoe UI',sans-serif}
.demo-box:hover{border-color:#1a2530}
.demo-box b{display:block;font-size:11px;color:#7c6f5e;margin-bottom:2px}
.admin-link{text-align:center;margin-top:18px;font-size:12px;color:#7c6f5e;font-family:'Segoe UI',sans-serif}
.admin-link a{color:#1a2530;text-decoration:none;font-weight:700}
.admin-link a:hover{text-decoration:underline}
</style>
</head>
<body>

<a href="../index.php" class="back-link">← Ana Sayfa</a>

<div class="login-wrap">
  <div class="emblem">
    <div class="emblem-icon">📚</div>
    <h1>KÜTÜPHANE SİSTEMİ</h1>
    <p>Üye Portalı</p>
    <div class="role-tag">Üye Girişi</div>
  </div>

  <div class="card">
    <div class="tabs">
      <div class="tab active" id="tab-giris-btn" onclick="switchTab('giris')">Giriş Yap</div>
      <div class="tab" id="tab-kayit-btn" onclick="switchTab('kayit')">Kayıt Ol</div>
    </div>

    <div id="alertBox" class="alert"></div>

    <!-- GİRİŞ -->
    <div id="tab-giris">
      <div class="form-group">
        <label>Kullanıcı Adı</label>
        <input id="g_kadi" type="text" placeholder="kullanici_adi" autocomplete="username">
      </div>
      <div class="form-group">
        <label>Şifre</label>
        <input id="g_sifre" type="password" placeholder="••••••••" autocomplete="current-password">
      </div>
      <button class="btn-submit" id="loginBtn" onclick="girisYap()">GİRİŞ YAP</button>
      
    </div>

    <!-- KAYIT -->
    <div id="tab-kayit" style="display:none">
      <div class="form-grid">
        <div class="form-group"><label>Ad *</label><input id="r_ad" placeholder="Adınız"></div>
        <div class="form-group"><label>Soyad *</label><input id="r_soy" placeholder="Soyadınız"></div>
        <div class="form-group full"><label>TC Kimlik No *</label><input id="r_tc" maxlength="11" placeholder="11 haneli TC"></div>
        <div class="form-group"><label>Telefon</label><input id="r_tel" placeholder="05xx xxx xxxx"></div>
        <div class="form-group"><label>E-posta *</label><input id="r_mail" type="email" placeholder="@mail.com"></div>
        <div class="form-group full"><label>Adres</label><input id="r_adres" placeholder="İl / İlçe"></div>
        <div class="form-group"><label>Kullanıcı Adı *</label><input id="r_kadi" placeholder="benzersiz_ad"></div>
        <div class="form-group"><label>Şifre *</label><input id="r_sifre" type="password" placeholder="min. 6 karakter"></div>
      </div>
      <button class="btn-submit" onclick="kayitOl()">KAYIT OL</button>
    </div>
  </div>

  <div class="admin-link">
    Yönetici misiniz? <a href="../admin/login.php">Yönetici Girişi →</a>
  </div>
</div>

<script>
const API = 'api.php';

async function api(action, data={}) {
  try {
    const fd = new FormData();
    fd.append('action', action);
    Object.entries(data).forEach(([k,v]) => fd.append(k,v));
    const r = await fetch(API, {method:'POST', body:fd});
    const text = await r.text();
    try { return JSON.parse(text); }
    catch(e) { return {error:'Sunucu hatası: ' + text.substring(0,100)}; }
  } catch(e) { return {error:'Bağlantı hatası'}; }
}

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
function hideAlert() { document.getElementById('alertBox').style.display='none'; }



async function girisYap() {
  const kadi  = document.getElementById('g_kadi').value.trim();
  const sifre = document.getElementById('g_sifre').value;
  if(!kadi || !sifre) { showAlert('Lütfen tüm alanları doldurun.'); return; }
  const btn = document.getElementById('loginBtn');
  btn.textContent = 'Giriş yapılıyor...'; btn.disabled = true;
  const res = await api('login', {kullanici_adi: kadi, sifre});
  btn.textContent = 'GİRİŞ YAP'; btn.disabled = false;
  if(res.ok) {
    if(res.user.rol === 'admin') { showAlert('Yönetici hesabı için yönetici girişini kullanın.'); return; }
    window.location.href = 'user_index.php';
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
