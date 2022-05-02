<?php

require_once '../config/baglanti.php';

if($sessionManager->kontrol()) //Əgər istifadəçi daxil olubsa
{
    sessionManager::sessionSil(); //Əvvəlcə Sessionlarını Silib
    setcookie("giris"," ",time()-30); //Daha sonra kukini tanımlayırıq və hansı zaman çərçivəsində siləcəyini təyin edirik
    header('Location: giris.php'); //Sonra giris.phpyə göndər gedsün
}
else //Əgər daxil olmuyubsa
{
    header('Location: giris.php'); //birdəfəlik giris.php yə yönləndir
}