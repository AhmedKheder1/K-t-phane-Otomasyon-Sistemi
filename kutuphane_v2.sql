-- ============================================================
--  KÜTÜPHANe OTOMASYON SİSTEMİ v2 — Veritabanı
-- ============================================================

CREATE DATABASE IF NOT EXISTS kutuphane_db
  CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci;

USE kutuphane_db;

-- ============================================================
-- TABLOLAR
-- ============================================================

CREATE TABLE IF NOT EXISTS kategori (
    kategori_id   INT          NOT NULL AUTO_INCREMENT,
    kategori_ad   VARCHAR(100) NOT NULL UNIQUE,
    aciklama      VARCHAR(300),
    PRIMARY KEY (kategori_id)
);

CREATE TABLE IF NOT EXISTS kullanici (
    kullanici_id  INT          NOT NULL AUTO_INCREMENT,
    ad            VARCHAR(64)  NOT NULL,
    soyad         VARCHAR(64)  NOT NULL,
    tc_no         VARCHAR(11)  NOT NULL UNIQUE,
    tel           VARCHAR(20)  NOT NULL,
    mail          VARCHAR(150) NOT NULL UNIQUE,
    adres         VARCHAR(300) NOT NULL,
    kullanici_adi VARCHAR(50)  NOT NULL UNIQUE,
    sifre         VARCHAR(255) NOT NULL,
    rol           ENUM('admin','user') NOT NULL DEFAULT 'user',
    kayit_tarih   DATE         NOT NULL DEFAULT (CURRENT_DATE),
    aktif         TINYINT(1)   NOT NULL DEFAULT 1,
    PRIMARY KEY (kullanici_id)
);

CREATE TABLE IF NOT EXISTS kitap (
    kitap_id      INT          NOT NULL AUTO_INCREMENT,
    isbn          VARCHAR(20)  NOT NULL UNIQUE,
    ad            VARCHAR(250) NOT NULL,
    yazar         VARCHAR(150) NOT NULL,
    yayinevi      VARCHAR(150) NOT NULL,
    baski_yil     YEAR         NOT NULL,
    kategori_id   INT          NOT NULL,
    toplam_adet   INT          NOT NULL DEFAULT 1 CHECK (toplam_adet >= 1),
    mevcut_adet   INT          NOT NULL DEFAULT 1 CHECK (mevcut_adet >= 0),
    raf           VARCHAR(20),
    PRIMARY KEY (kitap_id),
    FOREIGN KEY (kategori_id) REFERENCES kategori(kategori_id)
        ON DELETE RESTRICT ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS odunc (
    odunc_id      INT          NOT NULL AUTO_INCREMENT,
    kullanici_id  INT          NOT NULL,
    kitap_id      INT          NOT NULL,
    odunc_tarih   DATE         NOT NULL DEFAULT (CURRENT_DATE),
    iade_tarih    DATE         NOT NULL,
    gercek_iade   DATE         DEFAULT NULL,
    durum         VARCHAR(20)  NOT NULL DEFAULT 'Ödünçte',
    admin_notu    VARCHAR(300) DEFAULT NULL,
    PRIMARY KEY (odunc_id),
    FOREIGN KEY (kullanici_id) REFERENCES kullanici(kullanici_id)
        ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (kitap_id)     REFERENCES kitap(kitap_id)
        ON DELETE RESTRICT ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS talep (
    talep_id      INT          NOT NULL AUTO_INCREMENT,
    kullanici_id  INT          NOT NULL,
    kitap_id      INT          NOT NULL,
    talep_tarih   DATE         NOT NULL DEFAULT (CURRENT_DATE),
    durum         ENUM('Bekliyor','Onaylandı','Reddedildi') NOT NULL DEFAULT 'Bekliyor',
    admin_notu    VARCHAR(300) DEFAULT NULL,
    PRIMARY KEY (talep_id),
    FOREIGN KEY (kullanici_id) REFERENCES kullanici(kullanici_id)
        ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (kitap_id)     REFERENCES kitap(kitap_id)
        ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS ceza (
    ceza_id       INT             NOT NULL AUTO_INCREMENT,
    odunc_id      INT             NOT NULL UNIQUE,
    gec_gun       INT             NOT NULL,
    tutar         DECIMAL(10,2)   NOT NULL,
    odendi_mi     TINYINT(1)      NOT NULL DEFAULT 0,
    PRIMARY KEY (ceza_id),
    FOREIGN KEY (odunc_id) REFERENCES odunc(odunc_id)
        ON DELETE CASCADE ON UPDATE CASCADE
);

-- ============================================================
-- STORED PROCEDURES
-- ============================================================
DELIMITER $$

-- ── AUTH ──────────────────────────────────────────────────
CREATE PROCEDURE sp_login(IN p_kullanici_adi VARCHAR(50))
BEGIN
    SELECT kullanici_id, ad, soyad, kullanici_adi, sifre, rol, aktif
    FROM kullanici
    WHERE kullanici_adi = p_kullanici_adi AND aktif = 1;
END $$

CREATE PROCEDURE sp_kullanici_kayit(
    IN p_ad VARCHAR(64), IN p_soyad VARCHAR(64), IN p_tc VARCHAR(11),
    IN p_tel VARCHAR(20), IN p_mail VARCHAR(150), IN p_adres VARCHAR(300),
    IN p_kadi VARCHAR(50), IN p_sifre VARCHAR(255)
)
BEGIN
    INSERT INTO kullanici(ad, soyad, tc_no, tel, mail, adres, kullanici_adi, sifre, rol)
    VALUES(p_ad, p_soyad, p_tc, p_tel, p_mail, p_adres, p_kadi, p_sifre, 'user');
END $$

-- ── KATEGORİ ──────────────────────────────────────────────
CREATE PROCEDURE sp_kategori_listele()
BEGIN SELECT * FROM kategori ORDER BY kategori_ad; END $$

CREATE PROCEDURE sp_kategori_ekle(IN p_ad VARCHAR(100), IN p_ac VARCHAR(300))
BEGIN INSERT INTO kategori(kategori_ad, aciklama) VALUES(p_ad, p_ac); END $$

CREATE PROCEDURE sp_kategori_sil(IN p_id INT)
BEGIN DELETE FROM kategori WHERE kategori_id = p_id; END $$

-- ── KİTAP ─────────────────────────────────────────────────
CREATE PROCEDURE sp_kitap_listele()
BEGIN
    SELECT k.*, ka.kategori_ad FROM kitap k
    INNER JOIN kategori ka ON k.kategori_id = ka.kategori_id
    ORDER BY k.ad;
END $$

CREATE PROCEDURE sp_kitap_musait_listele()
BEGIN
    SELECT k.*, ka.kategori_ad FROM kitap k
    INNER JOIN kategori ka ON k.kategori_id = ka.kategori_id
    WHERE k.mevcut_adet > 0
    ORDER BY k.ad;
END $$

CREATE PROCEDURE sp_kitap_ara(IN p_filtre VARCHAR(100))
BEGIN
    SELECT k.*, ka.kategori_ad FROM kitap k
    INNER JOIN kategori ka ON k.kategori_id = ka.kategori_id
    WHERE k.ad    LIKE CONCAT('%',p_filtre,'%')
       OR k.yazar LIKE CONCAT('%',p_filtre,'%')
       OR k.isbn  LIKE CONCAT('%',p_filtre,'%');
END $$

CREATE PROCEDURE sp_kitap_ekle(
    IN p_isbn VARCHAR(20), IN p_ad VARCHAR(250), IN p_yazar VARCHAR(150),
    IN p_yay VARCHAR(150), IN p_yil YEAR, IN p_kat INT, IN p_adet INT, IN p_raf VARCHAR(20)
)
BEGIN
    INSERT INTO kitap(isbn,ad,yazar,yayinevi,baski_yil,kategori_id,toplam_adet,mevcut_adet,raf)
    VALUES(p_isbn,p_ad,p_yazar,p_yay,p_yil,p_kat,p_adet,p_adet,p_raf);
END $$

CREATE PROCEDURE sp_kitap_guncelle(
    IN p_id INT, IN p_ad VARCHAR(250), IN p_yazar VARCHAR(150),
    IN p_yay VARCHAR(150), IN p_yil YEAR, IN p_kat INT, IN p_raf VARCHAR(20)
)
BEGIN
    UPDATE kitap SET ad=p_ad,yazar=p_yazar,yayinevi=p_yay,
        baski_yil=p_yil,kategori_id=p_kat,raf=p_raf
    WHERE kitap_id=p_id;
END $$

CREATE PROCEDURE sp_kitap_sil(IN p_id INT)
BEGIN DELETE FROM kitap WHERE kitap_id=p_id; END $$

-- ── KULLANICI (Admin) ──────────────────────────────────────
CREATE PROCEDURE sp_kullanici_listele()
BEGIN
    SELECT kullanici_id,ad,soyad,tc_no,tel,mail,kullanici_adi,rol,kayit_tarih,aktif
    FROM kullanici ORDER BY rol, soyad;
END $$

CREATE PROCEDURE sp_kullanici_sil(IN p_id INT)
BEGIN UPDATE kullanici SET aktif=0 WHERE kullanici_id=p_id; END $$

CREATE PROCEDURE sp_kullanici_rol_guncelle(IN p_id INT, IN p_rol ENUM('admin','user'))
BEGIN UPDATE kullanici SET rol=p_rol WHERE kullanici_id=p_id; END $$

-- ── ÖDÜNÇ ─────────────────────────────────────────────────
CREATE PROCEDURE sp_odunc_listele()
BEGIN
    SELECT o.odunc_id, CONCAT(k.ad,' ',k.soyad) AS kullanici_adi,
           kt.ad AS kitap_adi, kt.isbn,
           o.odunc_tarih, o.iade_tarih, o.gercek_iade, o.durum, o.admin_notu
    FROM odunc o
    INNER JOIN kullanici k  ON o.kullanici_id = k.kullanici_id
    INNER JOIN kitap     kt ON o.kitap_id     = kt.kitap_id
    ORDER BY o.odunc_id DESC;
END $$

CREATE PROCEDURE sp_odunc_kullanici(IN p_kid INT)
BEGIN
    SELECT o.odunc_id, kt.ad AS kitap_adi, kt.yazar, kt.isbn,
           o.odunc_tarih, o.iade_tarih, o.gercek_iade, o.durum
    FROM odunc o
    INNER JOIN kitap kt ON o.kitap_id = kt.kitap_id
    WHERE o.kullanici_id = p_kid
    ORDER BY o.odunc_id DESC;
END $$

CREATE PROCEDURE sp_odunc_ver(IN p_kid INT, IN p_kitap INT, IN p_iade DATE)
BEGIN
    INSERT INTO odunc(kullanici_id, kitap_id, iade_tarih)
    VALUES(p_kid, p_kitap, p_iade);
END $$

CREATE PROCEDURE sp_odunc_iade(IN p_id INT)
BEGIN
    UPDATE odunc SET gercek_iade=CURRENT_DATE, durum='İade Edildi'
    WHERE odunc_id=p_id;
END $$

CREATE PROCEDURE sp_odunc_gecikme()
BEGIN
    SELECT o.odunc_id, CONCAT(k.ad,' ',k.soyad) AS kullanici_adi, k.tel,
           kt.ad AS kitap_adi, o.iade_tarih,
           DATEDIFF(CURRENT_DATE, o.iade_tarih) AS gec_gun,
           DATEDIFF(CURRENT_DATE, o.iade_tarih) * 2.00 AS tahmini_ceza
    FROM odunc o
    INNER JOIN kullanici k  ON o.kullanici_id = k.kullanici_id
    INNER JOIN kitap     kt ON o.kitap_id     = kt.kitap_id
    WHERE o.durum='Ödünçte' AND o.iade_tarih < CURRENT_DATE;
END $$

-- ── TALEP ─────────────────────────────────────────────────
CREATE PROCEDURE sp_talep_ekle(IN p_kid INT, IN p_kitap INT)
BEGIN
    INSERT INTO talep(kullanici_id, kitap_id) VALUES(p_kid, p_kitap);
END $$

CREATE PROCEDURE sp_talep_listele()
BEGIN
    SELECT t.talep_id, CONCAT(k.ad,' ',k.soyad) AS kullanici_adi,
           k.tel, kt.ad AS kitap_adi, kt.isbn,
           t.talep_tarih, t.durum, t.admin_notu
    FROM talep t
    INNER JOIN kullanici k  ON t.kullanici_id = k.kullanici_id
    INNER JOIN kitap     kt ON t.kitap_id     = kt.kitap_id
    ORDER BY FIELD(t.durum,'Bekliyor','Onaylandı','Reddedildi'), t.talep_id DESC;
END $$

CREATE PROCEDURE sp_talep_kullanici(IN p_kid INT)
BEGIN
    SELECT t.talep_id, kt.ad AS kitap_adi, kt.yazar,
           t.talep_tarih, t.durum, t.admin_notu
    FROM talep t
    INNER JOIN kitap kt ON t.kitap_id = kt.kitap_id
    WHERE t.kullanici_id = p_kid
    ORDER BY t.talep_id DESC;
END $$

CREATE PROCEDURE sp_talep_onayla(IN p_id INT, IN p_not VARCHAR(300))
BEGIN
    UPDATE talep SET durum='Onaylandı', admin_notu=p_not WHERE talep_id=p_id;
END $$

CREATE PROCEDURE sp_talep_reddet(IN p_id INT, IN p_not VARCHAR(300))
BEGIN
    UPDATE talep SET durum='Reddedildi', admin_notu=p_not WHERE talep_id=p_id;
END $$

-- ── CEZA ──────────────────────────────────────────────────
CREATE PROCEDURE sp_ceza_listele()
BEGIN
    SELECT c.ceza_id, CONCAT(k.ad,' ',k.soyad) AS kullanici_adi,
           kt.ad AS kitap_adi, c.gec_gun, c.tutar, c.odendi_mi
    FROM ceza c
    INNER JOIN odunc    o  ON c.odunc_id     = o.odunc_id
    INNER JOIN kullanici k  ON o.kullanici_id = k.kullanici_id
    INNER JOIN kitap    kt ON o.kitap_id      = kt.kitap_id
    ORDER BY c.odendi_mi, c.ceza_id DESC;
END $$

CREATE PROCEDURE sp_ceza_odendi(IN p_id INT)
BEGIN UPDATE ceza SET odendi_mi=1 WHERE ceza_id=p_id; END $$

-- ── DASHBOARD ─────────────────────────────────────────────
CREATE PROCEDURE sp_admin_stats()
BEGIN
    SELECT
        (SELECT COUNT(*) FROM kullanici WHERE aktif=1 AND rol='user')   AS toplam_uye,
        (SELECT COUNT(*) FROM kitap)                                      AS toplam_kitap,
        (SELECT COUNT(*) FROM odunc  WHERE durum='Ödünçte')               AS aktif_odunc,
        (SELECT COUNT(*) FROM talep  WHERE durum='Bekliyor')              AS bekleyen_talep,
        (SELECT COALESCE(SUM(tutar),0) FROM ceza WHERE odendi_mi=0)       AS odenmemis_ceza;
END $$

CREATE PROCEDURE sp_user_stats(IN p_kid INT)
BEGIN
    SELECT
        (SELECT COUNT(*) FROM odunc WHERE kullanici_id=p_kid AND durum='Ödünçte')        AS aktif_odunc,
        (SELECT COUNT(*) FROM odunc WHERE kullanici_id=p_kid)                             AS toplam_odunc,
        (SELECT COUNT(*) FROM talep WHERE kullanici_id=p_kid AND durum='Bekliyor')        AS bekleyen_talep,
        (SELECT COALESCE(SUM(c.tutar),0) FROM ceza c
         INNER JOIN odunc o ON c.odunc_id=o.odunc_id
         WHERE o.kullanici_id=p_kid AND c.odendi_mi=0)                                    AS ceza_toplam;
END $$

DELIMITER ;

-- ============================================================
-- FUNCTIONS
-- ============================================================
DELIMITER $$

CREATE FUNCTION fn_kullanici_ceza(p_kid INT)
RETURNS DECIMAL(10,2) DETERMINISTIC READS SQL DATA
BEGIN
    DECLARE t DECIMAL(10,2) DEFAULT 0;
    SELECT COALESCE(SUM(c.tutar),0) INTO t
    FROM ceza c INNER JOIN odunc o ON c.odunc_id=o.odunc_id
    WHERE o.kullanici_id=p_kid AND c.odendi_mi=0;
    RETURN t;
END $$

CREATE FUNCTION fn_kitap_musait(p_kitap_id INT)
RETURNS VARCHAR(20) DETERMINISTIC READS SQL DATA
BEGIN
    DECLARE m INT DEFAULT 0;
    SELECT mevcut_adet INTO m FROM kitap WHERE kitap_id=p_kitap_id;
    IF m > 0 THEN RETURN 'Müsait'; ELSE RETURN 'Müsait Değil'; END IF;
END $$

DELIMITER ;

-- ============================================================
-- TRIGGERS
-- ============================================================
DELIMITER $$

CREATE TRIGGER tg_odunc_stok_azalt
AFTER INSERT ON odunc FOR EACH ROW
BEGIN
    UPDATE kitap SET mevcut_adet=mevcut_adet-1 WHERE kitap_id=NEW.kitap_id;
END $$

CREATE TRIGGER tg_iade_stok_ceza
AFTER UPDATE ON odunc FOR EACH ROW
BEGIN
    IF NEW.durum='İade Edildi' AND OLD.durum='Ödünçte' THEN
        UPDATE kitap SET mevcut_adet=mevcut_adet+1 WHERE kitap_id=NEW.kitap_id;
        IF NEW.gercek_iade > OLD.iade_tarih THEN
            INSERT INTO ceza(odunc_id,gec_gun,tutar)
            VALUES(NEW.odunc_id,
                   DATEDIFF(NEW.gercek_iade,OLD.iade_tarih),
                   DATEDIFF(NEW.gercek_iade,OLD.iade_tarih)*2.00);
        END IF;
    END IF;
END $$

DELIMITER ;

-- ============================================================
-- ÖRNEK VERİLER
-- ============================================================
INSERT INTO kategori(kategori_ad,aciklama) VALUES
('Roman','Yerli ve yabancı roman'),('Bilim','Bilim ve teknoloji'),
('Tarih','Tarih ve biyografi'),('Çocuk','Çocuk ve gençlik'),('Felsefe','Felsefe');

-- Şifreler: admin→admin123, user1→user123 (MD5 hash)
INSERT INTO kullanici(ad,soyad,tc_no,tel,mail,adres,kullanici_adi,sifre,rol) VALUES
('Sistem','Yöneticisi','00000000001','0500 000 0000','admin@kutuphane.tr','Bartın',
 'admin', MD5('admin123'), 'admin'),
('Ahmet','Yılmaz','12345678901','0532 111 1111','ahmet@mail.com','Bartın',
 'ahmet', MD5('user123'), 'user'),
('Fatma','Kaya','23456789012','0533 222 2222','fatma@mail.com','Bartın',
 'fatma', MD5('user123'), 'user');

INSERT INTO kitap(isbn,ad,yazar,yayinevi,baski_yil,kategori_id,toplam_adet,mevcut_adet,raf) VALUES
('9789750719387','İnce Memed','Yaşar Kemal','YKY',2020,1,3,3,'A-01'),
('9789750726439','Tutunamayanlar','Oğuz Atay','İletişim',2021,1,2,2,'A-02'),
('9789753428218','Sapiens','Yuval N. Harari','Kolektif',2022,2,2,2,'B-01'),
('9789754588402','Osmanlı Tarihi','Halil İnalcık','TTK',2019,3,1,1,'C-01'),
('9789750738784','Küçük Prens','Antoine S.','Can',2023,4,4,4,'D-01');
