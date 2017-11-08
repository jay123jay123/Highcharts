<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/1 0001
 * Time: 15:18
 */

#print(date("Ymd",time()));


function X()
{
    $begin = "00:00";
    $end = "23:59";

    $begintime = strtotime($begin);
    $endtime = strtotime($end);

    $timearr = array();

    $i = 0;
    for ($start = $begintime; $start <= $endtime; $start += 60) {
        $timearr[$i] = date("H:i", $start);
        $i++;
    }
    return $timearr;
}

$a = X();
print(count($a));
print_r($a);

$aa = array_column($arr,'timestamp');
$bb = X();

$diffarr = array_diff($bb,$aa);

foreach ($diffarr as $value ){
    $tmp = array("timestamp" => $value,"elapsed" => "0");
    #print_r($tmp);
    array_push($arr,$tmp);
}
ksort($arr);
print_r($arr);