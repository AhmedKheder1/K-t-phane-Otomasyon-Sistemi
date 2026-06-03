<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__.'/business.php';

$bl     = new BL();
$action = $_REQUEST['action'] ?? '';
$data   = $_POST;

$public = ['login','kayit'];

if(!in_array($action,$public) && empty($_SESSION['user'])){
    echo json_encode(['error'=>'Oturum açmanız gerekiyor.']); exit;
}

$user = isset($_SESSION['user']) ? $_SESSION['user'] : null;
$rol  = $user ? $user['rol'] : '';
$kid  = $user ? (int)$user['kullanici_id'] : 0;

$adminOnly = ['kitap_ekle','kitap_guncelle','kitap_sil','kul_listele','kul_sil','kul_rol',
              'odunc_ver','odunc_iade','talep_listele','talep_onayla','talep_reddet',
              'ceza_listele','ceza_odendi','kat_ekle','kat_sil','admin_stats',
              'odunc_listele','odunc_gecikme'];

if(in_array($action,$adminOnly) && $rol !== 'admin'){
    echo json_encode(['error'=>'Yetersiz yetki.']); exit;
}

try {
    $result = null;

    if($action === 'login') {
        $r = $bl->login($data['kullanici_adi'] ?? '', $data['sifre'] ?? '');
        if($r['ok']) $_SESSION['user'] = $r['user'];
        $result = $r;
    }
    elseif($action === 'logout') {
        session_unset();
        session_destroy();
        $result = ['ok'=>true];
    }
    elseif($action === 'kayit')          { $result = $bl->kayit($data); }
    elseif($action === 'me')             { $result = ['ok'=>true,'user'=>$user]; }
    elseif($action === 'kat_listele')    { $result = $bl->katListele(); }
    elseif($action === 'kat_ekle')       { $result = $bl->katEkle(['ad'=>$data['ad']??'','ac'=>$data['ac']??'']); }
    elseif($action === 'kat_sil')        { $result = $bl->katSil((int)($data['kategori_id']??0)); }
    elseif($action === 'kitap_listele')  { $result = $bl->kitapListele(); }
    elseif($action === 'kitap_musait')   { $result = $bl->kitapMusait(); }
    elseif($action === 'kitap_ara')      { $result = $bl->kitapAra($data['filtre']??''); }
    elseif($action === 'kitap_ekle')     { $result = $bl->kitapEkle($data); }
    elseif($action === 'kitap_guncelle') { $result = $bl->kitapGuncelle($data); }
    elseif($action === 'kitap_sil')      { $result = $bl->kitapSil((int)($data['kitap_id']??0)); }
    elseif($action === 'kul_listele')    { $result = $bl->kulListele(); }
    elseif($action === 'kul_sil')        { $result = $bl->kulSil((int)($data['kullanici_id']??0)); }
    elseif($action === 'kul_rol')        { $result = $bl->kulRol((int)($data['kullanici_id']??0),$data['rol']??'user'); }
    elseif($action === 'odunc_listele')  { $result = $bl->oduncListele(); }
    elseif($action === 'odunc_benim')    { $result = $bl->oduncKullanici($kid); }
    elseif($action === 'odunc_ver')      { $result = $bl->oduncVer($data); }
    elseif($action === 'odunc_iade')     { $result = $bl->oduncIade((int)($data['odunc_id']??0)); }
    elseif($action === 'odunc_gecikme')  { $result = $bl->oduncGecikme(); }
    elseif($action === 'talep_ekle')     { $result = $bl->talepEkle($kid,(int)($data['kitap_id']??0)); }
    elseif($action === 'talep_listele')  { $result = $bl->talepListele(); }
    elseif($action === 'talep_benim')    { $result = $bl->talepKullanici($kid); }
    elseif($action === 'talep_onayla')   { $result = $bl->talepOnayla((int)($data['talep_id']??0),$data['not']??''); }
    elseif($action === 'talep_reddet')   { $result = $bl->talepReddet((int)($data['talep_id']??0),$data['not']??''); }
    elseif($action === 'ceza_listele')   { $result = $bl->cezaListele(); }
    elseif($action === 'ceza_odendi')    { $result = $bl->cezaOdendi((int)($data['ceza_id']??0)); }
    elseif($action === 'admin_stats')    { $result = $bl->adminStats(); }
    elseif($action === 'user_stats')     { $result = $bl->userStats($kid); }
    else { $result = ['error'=>"Bilinmeyen işlem: $action"]; }

    echo json_encode($result, JSON_UNESCAPED_UNICODE);

} catch(Throwable $e){
    http_response_code(500);
    echo json_encode(['error'=>$e->getMessage()], JSON_UNESCAPED_UNICODE);
}
