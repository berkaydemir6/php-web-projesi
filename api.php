<?php

session_start();

require_once "veritabani.php";

 
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: giris.php");
    exit;
}
else{
    if(isset($_SESSION['yetki']))
	{
		if($_SESSION['yetki'] == 3)
		{
            $icerik_kodu = $icerik_kodu_hatasi = "";
            $tur = $tur_hatasi = "";
            $fragman = "NONE";
            $sezon_sayisi = "NONE";
            $bolum_sayisi = "NONE";
            $soz = "NONE";

            if($_SERVER["REQUEST_METHOD"] == "POST"){
    
                if(empty(trim($_POST["icerik-kodu"]))){
                    $icerik_kodu_hatasi = "Lütfen içerik kodunu giriniz.";     
                }else{
                    $icerik_kodu = trim($_POST["icerik-kodu"]);
                }
    
                if(empty(trim($_POST["tur"]))){
                    $tur = "Lütfen film adını giriniz.";     
                }else{
                    $tur = trim($_POST["tur"]);
                }
                if(!empty(trim($_POST["fragman"]))){
                    $fragman = trim($_POST["fragman"]);
                }
            

            if(empty($icerik_kodu_hatasi) && empty($tur_hatasi) && empty($fragman_hatasi)){

                require_once 'vendor/autoload.php';
                require_once 'vendor/php-tmdb/api/apikey.php';
                $token  = new \Tmdb\ApiToken(TMDB_API_KEY);
                $client = new \Tmdb\Client($token);
                
                if($tur == 'Dizi'){
                    try{
                        $tvShow = $client->getTvApi()->getTvshow($icerik_kodu, array('language' => 'tr'));
                        $baslik =  $tvShow['name'];
                        $arkaplan = 'https://image.tmdb.org/t/p/original/'.explode("/", $tvShow['backdrop_path'])[1];
                        $poster =   'https://image.tmdb.org/t/p/original/'.explode("/", $tvShow['poster_path'])[1];
                        $ozet = $tvShow['overview'];
                        $yayin_tarihi = $tvShow['first_air_date'];
                        $bolum_sayisi = $tvShow['episode_run_time'][0];
                        $sezon_sayisi = $tvShow["number_of_seasons"];                                            }
                    catch (Exception $e){
                        header('location: 404.php?hata=Dizi eklenirken bir hata oluştu.&kod='. $e->getMessage());
                    }
                }
                elseif($tur == 'Film'){
                    try{
                        $movie = $client->getMoviesApi()->getMovie($icerik_kodu, array('language' => 'tr'));
                        $baslik =  $movie['title'];
                        $arkaplan = 'https://image.tmdb.org/t/p/original/'.explode("/", $movie['backdrop_path'])[1];
                        $poster =  'https://image.tmdb.org/t/p/original/'.explode("/", $movie['poster_path'])[1];
                        $soz = $movie['tagline'];
                        $ozet = $movie['overview'];
                        $yayin_tarihi = $movie['release_date'];                                            }
                    catch (Exception $e){
                        header('location: 404.php?hata=Film eklenirken bir hata oluştu.&kod='. $e->getMessage());
                    }
                }
                else{
                    header("location: 404.php?hata=İçerik Türü Bilinmiyor");
                }


$sql = "INSERT INTO icerik (icerik_adi, poster, arkaplan, tur, yil, sezon, bolum, fragman, ozet, soz) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
         
if($stmt = mysqli_prepare($link, $sql)){
mysqli_stmt_bind_param($stmt, "ssssiiisss", $param_icerik_adi, $param_poster, $param_arkaplan, $param_tur, $param_yil, $param_sezon, $param_bolum, $param_fragman, $param_ozet, $param_soz);


$param_icerik_adi = $baslik;
$param_arkaplan = $arkaplan;
$param_poster = $poster;
$param_tur = $tur;
$param_yil = $yayin_tarihi;
$param_sezon = $sezon_sayisi;
$param_bolum = $bolum_sayisi;
$param_fragman = $fragman;
$param_ozet = $ozet;
$param_soz = $soz;

if(mysqli_stmt_execute($stmt)){
    header("location: index.php");
} else{
    echo "Bir hata oluştu. Z0";
    // echo mysqli_error($link);
}

}
mysqli_stmt_close($stmt);

}
    
mysqli_close($link);

}

    }
    else{
        header("location: index.php");
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
    <title>TMDB Api</title>
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/admin.css">
    <link rel="stylesheet" href="font-awesome/css/all.css">
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
        <div id="content">

            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="container-fluid">

                    <span>TMDB Api | Hoşgeldiniz, <?php echo htmlspecialchars($_SESSION["isim"]) ?></span>

            </nav>
<div class="container">
<div class="col-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title">TMDB Api ile Dizi - Film ekleyin</h4>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
          <div class="form-group" style="margin-bottom: -10px;">
            <label for="icerik-kodu">İçerik Kodu</label>
            <input type="text" class="form-control" id="icerik-kodu" placeholder="İçerik Kodu" name="icerik-kodu">
            <span class="help-block"><?php echo $icerik_kodu_hatasi; ?></span>
          </div>
          <div class="form-group">
            <label for="ara">İçerik Ara</label>
            <table><tr><td><input type="text" class="form-control" id="ara" placeholder="Bir içerik adı giriniz" name="ara">
    </td><td><input style="margin-left: 20px;" type="button" value="Kod Ara" class="btn btn-warning" onclick="aramaYap();"/></td>
    <td><input style="margin-left: 10px;" type="button" value="Fragman Ara" class="btn btn-danger" onclick="fragmanAra();"/></td></tr></table>
            
        </div>
          
          <div class="form-group">
                  <label for="fragman">Fragman Bağlantısı</label>
                  <input type="text" class="form-control" id="fragman" placeholder="Fragman Bağlantısı" name="fragman">
            </div>
          <div class="form-group">
                <label for="tur">Tür</label>
                  <select class="form-control" id="tur" name="tur">
                    <option>Dizi</option>
                    <option>Film</option>
                  </select>
                  <span class="help-block"><?php echo $tur_hatasi; ?></span>
        </div>
          <button type="submit" class="btn btn-success">Ekle</button>
        </form>
      </div>
    </div>
  </div>
    </div>
    </div>
</div>
<script src="js/jquery.min.js"></script>
<script src="js/popper.min.js"></script>
<script src="js/bootstrap.min.js"></script>

<script type="text/javascript">
function aramaYap(){
var icerik_adi = document.getElementById("ara").value;
window.open("https://www.themoviedb.org/search?query=" + icerik_adi + "&language=tr-TR");
}
function fragmanAra(){
var fragman = document.getElementById("ara").value;
window.open("https://www.youtube.com/results?search_query=" + fragman + "+fragman");
}
$(document).ready(function () {
    $('#sidebarCollapse').on('click', function () {
        $('#sidebar').toggleClass('active');
    });
});
</script>
</body>
</html>