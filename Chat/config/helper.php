<?php

class helper
{
    static function yonlendir($url,$sure=0)  //Yönləndir funksiyasını tanımlıyırıq..Static ona görə ki hər səhifədə daha helper classını tanımlamayaq
    {
        if($sure != 0) //Əgər zaman 0 deyilsə
        {
            header("Refresh : $sure; url : $url");
        }
        else //Yəni biz pramoy yönləndirmə işləmi ediriksə
        {
            header("Location : $url");
        }
    }
}

//Bu classı sadəcə yönləndirmək məqsədilə qurduq.. Harada ki digər səhifəyə keçmək lazım olacağ o zaman bu classı çağırıb sadəcə həmin ssəhifənin linkini yazaraq çağıra bilərik
//helper::yonlendir("http://Chat/index.php")