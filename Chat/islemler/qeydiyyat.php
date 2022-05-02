<?php
require_once '../config/baglanti.php';
require_once '../template/header.php';
?>









<div class="row ">

    <div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1">

        <div style = "background-color:#2be57f;  opacity: 0.8; margin-top:100px;" class="panel-body">
            <form action = "" method="POST">
                <hr />
                <center><h3><b>Qeydiyyatdan Keç</b></h3></center>

                <?php




                if(isset($_POST['qeydiyyat']))
                {
                    $isim = strip_tags($_POST['isim']);
                    $soyisim = strip_tags($_POST['soyad']);
                    $email = strip_tags($_POST['email']);
                    $sifre = strip_tags($_POST['sifre']);
                    $cinsiyet = intval($_POST['cinsiyyet']);
                    $tarix = date("Y-m-d-");

                    if (!empty($isim) and !empty($soyad) and !empty($email) and !empty($sifre)) //Əgər verilənlər boş deyilsə
                    {
                        if(filter_var($email,FILTER_VALIDATE_EMAIL)) {
                            //EMAİl`in artıq işlənib işlənmədiyini yoxlamaq üçün metod
                            $control = $baglanti->db->prepare("SELECT * FROM istifadeci WHERE email = :email");//DB`dən məlumatları çəkirik
                            $control->bindParam(":email", $email, PDO::PARAM_STR); //Parametrləri daxil edirik...Buradakı emaillərin biri SQLdəki emaildi digəri isə istifadəçinin yazdığı emaildi
                            $control->execute(); //Yuxarıdakı əmri işlədirik
                            $sayi = $control->rowCount(); //Əgər Email əvvəlcədən varsa, sayını çıxardırıq
                            if ($sayi == 0) //Əgər həmin email`in DB`də sayı sıfırdısa yəni yoxdursa onda Qəbul elə
                            {
                                $sorgu = $baglanti->db->prepare("INSERT INTO istifadeci (isim,soyad,email,sifre,qeydiyyat_tarixi,cinsiyet)VALUES(?,?,?,?,?,?)"); //Qeydiyatdan keçən istifadəçinin bütün məlumatlarını DB`ə yazdır
                                $elave_et = $sorgu->execute(array($isim, $soyad, $email, md5($sifre), $tarix, $cinsiyet)); //Yuxarıdakı əmri işlət amma massiv olaraq verilənləri göndər...
                                if ($elave_et) //Əgər hərşey qaydasındadırsa
                                {
                                    $dizi=["email"=>$email,"sifre"=>md5($sifre)]; //Buradakı :email SessionManager səhifəsin foreach döngüsündəki key, $email isə oradakı value`dir..
                                    sessionManager::sessionYarat($dizi); //Session Manager ilə session yaradıb həmin metodu bura çağırırıq..və massivi işlədirik
                                    //helper::yonlendir("http://Chat/index.php"); //QEYD: helper səhifəsinin include olub olmamağına mütləq bax...ERROR 505 xətası verdi
                                    header('Location:../index.php');
                                }
                                else
                                {
                                    echo "Üzgünəm, Qeydiyyat uğursuz oldu";
                                }


                            }
                            else
                            {
                                echo 'Bu istifadəçi artıq qeydiyyatdan keçib';
                            }
                        }
                        else
                        {
                            echo "Email Doğru deyil";
                        }
                    }
                    else
                    {
                        echo "Lütfən bütün ünvanları doldurun";
                    }
                }



                ?>


                        <div class = "form">
                            <label>İsim</label>
                            <input type="text" class="form-control" name="isim"/>
                        </div>

                        <div class = "form">
                            <label>Soyad</label>
                            <input type="text" class="form-control" name="soyad"/>
                        </div>

                        <div class = "form">
                            <label>Email</label>
                            <input type="email" class="form-control" name="email"/>
                        </div>

                        <div class = "form">
                            <label>Şifrə</label>
                            <input type="password" class="form-control" name="sifre"/>
                        </div>

                        <div class = "form">
                            <label>Cinsiyyət</label>
                            <select name="cinsiyet" class="form-control" id="">
                                <option value="0">Kişi</option>
                                <option value="1">Qadın</option>
                            </select>
                        </div>


                        <input style="width: 100%" class="btn btn-success" type="submit" value="qeydiyyat" name="qeydiyyat">







                    </form>
                </div>
            </div>
        </div>
