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
    <title>İçerik Sil</title>
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/admin.css">
    <link rel="stylesheet" href="font-awesome/css/all.css">
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

                    <span>İçerik Sil | Hoşgeldiniz, <?php echo htmlspecialchars($_SESSION["isim"]) ?></span>

            </nav>

<?php




    if(isset($_SESSION['yetki']))
	{
		if($_SESSION['yetki']== 3)
		{
            if($_SERVER["REQUEST_METHOD"] == "POST"){
    

                if(isset($_POST["durum"])){
                   $durum = $_POST["durum"];
                }
                else{
                    header("location: icerikler.php");
                }
                if(isset($_GET["id"])){
                    $id = $_GET["id"];
                }
                else{
                    header("location: icerikler.php");
                    exit;
                }
                    if($durum == "sil"){
                        $sql = 'DELETE FROM icerik WHERE id = "'.$id.'"';

                        if($stmt = mysqli_prepare($link, $sql)){
                            mysqli_query($link, $sql);
                            if(mysqli_stmt_execute($stmt)){
                            } else{
                                echo "Bir hata oluştu. B0";
                                // echo mysqli_error($link);
                            }
                        }

                        mysqli_stmt_close($stmt);
                        
                    }


                    

            }
$query = 'SELECT * FROM icerik WHERE tur = "dizi"';
$result = mysqli_query($link, $query) or die(mysqli_error($link));
$i = 0;
echo '<div class="container"><h2 style="text-align: center; margin: 20px;">Dizileriniz</h2><table><tr><td>Dizi ID</td><td>Dizi Adı</td></tr><tr>';
while ($row = mysqli_fetch_array($result, MYSQLI_BOTH)) {
    $id = $row['id'];
    $icerik_adi = $row['icerik_adi'];
    echo '<td width="100px;">'.$id.'</td>';
    echo '<td width="1000px;">'.$icerik_adi.'</td>';

  echo '<td><button style="margin: 10px;" class="btn btn-warning" onclick="window.location.href=\'icerik.php?id='.$id.'\'">Görüntüle</button></td><td><form action="'.$_SERVER["PHP_SELF"].'?id='.$id.'" method="post" enctype="multipart/form-data">
    <div class="form-group">
    <input type="hidden" name="durum" class="form-control" value="sil">
    </div>
    <button style="margin-bottom: 15px;" type="submit" class="btn btn-danger">Sil</button>
  </form></td>';
  echo '</tr></tr>';

} 

$query = 'SELECT * FROM icerik WHERE tur = "film"';
$result = mysqli_query($link, $query) or die(mysqli_error($link));
$i = 0;
$baslik = true;
echo '<table><tr><td>Film ID</td><td>Film Adı</td></tr><tr>';
while ($row = mysqli_fetch_array($result, MYSQLI_BOTH)) {
    if($baslik){
        echo '<h2 style="text-align: center; margin: 20px; display: block;">Filmleriniz</h2>';
        $baslik = false;
      }
    $id = $row['id'];
    $icerik_adi = $row['icerik_adi'];
    echo '<td width="100px;">'.$id.'</td>';
    echo '<td width="1000px;">'.$icerik_adi.'</td>';

  echo '<td><button style="margin: 10px;" class="btn btn-warning" onclick="window.location.href=\'icerik.php?id='.$id.'\'">Görüntüle</button></td><td><form action="'.$_SERVER["PHP_SELF"].'?id='.$id.'" method="post" enctype="multipart/form-data">
    <div class="form-group">
    <input type="hidden" name="durum" class="form-control" value="sil">
    </div>
    <button style="margin-bottom: 15px;" type="submit" class="btn btn-danger">Sil</button>
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


<script src="js/jquery.min.js"></script>
<script src="js/popper.min.js"></script>
<script src="js/bootstrap.min.js"></script>

<script type="text/javascript">
function sil(id) {
    document.getElementById("durum"+id).value = "sil";
    document.getElementById(id).submit();
}
$(document).ready(function () {
    $('#sidebarCollapse').on('click', function () {
        $('#sidebar').toggleClass('active');
    });
});
</script>
</body>
</html>