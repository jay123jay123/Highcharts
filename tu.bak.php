<html>
<head>
<meta charset="UTF-8" />
<title>方法趋势图</title>
<script src="jquery.min.js"></script>
<script src="highcharts.js"></script>
</head>
<body>

<!-- <div id="container" style="width: 1800px; height: 400px; margin: 0 auto"></div> -->
<script language="JavaScript">



var tu = function(tablename,serviceInterface,method,postfix) {

           var title = {
//                          text: 'net.xuele.achieve.service.AchieveService'
                      };
           title.text = serviceInterface;
           var subtitle = {
                           text: 'Source: www.xueleyun.com'
                      };
           var xAxis = {
                         categories: []
                      };
           var yAxis = {
                         title: {
                                          text: '耗时毫秒'
                                       },
                         plotLines: [{
                                          value: 0,
                                          width: 1,
                                          color: '#808080'
                                       }]
                      };   

           var tooltip = {
                         valueSuffix: 'ms'
                      }

           var legend = {
                         layout: 'vertical',
                         align: 'right',
                         verticalAlign: 'middle',
                         borderWidth: 0
                      };

           var series =  [
                         {
                                          name: '',
                                          data:[]
                                       },
                         {
                                          name:'',
                                          data:[]
                         }
                      ];

            var commandsUrl = "http://192.168.8.253/tu/api.php?tb=avg_"+tablename+"_"+postfix+"&si="+serviceInterface+"&me="+method;
            series[0].name = method;
            $.ajax({
                    type : "get",
                    url : commandsUrl,
                    async : false,
                    success : function(data) {
                            for(var i = 0; i < data.length; i++){
                                    xAxis.categories.push(data[i].timestamp);
                                    series[0].data.push(+data[i].elapsed);
                           }
 
                             
                    }
            }); 



            var commandsUrl = "http://192.168.8.253/tu/api.php?tb=result_"+tablename+"&si="+serviceInterface+"&me="+method;
            series[1].name = 'Days-' + method;
            $.ajax({
                    type : "get",
                    url : commandsUrl,
                    async : false,
                    success : function(data) {
                            for(var i = 0; i < data.length; i++){
                                    series[1].data.push(+data[i].elapsed);
                           }
 
                             
                    }
            }); 








           var json = {};
        
           json.title = title;
           json.subtitle = subtitle;
           json.xAxis = xAxis;
           json.yAxis = yAxis;
           json.tooltip = tooltip;
           json.legend = legend;
           json.series = series;

           return json;

   
};

var hcharts = function(json,id){
        $('#container' + id ).highcharts(json);
};

</script>


<?php
$tb = $_REQUEST['tb'];

$mysql_conf = array(
                    'host'    => '127.0.0.1:3306',
                    'db'      => 'dubbomonitor',
                    'db_user' => 'root',
                    'db_pwd'  => 'xuele123',
                    );
$mysql_conn = @mysql_connect($mysql_conf['host'], $mysql_conf['db_user'], $mysql_conf['db_pwd']);
if (!$mysql_conn) {
            die("could not connect to the database:\n" . mysql_error());//诊断连接错误
}

mysql_query("set names 'utf8'");//编码转化
$select_db = mysql_select_db($mysql_conf['db']);
if (!$select_db) {
            die("could not connect to the db:\n" .  mysql_error());
}

$postfix = date("Ymd",time());
$SummarySql = 'select serviceInterface,method,elapsed from `avg_'.$tb.'_'.$postfix.'` group by serviceInterface,method';
$res = mysql_query($SummarySql);                           
$i = 0;
while ($row = mysql_fetch_assoc($res)) {
             $SMAry[$i]['serviceInterface'] = $row['serviceInterface'];
             $SMAry[$i]['method'] = $row['method'];
             echo "<div id=\"container".$i."\" style=\"width: 1800px; height: 400px; margin: 0 auto\"></div>\n";
             $i++;
}


//echo json_encode($SMAry);
mysql_close($mysql_conn);
$i=0;
foreach ( $SMAry as $value){
//        echo $value['serviceInterface'];
        echo "
        <script>
        var tutu = tu('". $tb ."','".$value['serviceInterface']."','".$value['method']."','".$postfix."');
        hcharts(tutu,'".$i."');
        </script>";
        $i++;
}

?>






</body>
</html>
