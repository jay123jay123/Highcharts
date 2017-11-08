<html>
<head>
    <meta charset="UTF-8" />
    <title>服务接口汇总</title>

    <style type="text/css">
        table.gridtable {
            font-family: verdana,arial,sans-serif;
            font-size:11px;
            color:#333333;
            border-width: 1px;
            border-color: #666666;
            border-collapse: collapse;
        }
        table.gridtable th {
            border-width: 1px;
            padding: 8px;
            border-style: solid;
            border-color: #666666;
            background-color: #dedede;
        }
        table.gridtable td {
            border-width: 1px;
            padding: 8px;
            border-style: solid;
            border-color: #666666;
            background-color: #ffffff;
        }
    </style>


</head>
<body>
<?php
include ("inc.php");
// http://192.168.8.253/tu/summary.php?tb=achieve-service
#header('Content-type:text/json');
$tb = $_REQUEST['tb'];

function serviceInterface($tb)
{
    $conn = MysqlConn();
    $postfix = date("Ymd", time());
    $SummarySql = 'select serviceInterface , sum(successCount + failureCount) as num , round(avg(elapsed),1) as avg from `avg_' . $tb . '_' . $postfix . '` group by serviceInterface order by num desc ';
    $res = mysql_query($SummarySql);
    $i = 0;
    while ($row = mysql_fetch_assoc($res)) {
        $SMAry[$i]['serviceInterface'] = $row['serviceInterface'];
        $SMAry[$i]['num'] = $row['num'];
        $SMAry[$i]['avg'] = $row['avg'];
        $i++;
    }
    MysqlClose($conn);

    return $SMAry;
}


$siAry = serviceInterface($tb);

echo "注：此为当天数据默认按照高低频排序↓<p>";
echo "<table class=\"gridtable\">
<tr>
	<th>服务接口名</th><th>调用次数</th><th>平均耗时</th>
</tr>

";

foreach ( $siAry as $value){
    echo "<tr><td><a href = \"method.php?tb=".$tb."&si=".$value['serviceInterface']."\">".$value['serviceInterface']."</a></td><td>".$value['num']."</td><td>".$value['avg']."ms</td></tr>";


}

echo "</table>";

?>
</body>
</html>
