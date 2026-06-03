<?php
require_once __DIR__.'/database.php';

class BL {
    private DAL $dal;
    public function __construct(){ $this->dal = new DAL(); }

    public function login(string $kadi, string $sifre): array {
        if(empty($kadi)||empty($sifre)) return ['ok'=>false,'msg'=>'Kullanıcı adı ve şifre zorunludur.'];
        $rows = $this->dal->login(trim($kadi));
        if(!$rows) return ['ok'=>false,'msg'=>'Kullanıcı bulunamadı.'];
        $u = $rows[0];
        if($u['sifre'] !== md5($sifre)) return ['ok'=>false,'msg'=>'Şifre hatalı.'];
        if(!$u['aktif']) return ['ok'=>false,'msg'=>'Hesabınız pasif durumda.'];
        return ['ok'=>true,'user'=>$u];
    }

    public function kayit(array $d): array {
        if(empty($d['ad'])||empty($d['soyad'])) return ['ok'=>false,'msg'=>'Ad Soyad zorunludur.'];
        if(strlen($d['tc_no']??'')!==11||!ctype_digit($d['tc_no'])) return ['ok'=>false,'msg'=>'TC No 11 haneli rakam olmalı.'];
        if(!filter_var($d['mail']??'',FILTER_VALIDATE_EMAIL)) return ['ok'=>false,'msg'=>'Geçerli e-posta girin.'];
        if(strlen($d['sifre']??'')<6) return ['ok'=>false,'msg'=>'Şifre en az 6 karakter olmalı.'];
        $hash = md5($d['sifre']);
        $this->dal->kayit($d['ad'],$d['soyad'],$d['tc_no'],$d['tel'],$d['mail'],$d['adres'],$d['kullanici_adi'],$hash);
        return ['ok'=>true,'msg'=>'Kayıt başarılı. Giriş yapabilirsiniz.'];
    }

    // Kategori
    public function katListele(): array { return $this->dal->katListele(); }
    public function katEkle(array $d): array {
        if(empty($d['ad'])) return ['ok'=>false,'msg'=>'Ad zorunlu.'];
        $this->dal->katEkle(trim($d['ad']),trim($d['ac']??''));
        return ['ok'=>true,'msg'=>'Kategori eklendi.'];
    }
    public function katSil(int $id): array { $this->dal->katSil($id); return ['ok'=>true,'msg'=>'Silindi.']; }

    // Kitap
    public function kitapListele(): array { return $this->dal->kitapListele(); }
    public function kitapMusait(): array  { return $this->dal->kitapMusait(); }
    public function kitapAra(string $f): array { return $this->dal->kitapAra($f); }
    public function kitapEkle(array $d): array {
        if(empty($d['isbn'])||empty($d['ad'])||empty($d['yazar'])) return ['ok'=>false,'msg'=>'ISBN, Ad ve Yazar zorunlu.'];
        if((int)($d['adet']??0)<1) return ['ok'=>false,'msg'=>'Adet en az 1 olmalı.'];
        $this->dal->kitapEkle($d['isbn'],$d['ad'],$d['yazar'],$d['yayinevi'],(int)$d['baski_yil'],(int)$d['kategori_id'],(int)$d['adet'],$d['raf']??'');
        return ['ok'=>true,'msg'=>'Kitap eklendi.'];
    }
    public function kitapGuncelle(array $d): array {
        if(empty($d['ad'])) return ['ok'=>false,'msg'=>'Ad zorunlu.'];
        $this->dal->kitapGuncelle((int)$d['kitap_id'],$d['ad'],$d['yazar'],$d['yayinevi'],(int)$d['baski_yil'],(int)$d['kategori_id'],$d['raf']??'');
        return ['ok'=>true,'msg'=>'Kitap güncellendi.'];
    }
    public function kitapSil(int $id): array { $this->dal->kitapSil($id); return ['ok'=>true,'msg'=>'Kitap silindi.']; }

    // Kullanici
    public function kulListele(): array { return $this->dal->kulListele(); }
    public function kulSil(int $id): array { $this->dal->kulSil($id); return ['ok'=>true,'msg'=>'Kullanıcı pasif yapıldı.']; }
    public function kulRol(int $id,string $rol): array { $this->dal->kulRol($id,$rol); return ['ok'=>true,'msg'=>'Rol güncellendi.']; }

    // Ödünç
    public function oduncListele(): array { return $this->dal->oduncListele(); }
    public function oduncKullanici(int $id): array { return $this->dal->oduncKullanici($id); }
    public function oduncVer(array $d): array {
        if(empty($d['kullanici_id'])||empty($d['kitap_id'])) return ['ok'=>false,'msg'=>'Kullanıcı ve kitap seçin.'];
        $iade = $d['iade_tarih']??date('Y-m-d',strtotime('+30 days'));
        if($iade<=date('Y-m-d')) return ['ok'=>false,'msg'=>'İade tarihi bugünden sonra olmalı.'];
        $this->dal->oduncVer((int)$d['kullanici_id'],(int)$d['kitap_id'],$iade);
        return ['ok'=>true,'msg'=>'Kitap ödünç verildi.'];
    }
    public function oduncIade(int $id): array { $this->dal->oduncIade($id); return ['ok'=>true,'msg'=>'İade alındı.']; }
    public function oduncGecikme(): array { return $this->dal->oduncGecikme(); }

    // Talep
    public function talepEkle(int $k,int $kt): array { $this->dal->talepEkle($k,$kt); return ['ok'=>true,'msg'=>'Talebiniz iletildi.']; }
    public function talepListele(): array { return $this->dal->talepListele(); }
    public function talepKullanici(int $id): array { return $this->dal->talepKullanici($id); }
    public function talepOnayla(int $id,string $not): array { $this->dal->talepOnayla($id,$not); return ['ok'=>true,'msg'=>'Talep onaylandı.']; }
    public function talepReddet(int $id,string $not): array { $this->dal->talepReddet($id,$not); return ['ok'=>true,'msg'=>'Talep reddedildi.']; }

    // Ceza
    public function cezaListele(): array { return $this->dal->cezaListele(); }
    public function cezaOdendi(int $id): array { $this->dal->cezaOdendi($id); return ['ok'=>true,'msg'=>'Ceza ödendi olarak işaretlendi.']; }

    // Stats
    public function adminStats(): array { return $this->dal->adminStats(); }
    public function userStats(int $id): array { return $this->dal->userStats($id); }
}
