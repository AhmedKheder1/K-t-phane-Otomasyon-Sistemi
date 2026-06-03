<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Yönetici Girişi — Kütüphane</title>
<style>
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:Georgia,'Times New Roman',serif;background:#1a2530;min-height:100vh;display:flex;flex-direction:column;align-items:center;justify-content:center}
.back-link{position:fixed;top:20px;left:24px;color:rgba(255,255,255,.45);font-size:13px;text-decoration:none;font-family:'Segoe UI',sans-serif;display:flex;align-items:center;gap:6px;transition:.2s}
.back-link:hover{color:#fff}
.login-wrap{width:100%;max-width:400px;padding:0 20px}
.emblem{text-align:center;margin-bottom:32px}
.emblem-icon{width:64px;height:64px;border:2px solid rgba(255,255,255,.2);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 14px;font-size:26px;background:rgba(255,255,255,.05)}
.emblem h1{font-size:20px;color:#f5f0e8;letter-spacing:.06em}
.emblem p{font-size:12px;color:rgba(255,255,255,.4);margin-top:6px;font-family:'Segoe UI',sans-serif}
.emblem .role-tag{display:inline-block;background:#8b1a1a;color:#fff;font-size:10px;padding:3px 12px;letter-spacing:.1em;text-transform:uppercase;font-family:'Segoe UI',sans-serif;margin-top:10px}
.card{background:#fdfaf5;border-top:4px solid #8b1a1a;padding:32px 36px}
.alert{padding:10px 14px;border:1px solid;font-size:13px;margin-bottom:16px;font-family:'Segoe UI',sans-serif;display:none}
.alert-error{background:#fef2f2;border-color:#fca5a5;color:#991b1b}
.form-group{margin-bottom:16px}
label{display:block;font-size:11px;font-weight:700;color:#4a3f35;text-transform:uppercase;letter-spacing:.07em;margin-bottom:6px;font-family:'Segoe UI',sans-serif}
input{width:100%;padding:11px 13px;border:1px solid #c8b99a;background:#fff;font-size:14px;font-family:'Segoe UI',sans-serif;outline:none;color:#1a2530;transition:.2s}
input:focus{border-color:#8b1a1a}
.btn-submit{width:100%;padding:13px;background:#8b1a1a;color:#fff;border:none;font-size:14px;font-family:Georgia,serif;letter-spacing:.08em;cursor:pointer;margin-top:6px;transition:.2s}
.btn-submit:hover{background:#a02020}
.btn-submit:disabled{opacity:.6;cursor:not-allowed}
.divider{text-align:center;font-size:11px;color:#9b8c7a;margin:16px 0;font-style:italic}
.demo-box{background:#f5f0e8;border:1px solid #e5ddd0;padding:12px 14px;text-align:center;cursor:pointer;transition:.2s;font-family:'Segoe UI',sans-serif}
.demo-box:hover{border-color:#8b1a1a}
.demo-box b{display:block;font-size:11px;color:#7c6f5e;margin-bottom:3px}
.demo-box span{font-size:13px;color:#1a2530}
.footer-link{text-align:center;margin-top:20px;font-size:12px;color:rgba(255,255,255,.35);font-family:'Segoe UI',sans-serif}
.footer-link a{color:rgba(255,255,255,.5);text-decoration:none}
.footer-link a:hover{color:#fff}
</style>
</head>
<body>

<a href="../index.php" class="back-link">← Ana Sayfa</a>

<div class="login-wrap">
  <div class="emblem">
    <div class="emblem-icon">📚</div>
    <h1>KÜTÜPHANE SİSTEMİ</h1>
    <p>Otomasyon Sistemi</p>
    <div class="role-tag">Yönetici Girişi</div>
  </div>

  <div class="card">
    <div id="alertBox" class="alert"></div>
    <div class="form-group">
      <label>Kullanıcı Adı</label>
      <input id="kadi" type="text" placeholder="Yönetici adınız" autocomplete="username">
    </div>
    <div class="form-group">
      <label>Şifre</label>
      <input id="sifre" type="password" placeholder="••••••••" autocomplete="current-password">
    </div>
    <button class="btn-submit" id="loginBtn" onclick="girisYap()">YÖNETİCİ GİRİŞİ</button>
    
  </div>

  <div class="footer-link">
    Üye misiniz? <a href="login.php" onclick="window.location.href='user_login.php';return false;">Üye Girişi →</a>
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

function showAlert(msg) {
  const el = document.getElementById('alertBox');
  el.className = 'alert alert-error';
  el.textContent = msg;
  el.style.display = 'block';
}



async function girisYap() {
  const kadi  = document.getElementById('kadi').value.trim();
  const sifre = document.getElementById('sifre').value;
  if(!kadi || !sifre) { showAlert('Lütfen tüm alanları doldurun.'); return; }
  const btn = document.getElementById('loginBtn');
  btn.textContent = 'Giriş yapılıyor...'; btn.disabled = true;
  const res = await api('login', {kullanici_adi: kadi, sifre});
  btn.textContent = 'YÖNETİCİ GİRİŞİ'; btn.disabled = false;
  if(res.ok) {
    if(res.user.rol !== 'admin') { showAlert('Bu hesap yönetici değil. Üye girişini kullanın.'); return; }
    window.location.href = 'admin_index.php';
  } else {
    showAlert(res.msg || res.error || 'Giriş başarısız.');
  }
}

document.getElementById('sifre').addEventListener('keydown', e => { if(e.key==='Enter') girisYap(); });
</script>
</body>
</html>
