    <?php

    if(isset($_GET["id"])){
    $id = $_GET["id"];
    }
    else{
    echo '<script>
        alert(2);
    }
  </script>';
    header("location: index.php");
    exit;
    }
    require_once "header.php";

    require_once "veritabani.php";

    $oyOrani = "Henüz oy verilmedi!<br>İlk oy veren siz olun!";
    $oyBasarili = false;
    $oyPuan = 1;
    $oyVerilmis = false;

    if(isset($_SESSION['yetki']))
    {
    if($_SESSION['yetki'] >= 1)
    {
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        

        if(isset($_POST["puan"]) && isset($_POST["icerik_id"])){
            if(empty(trim($_POST["puan"])) || trim($_POST["puan"]) < 1 || trim($_POST["puan"]) > 5){
                $puan_hatasi = "Beklenmeyen bir durum oluştu."; 
            }else{
                $puan = trim($_POST["puan"]);
            }
        
            if(empty(trim($_POST["icerik_id"]))){
                $icerik_hatasi = "Beklenmeyen bir durum oluştu."; 
            }else{
                $icerik_id = trim($_POST["icerik_id"]);
            }
            if(empty($yorum_hatasi) && empty($icerik_hatasi)){


                $query = 'SELECT * FROM puan WHERE icerik_id =  "'.$id.'" AND kullanici_id = "'.$_SESSION['id'].'"';
                $result = mysqli_query($link, $query) or die(mysqli_error($link));
                while ($row = mysqli_fetch_array($result, MYSQLI_BOTH)) {
                    $oyVerilmis = true;
                } 
                if($oyVerilmis == true){
                    $sql = 'UPDATE puan SET puan =  "'.$puan.'" WHERE kullanici_id = "'.$_SESSION['id'].'" AND icerik_id = "'.$icerik_id.'"';

                    if($stmt = mysqli_prepare($link, $sql)){
                        mysqli_query($link, $sql);
                        if(mysqli_stmt_execute($stmt)){
                        } else{
                            echo "Bir hata oluştu. C0";
                            // echo mysqli_error($link);
                        }
                    }
                }
                else{
                    $sql = "INSERT INTO puan (icerik_id, kullanici_id, puan) VALUES (?, ?, ?)";
             
                if($stmt = mysqli_prepare($link, $sql)){
                    mysqli_stmt_bind_param($stmt, "iii", $param_icerik_id, $param_kullanici_id, $param_puan);
                    
        
                    $param_icerik_id = $icerik_id;
                    $param_kullanici_id = $_SESSION['id'];
                    $param_puan = $puan;
                    
                    if(mysqli_stmt_execute($stmt)){
                        $oyBasarili = true;
                    } else{
                        echo "Bir hata oluştu. C1";
                        // echo mysqli_error($link);
                    }
                }
                }
                
                mysqli_stmt_close($stmt);
    
            }
        }
    }}}

    $query = 'SELECT * FROM puan WHERE icerik_id =  "'.$id.'"';
    $result = mysqli_query($link, $query) or die(mysqli_error($link));
    $oy = 0;
    $oySayisi = 0;
    while ($row = mysqli_fetch_array($result, MYSQLI_BOTH)) {
        $oy = $oy + $row['puan'];
        $oySayisi = $oySayisi + 1;
    } 

    if(!empty($oy)){
       $oyOrani =  (int)($oy / $oySayisi);
       $oyOrani = $oyOrani;
    }



    $yorum_yapildi_mi = false;

    $query = 'SELECT * FROM icerik WHERE id = "'.$id.'"';
    $result = mysqli_query($link, $query) or die(mysqli_error($link));

    while ($row = mysqli_fetch_array($result, MYSQLI_BOTH)) {
    $icerik_adi = $row['icerik_adi'];
    $poster = $row['poster'];
    $arkaplan = $row['arkaplan'];
    $tur = $row['tur'];
    $id = $row['id'];
    $yil = $row['yil'];
    $sezon = $row['sezon'];
    $bolum = $row['bolum'];
    $ozet = $row['ozet'];
    $ozlu_soz = $row['soz'];
    $fragman = $row['fragman'];
    $url = str_replace('https://www.youtube.com/watch?v=','',$fragman);
    echo '<!DOCTYPE html>
    <html lang="tr">
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/style.css">

    <script src="js/bootstrap.js"></script>
    <style>

    .container{

    height: 100%;
    color: black;
    }
    img{
    flex: 1;
    }
    span{
    display: inline;
    font-size: 18px;
    margin: 20px;
    }
    img{
    border: 3px solid white;
    border-radius: 3px;
    }
    .ozet{
    font-size: 14px;
    height: 50px;

    }
    h1{
    font-size: 23px;
    text-align: center;
    }
    .icerik-detaylari{
    background-image: radial-gradient(circle at 20% 50%, rgba(255, 255, 255, 1) 0%, rgba(255, 255, 255, 1) 100%);
    padding-right: 150px;
    }
    .card, card-body{
    background-color: transparent;
    background-image: radial-gradient(circle at 20% 50%, rgba(255, 255, 255, 0.9) 0%, rgba(255, 255, 255, 0.9) 100%);        display: block;
    height: 375px;
    width: 100%;
    flex: 2;

    }
    .card-body {
    padding: 1.5rem 2rem !important;
    }
    body{
    color: white !important;
    background-image: url("'.$arkaplan.'");
    position: absolute;
    left: 0;
    right: 0;
    width: 100%;
    height: 100%;
    z-index: -1;
    display: inline;
    background-size: cover;
    will-change: opacity;
    transition: filter 1s;
    }
    table{
    width: 600px;
    }
    .flex-container {
    display: flex;
    }

    .flex-container > div {
    margin: 10px;
    padding: 20px;
    font-size: 30px;
    }
    .flex-container-column {
    display: flex;
    flex-direction: column;
    }
    </style>
    <title>'.$icerik_adi.' - İçerik Veritabanım</title>
    </head>
    <body>
    <div class="arkaplan">
    <div class="container">

    <div class="flex-container-column">

    <div class="flex-container">
    <div style="flex: 1; text-align:center; "><img src="'.$poster.'" class="img-fluid" alt="'.$icerik_adi.'" width="250px" height="600px"></div>
    <div class="dikey" style="flex: 2;"><div class="card" style="width: 45rem; "><div class="card-body">
    <h1>'.$icerik_adi.'</h1>

    <span class="ozet">'.$ozet.'</span>

    </div></div>
        </div>
    </div>


    <div class="flex-container">
    <div style="text-align: center; padding-top: 80px;">
    <div class="card" style="width: 20rem; height: 318px;"><div class="card-body">
    <div class="flex-container-column">';
    if($tur == "Film"){
        echo '<span>'.$icerik_adi.'</span>';
        if($ozlu_soz != ""){
            echo '<span>'.$ozlu_soz.'</span>';
        }
        echo '<span>Yayın Yılı: '.$yil.'</span>';
        echo '
    <form id="puanVerForm" name="puanVerForm" action="'.$_SERVER["PHP_SELF"].'?id='.$id.'" method="post" enctype="multipart/form-data">
    <div class="form-group">';
    while($oyPuan != 6){
        if($oyOrani >= $oyPuan){
            echo '<span onclick="puanVer('.$oyPuan.')" style="margin: 5px;" class="fas fa-star checked"></span>';
        }
        else{
            echo '<span onclick="puanVer('.$oyPuan.')" style="margin: 5px;" class="far fa-star checked"></span>';
        }
        $oyPuan += 1;
    }
echo '<input type="hidden" name="puan" value="5" />
<input type="hidden" name="icerik_id" value="'.$id.'" />
</div>
</form>';


if($oyVerilmis == false && $oyBasarili == true){
    echo '<span style="margin-top: 0px;">Oyunuz gönderildi!</span>';
}
else{
    if($oyVerilmis == true){
        echo '<span style="margin-top: 0px;">Oyunuz güncellendi!</span>';
}
}
    }
    else{
        echo '<span>'.$icerik_adi.'</span>';
        echo '<span>Yayın Yılı: '.$yil.'</span>';
        echo '<span>'.$sezon.' Sezon / '.$bolum.' Bölüm</span>';
        echo '
    <form id="puanVerForm" name="puanVerForm" action="'.$_SERVER["PHP_SELF"].'?id='.$id.'" method="post" enctype="multipart/form-data">
    <div class="form-group">';

    while($oyPuan != 6){
        if($oyOrani >= $oyPuan){
            echo '<span onclick="puanVer('.$oyPuan.')" style="margin: 5px;" class="fas fa-star checked"></span>';
        }
        else{
            echo '<span onclick="puanVer('.$oyPuan.')" style="margin: 5px;" class="far fa-star checked"></span>';
        }
        $oyPuan += 1;
        }
echo '<input type="hidden" name="puan" value="5" />
<input type="hidden" name="icerik_id" value="'.$id.'" />
</div>
</form>';


if($oyVerilmis == false && $oyBasarili == true){
    echo '<span style="margin-top: 0px;">Oyunuz gönderildi!</span>';
}
else{
    if($oyVerilmis == true){
        echo '<span style="margin-top: 0px;">Oyunuz güncellendi!</span>';
}
}
    }


    echo '
    </div></div>

    </div></div>



    <div style="text-align: center; padding-top: 80px; padding-left: 30px; padding-right: 30px;  height: 100%;">
    <iframe width="560" height="315" src="https://www.youtube.com/embed/'.$url.'" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen style="margin-bottom: 10px;"></iframe>
    </div>

    </div>




    </div>

    ';

    if(isset($_SESSION['yetki']))
    {
    if($_SESSION['yetki'] >= 1)
    {

    $yorum = "";
    $yorum_hatasi = "";
    if($_SERVER["REQUEST_METHOD"] == "POST"){

        if(isset($_POST["yorum"])){
            if(empty(trim($_POST["yorum"]))){
                $yorum_hatasi = "Lütfen yorumunuzu giriniz.";     
                }else{
                $yorum = trim($_POST["yorum"]);
                }
                $isim = $_SESSION["isim"];
                $kullanici_id = $_SESSION["id"];
                $yorum_durum = 0;
            
                if(empty($yorum_hatasi)){
            
                $sql = "INSERT INTO yorum (yorum_sahibi, yorum_icerigi, yorum_durum, icerik_id, kullanici_id) VALUES (?, ?, ?, ?, ?)";
            
                if($stmt = mysqli_prepare($link, $sql)){
                mysqli_stmt_bind_param($stmt, "ssiii", $param_yorum_sahibi, $param_yorum_icerigi, $param_yorum_durum, $param_icerik_id, $param_kullanici_id);
            
            
                $param_yorum_sahibi = $isim;
                $param_yorum_icerigi = $yorum;
                $param_yorum_durum = $yorum_durum;
                $param_icerik_id = $id;
                $param_kullanici_id = $kullanici_id;
            
                echo $kullanici_id;
            
                if(mysqli_stmt_execute($stmt)){
                $yorum_yapildi_mi = true;
                } else{
                echo "Bir hata oluştu. C2";
                // echo mysqli_error($link);
                }
                }
            
                mysqli_stmt_close($stmt);
                }
        }
    }

    echo '<div class="flex-container" style="padding-left: 300px; padding-right: 300px; margin-top: 30px;">
    <div class="card" style=" text-align: center;">
    <div class="card-body">
    <h4 class="card-title" style="padding: 20px;">Yorum Yapın</h4>
    <form action="'.$_SERVER["PHP_SELF"].'?id='.$id.'" method="post" enctype="multipart/form-data">
    <div class="form-group">
    <label for="yorum">Yorumunuz</label>
    <textarea class="form-control" id="yorum" rows="8" name="yorum"></textarea>
    </div>
    <button type="submit" class="btn btn-gradient-primary mr-2">Gönder</button>
    </form> 
    </div> </div>
    </div>';
    if($yorum_yapildi_mi){
    echo '<p style="margin-top: 50px; font-size: 18px; text-align: center; color: white;">'.$_SESSION["isim"].', yorumunuz gönderildi ve onay bekliyor.';
    }
    echo '
    </div>
    </div>
    <div class="flex-container-column" style="padding-left: 200px; padding-right: 200px; padding-top: 50px; text-align: center;">
   ';
    $query = 'SELECT * FROM yorum WHERE icerik_id='.$id.' AND yorum_durum = 1';
    $result = mysqli_query($link, $query) or die(mysqli_error($link));
    while ($row = mysqli_fetch_array($result, MYSQLI_BOTH)) {
    $yorum_sahibi = $row['yorum_sahibi'];
    $yorum_icerigi = $row['yorum_icerigi'];
    $yorum_tarihi = $row['yorum_saati'];
    echo '
    <p style="font-size: 18px; border: 2px solid gray; border-radius: 100px; padding: 10px; text-align: left; width: 100%; color: black; background-color: #f5f5f5";>'.$yorum_sahibi.' > '.$yorum_tarihi.'</p>
    <p style="border: 1px solid black; padding: 10px; border-radius: 15px; font-size: 17px; background-color: #ffffff; color: black;">'.$yorum_icerigi.'</p>
    ';

    }
    echo ' 
   
    </div>';
    }

    }else{
        echo '
        </div>
        </div><p style="text-align: center; font-size: 20px;">Yorum yapabilmek için lütfen <a href="giris.php">giriş</a> yapın.</p>
        <div class="flex-container-column" style="padding-left: 200px; padding-right: 200px; padding-top: 50px; text-align: center;">
       ';
    $query = 'SELECT * FROM yorum WHERE icerik_id='.$id.' AND yorum_durum = 1';
    $result = mysqli_query($link, $query) or die(mysqli_error($link));
    while ($row = mysqli_fetch_array($result, MYSQLI_BOTH)) {
    $yorum_sahibi = $row['yorum_sahibi'];
    $yorum_icerigi = $row['yorum_icerigi'];
    $yorum_tarihi = $row['yorum_saati'];
    echo '
    <p style="font-size: 18px; border: 2px solid gray; border-radius: 100px; padding: 10px; text-align: left; width: 100%; color: black; background-color: #f5f5f5";>'.$yorum_sahibi.' > '.$yorum_tarihi.'</p>
    <p style="border: 1px solid black; padding: 10px; border-radius: 15px; font-size: 17px; background-color: #ffffff; color: black;">'.$yorum_icerigi.'</p>
    ';

    }
    echo ' 
    </div>';
    }
    mysqli_close($link);
    echo '

    </div>
    </div>
';

    }


    ?>
      <script>
    function puanVer(puan){
        document.puanVerForm.puan.value = puan;
        document.getElementById("puanVerForm").submit();
    }
  </script>