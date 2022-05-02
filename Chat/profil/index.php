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

if($netice == 0)
{
    header('Location: ../islemler/giris.php');
}
else
{
 $melumatlar = $istifadeciLog->istifadeciBilgi($id);
 if($melumatlar['cinsiyet'] == 0)
 {
     $cinsiyet = "Kişi";
 }
 else
 {
     $cinsiyet = "qadın";
 }
?>
    <div class="title">
        <?=$melumatlar['isim'];?>
        <?=$melumatlar['soyad'];?>
    </div>
    <div class="list"><?=$cinsiyet;?></div>

    <?php
    if(!empty($melumatlar['resm']))
    {
        echo '<img style="width: 100px;height: 100px;" src="'.SITE_URL.'/upload/'.$melumatlar['id'].'/'.$melumatlar['resm'].'">';
    }

}
?>

<?php
if($melumatlar['id'] != $userBilgi['id']) //Əgər bu xiyar öz özüynən mesajlaşmaq istəmirsə
{ ?>
<a href="<?=SITE_URL;?>/mesajlar/yarat.php?id=<?=$melumatlar['id'];?>">Mesaj Başlat</a>
<?php } ?>