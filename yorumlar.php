<?php

session_start();
 
require_once "veritabani.php";
 
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: giris.php");
    exit;
}

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>Yorumlar</title>
    <link rel="stylesheet" href="css/bootstrap.css">
    <script src="js/bootstrap.js"></script>
    <link rel="stylesheet" href="css/admin.css">
    <link href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" rel="stylesheet" />
    
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <!-- Popper.JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>

    <script type="text/javascript">
        $(document).ready(function () {
            $('#sidebarCollapse').on('click', function () {
                $('#sidebar').toggleClass('active');
            });
        });
    </script>
</head>
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

                    <span>Yorumlar | Hoşgeldiniz, <?php echo htmlspecialchars($_SESSION["isim"]) ?></span>

            </nav>

<?php
    if(isset($_SESSION['yetki']))
	{
		if($_SESSION['yetki'] == 2 || $_SESSION['yetki']== 3)
		{
            if($_SERVER["REQUEST_METHOD"] == "POST"){
    

                if(isset($_POST["durum"])){
                   $durum = $_POST["durum"];
                }
                else{
                    header("location: yorumlar.php");
                }
                if(isset($_GET["id"])){
                    $id = $_GET["id"];
                }
                else{
                    header("location: yorumlar.php");
                    exit;
                }
                    if($durum == "onayla"){
                        $sql = 'UPDATE yorum SET yorum_durum = 1 WHERE yorum_id = "'.$id.'"';

                        if($stmt = mysqli_prepare($link, $sql)){
                            mysqli_query($link, $sql);
                            if(mysqli_stmt_execute($stmt)){
                            } else{
                                echo "Bir hata oluştu. E0";
                                // echo mysqli_error($link);
                            }
                        }
                        mysqli_stmt_close($stmt);
                    }
                    elseif($durum == "sil"){
                        $sql = 'DELETE FROM yorum WHERE yorum_id = "'.$id.'"';

                        if($stmt = mysqli_prepare($link, $sql)){
                            mysqli_query($link, $sql);
                            if(mysqli_stmt_execute($stmt)){
                            } else{
                                echo "Bir hata oluştu. E1";
                                // echo mysqli_error($link);
                            }
                        }
                        mysqli_stmt_close($stmt);
                    }
            }
$query = "SELECT * FROM yorum WHERE yorum_durum = 0";
$result = mysqli_query($link, $query) or die(mysqli_error($link));
$i = 0;
$baslik = true;
while ($row = mysqli_fetch_array($result, MYSQLI_BOTH)) {
    if($row){
    if($baslik){
        echo '<div class="container"><h2 style="text-align: center; margin: 20px;">Onaylanmamış Yorumlar</h2><table><tr><td>ID</td><td>İçerik Adı</td><td>Yorum Sahibi</td><td>Yorum İçeriği</td><td>Yorum Tarihi</td><td>İşlem</td></tr><tr>';
        $baslik = false;
    }
    $yorum_id = $row['yorum_id'];
    $icerik_id = $row['icerik_id'];
    $yorum_sahibi = $row['yorum_sahibi'];
    $yorum_icerigi = $row['yorum_icerigi'];
    $yorum_tarihi = $row['yorum_saati'];
    $kullanici_id = $row['kullanici_id'];
    echo '<td width="50px;">'.$yorum_id.'</td>';
    $sql = 'SELECT * FROM icerik WHERE id = "'.$icerik_id.'"';
    $sonuc = mysqli_query($link, $sql) or die(mysqli_error($link));
    while ($sutun = mysqli_fetch_array($sonuc, MYSQLI_BOTH)) {
        $icerik_adi = $sutun['icerik_adi'];
        echo '<td width="210px;"><a href="icerik.php?id='.$icerik_id.'">'.$icerik_adi.'</a></td>';
    }
    echo '<td width="160px;"><a href="profil.php?id='.$kullanici_id.'">'.$yorum_sahibi.'</a></td>';
    echo '<td width="410px;">'.$yorum_icerigi.'</td>';
    echo '<td width="250px;">'.$yorum_tarihi.'</td>';
    echo '<td><form action="'.$_SERVER["PHP_SELF"].'?id='.$yorum_id.'" method="post" enctype="multipart/form-data">
    <div class="form-group">
    <input type="hidden" name="durum" class="form-control" value="onayla">
    </div>
    <button type="submit" class="btn btn-success mr-2">Onayla</button>
  </form></td>';
  echo '<td><form action="'.$_SERVER["PHP_SELF"].'?id='.$yorum_id.'" method="post" enctype="multipart/form-data">
    <div class="form-group">
    <input type="hidden" name="durum" class="form-control" value="sil">
    </div>
    <button type="submit" class="btn btn-danger">Sil</button>
  </form></td>';
  echo '</tr></tr>';
    }
    else{
        echo 'Onay bekleyen yorum bulunmamakta.';
    }

}

  $query = "SELECT * FROM yorum WHERE yorum_durum = 1";
  $result = mysqli_query($link, $query) or die(mysqli_error($link));
  $i = 0;
  $baslik = true;
  echo '<table><tr><td>ID</td><td>İçerik Adı</td><td>Yorum Sahibi</td><td>Yorum İçeriği</td><td>Yorum Tarihi</td><td>İşlem</td></tr><tr>';
  while ($row = mysqli_fetch_array($result, MYSQLI_BOTH)) {
      if($baslik){
        echo '<h2 style="text-align: center; margin: 20px; display: block;">Onaylanmış Yorumlar</h2>';
      } 
      $baslik = false;
      $yorum_id = $row['yorum_id'];
      $icerik_id = $row['icerik_id'];
      $yorum_sahibi = $row['yorum_sahibi'];
      $yorum_icerigi = $row['yorum_icerigi'];
      $yorum_tarihi = $row['yorum_saati'];
      $kullanici_id = $row['kullanici_id'];
      echo '<td width="50px;">'.$yorum_id.'</td>';
      $sql = 'SELECT * FROM icerik WHERE id = "'.$icerik_id.'"';
      $sonuc = mysqli_query($link, $sql) or die(mysqli_error($link));
      while ($sutun = mysqli_fetch_array($sonuc, MYSQLI_BOTH)) {
        $icerik_adi = $sutun['icerik_adi'];
        echo '<td width="170px;"><a href="icerik.php?id='.$icerik_id.'">'.$icerik_adi.'</a></td>';
      }
      echo '<td width="140px;"><a href="profil.php?id='.$kullanici_id.'">'.$yorum_sahibi.'</a></td>';
      echo '<td width="330px;">'.$yorum_icerigi.'</td>';
      echo '<td width="250px;">'.$yorum_tarihi.'</td>';
    echo '<td><form action="'.$_SERVER["PHP_SELF"].'?id='.$yorum_id.'" method="post" enctype="multipart/form-data">
      <div class="form-group">
      <input type="hidden" name="durum" class="form-control" value="sil">
      </div>
      <button type="submit" class="btn btn-danger">Sil</button>
    </form></td>';
    echo '</tr></tr>';
}

echo '</div></div>';
		}
        else{
            header("location: 404.php?hata=Yetkiniz olmayan bir sayfaya erişmeye çalıştınız.");
            exit;
        }
    }
    else{
        header("location: cikis.php");
    }
?>