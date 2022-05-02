<?php
require_once 'config/baglanti.php';


if(!$sessionManager->kontrol()) //İstifadəçini yoxla..Əgər həmin istifadəçi yoxdursa,
{
   // helper::yonlendir("/islemler/giris.php");
    header('Location: islemler/giris.php');
    //Giriş səhifəsinə göndər
    die(); //..
}

$userBilgi = $sessionManager->istifadeciBilgi(); // Bunu bütün səhifələrdə tanıtlamalıyıq
$istifadeciLog->start($userBilgi['id']); // Bunu bütün səhifələrdə tanımlamalıyıq
require_once 'template/header.php';

if($istifadeciLog->isOnline($userBilgi['id'])) //Əgər istifadəçi onlinedırsa
{
    echo "İstifadəçi ONline";
}
else
{
    echo "İstifadəçi OFFline";
}

?>

<div class="title">Salam <?php echo $userBilgi['isim']; ?></div>
<?php
if(!empty($userBilgi['resm'])){
?>
<div class="info"><img style="width: 100px;height: 100px;" src="<?php echo SITE_URL;?>/upload/<?=$userBilgi['id'];?>/<?php echo $userBilgi['resm'];?>" </div>
<?php } ?>
<div  class="info">Ad Soyad : <?php echo $userBilgi['isim'];?> <?php echo $userBilgi['soyad']; ?></div>

<a class="link" href="mesajlar">Mesajlar</a>
<a class="link" href="genel/onlineistifadeciler.php">Online istifadəçilər</a>
<a class="link" href="genel/istifadeciler.php">Bütün istifadəçilər</a>
<a class="link" href="parametrler/index.php">Parametrlər</a>
<a class="link" href="islemler/cixis.php">Çıxış</a>
