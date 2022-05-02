<?php
require_once '../config/baglanti.php';

if(!$sessionManager->kontrol()) //İstifadəçini yoxla..Əgər həmin istifadəçi yoxdursa,
{
    // helper::yonlendir("/islemler/giris.php");
    header('Location: islemler/giris.php');
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
                <center><h3><b>PROFİL MƏLUMATLARIMI DƏYİŞDİR</b></h3></center>

    <?php
    if(isset($_POST['parametrdeyis']))
    {
        $isim = strip_tags($_POST['isim']);
        $soyad = strip_tags($_POST['soyad']);
        $cinsiyyet = intval($_POST['cinsiyet']);

        if(!empty($isim) and !empty($soyad)) //Əgər Ad və soyad qismi boş deyilsə
        {
            $sorgu = $baglanti->db->prepare("UPDATE istifadeci SET isim = ?, soyisim = ?, cinsiyet = ? WHERE id = ?"); //DB`dən məlumatları çəkirik əvvəlcə QEYD: İD zatən kBilgi dəyişkənindən gəlir ona görə əlavə id dəyişkənini vermirik
            $calistir = $sorgu->execute(array($isim,$soyad,$cinsiyyet,$userBilgi['id'])); //Yuxarıdakə əmri massiv daxilində işlədirik

            if($calistir) //Əgər hərşey qaydasındadırsa
            {
                header('Location: ? ');
                echo "İstifadəçi bilgiləri düzənləndi";
            }
            else //Əgər nəsə səhvlik varsa
            {
                echo "Düzənlənmədi";
            }


        }
        else //Əgər boşdursa
        {
            echo "Lütfən bütün ünvanları doldurun";
        }
    }



    ?>











                <div class = "form">
                    <label>İsim</label>
                    <input type="text" class="form-control" name="isim" value="<?php echo $userBilgi['isim']; ?>"/> <!-- Adımızı qutunun içinə çəkirik -->
                </div>

                <div class = "form">
                    <label>Soyad</label>
                    <input type="text" class="form-control" name="soyad" value="<?php echo $userBilgi['soyad']; ?>"/> <!-- SoyAdımızı qutunun içinə çəkirik -->
                </div>

                <div class = "form">
                    <label>Cinsiyyət</label>
                    <select name="cinsiyet" class="form-control" id="">
                        <option <?php if($userBilgi['cinsiyet']==0){echo 'selected';} ?> value="0">Kişi</option>
                        <option <?php if($userBilgi['cinsiyet']==1){echo 'selected';} ?> value="1">Qadın</option>
                    </select>
                </div>
                <hr/>
                <input style="width: 100%" class="btn btn-success" type="submit" value="parametrdeyis" name="parametrdeyis">

            </form>
            <hr/>
            <a class="link" href="index.php">Parametrlərə geri dön</a>

        </div>
    </div>
</div>
