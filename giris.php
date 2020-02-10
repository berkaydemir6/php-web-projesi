<?php
session_start();

if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    
    header("location: index.php");
    exit;
}
require_once "veritabani.php";
 
$kullanici_adi = $sifre = "";
$kullanici_adi_hatasi = $sifre_hatasi = "";
 
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    if(empty(trim($_POST["kullanici_adi"]))){
        $kullanici_adi_hatasi = "Lütfen kullanıcı adı giriniz.";
    } else{
        $kullanici_adi = trim($_POST["kullanici_adi"]);
    }
    
    if(empty(trim($_POST["sifre"]))){
        $sifre_hatasi = "Lütfen şifre giriniz.";
    } else{
        $sifre = trim($_POST["sifre"]);
    }
    
    if(empty($kullanici_adi_hatasi) && empty($sifre_hatasi)){
        
        $sifre_hatasi = "Kullanıcı adı veya şifre hatalı.";

        $sql = "SELECT id, kullanici_adi, sifre, isim, yetki FROM kullanici WHERE kullanici_adi = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            mysqli_stmt_bind_param($stmt, "s", $param_kullanici_adi);
            
            $param_kullanici_adi = $kullanici_adi;
            
            if(mysqli_stmt_execute($stmt)){
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1){                    
                    mysqli_stmt_bind_result($stmt, $id, $kullanici_adi, $hashed_password, $isim, $yetki);
                    if(mysqli_stmt_fetch($stmt)){
                        if(password_verify($sifre, $hashed_password)){
                            session_start();
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["kullanici_adi"] = $kullanici_adi;
                            $_SESSION["isim"] = $isim;
                            $_SESSION["yetki"] = $yetki;

                            
                            header('location: index.php');
                            
                            } else{
                            $sifre_hatasi = "Şifre hatalı.";
                        }
                    }
                } else{
                    $kullanici_adi = "Bu kullanıcı adında bir isim bulunamadı.";
                }
            } else{
                echo "Hata.";
            }
        }
        mysqli_stmt_close($stmt);
    }
    mysqli_close($link);
}
?>
 
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Giriş Yap - İçerik Veritabanım</title>
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .container{
        padding-top:6rem;
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
        background-image: url("img/giris.jpg");
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
							<h1>Giriş</h1>
						 </div>
					</div>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                           <div class="form-group">
                              <label>Kullanıcı Adı</label>
                              <input type="text" name="kullanici_adi" class="form-control" placeholder="Lütfen kullanıcı adınızı giriniz." value="<?php echo $kullanici_adi; ?>">
                              <span class="help-block"><?php echo $kullanici_adi_hatasi; ?></span>
                           </div>
                           <div class="form-group <?php echo (!empty($sifre_hatasi)) ? 'has-error' : ''; ?>">
                              <label>Şifre</label>
                              <input type="password" name="sifre" id="sifre"  class="form-control" aria-describedby="emailHelp" placeholder="Lütfen şifrenizi giriniz.">
                                <span class="help-block"><?php echo $sifre_hatasi; ?></span>
                            </div>
                           <div class="col-md-12 text-center ">
                              <button type="submit" class=" btn btn-block mybtn btn-primary tx-tfm">Giriş</button>
                           </div>
                           <div class="col-md-12 ">
                              <div class="login-or">
                                 <hr class="hr-or">
                                 <span class="span-or">ya da</span>
                              </div>
                           </div>
                           <div class="form-group">
                              <p class="text-center">Hesabınız yok mu? <a href="kayit.php">Buradan kayıt olun</a></p>
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