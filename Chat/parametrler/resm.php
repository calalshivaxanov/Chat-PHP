<?php
error_reporting(0);
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
require_once '../config/class.upload.php';

?>

<div class="row ">

    <div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1">

        <div style = "background-color:#e5b02b;  opacity: 0.8; margin-top:100px;" class="panel-body">
            <form action = "" method="POST" enctype="multipart/form-data">
                <hr />
                <center><h3><b>PROFİL RƏSMİNİ DƏYİŞDİR</b></h3></center>

    <?php
    if($_FILES)
    {
        $image = $_FILES['image'];

        if(!empty($image['name'])) //İstifadəçi boş bir işləm etsə əgər, Yəni heçnə etmədən butona klikləsə
        {
            $foo = new Upload($image);

            if($foo->uploaded)
            {
                $foo->file_new_name_body = 'profil';
                $foo->allowed = array('image/*');


                $foo->Process('../upload/'.$userBilgi['id']);


                if($foo->processed)
                {
                    $isim = $foo->file_dst_name;

                    $sorgu = $baglanti->db->prepare("UPDATE istifadeci SET resim = ? WHERE id = ?");
                    $calistir = $sorgu-> execute(array($isim,$userBilgi['id']));

                    if($calistir)
                    {
                        echo "Profil rəsmi güncəlləndi :".$isim;
                    }
                    else
                    {
                        echo "Güncəllənmədi";
                    }

                }
                else
                {
                    echo "Rəsm əlavə olunmadı";
                }
            }
        }
        else
        {
            echo "Lütfən rəsminizi seçin";
        }
    }
    else
    {
    echo "Profil rəsminizi buradan dəyişdirə bilərsiniz";
    }
    ?>











                <div class = "form">
                    <label>Profil Resmi</label>
                    <input type="file" class="form-control" name="image"/> <!-- Adımızı qutunun içinə çəkirik -->
                </div>




                <hr/>
                <input style="width: 100%" class="btn btn-success" type="submit" value="Profil Rəsmini Dəyişdir" name="resmdeyis">

            </form>
            <hr/>
            <a class="link" href="index.php">Parametrlərə geri dön</a>

        </div>
    </div>
</div>
