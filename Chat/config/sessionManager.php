<?php
class sessionManager extends baglanti //Burada baglantı classına calaşdırıb ordakı dəyişənləri falan istifadə edirik..Bu zaman istifadəçinin məlumatlarına Database üzərinnən bağlanmış oluruq
{
    /*
     * 1.Session yaratma
     * 2.Session silmə
     * 3.Kullanıcı giriş kontrolu
     * Kullanıcı bilgilerini al
     */
    //Session məlumatlarını Array(massiv) içərisində çəkirik

    static function sessionYarat($array = [])
    {
        if(count($array) != 0) //Gələn massivin içərisində məlumat varsa(0-a bərabər deyilsə)
        {
            foreach ($array as $key => $value) //Gələn massivi al və listele
            {
                $_SESSION[$key] = $value; //Kayıt ol səhifəsindəki Massivin dəyərləridir..QEYD:Session oturumunu mütləq aç...Bu saytda baglanti səhifəsindədir
            }
        }
    }




    static function sessionSil()
    {
        session_destroy(); //Bütün Session`u sil
    }





    public function kontrol() //QEYD: public metodu tanımladığımız üçün bağlantı səhifəsinin içərisində bu səhifəni(classı) (SessionManager) tanımla
    {
        if(isset($_SESSION['email']) and isset($_SESSION['sifre'])) //Əgər sessionlardan bizə hər hansı email və şifrə gəlirsə, yəni boş dəyər gəlmirsə
        {
            $email = strip_tags($_SESSION['email']);
            $sifre = strip_tags($_SESSION['sifre']);

            $control = $this->db->prepare("SELECT * FROM istifadeci WHERE email= :email and sifre= :sifre"); //Əgər belə bir istifadəçi varsa kontrol elə
            $control->bindParam(":email",$email,PDO::PARAM_STR);// 1ci parametri (email) daxil edirik
            $control->bindParam(":sifre",$sifre,PDO::PARAM_STR);// 2ci parametri (sifre) daxil edirik
            $control->execute(); //Yuxarıdakı əmrləri işlə
            $sayi = $control->rowCount(); //Dönən sayını alırıq

            if($sayi == 0) //Yəni belə bir istifadəçi yoxdursa, Databasedəki sayı Sıfırdırsa
            {
                return false; //False döndür
            }
            else //Yox əgər belə bir istifadəçi varsa
            {
                return true; //True Döndür
            }
        }
        else //Əgər gəlmirsə
        {
            return false;  //İstifadəçi giriş etmiyib deyə false göndərəcəyik
        }
    }


    public function istifadeciBilgi()
    {
        if($this->kontrol()) // Əgər bu class($this) içərisində kontrol işləmi gedirsə yəni məlumatlar doğrudursa
        {
            $sorgu = $this->db->prepare("SELECT * FROM istifadeci WHERE email= :email and sifre= :sifre"); //Əgər belə bir istifadəçi varsa kontrol elə
            $sorgu->bindParam(":email",$_SESSION['email'],PDO::PARAM_STR); //Parametri daxil edirik..QEYD: Burada ona görə kontrolu etmirik ki yuxarıda function`da onsuzda etmişik..Yəni orda işləmirsə onsuzda bu sətrə keçməz yəni True döndərməz
            $sorgu->bindParam(":sifre",$_SESSION['sifre'],PDO::PARAM_STR); //Parametri daxil edirik..
            $sorgu->execute(); //Yuxarıdakı əmrləri işlət
            return $sorgu->fetch(PDO::FETCH_ASSOC); //Daha sonra isə məlumatları Fetch əmri ilə alırıq
        }
        else //Əgər bilgilər yanlışdırsa
        {
            return false; //False döndür
        }
    }




















}