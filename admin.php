<?php

require_once "veritabani.php";

session_start();

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: giris.php");
    exit;
}else{
    if(isset($_SESSION['yetki']))
	{
		if($_SESSION['yetki'] == 3 || $_SESSION['yetki'] == 2)
		{
            
		}
        else{
            header("location: 404.php?hata=Yetkiniz olmayan bir sayfaya erişmeye çalıştınız.");
            exit;
        }
    }
    else{
        header("location: cikis.php");
    }
}

?>


<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Admin Paneli - İçerik Veritabanım</title>
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/admin.css">
    <link rel="stylesheet" href="font-awesome/css/all.css">
    <style>
    .flex-container {
display: flex;
flex-direction: column;
background-color: DodgerBlue;
width: 0%;
color: #fefffd;
}

.flex-container > div {
background-color: #f1f1f1;
width: 100px;
text-align: center;
font-size: 20px;
}

.satir {
  display: flex;
}

.satir > div {
  margin: 10px;
  padding: 20px;

}</style>
</head>

<body>

    <div class="wrapper">
        <!-- Sidebar  -->
        <nav id="sidebar">
            <div class="sidebar-header">
                <h3>İçerik Veritabanım</h3>
            </div>

            <ul class="list-unstyled components">
                <li class="active">
                    <a href="#homeSubmenu" data-toggle="collapse" aria-expanded="true" class="dropdown-toggle">
                        <i class="fas fa-home"></i>
                        Admin Paneli
                    </a>
                    <ul class="list-unstyled collapse show" id="homeSubmenu">
                        <li>
                            <a href="admin.php">Anasayfa</a>
                        </li>
                        <li>
                            <a href="icerik-ekle.php">İçerik Ekle</a>
                        </li>
                        <li>
                            <a href="icerik-sil.php">İçerik Sil</a>
                        </li>
                        <li>
                            <a href="api.php">TMDB Api ile İçerik Ekle</a>
                        </li>
                        <li>
                            <a href="yorumlar.php">Yorumlar</a>
                        </li>
                        <li>
                            <a href="uyeler.php">Kullanıcılar</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="#pageSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                        <i class="fas fa-copy"></i>
                        Sayfalar
                    </a>
                    <ul class="collapse list-unstyled" id="pageSubmenu">
                        <li>
                            <a href="index.php">Anasayfa</a>
                        </li>
                        <li>
                            <a href="filmler.php">Filmler</a>
                        </li>
                        <li>
                        <a href="diziler.php">Diziler</a>
                        </li>
                    </ul>
                </li>
            </ul>

            <ul class="list-unstyled CTAs">
                <li>
                    <a href="cikis.php" class="download">Çıkış Yap</a>
                </li>
            </ul>
        </nav>

        <!-- Page Content  -->
        <div id="content">

            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="container-fluid">

                <span>Anasayfa | Hoşgeldiniz, <?php echo htmlspecialchars($_SESSION["isim"]) ?></span>

            </nav>

            <div class="container">
            <div class="satir">



            <?php

$query = 'SELECT COUNT(id) FROM icerik';
$result = mysqli_query($link, $query) or die(mysqli_error($link));
while ($row = mysqli_fetch_array($result, MYSQLI_BOTH)) {
    $icerik_sayisi = $row[0];
    echo '<div><div class="flex-container">
    <div style="background-color:#00c0ef; width: 240px; height: 140px; padding: 15px;"><h3>Toplam İçerik</h3><h1>'.$icerik_sayisi.'</h1></div>
    <div style="background-color:#01acd8; width: 240px; height: 40px; padding: 5px;"><a href="icerik-sil.php">Tümünü Gör</a></div>
</div></div>';
}

$query = 'SELECT COUNT(id) FROM icerik WHERE tur = "Dizi"';
$result = mysqli_query($link, $query) or die(mysqli_error($link));
while ($row = mysqli_fetch_array($result, MYSQLI_BOTH)) {
    $dizi_sayisi = $row[0];
    echo '<div><div class="flex-container">
    <div style="background-color:#f39c11; width: 240px; height: 140px; padding: 15px;"><h3>Dizi Sayısı</h3><h1>'.$dizi_sayisi.'</h1></div>
    <div style="background-color:#da8c10; width: 240px; height: 40px; padding: 5px;"><a href="api.php">Yeni Ekle</a></div>
</div></div>';
}
$query = 'SELECT COUNT(id) FROM icerik WHERE tur = "Film"';
$result = mysqli_query($link, $query) or die(mysqli_error($link));
while ($row = mysqli_fetch_array($result, MYSQLI_BOTH)) {
    $film_sayisi = $row[0];
    echo '<div><div class="flex-container">
    <div style="background-color:#de4b39; width: 240px; height: 140px; padding: 15px;"><h3>Film Sayısı</h3><h1>'.$film_sayisi.'</h1></div>
    <div style="background-color:#c64233; width: 240px; height: 40px; padding: 5px;"><a href="api.php">Yeni Ekle</a></div>
</div></div>';
}

echo '</div><div class="satir">';
$query = 'SELECT COUNT(yorum_id) FROM yorum WHERE yorum_durum = 1';
$result = mysqli_query($link, $query) or die(mysqli_error($link));
while ($row = mysqli_fetch_array($result, MYSQLI_BOTH)) {
    $onaylanmis_yorum = $row[0];
    echo '<div><div class="flex-container">
    <div style="background-color:#00a65a; width: 240px; height: 140px; padding: 15px;"><h3>Yorum Sayısı</h3><h1>'.$onaylanmis_yorum.'</h1></div>
    <div style="background-color:#009551; width: 240px; height: 40px; padding: 5px;"><a href="yorumlar.php">Tümünü Gör</a></div>
</div></div>';
}

$query = 'SELECT COUNT(yorum_id) FROM yorum WHERE yorum_durum = 0';
$result = mysqli_query($link, $query) or die(mysqli_error($link));
while ($row = mysqli_fetch_array($result, MYSQLI_BOTH)) {
    $onaylanmamis_yorum = $row[0];
    echo '<div><div class="flex-container">
    <div style="background-color:#0073b6; width: 240px; height: 140px; padding: 15px;"><h4>Onay Bekleyen Yorum Sayısı</h4><h1>'.$onaylanmamis_yorum.'</h1></div>
    <div style="background-color:#0067a4; width: 240px; height: 40px; padding: 5px;"><a href="yorumlar.php">Tümünü Gör</a></div>
</div></div>';
}

$query = 'SELECT COUNT(id) FROM kullanici';
$result = mysqli_query($link, $query) or die(mysqli_error($link));
while ($row = mysqli_fetch_array($result, MYSQLI_BOTH)) {
    $uye_sayisi = $row[0];
    echo '<div><div class="flex-container">
    <div style="background-color:#f012bf; width: 240px; height: 140px; padding: 15px;"><h4>Kullanıcı Sayısı</h4><h1>'.$uye_sayisi.'</h1></div>
    <div style="background-color:#d811ac; width: 240px; height: 40px; padding: 5px;"><a href="uyeler.php">Tümünü Gör</a></div>
</div></div>';
}

?>

</div>
            </div>
        
        
        </div>
    </div>

    <script src="js/jquery.min.js"></script>
<script src="js/popper.min.js"></script>
<script src="js/bootstrap.min.js"></script>

<script type="text/javascript">
$(document).ready(function () {
    $('#sidebarCollapse').on('click', function () {
        $('#sidebar').toggleClass('active');
    });
});
</script>
</body>
</html>