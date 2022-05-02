<?php
require_once '../config/baglanti.php';


if(!$sessionManager->kontrol()) //İstifadəçini yoxla..Əgər həmin istifadəçi yoxdursa,
{
    // helper::yonlendir("/islemler/giris.php");
    header('Location: ../islemler/giris.php');
    //Giriş səhifəsinə göndər
    die(); //..
}

$userBilgi = $sessionManager->istifadeciBilgi(); // Bunu bütün səhifələrdə tanıtlamalıyıq
$istifadeciLog->start($userBilgi['id']); // Bunu bütün səhifələrdə tanımlamalıyıq
require_once '../template/header.php';

$id = intval($_GET['id']); //Idmizi integer dəyərində çəkirik əvvəlcə


$netice = $mesajClass->kontrol($id);

if($netice == 0) //Əgər seçdiyin istifadəçi ilə mesajın yoxdursa
{
    header('Location:'.SITE_URL);
}

else //Əgər varsa
{
    /*
     * İstifadəçi mesaj göndərmə
     */
    /*
        if(isset($_POST['mesajgonder']))
        {
            $mesaj = strip_tags($_POST['mesaj']);
            $gonder = $mesajClass->gonder($userBilgi['id'],$id,$mesaj);

            if($gonder)
            {
                header('Location: ?id='.$id);
            }
            else
            {
                echo "Mesaj gönderilmədi";
            }
        }

    */

        if (isset($_POST['mesajgonder'])) {
            $mesaj = strip_tags($_POST['mesaj']);


            if (!empty($mesaj)) {
                $control = $baglanti->db->prepare("SELECT * FROM mesajlar WHERE (gonderen_id = ? and alan_id = ?) or (gonderen_id = ? and alan_id = ?)");
            //Yuxarıda SQL əmrində ya mesajı göndərən mənəm alan qarşı tərəfdəkidir.. Yada (or) Mesajı göndərən odur, alan mənəm
            $control->execute(array($userBilgi['id'], $id, $id, $userBilgi['id'])); //1ci İD mən 2ci İD isə digər istifadəçi
            $netice = $control->rowCount();
            if ($sonuc == 0) {
                $netice = $baglanti->db->prepare("INSERT INTO mesajlar(gonderen_id,alan_id,tarix) VALUES(?,?,?)");
                $elave_etme = $sorgu->execute(array($userBilgi['id'], $id, date("Y-m-d"),));

                if ($elave_etme) {
                    $last_id = $baglanti->db->lastInsertId(); //Yəni son yüklənən məlumatın İD sini al
                    $sorgu2 = $baglanti->db->prepare("INSERT INTO mesajlar_ic(mesaj_id,gonderen_id,mesaj,tarih,time) VALUES(?,?,?,?,?,?)");
                    $sorgu2->execute(array($last_id, $userBilgi['id'], $mesaj, date("Y-m-d"), time()));
                    header("Location: ?id=" . $userBilgi['id']);

                } else {
                    echo "Əlavə olunmadı";
                }
            } else //Əgər sonuç 0 dan fərqlidirsə
            {
                $control = $baglanti->db->prepare("SELECT * FROM mesajlar WHERE (gonderen_id = ? and alan_id = ?) or (gonderen_id = ? and alan_id = ?)");
                //Yuxarıda SQL əmrində ya mesajı göndərən mənəm alan qarşı tərəfdəkidir.. Yada (or) Mesajı göndərən odur, alan mənəm
                $control->execute(array($userBilgi['id'], $id, $id, $userBilgi['id'])); //1ci İD mən 2ci İD isə digər istifadəçi
                $sonuc = $control->fetch();

                $sorgu2 = $baglanti->db->prepare("INSERT INTO mesajlar_ic(mesaj_id,gonderen_id,mesaj,tarix,time) VALUES(?,?,?,?,?)");
                $sorgu2->execute(array($netice['id'], $userBilgi['id'],$mesaj, date("Y-m-d"), time()));
                header("Location: ?id=" . $netice['id']);

            }
        } else {
            echo "Lütfən mesajınızı daxil edin...";
        }
    }








    $bilgi = $mesajClass->bilgi($id);       //0             1                       2
    $user_id = $mesajClass->userBul([$kBilgi['id'],$bilgi['gonderen_id'],$bilgi['alan_id']]);
    $userBilgi = $kullaniciLog->kullaniciBilgi($user_id);


    $cek = $baglanti->db->prepare("SELECT * FROM mesajlar_ic WHERE mesaj_id = :id");
    $cek->bindParam(":id",$id,PDO::PARAM_INT);
    $cek->execute();

    $veriler = $cek->fetchAll();
    foreach ($veriler as $key => $value)
    {
        $userBilgi2 = $kullaniciLog->kullaniciBilgi($value['gonderen_id']);

    }


}


?>

<form action="" method="POST">
    <div class="col-md-6">

        <div class="panel panel-success">
            <div class="panel-heading">
                <?php echo $userBilgi['isim'];?>
            </div>
            <div class="panel-body" style="padding: 0px;">
                <div class="chat-widget-main">


                    <div class="chat-widget-left">
                        <?php $cek = $baglanti->db->prepare("SELECT * FROM mesajlar_ic WHERE mesaj_id = :id");
                        $cek->bindParam(":id",$id,PDO::PARAM_INT);
                        $cek->execute();

                        $veriler = $cek->fetchAll();
                        foreach ($veriler as $key => $value)
                        {
                            $userBilgi2 = $kullaniciLog->kullaniciBilgi($value['mesaj']);
                            echo $value['mesaj'];

                        }
                        ?>
                    </div>
                    <div class="chat-widget-name-left">
                        <h4><?php echo $userBilgi['isim'];?></h4>
                    </div>
                    <div class="chat-widget-right">
                        <?php echo $value['mesaj'];?>
                    </div>
                    <div class="chat-widget-name-right">
                        <h4><?php echo $kBilgi['isim'];?></h4>
                    </div>

                </div>
            </div>
        </div>
        <div class="panel-footer">
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Enter Message" name="mesaj"/>
                <span class="input-group-btn">
                 <input class="btn btn-success" type="submit" value="Mesaj Gönder" name="mesajgonder">
             </span>
         </div>
     </div>
 </div>
</form>
