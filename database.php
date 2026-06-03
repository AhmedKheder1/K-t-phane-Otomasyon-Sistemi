<?php
define('DB_HOST','localhost');
define('DB_USER','root');
define('DB_PASS','');
define('DB_NAME','kutuphane_db');

function getConn(): mysqli {
    $c = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME);
    if($c->connect_error) die(json_encode(['error'=>$c->connect_error]));
    $c->set_charset('utf8mb4');
    return $c;
}

class DAL {
    private mysqli $c;
    public function __construct(){ $this->c = getConn(); }

    private function q(string $sp, string $t='', array $p=[]): array {
        $stmt = $this->c->prepare("CALL $sp(".implode(',',array_fill(0,count($p),'?')).")");
        if(!$stmt) return ['error'=>$this->c->error];
        if($p) $stmt->bind_param($t,...$p);
        $stmt->execute();
        $r = $stmt->get_result();
        $d = $r ? $r->fetch_all(MYSQLI_ASSOC) : [];
        $stmt->close(); $this->c->next_result();
        return $d;
    }

    private function x(string $sp, string $t='', array $p=[]): bool {
        $stmt = $this->c->prepare("CALL $sp(".implode(',',array_fill(0,count($p),'?')).")");
        if(!$stmt) return false;
        if($p) $stmt->bind_param($t,...$p);
        $ok = $stmt->execute();
        $stmt->close(); $this->c->next_result();
        return $ok;
    }

    // Auth
    public function login(string $k): array { return $this->q('sp_login','s',[$k]); }
    public function kayit(string $ad,string $so,string $tc,string $tel,string $mail,string $adres,string $ku,string $si): bool {
        return $this->x('sp_kullanici_kayit','ssssssss',[$ad,$so,$tc,$tel,$mail,$adres,$ku,$si]);
    }

    // Kategori
    public function katListele(): array  { return $this->q('sp_kategori_listele'); }
    public function katEkle(string $a,string $ac): bool { return $this->x('sp_kategori_ekle','ss',[$a,$ac]); }
    public function katSil(int $id): bool { return $this->x('sp_kategori_sil','i',[$id]); }

    // Kitap
    public function kitapListele(): array  { return $this->q('sp_kitap_listele'); }
    public function kitapMusait(): array   { return $this->q('sp_kitap_musait_listele'); }
    public function kitapAra(string $f): array { return $this->q('sp_kitap_ara','s',[$f]); }
    public function kitapEkle(string $isbn,string $ad,string $ya,string $yay,int $yil,int $kat,int $adet,string $raf): bool {
        return $this->x('sp_kitap_ekle','ssssiisi',[$isbn,$ad,$ya,$yay,$yil,$kat,$adet,$raf]);
    }
    public function kitapGuncelle(int $id,string $ad,string $ya,string $yay,int $yil,int $kat,string $raf): bool {
        return $this->x('sp_kitap_guncelle','isssiss',[$id,$ad,$ya,$yay,$yil,$kat,$raf]);
    }
    public function kitapSil(int $id): bool { return $this->x('sp_kitap_sil','i',[$id]); }

    // Kullanici
    public function kulListele(): array { return $this->q('sp_kullanici_listele'); }
    public function kulSil(int $id): bool { return $this->x('sp_kullanici_sil','i',[$id]); }
    public function kulRol(int $id,string $rol): bool { return $this->x('sp_kullanici_rol_guncelle','is',[$id,$rol]); }

    // Ödünç
    public function oduncListele(): array { return $this->q('sp_odunc_listele'); }
    public function oduncKullanici(int $id): array { return $this->q('sp_odunc_kullanici','i',[$id]); }
    public function oduncVer(int $k,int $kt,string $iade): bool { return $this->x('sp_odunc_ver','iis',[$k,$kt,$iade]); }
    public function oduncIade(int $id): bool { return $this->x('sp_odunc_iade','i',[$id]); }
    public function oduncGecikme(): array { return $this->q('sp_odunc_gecikme'); }

    // Talep
    public function talepEkle(int $k,int $kt): bool { return $this->x('sp_talep_ekle','ii',[$k,$kt]); }
    public function talepListele(): array { return $this->q('sp_talep_listele'); }
    public function talepKullanici(int $id): array { return $this->q('sp_talep_kullanici','i',[$id]); }
    public function talepOnayla(int $id,string $not): bool { return $this->x('sp_talep_onayla','is',[$id,$not]); }
    public function talepReddet(int $id,string $not): bool { return $this->x('sp_talep_reddet','is',[$id,$not]); }

    // Ceza
    public function cezaListele(): array { return $this->q('sp_ceza_listele'); }
    public function cezaOdendi(int $id): bool { return $this->x('sp_ceza_odendi','i',[$id]); }

    // Stats
    public function adminStats(): array { $r=$this->q('sp_admin_stats'); return $r[0]??[]; }
    public function userStats(int $id): array { $r=$this->q('sp_user_stats','i',[$id]); return $r[0]??[]; }
}
