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



?>

<div class="title">ONLİNE İSTİFADƏÇİLƏR</div>


<?php
$date1 = date("Y-m-d-H-i");
@$date2 = date("Y-m-d-H-i")-1;
@$date3 = date("Y-m-d-H-i")-2;

$sorgu = $baglanti->db->prepare("SELECT * FROM istifadeci WHERE online IN(?,?,?)");
$sorgu->execute(array($date1,$date2,$date3));
$cek = $sorgu->fetchAll(PDO::FETCH_ASSOC);

if(count($cek) != 0) // Əgər cek hissəsi 0 deyilsə  QEYD:count bizə massiv tipində dəyər göndərir
{
    foreach ($cek as $key => $value)
    {
        ?>
<div class="list"><?php echo $value['isim'].' '.$value['soyad'];?></div>
<?php
    }
}



?>

<a class="link" href="../index.php">Panelə geri dön</a>