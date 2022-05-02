<?php

//Bu Class istifadəçi nə zaman harada oluğunu DB`də görmək üçündür
class istifadeciLog extends baglanti
{
    public function start($id=0)
    {
        if($id != 0) //Əgər id 0 deyilsə
        {
            $requestUrl = $_SERVER['REQUEST_URI']; //Öntanımlı SERVER dəyişkənindən RequestUrli alırıq
            $tarih = date("Y-m-d");
            $time = time();

            $sorgu = $this->db->prepare("INSERT INTO log(istifadeci_id,harada,tarix,time) VALUES(?,?,?,?)");//DB`yə qoşuluruq
            $sorgu->execute(array($id,$requestUrl,$tarih,$time));

        }
    }



//Bu metod istifadəçilərin online olub olmamasını yoxlamaq üçündür
public function onlineSet($array)
{
    if($array != false)
    {
        $online = date("Y-m-d-H-i");
        $sorgu = $this->db->prepare("UPDATE istifadeci SET online = ? WHERE id = ?");
        $sorgu->execute(array($online, $array['id']));
    }
}

//Onlinedırsa onu kontrol etmək üçündür
public function isOnline($id)
{
    $date1 = date("Y-m-d-H-i");
    @$date2 = date("Y-m-d-H-i")-1;
    @$date3 = date("Y-m-d-H-i")-2;
    $dizi = [$date1,$date2,$date3];
    $sorgu = $this->db->prepare("SELECT * FROM istifadeci WHERE id = :id");
    $sorgu->bindParam(":id",$id,PDO::PARAM_INT);
    $sorgu->execute();
    $cek = $sorgu->fetch(PDO::FETCH_ASSOC); //Online vaxtını al

    if(in_array($cek['online'],$dizi)) // Əgər massivin içərisində online dəyəri varsa qısaca online olan varsa
    {
        return true; //online olsun
    }
    else //Deyilsə
    {
        return false; //Offline olsun
    }


}



//Profilin qurulması üçün Metod
public function istifadeciBilgi($id)
{
    $sorgu = $this->db->prepare("SELECT * FROM istifadeci WHERE id = :id"); //DB`dən id mizi çəkirik
    $sorgu->bindParam(":id",$id,PDO::PARAM_INT);
    $sorgu->execute();
    return $sorgu->fetch(PDO::FETCH_ASSOC);

}






}