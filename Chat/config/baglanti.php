<?php

session_start();
ini_set ( 'display_errors' , 1 );
ini_set ( 'display_startup_errors' , 1 );
error_reporting ( E_ALL );

class baglanti
{
    public $db;
    function __construct()
    {
        $this->db = new PDO("mysql:host=localhost;dbname=chatsite;charset=utf8", "root","2352ceka20");
    }
}

date_default_timezone_set('Asia/Baku');
define("SITE_NAME","İstifadəçi Sistemi v.0");
define("SITE_URL","http://localhost/Chat");

require_once "sessionManager.php"; //Bağlantını hər səhifədə işlədəcəyimiz üçün bunu səhifəni də bura daxil edirik..
require_once "helper.php";  //Yuxarıdakı yorum bunun üçün də keçərlidir
require_once "istifadeciLog.php";
require_once  "mesajlar.php";

$baglanti = new baglanti(); //Bağlantı sinfini aktivləşdirdik...Yəni obyekt olaraq tanımladıq
$sessionManager = new sessionManager(); //SessionManager sinfini aktivləşdirdik ..Yəni obyekt olaraq tanımladıq
$istifadeciLog = new istifadeciLog();
$istifadeciLog -> onlineSet($sessionManager->istifadeciBilgi()); // QEYD: İstifadəçinin online olub olmadığını yoxlamaq üçün onun məlumatlarını bu metodlar çəkməy vacibdi
$mesajClass = new mesajlar();

?>