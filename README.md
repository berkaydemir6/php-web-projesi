# PHP Dizi - Film Web Projesi

Web Projesi Yönetimi dersi için PHP öğrenimi sırasında yapılmış, film - dizi ekleyebildiğiniz bir PHP şablonu.

Hobi amaçlı kullanabilir, geliştirebilirsiniz. Mobil uyumlu değil. 
Tasarımda eksik nokta çok.
# Demo
[Heroku üzerinde demo'yu görüntüle](https://berkaydemir-web-projesi.herokuapp.com/)
# Ekran Görüntüleri
![index.php](https://raw.githubusercontent.com/b3rkaydem1r/php-web-projesi/master/github-images/screenshot-1.png)
![admin.php](https://raw.githubusercontent.com/b3rkaydem1r/php-web-projesi/master/github-images/screenshot-2.png)
![api.php](https://raw.githubusercontent.com/b3rkaydem1r/php-web-projesi/master/github-images/screenshot-3.png)
# Nasıl Yüklenir

> veritabani-olustur.php

dosyası ile veritabanının oluşmasını sağlayın. kayit.php ile kayıt olup sistemi kullanabilirsiniz. İlk sefere mahsus kayıt olduğunuz kullanıcının yetkisinin el ile PhpMyAdmin üzerinden değiştirmeniz gerekmekte.
# TMDB Api
Otomasyonu kullanmak için themoviedb.org adresinden Api Key almanız gerekmekte.

> [https://www.themoviedb.org/settings/api](https://www.themoviedb.org/settings/api)

Composer ile gerekli kütüphanelerin kurulumunu yapın.

> composer install

**apikey.php** dosyasını oluşturun.

> vendor/tmdb-api/apikey.php

Aldığınız Api Key'i bu dosyaya tanımlayın.

    define('TMDB_API_KEY', 'API_KEY_BURAYA');

Kurulum tamamlandı.