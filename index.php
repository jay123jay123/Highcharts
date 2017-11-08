<html>
<head>
    <meta charset="UTF-8" />
    <title>子系统列表</title>

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
header('Content-type:text/html');


$conn = MysqlConn();

$SummarySql = 'select name from application';
$res = mysql_query($SummarySql,$conn);

echo "<table class=\"gridtable\"><tr><th>子系统名</th></tr>";

while ($row = mysql_fetch_assoc($res)) {
        echo "<tr><td><a href = \"summary.php?tb=".$row['name']."\">".$row['name']."</a></td></tr>";
}
echo "</table>";
MysqlClose($conn);
#mysql_close($mysql_conn);
?>
</body>
</html>