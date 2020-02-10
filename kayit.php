<?php
require_once "veritabani.php";

$kullanici_adi = $sifre = $sifreyi_onayla = $isim = "";
$kullanici_adi_hatasi = $sifre_hatasi = $sifreyi_onayla_hatasi = $isim_hatasi = "";
$yetki = 1;
 
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    if(empty(trim($_POST["kullanici_adi"]))){
        $kullanici_adi_hatasi = "Lütfen kullanıcı adını giriniz.";
    } else{
        $sql = "SELECT id FROM kullanici WHERE kullanici_adi = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            mysqli_stmt_bind_param($stmt, "s", $param_kullanici_adi);
            
            $param_kullanici_adi = trim($_POST["kullanici_adi"]);
            
            if(mysqli_stmt_execute($stmt)){
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1){
                    $kullanici_adi_hatasi= "Bu kullanıcı adı zaten kullanılıyor.";
                } else{
                    $kullanici_adi = trim($_POST["kullanici_adi"]);
                }
            } else{
                echo "Bir hata oluştu, lütfen tekrar deneyin.";
            }
        }
         
        mysqli_stmt_close($stmt);
    }
    
    if(empty(trim($_POST["sifre"]))){
        $sifre_hatasi = "Lütfen bir şifre giriniz.";     
    } elseif(strlen(trim($_POST["sifre"])) < 6){
        $sifre_hatasi = "Şifreniz 6 karakterden uzun olmalıdır.";
    } else{
        $sifre = trim($_POST["sifre"]);
    }
    
    if(empty(trim($_POST["sifreyi_onayla"]))){
        $sifreyi_onayla_hatasi = "Lütfen şifrenizi tekrar giriniz.";     
    } else{
        $sifreyi_onayla = trim($_POST["sifreyi_onayla"]);
        if(empty($sifre_hatasi) && ($sifre != $sifreyi_onayla)){
            $sifreyi_onayla_hatasi = "Şifreler uyuşmuyor.";
        }
    }

    if(empty(trim($_POST["isim"]))){
        $isim_hatasi = "Lütfen isminizi giriniz.";     
    } elseif(strlen(trim($_POST["isim"])) > 60){
        $isim_hatasi = "İsminiz çok uzun.";
    } else{
        $isim = trim($_POST["isim"]);
    }

    if($stmt = mysqli_prepare($link, $sql)){
        mysqli_stmt_bind_param($stmt, "s", $param_kullanici_adi);
        
        $param_kullanici_adi = trim($_POST["kullanici_adi"]);
    
    if(empty($kullanici_adi_hatasi) && empty($sifre_hatasi) && empty($sifreyi_onayla_hatasi) && empty($isim_hatasi)){
        
        $sql = "INSERT INTO kullanici (kullanici_adi, sifre, isim, yetki) VALUES (?, ?, ?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            mysqli_stmt_bind_param($stmt, "sssi", $param_kullanici_adi, $param_sifre, $isim, $yetki);
            
            $param_kullanici_adi = $kullanici_adi;
            $param_sifre = password_hash($sifre, PASSWORD_DEFAULT);
            
            if(mysqli_stmt_execute($stmt)){
                header("location: giris.php");
            } else{
                echo "Bir hata oluştu, tekrar deneyin.";
            }
        }
        mysqli_stmt_close($stmt);
    }
    
    mysqli_close($link);
}}
?>
 
 <!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Kayıt ol - İçerik Veritabanım</title>
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .container{
        padding-top:2rem;
		padding-bottom:4.2rem;
        height: 100%;
        }
        a{
         text-decoration:none !important;
         }
         h1,h2,h3{
         font-family: 'Kaushan Script', cursive;
         }
          .myform{
		position: relative;
		display: -ms-flexbox;
		display: flex;
		padding: 1rem;
		-ms-flex-direction: column;
		flex-direction: column;
		width: 100%;
		pointer-events: auto;
		background-color: #fff;
		background-clip: padding-box;
		border: 1px solid rgba(0,0,0,.2);
		border-radius: 1.1rem;
		outline: 0;
		max-width: 500px;
		 }
         .tx-tfm{
         text-transform:uppercase;
         }
         .mybtn{
         border-radius:50px;
         }
        
         .login-or {
         position: relative;
         color: #aaa;
         margin-top: 10px;
         margin-bottom: 10px;
         padding-top: 10px;
         padding-bottom: 10px;
         }
         .span-or {
         display: block;
         position: absolute;
         left: 50%;
         top: -2px;
         margin-left: -25px;
         background-color: #fff;
         width: 50px;
         text-align: center;
         }
         .hr-or {
         height: 1px;
         margin-top: 0px !important;
         margin-bottom: 0px !important;
         }
         .google {
         color:#666;
         width:100%;
         height:40px;
         text-align:center;
         outline:none;
         border: 1px solid lightgrey;
         }
          form .error {
         color: #ff0000;
         }
         #second{display:none;}
         .arkaplan{
        background-image: url("img/kayit.jpg");
        position: absolute;
        left: 0;
        right: 0;
        width: 100%;
        height: 100%;
        z-index: -1;
        display: block;
        background-size: cover;
        background-repeat: no-repeat;
        background-position: 50% 50%;
        will-change: opacity;
        transition: filter 1s;
    }
    </style>
    <link rel="stylesheet" href="css/bootstrap.css">
    <script src="js/bootstrap.js"></script>
</head>
<body>
    <div class="arkaplan">
    <div class="container">
        <div class="row">
			<div class="col-md-5 mx-auto">
			<div id="first">
				<div class="myform form ">
					 <div class="logo mb-3">
						 <div class="col-md-12 text-center">
							<h1>Kayıt olun</h1>
						 </div>
					</div>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                           <div class="form-group">
                              <label>İsim</label>
                              <input type="text" name="isim" class="form-control" placeholder="Lütfen adınızı giriniz.">
                              <span class="help-block"><?php echo $isim_hatasi; ?></span>
                           </div>
                           <div class="form-group <?php echo (!empty($kullanici_adi_hatasi)) ? 'has-error' : ''; ?>">
                                <label>Kullanıcı Adı</label>
                                <input type="text" name="kullanici_adi" class="form-control" placeholder="Lütfen kullanıcı adınızı giriniz.">
                                <span class="help-block"><?php echo $kullanici_adi_hatasi; ?></span>
                            </div>    
                            <div class="form-group <?php echo (!empty($sifre_hatasi)) ? 'has-error' : ''; ?>">
                                <label>Şifre</label>
                                <input type="password" name="sifre" class="form-control" placeholder="Lütfen şifrenizi giriniz.">
                                <span class="help-block"><?php echo $sifre_hatasi; ?></span>
                            </div>
                            <div class="form-group <?php echo (!empty($sifreyi_onayla_hatasi)) ? 'has-error' : ''; ?>">
                                <label>Şifreyi onayla</label>
                                <input type="password" name="sifreyi_onayla" class="form-control" placeholder="Lütfen şifrenizi tekrar giriniz.">
                                <span class="help-block"><?php echo $sifreyi_onayla_hatasi; ?></span>
                            </div>
                           <div class="col-md-12 text-center ">
                              <button type="submit" class=" btn btn-block mybtn btn-primary tx-tfm">Kayıt ol</button>
                           </div>
                           <div class="col-md-12 ">
                              <div class="login-or">
                                 <hr class="hr-or">
                                 <span class="span-or">ya da</span>
                              </div>
                           </div>
                           <div class="form-group">
                              <p class="text-center">Hesabınız var mı? <a href="giris.php">Buradan giriş yapın</a></p>
                           </div>
                        </form>
                 
				</div>
			</div>
			</div> 
         </div>
         </div>
    </div>
</body>
</html>