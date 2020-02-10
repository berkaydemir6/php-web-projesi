<?php

if(isset($_GET["id"])){
  $id = $_GET["id"];
  }
  else{
  header("location: index.php");
  exit;
}

require_once "header.php";
require_once "veritabani.php";

echo '<style>
body{
  background: #F1F3FA;
}
</style>';

$query = 'SELECT * FROM kullanici WHERE id = "'.$id.'"';
$result = mysqli_query($link, $query) or die(mysqli_error($link));

while ($row = mysqli_fetch_array($result, MYSQLI_BOTH)) {
$isim = $row['isim'];
$fotograf = $row['fotograf'];
echo '<div class="container"><div class="row profile">
<div class="col-md-7">
<div class="text-center">
<div class="Aligner">
  <div class="Aligner-item Aligner-item--top"><img src="profile/'.$fotograf.'" class="rounded" width="150px" height="150px">
  </div>
  <div class="Aligner-item" style="margin: 30px;"><h3>'.$isim.'</h3></div>
</div>
</div></div><div class="col">';
}

$query = 'SELECT * FROM yorum WHERE kullanici_id = "'.$id.'"';
$result = mysqli_query($link, $query) or die(mysqli_error($link));

while ($row = mysqli_fetch_array($result, MYSQLI_BOTH)) {
$icerik_id = $row['icerik_id'];
$yorum = $row['yorum_icerigi'];

$sql = 'SELECT * FROM icerik WHERE id = "'.$icerik_id.'"';
$sonuc = mysqli_query($link, $sql) or die(mysqli_error($link));
while ($sutun = mysqli_fetch_array($sonuc, MYSQLI_BOTH)) {
    $icerik_adi = $sutun['icerik_adi'];

    echo '<div class="Aligner">
    <div class="Aligner-item Aligner-item--top"><h3><a href="icerik.php?id='.$icerik_id.'">'.$icerik_adi.'</a></h3></div>';
    echo '<div class="Aligner-item Aligner-item--bottom" style="margin-bottom: 30px;">'.$yorum.'</div>
    </div>';

}
}
echo '</div>';

require_once "footer.php";

?>