<?php
require_once '../config/baglanti.php';
require_once '../template/header.php';



?>

<div class="row ">

    <div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1">

        <div style = "background-color:#2be57f;  opacity: 0.8; margin-top:100px;" class="panel-body">
            <form action = "" method="POST">
                <hr />
                <center><h3><b>ŞİFRƏMİ UNUTDUM 2.addım</b></h3></center>

                <?php
                if(isset($_POST['yenisifre'])) //Əgər istifadəçi Kodgonder butonunu klikləyibsə
                {
                    $email = strip_tags($_POST['email']);
                    $sifre = strip_tags($_POST['sifre']);
                    $sifretekrar = strip_tags($_POST['sifretekrar']);
                    $kod = strip_tags($_POST['kod']);

                    if(!empty($email) and !empty($sifre) and !empty($sifretekrar) and !empty($kod)) //Əgər istifadəçi bütün məlumatları daxil edibsə
                    {
                        if($sifre == $sifretekrar) //Əgər şifrə və təkrar şifrə hissəsi eynidirsə
                        {
                            $control = $baglanti->db->prepare("SELECT * FROM istifadeci WHERE unutdum = ? and email = ?"); //DByə bağlanırıq, əgər unuttum sütunundakı kodla email eynidir mi?
                            $control->execute(array($kod,$email)); //Bunları işlət
                            $netice = $control->rowCount(); //Sayını çıxar

                            if($netice != 0) //əgər nəticə 0 deyilsə yəni hərşey doğrudursa
                            {
                                $sorgu = $baglanti->db->prepare("UPDATE istifadeci SET sifre = ?, unutdum = ? WHERE email = ?"); //DByə bağlan və şifrəni dəyişdir unuttum kodunu dəyişir
                                $calisdir = $sorgu->execute(array(md5($sifre),"",$email));

                                if($calisdir)
                                {
                                    header('Location: giris.php');
                                    echo "Yeni Şifrəniz xeyirli olsun";
                                }
                                else
                                {
                                    echo "Şifrə dəyişmədi";
                                }
                            }
                            else //Əgər məlumatlar yanlışdırsa
                            {
                                echo "Tək istifadəlik kodunuz yanlış";
                            }
                        }
                        else
                        {
                            echo "Yeni şifrələr eyni deyil";
                        }
                    }
                    else //Əgər bütün məlumatlar daxil olmayıbsa
                    {
                        echo "Lütfən bütün ünvanları doldurun";
                    }


                }
                ?>




                <div class = "form">
                    <label>Email</label>
                    <input type="email" class="form-control" name="email"/>
                </div>

                <div class = "form">
                    <label>Tek kullanımlık Şifreniz</label>
                    <input type="text" class="form-control" name="kod"/>
                </div>

                <div class = "form">
                    <label>Yeni Şifreniz</label>
                    <input type="text" class="form-control" name="sifre"/>
                </div>

                <div class = "form">
                    <label>Tekrar Yeni Şifreniz</label>
                    <input type="text" class="form-control" name="sifretekrar"/>
                </div>



                <hr/>
                <input style="width: 100%" class="btn btn-success" type="submit" value="Deyiştir" name="yenisifre">


            </form>
        </div>
    </div>
</div>

