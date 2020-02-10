<?php 

$servername = "localhost";
$username = "root";
$password = "";

$link = mysqli_connect($servername, $username, $password);

$link->set_charset('utf-8');

if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}

$sql = "CREATE DATABASE web_projesi CHARACTER SET utf8 COLLATE utf8_general_ci;";
if(mysqli_query($link, $sql)){
    echo "web_projesi veritabanı başarıyla oluşturuldu.<br>";

    $sql = "USE web_projesi;";

    if ($link->query($sql) === TRUE) {
    } else {
        echo mysqli_error($link);
    }

    $sql = "CREATE TABLE `kullanici` (
        `id` int(11) NOT NULL UNIQUE AUTO_INCREMENT,
        `kullanici_adi` varchar(50) NOT NULL,
        `sifre` varchar(255) NOT NULL,
        `isim` varchar(255) NOT NULL,
        `yetki` int(11) NOT NULL,
        `fotograf` varchar(255) DEFAULT 'blank.jpg'
      );";

    if ($link->query($sql) === TRUE) {
        echo "kullanici tablosu oluşturuldu.<br>";
    } else {
        echo mysqli_error($link);
    }

    $sql = "CREATE TABLE `icerik` (
        `id` int(11) NOT NULL UNIQUE AUTO_INCREMENT,
        `icerik_adi` varchar(255) NOT NULL,
        `poster` varchar(255) DEFAULT NULL,
        `arkaplan` varchar(255) DEFAULT NULL,
        `tur` varchar(50) NOT NULL,
        `yil` int(4) DEFAULT NULL,
        `sezon` int(4) DEFAULT NULL,
        `puan` int(2) DEFAULT NULL,
        `bolum` int(4) DEFAULT NULL,
        `fragman` varchar(255) DEFAULT NULL,
        `ozet` LONGTEXT DEFAULT NULL,
        `soz` varchar(255) DEFAULT NULL
      );";

    if ($link->query($sql) === TRUE) {
        echo "icerik tablosu oluşturuldu.<br>";
    } else {
        echo mysqli_error($link);
    }

    $sql = "CREATE TABLE `yorum` (
        `yorum_id` int(11) NOT NULL UNIQUE AUTO_INCREMENT,
        `yorum_sahibi` varchar(60) NOT NULL,
        `yorum_icerigi` varchar(1000) NOT NULL,
        `yorum_durum` int(11) DEFAULT '0',
        `icerik_id` int(11) NOT NULL,
        `yorum_saati` datetime DEFAULT CURRENT_TIMESTAMP,
        `kullanici_id` int(5) DEFAULT NULL
      );";

    if ($link->query($sql) === TRUE) {
        echo "yorum tablosu oluşturuldu.<br>";
    } else {
        echo mysqli_error($link);
    }

    $sql = "CREATE TABLE `puan` (
        `id` int(11) NOT NULL UNIQUE AUTO_INCREMENT,
        `icerik_id` int(11) NOT NULL,
        `kullanici_id` int(11) NOT NULL,
        `puan` int(11) NOT NULL
      );";

    if ($link->query($sql) === TRUE) {
        echo "puan tablosu oluşturuldu.<br>";
    } else {
        echo mysqli_error($link);
    }
    
}

?>