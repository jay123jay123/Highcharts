<?php
include ("inc.php");
//http://192.168.8.253/tu/api.php?tb=achieve-service&si=net.xuele.achieve.service.AchieveService&me=countUserAchieve
header('Content-type:text/json');
$tb = $_REQUEST['tb'];
$si = $_REQUEST['si'];
$mt = $_REQUEST['mt'];



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

function Y($arr){

    $aa = array_column($arr,'timestamp');
    $bb = X();

    $diffarr = array_diff($bb,$aa);

    foreach ($diffarr as $value ){
        $tmp = array("timestamp" => $value,"elapsed" => "0");
        #print_r($tmp);
        array_push($arr,$tmp);
    }

    foreach ($arr as $value){
        # echo $value["timestamp"]." ".$value["elapsed"]."\n";
        $avgary[$value["timestamp"]] = $value["elapsed"];

    }
    ksort($avgary);



    return $avgary;
}

$postfix = date("Ymd",time());
#$avgtb = 'avg_'.$tb."_".$postfix;
#$restb = 'result_'.$tb;

$sql = "select timestamp,elapsed from `".$tb."` where serviceInterface = '".$si."' and method = '".$mt."' order by timestamp asc";
$conn = MysqlConn();
$res = mysql_query($sql,$conn);
if (!$res) {
            die("could get the res:\n" . mysql_error());
}
$i = 0;
while ($row = mysql_fetch_assoc($res)) {
            $arr[$i] = $row;

            $i++;
}
MysqlClose($conn);

$avgary = Y($arr);

#print_r($avgary);

print_r(json_encode($avgary));


?>
