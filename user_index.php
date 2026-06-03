<?php
session_start();
if(empty($_SESSION['user']) || $_SESSION['user']['rol'] !== 'user'){
    header('Location: user_login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Üye Paneli — Kütüphane</title>
<style>
*{box-sizing:border-box;margin:0;padding:0}
:root{--ink:#1a2530;--ink2:#4a3f35;--paper:#fdfaf5;--cream:#f5f0e8;--border:#c8b99a;--border2:#e5ddd0;--accent:#1a4a2e}
body{font-family:'Segoe UI',Arial,sans-serif;background:var(--cream);color:var(--ink)}

header{background:var(--ink);color:#f5f0e8;padding:0 32px;display:flex;align-items:center;justify-content:space-between;height:56px;position:sticky;top:0;z-index:50}
.header-brand{display:flex;align-items:center;gap:12px}
.header-brand h1{font-family:Georgia,serif;font-size:16px;letter-spacing:.06em}
.header-brand span{font-size:11px;color:rgba(255,255,255,.4);border-left:1px solid rgba(255,255,255,.2);padding-left:12px;letter-spacing:.08em;text-transform:uppercase}
.header-right{display:flex;align-items:center;gap:16px}
.header-user{font-size:13px;color:rgba(255,255,255,.7)}
.header-user b{color:#f5f0e8}
.btn-logout{background:none;border:1px solid rgba(255,255,255,.25);color:rgba(255,255,255,.7);padding:6px 14px;font-size:12px;cursor:pointer;font-family:'Segoe UI',sans-serif;letter-spacing:.04em}
.btn-logout:hover{background:rgba(255,255,255,.1);color:#f5f0e8}

.nav-tabs{background:#fff;border-bottom:2px solid var(--border);padding:0 32px;display:flex;gap:0}
.nav-tab{padding:14px 20px;font-size:13px;color:#7c6f5e;cursor:pointer;border-bottom:3px solid transparent;margin-bottom:-2px;letter-spacing:.03em;font-weight:500;display:flex;align-items:center;gap:6px}
.nav-tab:hover{color:var(--ink)}
.nav-tab.active{color:var(--ink);border-bottom-color:var(--accent);font-weight:700}
.nav-tab .cnt{background:var(--accent);color:#fff;font-size:10px;padding:1px 6px;border-radius:10px}

.container{max-width:1100px;margin:0 auto;padding:28px 32px}

.welcome{background:var(--paper);border:1px solid var(--border);border-left:5px solid var(--accent);padding:20px 24px;margin-bottom:24px;display:flex;align-items:center;justify-content:space-between}
.welcome h2{font-family:Georgia,serif;font-size:17px;color:var(--ink)}
.welcome p{font-size:13px;color:#7c6f5e;margin-top:4px;font-style:italic}

.stats{display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:24px}
.stat{background:var(--paper);border:1px solid var(--border);padding:16px;text-align:center}
.stat-val{font-family:Georgia,serif;font-size:28px;color:var(--ink);font-weight:700}
.stat-label{font-size:11px;color:#7c6f5e;text-transform:uppercase;letter-spacing:.07em;margin-top:4px}

.page{display:none}.page.active{display:block}
.section-head{display:flex;align-items:center;justify-content:space-between;margin-bottom:14px}
.section-head h3{font-family:Georgia,serif;font-size:15px;color:var(--ink)}

.card{background:var(--paper);border:1px solid var(--border)}
.card-head{padding:11px 16px;background:#faf7f2;border-bottom:1px solid var(--border2);display:flex;align-items:center;justify-content:space-between}
.card-head h3{font-family:Georgia,serif;font-size:13px;color:var(--ink)}
.toolbar{padding:11px 16px;border-bottom:1px solid var(--border2);display:flex;gap:10px}
.toolbar input{flex:1;padding:8px 11px;border:1px solid var(--border);background:var(--paper);font-size:13px;outline:none}
.toolbar input:focus{border-color:var(--ink)}

table{width:100%;border-collapse:collapse;font-size:13px}
th{padding:10px 14px;background:#f0ead8;color:var(--ink2);font-size:11px;letter-spacing:.07em;text-transform:uppercase;border-bottom:2px solid var(--border);font-weight:700;text-align:left}
td{padding:10px 14px;border-bottom:1px solid var(--border2);vertical-align:middle}
tr:last-child td{border-bottom:none}
tr:hover td{background:#faf7f0}

.badge{display:inline-block;padding:2px 9px;font-size:11px;font-weight:700;letter-spacing:.04em;border:1px solid}
.badge-green{background:#f0fdf4;border-color:#86efac;color:#166534}
.badge-red{background:#fef2f2;border-color:#fca5a5;color:#991b1b}
.badge-amber{background:#fffbeb;border-color:#fcd34d;color:#92400e}
.badge-blue{background:#eff6ff;border-color:#93c5fd;color:#1e3a5f}
.badge-gray{background:#f9fafb;border-color:#d1d5db;color:#374151}

.btn{padding:8px 16px;border:1px solid var(--border);background:var(--paper);color:var(--ink);font-size:12px;cursor:pointer;letter-spacing:.04em;transition:.15s;display:inline-flex;align-items:center;gap:5px}
.btn:hover{background:var(--ink);color:var(--paper);border-color:var(--ink)}
.btn-primary{background:var(--accent);color:#fff;border-color:var(--accent)}
.btn-primary:hover{filter:brightness(.9)}
.btn-sm{padding:5px 10px;font-size:11px}
.btn:disabled{opacity:.4;cursor:not-allowed}

.empty-row{text-align:center;padding:32px;color:#9b8c7a;font-style:italic;font-size:13px}

.modal{display:none;position:fixed;inset:0;background:rgba(10,15,20,.55);z-index:300;align-items:center;justify-content:center}
.modal.open{display:flex}
.modal-box{background:var(--paper);border:1px solid var(--border);border-top:4px solid var(--accent);padding:28px;width:100%;max-width:440px}
.modal-box h3{font-family:Georgia,serif;font-size:15px;margin-bottom:16px;padding-bottom:10px;border-bottom:1px solid var(--border2)}
.modal-close{float:right;background:none;border:none;font-size:20px;cursor:pointer;color:#9b8c7a}
.form-group{margin-bottom:14px}
label{display:block;font-size:11px;font-weight:700;color:var(--ink2);text-transform:uppercase;letter-spacing:.07em;margin-bottom:5px}
select,input{width:100%;padding:9px 11px;border:1px solid var(--border);background:var(--paper);font-size:13px;font-family:'Segoe UI',sans-serif;outline:none;color:var(--ink)}
select:focus,input:focus{border-color:var(--ink)}
.form-actions{display:flex;gap:10px;justify-content:flex-end;margin-top:18px;padding-top:14px;border-top:1px solid var(--border2)}

#toast{position:fixed;bottom:24px;right:24px;z-index:999;display:flex;flex-direction:column;gap:8px}
.toast-item{padding:11px 16px;font-size:13px;border-left:4px solid;min-width:220px;background:var(--paper);box-shadow:0 4px 16px rgba(0,0,0,.1);animation:si .25s ease}
.toast-success{border-color:#166534;color:#166534}.toast-error{border-color:#991b1b;color:#991b1b}
@keyframes si{from{transform:translateX(40px);opacity:0}to{transform:translateX(0);opacity:1}}
</style>
</head>
<body>

<header>
  <div class="header-brand">
    <h1>📚 KÜTÜPHANE SİSTEMİ</h1>
    <span>Üye Portalı</span>
  </div>
  <div class="header-right">
    <span class="header-user">Hoş geldiniz, <b id="userName">—</b></span>
    <button class="btn-logout" onclick="logout()">Çıkış</button>
  </div>
</header>

<div class="nav-tabs">
  <div class="nav-tab active" onclick="showTab('anasayfa',this)">⊞ Ana Sayfa</div>
  <div class="nav-tab" onclick="showTab('katalog',this)">📖 Kitap Kataloğu</div>
  <div class="nav-tab" onclick="showTab('odunclerim',this)">⇄ Ödünçlerim</div>
  <div class="nav-tab" onclick="showTab('taleplerim',this)">✉ Taleplerim <span class="cnt" id="talepCnt" style="display:none">0</span></div>
</div>

<div class="container">

  <!-- ANA SAYFA -->
  <div id="tab-anasayfa" class="page active">
    <div class="welcome">
      <div><h2 id="welcomeMsg">Hoş geldiniz!</h2><p>Kütüphane üye panelinize hoş geldiniz. Katalogdan kitap seçerek ödünç talebinde bulunabilirsiniz.</p></div>
      <button class="btn btn-primary" onclick="showTab('katalog',document.querySelectorAll('.nav-tab')[1])">Kataloga Git →</button>
    </div>
    <div class="stats">
      <div class="stat"><div class="stat-val" id="u-aktif">—</div><div class="stat-label">Aktif Ödünç</div></div>
      <div class="stat"><div class="stat-val" id="u-toplam">—</div><div class="stat-label">Toplam Ödünç</div></div>
      <div class="stat"><div class="stat-val" id="u-talep">—</div><div class="stat-label">Bekleyen Talep</div></div>
      <div class="stat"><div class="stat-val" id="u-ceza">—</div><div class="stat-label">Ceza (₺)</div></div>
    </div>
    <div class="card">
      <div class="card-head"><h3>Son Ödünçlerim</h3></div>
      <table id="anaOduncTable"><thead><tr><th>Kitap</th><th>Ödünç Tarihi</th><th>İade Tarihi</th><th>Durum</th></tr></thead><tbody></tbody></table>
    </div>
  </div>

  <!-- KATALOG -->
  <div id="tab-katalog" class="page">
    <div class="card">
      <div class="card-head"><h3>Kitap Kataloğu</h3><span style="font-size:11px;color:#9b8c7a;font-style:italic">Müsait kitaplar listelenmektedir</span></div>
      <div class="toolbar"><input id="katAra" type="text" placeholder="Kitap adı, yazar veya ISBN ile arayın..."></div>
      <table id="katalogTable">
        <thead><tr><th>Kitap Adı</th><th>Yazar</th><th>Yayınevi</th><th>Kategori</th><th>Raf</th><th>Durum</th><th>İşlem</th></tr></thead>
        <tbody></tbody>
      </table>
    </div>
  </div>

  <!-- ÖDÜNÇLERİM -->
  <div id="tab-odunclerim" class="page">
    <div class="card">
      <div class="card-head"><h3>Ödünç Geçmişim</h3></div>
      <table id="odunclerimTable">
        <thead><tr><th>Kitap</th><th>Yazar</th><th>Ödünç Tarihi</th><th>İade Tarihi</th><th>Gerçek İade</th><th>Durum</th></tr></thead>
        <tbody></tbody>
      </table>
    </div>
  </div>

  <!-- TALEPLERİM -->
  <div id="tab-taleplerim" class="page">
    <div class="card">
      <div class="card-head"><h3>Ödünç Taleplerim</h3></div>
      <table id="taleplerimTable">
        <thead><tr><th>Kitap</th><th>Yazar</th><th>Talep Tarihi</th><th>Durum</th><th>Yönetici Notu</th></tr></thead>
        <tbody></tbody>
      </table>
    </div>
  </div>

</div>

<!-- Talep Modal -->
<div class="modal" id="mTalep"><div class="modal-box">
  <button class="modal-close" onclick="closeModal('mTalep')">✕</button>
  <h3>Ödünç Talebi Gönder</h3>
  <p style="font-size:13px;color:#7c6f5e;margin-bottom:16px;font-style:italic">Seçtiğiniz kitap için ödünç talebiniz yöneticiye iletilecektir.</p>
  <div style="background:#f0ead8;border:1px solid var(--border);padding:12px 16px;margin-bottom:16px">
    <p style="font-size:13px;font-weight:700" id="talepKitapAd">—</p>
    <p style="font-size:12px;color:#7c6f5e;margin-top:3px" id="talepKitapYazar">—</p>
  </div>
  <input type="hidden" id="talep_kitap_id">
  <div class="form-actions">
    <button class="btn" onclick="closeModal('mTalep')">Vazgeç</button>
    <button class="btn btn-primary" onclick="talepGonder()">Talep Gönder</button>
  </div>
</div></div>

<div id="toast"></div>

<script>
const API='api.php';
let ME=null;

async function api(action,data={}){
  const fd=new FormData();fd.append('action',action);
  Object.entries(data).forEach(([k,v])=>fd.append(k,v));
  const r=await fetch(API,{method:'POST',body:fd});
  const j=await r.json();
  if(j.error==='Oturum açmanız gerekiyor.') window.location.href='user_login.php';
  return j;
}

function toast(msg,type='success'){
  const t=document.getElementById('toast');
  const d=document.createElement('div');d.className=`toast-item toast-${type}`;d.textContent=msg;
  t.appendChild(d);setTimeout(()=>d.remove(),3500);
}
function openModal(id){document.getElementById(id).classList.add('open');}
function closeModal(id){document.getElementById(id).classList.remove('open');}
document.querySelectorAll('.modal').forEach(m=>m.addEventListener('click',e=>{if(e.target===m)m.classList.remove('open');}));

function showTab(name,el){
  document.querySelectorAll('.page').forEach(p=>p.classList.remove('active'));
  document.querySelectorAll('.nav-tab').forEach(a=>a.classList.remove('active'));
  document.getElementById('tab-'+name).classList.add('active');
  if(el)el.classList.add('active');
  ({anasayfa:loadAna,katalog:loadKatalog,odunclerim:loadOdunclerim,taleplerim:loadTaleplerim})[name]?.();
}

const fmt=d=>d?d.split('-').reverse().join('.'):'—';
const empty=n=>`<tr><td colspan="${n}" class="empty-row">Kayıt bulunamadı.</td></tr>`;

async function init(){
  ME = {
    kullanici_id: <?php echo (int)$_SESSION['user']['kullanici_id']; ?>,
    ad: '<?php echo htmlspecialchars($_SESSION["user"]["ad"]); ?>',
    soyad: '<?php echo htmlspecialchars($_SESSION["user"]["soyad"]); ?>',
    rol: 'user'
  };
  document.getElementById('userName').textContent=ME.ad+' '+ME.soyad;
  document.getElementById('welcomeMsg').textContent='Hoş geldiniz, '+ME.ad+' Bey/Hanım!';
  loadAna();
  loadTalepCnt();
}

async function logout(){await api('logout');window.location.href='user_login.php';}

async function loadTalepCnt(){
  const rows=await api('talep_benim');
  const b=Array.isArray(rows)?rows.filter(r=>r.durum==='Bekliyor').length:0;
  const cnt=document.getElementById('talepCnt');
  if(b>0){cnt.textContent=b;cnt.style.display='inline';}
}

// ── ANA ──
async function loadAna(){
  const s=await api('user_stats');
  document.getElementById('u-aktif').textContent=s.aktif_odunc??0;
  document.getElementById('u-toplam').textContent=s.toplam_odunc??0;
  document.getElementById('u-talep').textContent=s.bekleyen_talep??0;
  document.getElementById('u-ceza').textContent=parseFloat(s.ceza_toplam||0).toFixed(2);
  const rows=await api('odunc_benim');
  const tb=document.querySelector('#anaOduncTable tbody');
  if(!Array.isArray(rows)||!rows.length){tb.innerHTML=empty(4);return;}
  tb.innerHTML=rows.slice(0,5).map(r=>`<tr>
    <td><b>${r.kitap_adi}</b></td><td>${fmt(r.odunc_tarih)}</td><td>${fmt(r.iade_tarih)}</td>
    <td>${r.durum==='Ödünçte'?'<span class="badge badge-amber">Ödünçte</span>':'<span class="badge badge-green">İade Edildi</span>'}</td>
  </tr>`).join('');
}

// ── KATALOG ──
async function loadKatalog(f=''){
  const rows=await api(f?'kitap_ara':'kitap_musait',f?{filtre:f}:{});
  const tb=document.querySelector('#katalogTable tbody');
  if(!Array.isArray(rows)||!rows.length){tb.innerHTML=empty(7);return;}
  tb.innerHTML=rows.map(r=>`<tr>
    <td><b>${r.ad}</b></td><td>${r.yazar}</td><td>${r.yayinevi}</td>
    <td>${r.kategori_ad}</td><td>${r.raf||'—'}</td>
    <td>${r.mevcut_adet>0?`<span class="badge badge-green">Müsait (${r.mevcut_adet})</span>`:'<span class="badge badge-red">Müsait Değil</span>'}</td>
    <td>${r.mevcut_adet>0?`<button class="btn btn-primary btn-sm" onclick="openTalep(${r.kitap_id},'${r.ad.replace(/'/g,"\\'")}','${r.yazar.replace(/'/g,"\\'")}')">Talep Et</button>`:'<button class="btn btn-sm" disabled>Müsait Değil</button>'}</td>
  </tr>`).join('');
}
document.getElementById('katAra').addEventListener('input',e=>loadKatalog(e.target.value.trim()));

function openTalep(id,ad,yazar){
  document.getElementById('talep_kitap_id').value=id;
  document.getElementById('talepKitapAd').textContent=ad;
  document.getElementById('talepKitapYazar').textContent=yazar;
  openModal('mTalep');
}
async function talepGonder(){
  const res=await api('talep_ekle',{kitap_id:document.getElementById('talep_kitap_id').value});
  toast(res.msg||res.error,res.ok?'success':'error');
  if(res.ok){closeModal('mTalep');loadTalepCnt();}
}

// ── ÖDÜNÇLERİM ──
async function loadOdunclerim(){
  const rows=await api('odunc_benim');
  const tb=document.querySelector('#odunclerimTable tbody');
  if(!Array.isArray(rows)||!rows.length){tb.innerHTML=empty(6);return;}
  tb.innerHTML=rows.map(r=>`<tr>
    <td><b>${r.kitap_adi}</b></td><td>${r.yazar}</td>
    <td>${fmt(r.odunc_tarih)}</td><td>${fmt(r.iade_tarih)}</td><td>${fmt(r.gercek_iade)}</td>
    <td>${r.durum==='Ödünçte'?'<span class="badge badge-amber">Ödünçte</span>':'<span class="badge badge-green">İade Edildi</span>'}</td>
  </tr>`).join('');
}

// ── TALEPLERİM ──
async function loadTaleplerim(){
  const rows=await api('talep_benim');
  const tb=document.querySelector('#taleplerimTable tbody');
  if(!Array.isArray(rows)||!rows.length){tb.innerHTML=empty(5);return;}
  tb.innerHTML=rows.map(r=>`<tr>
    <td><b>${r.kitap_adi}</b></td><td>${r.yazar}</td>
    <td>${fmt(r.talep_tarih)}</td>
    <td>${r.durum==='Bekliyor'?'<span class="badge badge-amber">Bekliyor</span>':r.durum==='Onaylandı'?'<span class="badge badge-green">Onaylandı</span>':'<span class="badge badge-red">Reddedildi</span>'}</td>
    <td>${r.admin_notu||'—'}</td>
  </tr>`).join('');
  loadTalepCnt();
}

init();
</script>
</body>
</html>
