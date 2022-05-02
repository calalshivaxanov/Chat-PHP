<?php
require_once '../config/baglanti.php';

if(!$sessionManager->kontrol()) //İstifadəçini yoxla..Əgər həmin istifadəçi yoxdursa,
{
    // helper::yonlendir("/islemler/giris.php");
    header('Location: ../islemler/giris.php');
    //Giriş səhifəsinə göndər
    die(); //Və öldür gedsin..
}
$userBilgi = $sessionManager->istifadeciBilgi();
$istifadeciLog->start($userBilgi['id']);
require_once '../template/header.php';

?>

<div class="row ">

    <div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1">

        <div style = "background-color:#e5b02b;  opacity: 0.8; margin-top:100px;" class="panel-body">
            <form action = "" method="POST">
                <hr />
                <center><h3><b>ŞİFRƏMİ DƏYİŞDİR</b></h3></center>

                <?php
                if(isset($_POST['sifredeyis']))
                {
                    $sifre = strip_tags($_POST['sifre']);
                    $yenisifre = strip_tags($_POST['yenisifre']);
                    $yenisifretekrar = strip_tags($_POST['yenisifretekrar']);

                    if(!empty($sifre) and !empty($yenisifre) and !empty($yenisifretekrar)) //Əgər bunlar boş deyilsə
                    {
                        if($yenisifre == $yenisifretekrar) //Və Əgər yeni şifrələr bərabərdirsə
                        {
                            //Mövcud(köhnə) şifrənin yoxlanılması
                            if($userBilgi['sifre'] == md5($sifre)) //Əgər köhnə şifrəmiz doğrudursa (md5 mütləq)
                            {

                                $sorgu = $baglanti->db->prepare("UPDATE istifadeci SET sifre = ? WHERE id = ?"); //DB`dən məlumatı çəkirik
                                $calistir = $sorgu->execute(array(md5($yenisifre),$userBilgi['id'])); // Sonra isə yeni şifrəmizi və İdmizi göndəririk

                                if($calistir) //Əgər hərşey qaydasındadırsa
                                {
                                    //QEYD: Şifrə və email əsas faktor olduğuna görə biz onu dəyişdirdikdən sonra avtomatik olaraq giris.php səhifəsinə göndərir.. Bunun qarşısını almaq üçün aşağıdakı metoddan istifadə edirik
                                    //Bunun üçün biz şifrəni dəyişdirdiyimizi SessionManagerə məlumat verməliyik

                                    sessionManager::sessionYarat(array("email"=>$userBilgi['email'],"sifre"=>md5($yenisifre)));

                                    echo "Şifrə dəyişdi";
                                }
                                else //Əgər prablem varsa
                                {
                                    echo "Şifrə dəyişmədi";
                                }

                            }
                            else //Əgər köhnə şifrə doğru deyilsə
                            {
                                echo "Mövcud şifrə doğru deyil";
                            }
                        }
                        else //əgər yeni şifrələr = deyilsə
                        {
                            echo "Yeni şifrələr eyni deyil";
                        }

                    }
                    else // Əgər boş veribsə
                    {
                        echo "Lütfən bütün ünvanları doldurun";
                    }
                }
                else
                {
                    echo "Şifrənizi buradan dəyişdirə bilərsiniz";
                }
                ?>











                <div class = "form">
                    <label>Mövcud Şifrə</label>
                    <input type="text" class="form-control" name="sifre"/> <!-- Adımızı qutunun içinə çəkirik -->
                </div>

                <div class = "form">
                    <label>Yeni Şifrə</label>
                    <input type="text" class="form-control" name="yenisifre"/> <!-- SoyAdımızı qutunun içinə çəkirik -->
                </div>

                <div class = "form">
                    <label>Yeni Şifrə Təkrar</label>
                    <input type="text" class="form-control" name="yenisifretekrar"/> <!-- SoyAdımızı qutunun içinə çəkirik -->
                </div>


                <hr/>
                <input style="width: 100%" class="btn btn-success" type="submit" value="Şifremi Deyiştir" name="sifredeyis">

            </form>
            <hr/>
            <a class="link" href="index.php">Parametrlərə geri dön</a>

        </div>
    </div>
</div>
