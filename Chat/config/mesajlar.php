<?php


class mesajlar extends baglanti
{
    public function userBul($array = [])
    {
        if($array[1] == $array[0]) //Əgər mesajı göndərən mənəmsə
        {
            $user_id = $array[2];
        }
        else
        {
            $user_id = $array[1];
        }

        //QEYD: Massivin içindəki dəyərlər mesajlar/index də foreachin içindəki idlərdi, Belə ki
        /*
         * 0 = $id
         * 1 = $gonderen_id
         * 2 = $alan_id
         */

        return $user_id;
    }



    //Daxil edilən id ilə mesajın olub olmamağını yoxlayırıq
    public function kontrol($id)
    {
        $kontrol = $this->db->prepare("SELECT * FROM mesajlar WHERE id = :id");
        $kontrol->bindParam(":id",$id,PDO::PARAM_INT);
        $kontrol->execute();
        return $kontrol->rowCount();
    }


    //Danışdığımız istifadəçinin məlumatlarını çəkirik
    public function bilgi($id)
    {
        $kontrol = $this->db->prepare("SELECT * FROM mesajlar WHERE id = :id");
        $kontrol->bindParam(":id",$id,PDO::PARAM_INT);
        $kontrol->execute();
        return $kontrol->fetch(PDO::FETCH_ASSOC);
    }


    //Mesaj göndərmə Classı
    public function gonder($gonderen_id,$mesaj_id,$mesaj)
    {
        $gonder = $this->db->prepare("INSERT INTO mesajlar_ic(mesaj_id,gonderen_id,mesaj,tarix,time)VALUES(?,?,?,?,?)");
        $netice = $gonder->execute(array($mesaj_id,$gonderen_id,$mesaj,date("Y-m-d",time())));

        if($netice)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

}