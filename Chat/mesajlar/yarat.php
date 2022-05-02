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

$sorgu = $baglanti->db->prepare("SELECT * FROM istifadeci WHERE id = :id"); //Linkdə girilən id`nin DBdə olub olmadığını yoxlayırıq
$sorgu->bindParam(":id",$id,PDO::PARAM_INT); //Parametrlərini çəkirik
$sorgu->execute();
$netice = $sorgu->rowCount();

if($netice == 0 or $userBilgi['id'] == $id) //Nəticə sıfırdırsa, vəya Bizim DB`dən gələn id Link paneldən gələn idyə bərabərdirsə
{
    header('Location: ../');
}
else
{
    $melumatlar = $istifadeciLog->istifadeciBilgi($id);
    ?>




    <?php
    if(isset($_POST['mesajgonder']))
    {
        $mesaj = strip_tags($_POST['mesaj']);
        if(!empty($mesaj))
        {
            $control = $baglanti->db->prepare("SELECT * FROM mesajlar WHERE (gonderen_id = ? and alan_id = ?) or (gonderen_id = ? and alan_id = ?)");
            //Yuxarıda SQL əmrində ya mesajı göndərən mənəm alan qarşı tərəfdəkidir.. Yada (or) Mesajı göndərən odur, alan mənəm
            $control->execute(array($userBilgi['id'],$id,$id,$userBilgi['id'])); //1ci İD mən 2ci İD isə digər istifadəçi
            $netice = $control->rowCount();
            if($userBilgi == 0)
            {
                $sorgu = $baglanti->db->prepare("INSERT INTO mesajlar(gonderen_id,alan_id,tarix) VALUES(?,?,?)");
                $elave_etme = $sorgu->execute(array($userBilgi['id'],$id,date("Y-m-d"),));

                if($elave_etme)
                {
                    $last_id = $baglanti->db->lastInsertId(); //Yəni son yüklənən məlumatın İD sini al
                    $sorgu2 = $baglanti->db->prepare("INSERT INTO mesajlar_ic(mesaj_id,gonderen_id,mesaj,tarix,time) VALUES(?,?,?,?,?)");
                    $sorgu2->execute(array($last_id,$userBilgi['id'],$mesaj,date("Y-m-d"),time()));
                    header("Location: chat.php?id=".$userBilgi['id']);
                }
                else
                {
                    echo "Əlavə olunmadı";
                }
            }
            else //Əgər sonuç 0 dan fərqlidirsə
            {
                $control = $baglanti->db->prepare("SELECT * FROM mesajlar WHERE (gonderen_id = ? and alan_id = ?) or (gonderen_id = ? and alan_id = ?)");
                //Yuxarıda SQL əmrində ya mesajı göndərən mənəm alan qarşı tərəfdəkidir.. Yada (or) Mesajı göndərən odur, alan mənəm
                $control->execute(array($userBilgi['id'],$id,$id,$userBilgi['id'])); //1ci İD mən 2ci İD isə digər istifadəçi
                $netice = $control->fetch();

                $sorgu2 = $baglanti->db->prepare("INSERT INTO mesajlar_ic(mesaj_id,gonderen_id,mesaj,tarix,time) VALUES(?,?,?,?,?)");
                $sorgu2->execute(array($netice['id'],$userBilgi['id'],$mesaj,date("Y-m-d"),time()));
                header("Location: chat.php?id=".$netice['id']);
            }
        }
        else
        {
            echo "Lütfən mesajınızı daxil edin...";
        }
    }
    ?>


    <form action="" method="POST">
        <div class="form-control">
            <span>Mesajınız</span>
            <hr/>
            <textarea name="mesaj" id="" cols="30" rows="10"></textarea>
            <input class="btn btn-success" type="submit" value="MESAJ GÖNDƏR" name="mesajgonder">
        </div>


    </form>







<?php
} ?>


