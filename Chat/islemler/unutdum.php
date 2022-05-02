<?php
require_once '../config/baglanti.php';
require_once '../template/header.php';



?>

<div class="row ">

    <div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1">

        <div style = "background-color:#2be57f;  opacity: 0.8; margin-top:100px;" class="panel-body">
            <form action = "" method="POST">
                <hr />
                <center><h3><b>ŞİFRƏMİ UNUTDUM</b></h3></center>

                <?php
                if(isset($_POST['kodgonder'])) //Əgər istifadəçi Kodgonder butonunu klikləyibsə
                {
                    $email = strip_tags($_POST['email']);
                    if(!empty($email)) //Əgər email hissəsi boş deyilsə
                    {
                        $control = $baglanti->db->prepare("SELECT * FROM istifadeci WHERE email = :email"); //DB`dən məlumatları al
                        $control->bindParam(":email",$email,PDO::PARAM_STR); //Parametrləri daxil et
                        $control->execute(); //Əmrləri işlə
                        $netice = $control->rowCount(); //Sayını çıxar

                        if($netice != 0) //Əgər nəticə 0 deyilsə, yəni həmən email DBdə varsa
                        {
                            $kod = rand(1,9000)."_".rand(1,9000); //Həmən emailə random olaraq kod göndəcəyik
                            $sorgu = $baglanti->db->prepare("UPDATE istifadeci SET unutdum = ? WHERE email = ?"); //DB`dən random kodu dəyişdiririk və ya əlavə edirik.. Emailə görə
                            $calis = $sorgu->execute(array($kod,$email)); //Yuxarıdakı əmri massivlərə görə işləmə veririk

                            if($calis) //Hərşey qaydasındadırsa
                            {
                                $mesaj = "Salam siz şifrəmi unuttum deyə bizə müraciət bildirdiniz.../n
                                Tək istifadəlik kodun:".$kod;
                                mail($email,"Şifremi Unuttum",$mesaj);
                                echo "Mailinizə kod göndərildi";
                                header('Location: unutdum2.php');
                            }
                            else
                            {
                                echo "İşləm çalışmadı";
                            }

                        }
                        else //Əgər həmən email DB`də yoxdursa
                        {
                            echo "Bu email mövcud deyil";
                        }
                    }
                    else
                    {
                        echo "Email hissəsi boşdur";
                    }

                }
                ?>




                <div class = "form">
                    <label>Email</label>
                    <input type="email" class="form-control" name="email"/>
                </div>

 <hr/>
                <input style="width: 100%" class="btn btn-success" type="submit" value="Kod göndər" name="kodgonder">


            </form>
        </div>
    </div>
</div>

