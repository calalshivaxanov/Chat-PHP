<?php
require_once '../config/baglanti.php';


if(!$sessionManager->kontrol()) //İstifadəçini yoxla..Əgər həmin istifadəçi yoxdursa,
{
    // helper::yonlendir("/islemler/giris.php");
    header('Location: ../islemler/giris.php');
    //Giriş səhifəsinə göndər
    die(); //Və öldür gedsin..
}

$userBilgi = $sessionManager->istifadeciBilgi(); // Bunu bütün səhifələrdə tanıtlamalıyıq
$istifadeciLog->start($userBilgi['id']); // Bunu bütün səhifələrdə tanımlamalıyıq
require_once '../template/header.php';



?>

<div class="title">ONLİNE İSTİFADƏÇİLƏR</div>


<?php
$sorgu = $baglanti->db->prepare("SELECT * FROM istifadeci ");
$sorgu->execute();
$cek = $sorgu->fetchAll(PDO::FETCH_ASSOC);

if(count($cek) != 0) // Əgər cek hissəsi 0 deyilsə  QEYD:count bizə massiv tipində dəyər göndərir
{
    foreach ($cek as $key => $value)
    {
        ?>
        <div class="list"><a href="../profil/index.php?id=<?php echo $value['id'];?>"><?php echo $value['isim'].' '.$value['soyad'];?></a></div>
        <?php
    }
}



?>
