<?php
session_start();
if(empty($_SESSION['user']) || $_SESSION['user']['rol'] !== 'admin'){
    header('Location: admin_login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Yönetim Paneli — Kütüphane</title>
<style>
*{box-sizing:border-box;margin:0;padding:0}
:root{
  --ink:#1a2530;--ink2:#4a3f35;--paper:#fdfaf5;--cream:#f5f0e8;
  --border:#c8b99a;--border2:#e5ddd0;--accent:#8b1a1a;
  --green:#166534;--amber:#92400e;--blue:#1e3a5f;
}
body{font-family:'Segoe UI',Arial,sans-serif;background:var(--cream);color:var(--ink);display:flex;min-height:100vh}

/* ── SIDEBAR ── */
#sidebar{width:250px;background:var(--ink);min-height:100vh;display:flex;flex-direction:column;position:fixed;left:0;top:0;z-index:100}
.side-brand{padding:22px 20px 18px;border-bottom:1px solid rgba(255,255,255,.1);text-align:center}
.side-brand h2{font-family:Georgia,serif;font-size:15px;color:#f5f0e8;letter-spacing:.06em}
.side-brand span{font-size:10px;color:rgba(255,255,255,.35);letter-spacing:.1em;text-transform:uppercase;display:block;margin-top:3px}
.side-brand .badge-admin{display:inline-block;background:var(--accent);color:#fff;font-size:10px;padding:2px 8px;letter-spacing:.08em;margin-top:8px}

nav{flex:1;padding:14px 0}
.nav-group{margin-bottom:4px}
.nav-group-title{padding:10px 20px 4px;font-size:10px;color:rgba(255,255,255,.3);letter-spacing:.12em;text-transform:uppercase}
.nav-link{display:flex;align-items:center;gap:10px;padding:9px 20px;color:rgba(255,255,255,.65);font-size:13px;cursor:pointer;border-left:3px solid transparent;transition:.15s}
.nav-link:hover{color:#f5f0e8;background:rgba(255,255,255,.06)}
.nav-link.active{color:#f5f0e8;background:rgba(139,26,26,.25);border-left-color:var(--accent)}
.nav-link .ico{width:18px;text-align:center;font-size:14px}
.nav-badge{margin-left:auto;background:var(--accent);color:#fff;font-size:10px;padding:1px 6px;border-radius:10px;min-width:18px;text-align:center}

.side-user{padding:14px 20px;border-top:1px solid rgba(255,255,255,.08);display:flex;align-items:center;gap:10px}
.side-user .av{width:32px;height:32px;background:var(--accent);display:flex;align-items:center;justify-content:center;font-size:12px;color:#fff;font-weight:700;flex-shrink:0}
.side-user p{font-size:12px;color:#f5f0e8;line-height:1.3}
.side-user small{font-size:10px;color:rgba(255,255,255,.4)}
.logout-btn{margin-left:auto;background:none;border:none;color:rgba(255,255,255,.4);cursor:pointer;font-size:16px}

/* ── MAIN ── */
#main{margin-left:250px;flex:1;padding:28px 32px}

.topbar{display:flex;align-items:flex-end;justify-content:space-between;margin-bottom:24px;padding-bottom:16px;border-bottom:2px solid var(--border)}
.topbar-left h1{font-family:Georgia,serif;font-size:20px;color:var(--ink);font-weight:700}
.topbar-left p{font-size:12px;color:#7c6f5e;margin-top:3px;font-style:italic}
.btn{padding:8px 18px;border:1px solid var(--border);background:var(--paper);color:var(--ink);font-size:13px;cursor:pointer;font-family:'Segoe UI',sans-serif;transition:.15s;display:inline-flex;align-items:center;gap:6px}
.btn:hover{background:var(--ink);color:var(--paper);border-color:var(--ink)}
.btn-primary{background:var(--ink);color:var(--paper);border-color:var(--ink)}
.btn-primary:hover{background:#2c3e50}
.btn-danger{background:var(--accent);color:#fff;border-color:var(--accent)}
.btn-danger:hover{filter:brightness(.9)}
.btn-success{background:#14532d;color:#fff;border-color:#14532d}
.btn-sm{padding:5px 11px;font-size:12px}

.page{display:none}.page.active{display:block}

/* STATS */
.stats{display:grid;grid-template-columns:repeat(5,1fr);gap:12px;margin-bottom:24px}
.stat{background:var(--paper);border:1px solid var(--border);padding:16px 14px;position:relative;overflow:hidden}
.stat::before{content:'';position:absolute;top:0;left:0;right:0;height:3px}
.stat.s-blue::before{background:var(--blue)}
.stat.s-green::before{background:#166534}
.stat.s-amber::before{background:var(--amber)}
.stat.s-red::before{background:var(--accent)}
.stat.s-ink::before{background:var(--ink)}
.stat-label{font-size:11px;color:#7c6f5e;text-transform:uppercase;letter-spacing:.07em;margin-bottom:8px}
.stat-val{font-family:Georgia,serif;font-size:26px;color:var(--ink);font-weight:700}
.stat-sub{font-size:11px;color:#9b8c7a;margin-top:4px;font-style:italic}

/* CARD */
.card{background:var(--paper);border:1px solid var(--border);margin-bottom:20px}
.card-head{padding:12px 18px;border-bottom:1px solid var(--border2);display:flex;align-items:center;justify-content:space-between;background:#faf7f2}
.card-head h3{font-family:Georgia,serif;font-size:14px;color:var(--ink);font-weight:700;letter-spacing:.03em}
.card-head .note{font-size:11px;color:#9b8c7a;font-style:italic}

/* TOOLBAR */
.toolbar{padding:12px 18px;border-bottom:1px solid var(--border2);display:flex;gap:10px;align-items:center;background:#fdfaf5}
.toolbar input{flex:1;padding:8px 12px;border:1px solid var(--border);background:var(--paper);font-size:13px;outline:none;font-family:'Segoe UI',sans-serif;color:var(--ink)}
.toolbar input:focus{border-color:var(--ink)}

/* TABLE */
.tbl-wrap{overflow-x:auto}
table{width:100%;border-collapse:collapse;font-size:13px}
th{padding:10px 14px;text-align:left;background:#f0ead8;color:var(--ink2);font-size:11px;letter-spacing:.07em;text-transform:uppercase;border-bottom:2px solid var(--border);font-weight:700}
td{padding:10px 14px;border-bottom:1px solid var(--border2);color:var(--ink);vertical-align:middle}
tr:last-child td{border-bottom:none}
tr:hover td{background:#faf7f0}

/* BADGES */
.badge{display:inline-block;padding:2px 9px;font-size:11px;font-weight:700;letter-spacing:.04em;border:1px solid}
.badge-green{background:#f0fdf4;border-color:#86efac;color:#166534}
.badge-red{background:#fef2f2;border-color:#fca5a5;color:#991b1b}
.badge-amber{background:#fffbeb;border-color:#fcd34d;color:#92400e}
.badge-blue{background:#eff6ff;border-color:#93c5fd;color:#1e3a5f}
.badge-gray{background:#f9fafb;border-color:#d1d5db;color:#374151}

/* MODAL */
.modal{display:none;position:fixed;inset:0;background:rgba(10,15,20,.55);z-index:300;align-items:center;justify-content:center}
.modal.open{display:flex}
.modal-box{background:var(--paper);border:1px solid var(--border);border-top:4px solid var(--ink);padding:28px 32px;width:100%;max-width:500px;max-height:90vh;overflow-y:auto}
.modal-box h3{font-family:Georgia,serif;font-size:16px;margin-bottom:20px;color:var(--ink);padding-bottom:10px;border-bottom:1px solid var(--border2)}
.modal-close{float:right;background:none;border:none;font-size:20px;cursor:pointer;color:#9b8c7a;margin-top:-4px}
.form-group{margin-bottom:14px}
label{display:block;font-size:11px;font-weight:700;color:var(--ink2);text-transform:uppercase;letter-spacing:.07em;margin-bottom:5px}
input,select,textarea{width:100%;padding:9px 11px;border:1px solid var(--border);background:var(--paper);font-size:13px;font-family:'Segoe UI',sans-serif;outline:none;color:var(--ink)}
input:focus,select:focus,textarea:focus{border-color:var(--ink)}
.form-grid{display:grid;grid-template-columns:1fr 1fr;gap:12px}
.form-grid .full{grid-column:1/-1}
.form-actions{display:flex;gap:10px;justify-content:flex-end;margin-top:20px;padding-top:16px;border-top:1px solid var(--border2)}

/* TOAST */
#toast{position:fixed;bottom:24px;right:24px;z-index:999;display:flex;flex-direction:column;gap:8px}
.toast-item{padding:11px 16px;font-size:13px;border-left:4px solid;min-width:220px;background:var(--paper);box-shadow:0 4px 16px rgba(0,0,0,.12);animation:slideIn .25s ease}
.toast-success{border-color:#166534;color:#166534}
.toast-error{border-color:#991b1b;color:#991b1b}
.toast-info{border-color:var(--blue);color:var(--blue)}
@keyframes slideIn{from{transform:translateX(40px);opacity:0}to{transform:translateX(0);opacity:1}}
.empty-row{text-align:center;padding:32px;color:#9b8c7a;font-style:italic;font-size:13px}
</style>
</head>
<body>

<!-- SIDEBAR -->
<div id="sidebar">
  <div class="side-brand">
    <h2>📚 KÜTÜPHANE</h2>
    <span>Otomasyon Sistemi</span>
    <div class="badge-admin">YÖNETİCİ PANELİ</div>
  </div>
  <nav>
    <div class="nav-group">
      <div class="nav-group-title">Genel Bakış</div>
      <div class="nav-link active" onclick="showPage('dashboard',this)"><span class="ico">⊞</span> Kontrol Paneli</div>
    </div>
    <div class="nav-group">
      <div class="nav-group-title">Katalog</div>
      <div class="nav-link" onclick="showPage('kitaplar',this)"><span class="ico">📖</span> Kitap Yönetimi</div>
      <div class="nav-link" onclick="showPage('kategoriler',this)"><span class="ico">§</span> Kategoriler</div>
    </div>
    <div class="nav-group">
      <div class="nav-group-title">Üyeler & İşlemler</div>
      <div class="nav-link" onclick="showPage('kullanici',this)"><span class="ico">👤</span> Kullanıcılar</div>
      <div class="nav-link" onclick="showPage('odunc',this)"><span class="ico">⇄</span> Ödünç İşlemleri</div>
      <div class="nav-link" onclick="showPage('talepler',this)"><span class="ico">✉</span> Talepler <span class="nav-badge" id="talepBadge">0</span></div>
    </div>
    <div class="nav-group">
      <div class="nav-group-title">Takip</div>
      <div class="nav-link" onclick="showPage('gecikme',this)"><span class="ico">⏱</span> Gecikmeler</div>
      <div class="nav-link" onclick="showPage('ceza',this)"><span class="ico">₺</span> Cezalar</div>
    </div>
  </nav>
  <div class="side-user">
    <div class="av" id="adminAv">A</div>
    <div><p id="adminAd">Yönetici</p><small>Yönetici</small></div>
    <button class="logout-btn" onclick="logout()" title="Çıkış">⏻</button>
  </div>
</div>

<!-- MAIN -->
<main id="main">

  <!-- DASHBOARD -->
  <div id="page-dashboard" class="page active">
    <div class="topbar">
      <div class="topbar-left"><h1>Kontrol Paneli</h1><p id="tarihStr"></p></div>
    </div>
    <div class="stats">
      <div class="stat s-blue"><div class="stat-label">Aktif Üye</div><div class="stat-val" id="s-uye">—</div><div class="stat-sub">Kayıtlı üye</div></div>
      <div class="stat s-green"><div class="stat-label">Kitap</div><div class="stat-val" id="s-kitap">—</div><div class="stat-sub">Katalogda</div></div>
      <div class="stat s-ink"><div class="stat-label">Ödünçte</div><div class="stat-val" id="s-odunc">—</div><div class="stat-sub">Aktif ödünç</div></div>
      <div class="stat s-amber"><div class="stat-label">Bekleyen Talep</div><div class="stat-val" id="s-talep">—</div><div class="stat-sub">Onay bekliyor</div></div>
      <div class="stat s-red"><div class="stat-label">Ceza (₺)</div><div class="stat-val" id="s-ceza">—</div><div class="stat-sub">Ödenmemiş</div></div>
    </div>
    <div class="card">
      <div class="card-head"><h3>Son Ödünç Kayıtları</h3><span class="note">Son 10 işlem</span></div>
      <div class="tbl-wrap"><table id="dashTable"><thead><tr><th>#</th><th>Kullanıcı</th><th>Kitap</th><th>Ödünç Tarihi</th><th>İade Tarihi</th><th>Durum</th></tr></thead><tbody></tbody></table></div>
    </div>
  </div>

  <!-- KİTAPLAR -->
  <div id="page-kitaplar" class="page">
    <div class="topbar"><div class="topbar-left"><h1>Kitap Yönetimi</h1><p>Kütüphane kataloğu</p></div><button class="btn btn-primary" onclick="openModal('mKitapEkle')">+ Yeni Kitap</button></div>
    <div class="card">
      <div class="toolbar"><input id="kitapAra" type="text" placeholder="Kitap adı, yazar veya ISBN..."></div>
      <div class="tbl-wrap"><table id="kitapTable"><thead><tr><th>ISBN</th><th>Kitap Adı</th><th>Yazar</th><th>Yayınevi</th><th>Kategori</th><th>Toplam</th><th>Mevcut</th><th>Raf</th><th>İşlem</th></tr></thead><tbody></tbody></table></div>
    </div>
  </div>

  <!-- KULLANICI -->
  <div id="page-kullanici" class="page">
    <div class="topbar"><div class="topbar-left"><h1>Kullanıcı Yönetimi</h1><p>Üye ve yönetici hesapları</p></div></div>
    <div class="card">
      <div class="tbl-wrap"><table id="kulTable"><thead><tr><th>Ad Soyad</th><th>Kullanıcı Adı</th><th>TC No</th><th>Tel</th><th>Rol</th><th>Kayıt</th><th>Durum</th><th>İşlem</th></tr></thead><tbody></tbody></table></div>
    </div>
  </div>

  <!-- ÖDÜNÇ -->
  <div id="page-odunc" class="page">
    <div class="topbar"><div class="topbar-left"><h1>Ödünç İşlemleri</h1><p>Verme ve iade kayıtları</p></div><button class="btn btn-primary" onclick="openModal('mOduncVer')">+ Kitap Ver</button></div>
    <div class="card">
      <div class="tbl-wrap"><table id="oduncTable"><thead><tr><th>Kullanıcı</th><th>Kitap</th><th>Ödünç Tarihi</th><th>İade Tarihi</th><th>Gerçek İade</th><th>Durum</th><th>İşlem</th></tr></thead><tbody></tbody></table></div>
    </div>
  </div>

  <!-- TALEPLER -->
  <div id="page-talepler" class="page">
    <div class="topbar"><div class="topbar-left"><h1>Ödünç Talepleri</h1><p>Üyelerin istekleri</p></div></div>
    <div class="card">
      <div class="tbl-wrap"><table id="talepTable"><thead><tr><th>Kullanıcı</th><th>Tel</th><th>Kitap</th><th>Talep Tarihi</th><th>Durum</th><th>İşlem</th></tr></thead><tbody></tbody></table></div>
    </div>
  </div>

  <!-- GECİKME -->
  <div id="page-gecikme" class="page">
    <div class="topbar"><div class="topbar-left"><h1>Geciken Kitaplar</h1><p>İade tarihi geçmiş ödünçler</p></div></div>
    <div class="card">
      <div class="tbl-wrap"><table id="gecikmeTable"><thead><tr><th>Kullanıcı</th><th>Tel</th><th>Kitap</th><th>İade Tarihi</th><th>Geciken Gün</th><th>Tahmini Ceza</th></tr></thead><tbody></tbody></table></div>
    </div>
  </div>

  <!-- CEZA -->
  <div id="page-ceza" class="page">
    <div class="topbar"><div class="topbar-left"><h1>Ceza Yönetimi</h1><p>Gecikme ücretleri</p></div></div>
    <div class="card">
      <div class="tbl-wrap"><table id="cezaTable"><thead><tr><th>Kullanıcı</th><th>Kitap</th><th>Geciken Gün</th><th>Tutar</th><th>Durum</th><th>İşlem</th></tr></thead><tbody></tbody></table></div>
    </div>
  </div>

  <!-- KATEGORİLER -->
  <div id="page-kategoriler" class="page">
    <div class="topbar"><div class="topbar-left"><h1>Kategoriler</h1></div><button class="btn btn-primary" onclick="openModal('mKatEkle')">+ Yeni Kategori</button></div>
    <div class="card">
      <div class="tbl-wrap"><table id="katTable"><thead><tr><th>Kategori Adı</th><th>Açıklama</th><th>İşlem</th></tr></thead><tbody></tbody></table></div>
    </div>
  </div>

</main>

<!-- MODALS -->
<div class="modal" id="mKitapEkle"><div class="modal-box">
  <button class="modal-close" onclick="closeModal('mKitapEkle')">✕</button>
  <h3>Yeni Kitap Ekle</h3>
  <div class="form-grid">
    <div class="form-group"><label>ISBN *</label><input id="ke_isbn"></div>
    <div class="form-group"><label>Basım Yılı</label><input id="ke_yil" type="number" value="2024"></div>
    <div class="form-group full"><label>Kitap Adı *</label><input id="ke_ad"></div>
    <div class="form-group"><label>Yazar *</label><input id="ke_yazar"></div>
    <div class="form-group"><label>Yayınevi</label><input id="ke_yay"></div>
    <div class="form-group"><label>Kategori</label><select id="ke_kat"></select></div>
    <div class="form-group"><label>Adet</label><input id="ke_adet" type="number" value="1" min="1"></div>
    <div class="form-group"><label>Raf</label><input id="ke_raf" placeholder="A-01"></div>
  </div>
  <div class="form-actions">
    <button class="btn" onclick="closeModal('mKitapEkle')">İptal</button>
    <button class="btn btn-primary" onclick="kitapEkle()">Kaydet</button>
  </div>
</div></div>

<div class="modal" id="mKitapDuzenle"><div class="modal-box">
  <button class="modal-close" onclick="closeModal('mKitapDuzenle')">✕</button>
  <h3>Kitap Düzenle</h3>
  <input type="hidden" id="kd_id">
  <div class="form-grid">
    <div class="form-group full"><label>Kitap Adı *</label><input id="kd_ad"></div>
    <div class="form-group"><label>Yazar</label><input id="kd_yazar"></div>
    <div class="form-group"><label>Yayınevi</label><input id="kd_yay"></div>
    <div class="form-group"><label>Yıl</label><input id="kd_yil" type="number"></div>
    <div class="form-group"><label>Kategori</label><select id="kd_kat"></select></div>
    <div class="form-group"><label>Raf</label><input id="kd_raf"></div>
  </div>
  <div class="form-actions">
    <button class="btn" onclick="closeModal('mKitapDuzenle')">İptal</button>
    <button class="btn btn-primary" onclick="kitapGuncelle()">Güncelle</button>
  </div>
</div></div>

<div class="modal" id="mOduncVer"><div class="modal-box">
  <button class="modal-close" onclick="closeModal('mOduncVer')">✕</button>
  <h3>Kitap Ödünç Ver</h3>
  <div class="form-group"><label>Kullanıcı *</label><select id="ov_kul"></select></div>
  <div class="form-group"><label>Kitap *</label><select id="ov_kitap"></select></div>
  <div class="form-group"><label>İade Tarihi *</label><input id="ov_iade" type="date"></div>
  <div class="form-actions">
    <button class="btn" onclick="closeModal('mOduncVer')">İptal</button>
    <button class="btn btn-success" onclick="oduncVer()">Ödünç Ver</button>
  </div>
</div></div>

<div class="modal" id="mKatEkle"><div class="modal-box">
  <button class="modal-close" onclick="closeModal('mKatEkle')">✕</button>
  <h3>Yeni Kategori</h3>
  <div class="form-group"><label>Kategori Adı *</label><input id="kat_ad"></div>
  <div class="form-group"><label>Açıklama</label><input id="kat_ac"></div>
  <div class="form-actions">
    <button class="btn" onclick="closeModal('mKatEkle')">İptal</button>
    <button class="btn btn-primary" onclick="katEkle()">Kaydet</button>
  </div>
</div></div>

<div class="modal" id="mTalepNot"><div class="modal-box">
  <button class="modal-close" onclick="closeModal('mTalepNot')">✕</button>
  <h3 id="mTalepNotTitle">Talep İşlemi</h3>
  <input type="hidden" id="tn_id"><input type="hidden" id="tn_action">
  <div class="form-group"><label>Not (isteğe bağlı)</label><textarea id="tn_not" rows="3" placeholder="Üyeye iletilecek not..."></textarea></div>
  <div class="form-actions">
    <button class="btn" onclick="closeModal('mTalepNot')">İptal</button>
    <button class="btn btn-primary" id="tn_btn" onclick="talepKaydet()">Onayla</button>
  </div>
</div></div>

<div id="toast"></div>

<script>
const API = 'api.php';

async function api(action, data={}) {
  const fd = new FormData();
  fd.append('action',action);
  Object.entries(data).forEach(([k,v])=>fd.append(k,v));
  const r = await fetch(API,{method:'POST',body:fd});
  const j = await r.json();
  if(j.error==='Oturum açmanız gerekiyor.') window.location.href='admin_login.php';
  return j;
}

function toast(msg,type='success'){
  const t=document.getElementById('toast');
  const d=document.createElement('div');
  d.className=`toast-item toast-${type}`;
  d.textContent=msg;
  t.appendChild(d);
  setTimeout(()=>d.remove(),3500);
}

function openModal(id){document.getElementById(id).classList.add('open');}
function closeModal(id){document.getElementById(id).classList.remove('open');}
document.querySelectorAll('.modal').forEach(m=>m.addEventListener('click',e=>{if(e.target===m)m.classList.remove('open');}));

function showPage(name,el){
  document.querySelectorAll('.page').forEach(p=>p.classList.remove('active'));
  document.querySelectorAll('.nav-link').forEach(a=>a.classList.remove('active'));
  document.getElementById('page-'+name).classList.add('active');
  if(el) el.classList.add('active');
  ({dashboard:loadDashboard,kitaplar:loadKitaplar,kullanici:loadKullanici,
    odunc:loadOdunc,talepler:loadTalepler,gecikme:loadGecikme,
    ceza:loadCeza,kategoriler:loadKategoriler})[name]?.();
}

const fmt=d=>d?d.split('-').reverse().join('.'):'—';
const empty=n=>`<tr><td colspan="${n}" class="empty-row">Kayıt bulunamadı.</td></tr>`;

// ── INIT ──
async function init(){
  document.getElementById('adminAd').textContent='<?php echo htmlspecialchars($_SESSION["user"]["ad"]." ".$_SESSION["user"]["soyad"]); ?>';
  document.getElementById('adminAv').textContent='<?php echo strtoupper(substr($_SESSION["user"]["ad"],0,1).substr($_SESSION["user"]["soyad"],0,1)); ?>';
  const g=['Pazar','Pazartesi','Salı','Çarşamba','Perşembe','Cuma','Cumartesi'];
  const now=new Date();
  document.getElementById('tarihStr').textContent=g[now.getDay()]+', '+now.toLocaleDateString('tr-TR',{day:'2-digit',month:'long',year:'numeric'});
  loadDashboard();
  // badge
  api('talep_listele').then(rows=>{
    const b=rows.filter?rows.filter(r=>r.durum==='Bekliyor').length:0;
    document.getElementById('talepBadge').textContent=b;
  });
}

async function logout(){await api('logout');window.location.href='admin_login.php';}

// ── DASHBOARD ──
async function loadDashboard(){
  const s=await api('admin_stats');
  document.getElementById('s-uye').textContent=s.toplam_uye??0;
  document.getElementById('s-kitap').textContent=s.toplam_kitap??0;
  document.getElementById('s-odunc').textContent=s.aktif_odunc??0;
  document.getElementById('s-talep').textContent=s.bekleyen_talep??0;
  document.getElementById('s-ceza').textContent=parseFloat(s.odenmemis_ceza||0).toFixed(2);
  const rows=await api('odunc_listele');
  const tb=document.querySelector('#dashTable tbody');
  if(!rows.length){tb.innerHTML=empty(6);return;}
  tb.innerHTML=rows.slice(0,10).map(r=>`<tr>
    <td>${r.odunc_id}</td><td>${r.kullanici_adi}</td><td><b>${r.kitap_adi}</b></td>
    <td>${fmt(r.odunc_tarih)}</td><td>${fmt(r.iade_tarih)}</td>
    <td>${r.durum==='Ödünçte'?'<span class="badge badge-amber">Ödünçte</span>':'<span class="badge badge-green">İade Edildi</span>'}</td>
  </tr>`).join('');
}

// ── KİTAPLAR ──
async function loadKitaplar(f=''){
  const rows=await api(f?'kitap_ara':'kitap_listele',f?{filtre:f}:{});
  const tb=document.querySelector('#kitapTable tbody');
  if(!rows.length){tb.innerHTML=empty(9);return;}
  tb.innerHTML=rows.map(r=>`<tr>
    <td><small>${r.isbn}</small></td><td><b>${r.ad}</b></td><td>${r.yazar}</td>
    <td>${r.yayinevi}</td><td>${r.kategori_ad}</td><td>${r.toplam_adet}</td>
    <td>${r.mevcut_adet>0?`<span class="badge badge-green">${r.mevcut_adet}</span>`:'<span class="badge badge-red">0</span>'}</td>
    <td>${r.raf||'—'}</td>
    <td style="white-space:nowrap">
      <button class="btn btn-sm" onclick='openKitapDuzenle(${JSON.stringify(r)})'>Düzenle</button>
      <button class="btn btn-danger btn-sm" onclick="kitapSil(${r.kitap_id})">Sil</button>
    </td>
  </tr>`).join('');
}
document.getElementById('kitapAra').addEventListener('input',e=>loadKitaplar(e.target.value.trim()));

async function kitapEkle(){
  const res=await api('kitap_ekle',{isbn:document.getElementById('ke_isbn').value,ad:document.getElementById('ke_ad').value,yazar:document.getElementById('ke_yazar').value,yayinevi:document.getElementById('ke_yay').value,baski_yil:document.getElementById('ke_yil').value,kategori_id:document.getElementById('ke_kat').value,adet:document.getElementById('ke_adet').value,raf:document.getElementById('ke_raf').value});
  toast(res.msg||res.error,res.ok?'success':'error');
  if(res.ok){closeModal('mKitapEkle');loadKitaplar();}
}
function openKitapDuzenle(r){
  document.getElementById('kd_id').value=r.kitap_id;
  document.getElementById('kd_ad').value=r.ad;
  document.getElementById('kd_yazar').value=r.yazar;
  document.getElementById('kd_yay').value=r.yayinevi;
  document.getElementById('kd_yil').value=r.baski_yil;
  document.getElementById('kd_raf').value=r.raf||'';
  fillKat('kd_kat',r.kategori_id);
  openModal('mKitapDuzenle');
}
async function kitapGuncelle(){
  const res=await api('kitap_guncelle',{kitap_id:document.getElementById('kd_id').value,ad:document.getElementById('kd_ad').value,yazar:document.getElementById('kd_yazar').value,yayinevi:document.getElementById('kd_yay').value,baski_yil:document.getElementById('kd_yil').value,kategori_id:document.getElementById('kd_kat').value,raf:document.getElementById('kd_raf').value});
  toast(res.msg||res.error,res.ok?'success':'error');
  if(res.ok){closeModal('mKitapDuzenle');loadKitaplar();}
}
async function kitapSil(id){
  if(!confirm('Bu kitabı silmek istediğinize emin misiniz?'))return;
  const res=await api('kitap_sil',{kitap_id:id});
  toast(res.msg||res.error,res.ok?'success':'error');
  if(res.ok)loadKitaplar();
}

// ── KULLANICI ──
async function loadKullanici(){
  const rows=await api('kul_listele');
  const tb=document.querySelector('#kulTable tbody');
  if(!rows.length){tb.innerHTML=empty(8);return;}
  tb.innerHTML=rows.map(r=>`<tr>
    <td><b>${r.ad} ${r.soyad}</b></td><td>${r.kullanici_adi}</td>
    <td><small>${r.tc_no}</small></td><td>${r.tel}</td>
    <td>${r.rol==='admin'?'<span class="badge badge-red">Yönetici</span>':'<span class="badge badge-blue">Üye</span>'}</td>
    <td>${fmt(r.kayit_tarih)}</td>
    <td>${r.aktif==1?'<span class="badge badge-green">Aktif</span>':'<span class="badge badge-gray">Pasif</span>'}</td>
    <td><button class="btn btn-danger btn-sm" onclick="kulSil(${r.kullanici_id})">Pasif Yap</button></td>
  </tr>`).join('');
}
async function kulSil(id){
  if(!confirm('Kullanıcıyı pasif yapmak istediğinize emin misiniz?'))return;
  const res=await api('kul_sil',{kullanici_id:id});
  toast(res.msg||res.error,res.ok?'success':'error');
  if(res.ok)loadKullanici();
}

// ── ÖDÜNÇ ──
async function loadOdunc(){
  const rows=await api('odunc_listele');
  const tb=document.querySelector('#oduncTable tbody');
  if(!rows.length){tb.innerHTML=empty(7);return;}
  tb.innerHTML=rows.map(r=>`<tr>
    <td>${r.kullanici_adi}</td><td><b>${r.kitap_adi}</b></td>
    <td>${fmt(r.odunc_tarih)}</td><td>${fmt(r.iade_tarih)}</td><td>${fmt(r.gercek_iade)}</td>
    <td>${r.durum==='Ödünçte'?'<span class="badge badge-amber">Ödünçte</span>':'<span class="badge badge-green">İade Edildi</span>'}</td>
    <td>${r.durum==='Ödünçte'?`<button class="btn btn-success btn-sm" onclick="oduncIade(${r.odunc_id})">İade Al</button>`:'—'}</td>
  </tr>`).join('');
}
async function fillOduncModal(){
  const [kuls,kitaplar]=await Promise.all([api('kul_listele'),api('kitap_listele')]);
  document.getElementById('ov_kul').innerHTML=kuls.filter(u=>u.aktif==1&&u.rol==='user').map(u=>`<option value="${u.kullanici_id}">${u.ad} ${u.soyad}</option>`).join('');
  document.getElementById('ov_kitap').innerHTML=kitaplar.filter(k=>k.mevcut_adet>0).map(k=>`<option value="${k.kitap_id}">${k.ad}</option>`).join('');
  const d=new Date();d.setDate(d.getDate()+30);
  document.getElementById('ov_iade').value=d.toISOString().split('T')[0];
}
async function oduncVer(){
  const res=await api('odunc_ver',{kullanici_id:document.getElementById('ov_kul').value,kitap_id:document.getElementById('ov_kitap').value,iade_tarih:document.getElementById('ov_iade').value});
  toast(res.msg||res.error,res.ok?'success':'error');
  if(res.ok){closeModal('mOduncVer');loadOdunc();}
}
async function oduncIade(id){
  if(!confirm('Kitabı iade almak istediğinize emin misiniz?'))return;
  const res=await api('odunc_iade',{odunc_id:id});
  toast(res.msg||res.error,res.ok?'success':'error');
  if(res.ok)loadOdunc();
}

// ── TALEPLER ──
async function loadTalepler(){
  const rows=await api('talep_listele');
  const tb=document.querySelector('#talepTable tbody');
  if(!rows.length){tb.innerHTML=empty(6);return;}
  tb.innerHTML=rows.map(r=>`<tr>
    <td><b>${r.kullanici_adi}</b></td><td>${r.tel}</td><td>${r.kitap_adi}</td>
    <td>${fmt(r.talep_tarih)}</td>
    <td>${r.durum==='Bekliyor'?'<span class="badge badge-amber">Bekliyor</span>':r.durum==='Onaylandı'?'<span class="badge badge-green">Onaylandı</span>':'<span class="badge badge-red">Reddedildi</span>'}</td>
    <td style="white-space:nowrap">${r.durum==='Bekliyor'?`
      <button class="btn btn-success btn-sm" onclick="openTalepNot(${r.talep_id},'onayla')">Onayla</button>
      <button class="btn btn-danger btn-sm" onclick="openTalepNot(${r.talep_id},'reddet')">Reddet</button>`:'—'}
    </td>
  </tr>`).join('');
  document.getElementById('talepBadge').textContent=rows.filter(r=>r.durum==='Bekliyor').length;
}
function openTalepNot(id,action){
  document.getElementById('tn_id').value=id;
  document.getElementById('tn_action').value=action;
  document.getElementById('tn_not').value='';
  document.getElementById('mTalepNotTitle').textContent=action==='onayla'?'Talebi Onayla':'Talebi Reddet';
  document.getElementById('tn_btn').textContent=action==='onayla'?'Onayla':'Reddet';
  openModal('mTalepNot');
}
async function talepKaydet(){
  const id=document.getElementById('tn_id').value;
  const action=document.getElementById('tn_action').value;
  const not=document.getElementById('tn_not').value;
  const res=await api('talep_'+action,{talep_id:id,not});
  toast(res.msg||res.error,res.ok?'success':'error');
  if(res.ok){closeModal('mTalepNot');loadTalepler();}
}

// ── GECİKME ──
async function loadGecikme(){
  const rows=await api('odunc_gecikme');
  const tb=document.querySelector('#gecikmeTable tbody');
  if(!rows.length){tb.innerHTML='<tr><td colspan="6" class="empty-row">✓ Gecikmiş kitap bulunmuyor.</td></tr>';return;}
  tb.innerHTML=rows.map(r=>`<tr>
    <td>${r.kullanici_adi}</td><td>${r.tel}</td><td><b>${r.kitap_adi}</b></td>
    <td>${fmt(r.iade_tarih)}</td>
    <td><span class="badge badge-red">${r.gec_gun} gün</span></td>
    <td><b>${parseFloat(r.tahmini_ceza).toFixed(2)} ₺</b></td>
  </tr>`).join('');
}

// ── CEZA ──
async function loadCeza(){
  const rows=await api('ceza_listele');
  const tb=document.querySelector('#cezaTable tbody');
  if(!rows.length){tb.innerHTML=empty(6);return;}
  tb.innerHTML=rows.map(r=>`<tr>
    <td>${r.kullanici_adi}</td><td>${r.kitap_adi}</td>
    <td>${r.gec_gun} gün</td><td><b>${parseFloat(r.tutar).toFixed(2)} ₺</b></td>
    <td>${r.odendi_mi==1?'<span class="badge badge-green">Ödendi</span>':'<span class="badge badge-red">Ödenmedi</span>'}</td>
    <td>${r.odendi_mi==0?`<button class="btn btn-success btn-sm" onclick="cezaOdendi(${r.ceza_id})">Ödendi</button>`:'—'}</td>
  </tr>`).join('');
}
async function cezaOdendi(id){
  const res=await api('ceza_odendi',{ceza_id:id});
  toast(res.msg||res.error,res.ok?'success':'error');
  if(res.ok)loadCeza();
}

// ── KATEGORİ ──
async function loadKategoriler(){
  const rows=await api('kat_listele');
  const tb=document.querySelector('#katTable tbody');
  if(!rows.length){tb.innerHTML=empty(3);return;}
  tb.innerHTML=rows.map(r=>`<tr>
    <td><b>${r.kategori_ad}</b></td><td>${r.aciklama||'—'}</td>
    <td><button class="btn btn-danger btn-sm" onclick="katSil(${r.kategori_id})">Sil</button></td>
  </tr>`).join('');
}
async function katEkle(){
  const res=await api('kat_ekle',{ad:document.getElementById('kat_ad').value,ac:document.getElementById('kat_ac').value});
  toast(res.msg||res.error,res.ok?'success':'error');
  if(res.ok){closeModal('mKatEkle');loadKategoriler();}
}
async function katSil(id){
  if(!confirm('Silinsin mi?'))return;
  const res=await api('kat_sil',{kategori_id:id});
  toast(res.msg||res.error,res.ok?'success':'error');
  if(res.ok)loadKategoriler();
}

// ── KAT SELECT ──
async function fillKat(elId,sel=null){
  const kats=await api('kat_listele');
  document.getElementById(elId).innerHTML=kats.map(k=>`<option value="${k.kategori_id}"${k.kategori_id==sel?' selected':''}>${k.kategori_ad}</option>`).join('');
}

const _open=openModal;
window.openModal=async function(id){
  if(id==='mKitapEkle') await fillKat('ke_kat');
  if(id==='mOduncVer') await fillOduncModal();
  _open(id);
};

init();
</script>
</body>
</html>
