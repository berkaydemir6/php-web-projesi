<?php

require_once "header.php";

require_once "veritabani.php";

$query = "SELECT arkaplan FROM icerik ORDER BY RAND() LIMIT 1";
$result = mysqli_query($link, $query) or die(mysqli_error($link));
while ($row = mysqli_fetch_array($result, MYSQLI_BOTH)) {
    $arkaplan = $row['arkaplan'];
    echo '<!DOCTYPE html>
    <html lang="tr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <link rel="stylesheet" href="css/bootstrap.css">
        <link rel="stylesheet" href="css/style.css">
        <script src="js/bootstrap.js"></script>
        <title>Anasayfa - İçerik Veritabanım</title><style>
    td{
      margin: 10px;
      padding: 24px;
    }

.header{
   background-color: white;
   text-align: center;
   line-height: 150%;
}
.container{
  display: flex;
}
.flip-card {
  margin-top: 30px;
  padding: 10px;
  padding-bottom: 0;
  background-color: transparent;
  height: 450px;
  perspective: 1000px;
  flex: 1;
}

.flip-card-inner {
  position: relative;
  height: 100%;
  width: 100%;
  text-align: center;
  transition: transform 0.6s;
  transform-style: preserve-3d;
}

.flip-card:hover .flip-card-inner {
  transform: rotateY(180deg);
}

.flip-card-front, .flip-card-back {
  position: absolute;
  height: 100%;
  backface-visibility: hidden;
}

.flip-card-front {
  background-color: #bbb;
  color: black;
  z-index: 2;
}

.flip-card-back {
  background-image: radial-gradient(circle at 20% 50%, rgba(0, 0, 0, 0.6) 0%, rgba(0, 0, 0, 0.6) 100%);
  color: white;
  transform: rotateY(180deg);
  z-index: 1;
  padding: 20px;
}
.flip-card-back p{
  padding-left: 10px;
  padding-right: 10px;
}
.flip-card-back h1{
  padding-top: 10px;
}
body{
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
<body>';
}

if(isset($_GET["s"])){
  $ara = $_GET["s"];
  $sql = 'SELECT * FROM icerik';
  $result = mysqli_query($link, $sql) or die(mysqli_error($link));
  $i = 0;
  echo '<div class="container">';
   while ($row = mysqli_fetch_array($result, MYSQLI_BOTH)) {
       $icerik_adi = $row['icerik_adi'];

       if( strpos(strtolower($icerik_adi), $ara ) !== false) {
           $poster = $row['poster'];
           $arkaplan = $row['arkaplan'];
           $ozet = $row['ozet'];
           $puan = $row['puan'];
           $id = $row['id'];
           if($ozet){
            $splitat = strpos($ozet," ",300);
            $ozet = substr($ozet, 0, $splitat);
          }
          else{
            $ozet = 'Özet bulunamadı.';
          }
          echo '<div class="col-md-4 col-12"><div class="flip-card">
  <div class="flip-card-inner">
    <div class="flip-card-front">
      <img src="'.$poster.'" alt="'.$icerik_adi.'" style="width:300px;height:450px;">
    </div>
    <div class="flip-card-back">
      <h1>'.$icerik_adi.'</h1> 
      <p>'.$ozet.'...</p> 
    <a href="icerik.php?id='.$id.'" style="color: #917AD0;">Detayları Görüntüle</a>
    </div>
  </div>
  </div>
  </div>';
  $i+=1;
  if ($i == 3){
      $i = 0;
      echo '</div><div class="container">';
  }
       }
   }
}

 else{$query = "SELECT * FROM icerik ORDER BY id DESC";
  $result = mysqli_query($link, $query) or die(mysqli_error($link));
  $i = 0;
  echo '<div class="container">';
  while ($row = mysqli_fetch_array($result, MYSQLI_BOTH)) {
      $icerik_adi = $row['icerik_adi'];
      $poster = $row['poster'];
      $arkaplan = $row['arkaplan'];
      $ozet = $row['ozet'];
      $puan = $row['puan'];
      $id = $row['id'];
      if($ozet){
        if(strlen($ozet) > 300) {
          $splitat = strpos($ozet," ",300);
          $ozet = substr($ozet, 0, $splitat);
      }
      }
      else{
        $ozet = 'Özet bulunamadı.';
      }
      
  echo '<div class="col-md-4 col-12">
  <div class="flip-card">
  <div class="flip-card-inner">
    <div class="flip-card-front">
      <img src="'.$poster.'" alt="'.$icerik_adi.'" style="width:300px;height:450px;">
    </div>
    <div class="flip-card-back">
      <h1>'.$icerik_adi.'</h1> 
      <p>'.$ozet.'...</p> 
    <a href="icerik.php?id='.$id.'" style="color: #917AD0;">Detayları Görüntüle</a>
    </div>
  </div>
  </div>
  </div>';
  $i+=1;
  if ($i == 3){
      $i = 0;
      echo '</div><div class="container">';
  }
  }
}

echo "</div>";
?>
        
    </div>

<?php

require_once "footer.php";

?>
