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
            $yetki = $_SESSION['yetki'];
            $icerik_adi = $tur = $yayin = $sezon = $bolum = $fragman = $ozet = $yil = "";
            $icerik_adi_hatasi = "";
            $tur_hatasi = "";

            if($_SERVER["REQUEST_METHOD"] == "POST"){
    
            if(empty(trim($_POST["icerik-adi"]))){
                $icerik_adi_hatasi = "Lütfen içeriğin adınız giriniz.";     
            }else{
                $icerik_adi = trim($_POST["icerik-adi"]);
            }

            if(empty(trim($_POST["tur"]))){
                $tur = "Lütfen içerik türünü giriniz.";     
            }else{
                $tur = trim($_POST["tur"]);
            }

            if(!empty(trim($_POST["yil"]))){
                $yil = trim($_POST["yil"]);  
            }

            if(!empty(trim($_POST["sezon"]))){
                $sezon = trim($_POST["sezon"]);  
            }

            if(!empty(trim($_POST["bolum"]))){
                $bolum = trim($_POST["bolum"]);  
            }

            if(!empty(trim($_POST["fragman"]))){
                $fragman = trim($_POST["fragman"]);
            }

            if(!empty(trim($_POST["ozet"]))){
                $ozet = trim($_POST["ozet"]);  
            }
            

            $poster_dosyasi = basename($_FILES["poster-goruntusu"]["name"]);
            $arkaplan_dosyasi = basename($_FILES["arkaplan-goruntusu"]["name"]);
            $yuklemeBasarili = 1;
            $poster_dosya_tipi = strtolower(pathinfo($poster_dosyasi,PATHINFO_EXTENSION));
            $arkaplan_dosya_tipi = strtolower(pathinfo($arkaplan_dosyasi,PATHINFO_EXTENSION));
            $poster_kontrol = getimagesize($_FILES["poster-goruntusu"]["tmp_name"]);
            $arkaplan_kontrol = getimagesize($_FILES["arkaplan-goruntusu"]["tmp_name"]);
            if($poster_kontrol !== false && $arkaplan_kontrol !== false) {
                $yuklemeBasarili = 1;
            } else {
                echo "Lütfen geçerli bir resim dosyası yükleyin.";
                $yuklemeBasarili = 0;
            }
            if ($_FILES["poster-goruntusu"]["size"] > 2000000) {
                echo "Dosyanız çok büyük.";
                $yuklemeBasarili = 0;
            }
            if ($_FILES["arkaplan-goruntusu"]["size"] > 2000000) {
                echo "Dosyanız çok büyük.";
                $yuklemeBasarili = 0;
            }
            if($poster_dosya_tipi != "jpg" && $poster_dosya_tipi != "png" && $poster_dosya_tipi != "jpeg"
            && $poster_dosya_tipi != "gif" ) {
                echo "Yalnızca JPG, JPEG, PNG & GIF dosya türleri kabul edilmekte.";
                $yuklemeBasarili = 0;
            }
            if($arkaplan_dosya_tipi != "jpg" && $arkaplan_dosya_tipi != "png" && $arkaplan_dosya_tipi != "jpeg"
            && $arkaplan_dosya_tipi != "gif" ) {
                echo "Yalnızca JPG, JPEG, PNG & GIF dosya türleri kabul edilmekte.";
                $yuklemeBasarili = 0;
            }
            if ($yuklemeBasarili == 0) {
                echo "Bir hata oluştu. A0";
                // echo mysqli_error($link);
            } else {
                if (move_uploaded_file($_FILES["poster-goruntusu"]["tmp_name"], 'uploads/'.$poster_dosyasi)) {
                    echo basename( $_FILES["poster-goruntusu"]["name"]). " isimli resim dosyası başarıyla yüklendi.";
                } else {
                    echo "Dosyanız yüklenirken bir hata oluştu.";
                }
                if (move_uploaded_file($_FILES["arkaplan-goruntusu"]["tmp_name"], 'uploads/'.$arkaplan_dosyasi)) {
                    echo basename( $_FILES["arkaplan-goruntusu"]["name"]). " isimli resim dosyası başarıyla yüklendi.";
                } else {
                    echo "Dosyanız yüklenirken bir hata oluştu.";
                }
            }
    if(empty($icerik_adi_hatasi) && empty($tur_hatasi)){
        
        $sql = "INSERT INTO icerik (icerik_adi, poster, arkaplan, tur, yil, sezon, bolum, fragman, ozet) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            mysqli_stmt_bind_param($stmt, "ssssiiiss", $param_icerik_adi, $param_poster, $param_arkaplan, $param_tur, $param_yil, $param_sezon, $param_bolum, $param_fragman, $param_ozet);
            

            $param_icerik_adi = $icerik_adi;
            $param_arkaplan = 'uploads/'.basename( $_FILES["arkaplan-goruntusu"]["name"]);
            $param_poster = 'uploads/'.basename( $_FILES["poster-goruntusu"]["name"]);
            $param_tur = $tur;
            $param_yil = $yil;
            $param_sezon = $sezon;
            $param_bolum = $bolum;
            $param_fragman = $fragman;
            $param_ozet = $ozet;
            
            if(mysqli_stmt_execute($stmt)){
                header("location: index.php");
            } else{
                echo "Bir hata oluştu. A1";
                // echo mysqli_error($link);
            }
        }
         
        mysqli_stmt_close($stmt);
    }
    
    mysqli_close($link);

}

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

    <title>İçerik Ekle</title>
    <link rel="stylesheet" href="css/style.css">
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

                    <span>İçerik Ekle | Hoşgeldiniz, <?php echo htmlspecialchars($_SESSION["isim"]) ?></span>

            </nav>  
<div class="container">
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <h4 class="card-title">Dizi - Film ekleyin</h4>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
              <div class="form-group">
                <label for="icerik-adi">İçerik Adı</label>
                <input type="text" class="form-control" id="icerik-adi" placeholder="İçerik Adı" name="icerik-adi">
                <span class="help-block"><?php echo $icerik_adi_hatasi; ?></span>
              </div>
              <div class="form-group">
                  <label for="yil">Yayın Yılı</label>
                  <input type="text" class="form-control" id="yil" placeholder="Yayın Yılı" name="yil">
              </div>
              <div class="form-group">
                  <label for="sezon">Sezon Sayısı</label>
                  <input type="text" class="form-control" id="sezon" placeholder="Sezon Sayısı" name="sezon">
              </div>
              <div class="form-group">
                    <label for="bolum">Bölüm Sayısı</label>
                    <input type="text" class="form-control" id="bolum" placeholder="Bölüm Sayısı" name="bolum">
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
              <div class="form-group">
                <label>Poster</label>
                <input type="file" name="poster-goruntusu" class="file-upload-default">
                <div class="input-group col-xs-12">
                  <input type="text" class="form-control file-upload-info" disabled placeholder="Bir görüntü yükleyin.">
                  <span class="input-group-append">
                    <button class="file-upload-browse btn btn-gradient-primary" type="button">Yükle</button>
                  </span>
                </div>
              </div>
              <div class="form-group">
                  <label>Arkaplan</label>
                  <input type="file" name="arkaplan-goruntusu" class="file-upload-default">
                  <div class="input-group col-xs-12">
                    <input type="text" class="form-control file-upload-info" disabled placeholder="Bir görüntü yükleyin.">
                    <span class="input-group-append">
                      <button class="file-upload-browse btn btn-gradient-primary" type="button">Yükle</button>
                    </span>
                  </div>
                </div>
              <div class="form-group">
                <label for="ozet">Özet</label>
                <textarea class="form-control" id="ozet" rows="4" name="ozet"></textarea>
              </div>
              <button type="submit" class="btn btn-gradient-primary mr-2">Ekle</button>
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
<script src="js/header.js"></script>
<script src="js/file-upload.js"></script>
</body>
</html>