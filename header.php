<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="font-awesome/css/all.css">
    <style>
    .ust-menu{
        background: rgba(0, 0, 0, 0.5);
    }
    .searchbar{
    margin-bottom: auto;
    margin-top: auto;
    height: 60px;
    background-color: #353b48;
    border-radius: 30px;
    padding: 10px;
    }

    .search_input{
    color: white;
    border: 0;
    outline: 0;
    background: none;
    width: 0;
    caret-color:transparent;
    line-height: 40px;
    transition: width 0.4s linear;
    }

    ::placeholder {
    color: white;
    }

    .searchbar:hover > .search_input{
    padding: 0 10px;
    width: 250px;
    caret-color:red;
    transition: width 0.4s linear;
    }

    .searchbar:hover > .search_icon{
    background: white;
    color: #e74c3c !important;
    }

    .search_icon{
    height: 40px;
    width: 40px;
    float: right;
    display: flex;
    justify-content: center;
    align-items: center;
    border-radius: 50%;
    color: white !important;
    
    }
    
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="ust-menu">
        <h2>İçerik Veritabanım</h2>
        <h6>İzlemeyi düşündüğünüz dizi ve filmler hakkında tüm bilgiler burada...</h6>
      
        </div>

        <div class="d-flex justify-content-center h-100" style="margin-top: 35px;">
        <div class="searchbar">
          <input class="search_input" type="text" name="" id="ara" placeholder="Bir içerik arayın...">
          <a id="arama" onclick="aramaYap()" class="search_icon"><i class="fas fa-search"></i></a>
        </div>
      </div>
        
        <nav class="tabs">
          <div class="selector"></div>
          <a href="index.php"><i class="fas fa-home"></i> Anasayfa</a>
          <a href="filmler.php"><i class="fas fa-film"></i> Filmler</a>
          <a href="diziler.php"><i class="fas fa-tv"></i> Diziler</a>
          

<?php

session_start();

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    echo '<a href="giris.php"><i class="fas fa-sign-in-alt"></i> Giriş</a>
    <a href="kayit.php"><i class="fas fa-user-plus"></i> Kayıt</a>';
}
    else{
        echo '<a href="profil.php?id='.$_SESSION['id'].'"><i class="fas fa-user-circle"></i> Profilim</a>';
        if($_SESSION['yetki'] == 3 || $_SESSION['yetki'] == 2)
		{
            echo '<a href="admin.php"><i class="fas fa-user-shield"></i>Yönetici Paneli</a>';
        }
        echo '<a href="cikis.php"><i class="fas fa-sign-out-alt"></i> Çıkış Yap</a>';
    }
?>
        </nav>
      </div>