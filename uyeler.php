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
    <title>Kullanıcılar</title>
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

                    <span>Yorumlar | Hoşgeldiniz, <?php echo htmlspecialchars($_SESSION["isim"]) ?></span>

            </nav>

<?php
    if(isset($_SESSION['yetki']))
	{
		if($_SESSION['yetki']== 3)
		{
            if($_SERVER["REQUEST_METHOD"] == "POST"){

                if(isset($_GET["id"])){
                    $id = $_GET["id"];
                }
                else{
                    header("location: uyeler.php");
                    exit;
                }

                if(isset($_POST["durum"])){
                   $durum = $_POST["durum"];
                   if($durum == "sil"){
                    $sql = 'DELETE FROM kullanici WHERE id = "'.$id.'"';
                    if($stmt = mysqli_prepare($link, $sql)){
                        mysqli_query($link, $sql);
                        if(mysqli_stmt_execute($stmt)){
                        } else{
                            echo "Bir hata oluştu. D0";
                            // echo mysqli_error($link);
                        }
                    }
                    mysqli_stmt_close($stmt);
                }
                else{
                    
                    if(isset($_POST["yetki_durum"])){
                        $yetki_durum = $_POST["yetki_durum"];
                        if ($yetki_durum == 'uye'){
                            $sql = 'UPDATE kullanici SET yetki = 1 WHERE id = "'.$id.'"';
                        }
                        elseif ($yetki_durum == 'editor'){
                            $sql = 'UPDATE kullanici SET yetki = 2 WHERE id = "'.$id.'"';
                        }
                        elseif ($yetki_durum == 'yonetici'){
                            $sql = 'UPDATE kullanici SET yetki = 3 WHERE id = "'.$id.'"';
                        }
                       

                    if($stmt = mysqli_prepare($link, $sql)){
                        mysqli_query($link, $sql);
                        if(mysqli_stmt_execute($stmt)){
                        } else{
                            echo "Bir hata oluştu. D1";
                            // echo mysqli_error($link);
                        }
                    }
                    mysqli_stmt_close($stmt);
                     }
                }
                }
                else{
                    header("location: uyeler.php");
                }
                
                    
            }
$query = "SELECT * FROM kullanici";
$result = mysqli_query($link, $query) or die(mysqli_error($link));
$i = 0;
echo '<div class="container"><h2 style="text-align: center; margin: 20px;">Kayıtlı Kullanıcılar</h2><table><tr><td>ID</td><td>Kullanıcı Adı</td><td>İsim</td><td>Yetki</td><td>Yetkiyi Düzenle</td></tr><tr>';
while ($row = mysqli_fetch_array($result, MYSQLI_BOTH)) {
    $uye_id = $row['id'];
    $kullanici_adi = $row['kullanici_adi'];
    $isim = $row['isim'];
    $yetki = $row['yetki'];
    if($yetki == 1){
        $yetki = 'Üye';
    }
    elseif($yetki == 2){
        $yetki = 'Editör';
    }
    elseif($yetki == 3){
        $yetki = 'Yönetici';
    }
    else{
        $yetki = 'Yetkisiz Giriş';
    }
    echo '<td width="100px;">'.$uye_id.'</td>';
    echo '<td width="150px;">'.$kullanici_adi.'</td>';
    echo '<td width="250px;"><a href="profil.php?id='.$uye_id.'">'.$isim.'</a></td>';
    echo '<td width="150px;">'.$yetki.'</td>';
    echo '<td width="150px;"><form action="'.$_SERVER["PHP_SELF"].'?id='.$uye_id.'" method="post" enctype="multipart/form-data" id="'.$uye_id.'">
    <div class="form-group">
    <select name="yetki_durum" style="margin-top:20px;">';
    if ($yetki == 'Üye'){
        echo '<option name="uye" value="uye">Üye</option>
        <option name="editor" value="editor">Editör</option>
        <option name="yonetici" value="yonetici">Yönetici</option>';
    }
    elseif ($yetki == 'Editör'){
        echo '<option name="editor" value="editor">Editör</option>
        <option name="uye" value="uye">Üye</option>
        <option name="yonetici" value="yonetici">Yönetici</option>';
    }
    else {
        echo '<option name="yonetici" value="yonetici">Yönetici</option>
        <option name="uye" value="uye">Üye</option>
        <option name="editor" value="editor">Editör</option>';
    }
    echo '
  </select> 
    </div></td><td>
    <button style="margin:10px;" type="submit" class="btn btn-success">Güncelle</button>
  </td>';
  echo '<td>
    <div class="form-group">
    <input type="hidden" name="durum" id="durum'.$uye_id.'" value="none" class="form-control">
    </div>
    
  </form>
  <button onclick="sil('.$uye_id.')" style="margin-bottom: 15px;" type="submit" class="btn btn-danger">Sil</button></td>';
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