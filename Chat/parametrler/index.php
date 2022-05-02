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

<div class="title">PARAMETRLƏR</div>
<a class="link" href="profil.php">Profil məlumatlarını düzəlt</a>
<a class="link" href="sifre.php">Şifrəmi dəyişdir</a>
<a class="link" href="resm.php">Profil fotoğrafımı dəyişdir</a>
<a class="link" href="../index.php">Panelə geri dön</a>