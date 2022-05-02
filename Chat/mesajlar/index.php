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

    <div class="title">Mesajlarım</div>

<?php

$sorgu = $baglanti->db->prepare("SELECT * FROM mesajlar WHERE gonderen_id =? or alan_id = ?");
$sorgu->execute(array($userBilgi['id'],$userBilgi['id']));
$netice = $sorgu->fetchAll();

if(!empty(count($netice)))
{
    foreach ($netice as $key => $value)
    {
                                         // 0-cı dəyər         1ci dəyər            2ci dəyər  Bunları config/mesajlar classında istifadə edirik
        $user_id = $mesajClass->userBul([$userBilgi['id'],$value['gonderen_id'],$value['alan_id']]);



        $cek = $istifadeciLog->istifadeciBilgi($user_id);
        echo '<div class="list">
        <a href="chat.php?id='.$value['id'].'"> '.$cek['isim'].' '.$cek['soyad'].' </a>
            </div>';

    }
}
else
{
    echo "Mesaj qutunuz boş";
}


?>
