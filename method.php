<html>
<head>
    <meta charset="UTF-8" />
    <title>方法列表</title>

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
$si = $_REQUEST['si'];



function method($tb,$serviceInterface)
{
    $conn = MysqlConn();
    $postfix = date("Ymd", time());
    $SummarySql = 'select method , sum(successCount + failureCount) as num  ,  round(avg(elapsed),1) as avg from `avg_' . $tb . '_' . $postfix . '`  where serviceInterface = \''.$serviceInterface.'\' group by method  order by num desc ';
    $res = mysql_query($SummarySql);
    $i = 0;
    while ($row = mysql_fetch_assoc($res)) {
        $SMAry[$i]['method'] = $row['method'];
        $SMAry[$i]['num'] = $row['num'];
        $SMAry[$i]['avg'] = $row['avg'];
        $i++;
    }
    MysqlClose($conn);

    return $SMAry;
}


$methodAry = method($tb,$si);

echo "注：此为当天数据默认按照高低频排序↓<p>";
echo "<table class=\"gridtable\"><tr><th>方法名</th><th>次数</th><th>平均耗时</th></tr>";

foreach ( $methodAry as $value){
    echo "<tr><td><a href = \"tu.php?tb=".$tb."&si=".$si."&mt=".$value['method']."\">".$value['method']."</a></td><td>".$value['num']."</td><td>".$value['avg']."ms</td></tr>";


}
echo "</table>";
?>
</body>
</html>
