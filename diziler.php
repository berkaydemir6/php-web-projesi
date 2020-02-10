<?php

require_once "header.php";

?>

<title>Diziler</title>

<?php
 
require_once "veritabani.php";

$query = 'SELECT arkaplan FROM icerik WHERE tur = "Dizi" ORDER BY RAND() LIMIT 1';
$result = mysqli_query($link, $query) or die(mysqli_error($link));
while ($row = mysqli_fetch_array($result, MYSQLI_BOTH)) {
    $arkaplan = $row['arkaplan'];
echo '<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Diziler - İçerik Veritabanım</title>
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/style.css">
    <script src="js/bootstrap.js"></script>
    <style>
      td{
        margin: 10px;
        padding: 24px;
      }
.header{
     background-color: white;
     text-align: center;
     line-height: 150%;
}body{
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

    </style>
</head>
<body>
    <div class="arkaplan">';
}

$query = "SELECT * FROM icerik WHERE tur = 'Dizi' ORDER BY id DESC";
$result = mysqli_query($link, $query) or die(mysqli_error($link));
$i = 0;
echo '<div class="container"><table><tr>';
while ($row = mysqli_fetch_array($result, MYSQLI_BOTH)) {
    $icerik_adi = $row['icerik_adi'];
    $poster = $row['poster'];
    $arkaplan = $row['arkaplan'];
    $id = $row['id'];
    $ozet = $row['ozet'];
    $puan = $row['puan'];
    if($ozet){
        if(strlen($ozet) > 250) {
            $splitat = strpos($ozet," ",250);
            $ozet = substr($ozet, 0, $splitat);
      }
      }
      else{
        $ozet = 'Özet bulunamadı.';
    }
    echo '<td><div class="card" style="width: 20rem;">
    <img class="card-img-top" src="'.$poster.'" alt="'.$icerik_adi.'" style="width: 320px; height: 480px;">
    <div class="card-body">
    <h5 class="card-title">'.$icerik_adi.'</h5>
    <p class="card-text">'.$ozet.'...</p>
    </div>
    <div class="card-body">
    <a href="icerik.php?id='.$id.'" class="card-link">Detayları Görüntüle</a>
    </div>
</div></td>';
$i+=1;
if ($i == 3){
    $i = 0;
    echo '</tr></tr>';
}
}
?>   
    </div>

<?php

require_once "footer.php";

?>