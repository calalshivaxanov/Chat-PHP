<?php
require_once '../config/baglanti.php';
require_once '../template/header.php';



?>

<div class="row ">

    <div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1">

        <div style = "background-color:#2be57f;  opacity: 0.8; margin-top:100px;" class="panel-body">
            <form action = "" method="POST">
                <hr />
                <center><h3><b>GİRİŞ</b></h3></center>

                <?php
if(isset($_COOKIE['giris'])) // Əgər kukilərdən giriş varsa
{
    $json = json_decode($_COOKIE['giris'],true); //Kukimiz json formatında olduğu üçün biz onu bu metodla yenidən massivə çeviririk
    sessionManager::sessionYarat($json); //SessionManager ilə bunun Sessionunu yaradırıq
    //QEYD: Hər hansı güvənlik işləmi almağımıza ehtiyac yoxdu.. Çünki sessionManager classında almışıq..Onsuzda bu məlumatlar əvvəlcə orda işləyib sora bizə dönəcək
    header('Location: ../index.php');

    //Bu Metodun sayəsində istifadəçinin oturumu bitsə belə biz aşağıda(kukilərdə) tanımladığımız zaman çərçivəsində artıq istifadəçi MəniXatırla dediyini zaman avtomatik olaraq giriş edə biləcək...İndi isə gedib cikis.phpdə bunu silə bilərik
}





                if(isset($_POST['giris'])) //Əgər istifadəçi GirişYap butonunu klikləyibsə
                {
                    $hatirla = @intval($_POST['xatirla']); // Məni xatırla hissəsinin dəyişkəni.. @ İşarəsini də ona görə qoyuruq ki əgər istifadəçi onu seçmiyibsə prablem yaratmasun. Seçibsə işləsin
                    $email = strip_tags($_POST['email']);
                    $sifre = strip_tags($_POST['sifre']);

                    if (!empty($email) and !empty($sifre)) //Əgər istifadəçi email və sifre hissəsini boş qoymayıbsa
                    {
                        $sifre = md5($sifre);
                        $sorgu = $baglanti->db->prepare("SELECT * FROM istifadeci WHERE email = :email and sifre = :sifre"); //DB`dən məlumatları çəkirik
                        $sorgu->bindParam(":email",$email,PDO::PARAM_STR); //1ci parametri daxil edirik
                        $sorgu->bindParam(":sifre",$sifre,PDO::PARAM_STR); //2ci parametri daxil edirik
                        $sorgu->execute(); //Yuxarıdakı əmrləri işlə
                        $sayi = $sorgu->rowCount(); // İstifadəçinin databasedə olub olmadığını yoxlamaq üçün sayını alırıq
                        if($sayi == 0) //Əgər say sıfırdırsa, yəni DB`də yoxdursa
                        {
                            echo 'Bu kullanıcı mevcut deyil';
                        }
                        else //Yox əgər DB`də varsa
                        {

                            //Məni xatırlanı yoxlasın əvvəlcə
                            if($xatirla == 1) //Əgər məni xatırlanın dəyəri 1dirsə(selectbox hissəsində özümüz dəyər verdik 1 olaraq)
                            {
                                $cookieArray = array("email"=>$email,"sifre"=>$sifre); //kukisini və massivini yaradırıq
                                $cookieArray = json_encode($cookieArray); //json yaradırıq çünki biz kukilərə massiv ata bilmiriy.. Json bunun üçündür..
                                setcookie("giris",$cookieArray,time()+30,'/'); //Daha sonra kukini tanımlayırıq..Mütləq... və hansı zaman çərçivəsində saxlayacağını təyin edirik və ən sonda mütləq gedəcəyi yolu göstəririk.. / ona görə qoyduq ki hər yerdə görünə bilsin

                            }



                            sessionManager::sessionYarat(array("email"=>$email, "sifre"=>$sifre)); //Session yaradırıq
                            header('Location: ../index.php'); //Hər şey qaydasındadırsa index.phpyə göndər
                        }
                    }
                    else //Əgər email və sifre hissəsini boş qoyubsa
                    {
                        echo "Lütfən bütün ünvanları doldurun";
                    }
                }








                ?>




                <div class = "form">
                    <label>Email</label>
                    <input type="email" class="form-control" name="email"/>
                </div>

                <div class = "form">
                    <label>Şifrə</label>
                    <input type="password" class="form-control" name="sifre"/>
                </div>

                <div class = "form">
                    <span>Məni Xatırla</span>
                    <input type="checkbox" name="xatirla" value="1">
                </div>


                <input style="width: 100%" class="btn btn-success" type="submit" value="GİRİŞ" name="giris">
                <span class="col-lg-pull-4">
                             <a href="unutdum.php" >Şifrəmi Unutdum ? </a>
                </span>

            </form>
        </div>
    </div>
</div>

