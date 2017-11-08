<html>
<head>
<meta charset="UTF-8" />
<title>方法趋势图</title>
<script src="jquery.min.js"></script>
<script src="highcharts.js"></script>

    <style type="text/css">
        body{width:100%;}
        body>div{
            width:900px!important;float:left;

        }
    </style>
</head>
<body>
<!-- 新加failureCount 和  successCount   -->
<!-- <div id="container" style="width: 1800px; height: 400px; margin: 0 auto"></div> -->
<script language="JavaScript">



var tu = function(tablename,serviceInterface,method,postfix) {

           var title = {
//                          text: 'net.xuele.achieve.service.AchieveService'
                      };
           title.text = serviceInterface;
           var subtitle = {
                           text: 'Source: dubbo.neiwang.com'
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

           var yAxisCount = {
               title: {
                   text: '平均次数'
               },
               plotLines: [{
                   value: 0,
                   width: 1,
                   color: '#606060'
               }]
           };

           var tooltip = {
                         valueSuffix: 'ms'
                      };
           var tooltipCount = {
                         valueSuffix: '次'
                      };


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

           var seriesCount =  [
               {
                   name: 'failureCount',
                   data:[]
               },
               {
                   name:'successCount',
                   data:[]
               },
               {
                   name: 'Days-failureCount',
                   data:[]
               },
               {
                   name:'Days-successCount',
                   data:[]
               }
           ];


            var commandsUrl = "/api2.php?tb=avg_"+tablename+"_"+postfix+"&si="+serviceInterface+"&me="+method;
            series[0].name = method;
            $.ajax({
                    type : "get",
                    url : commandsUrl,
                    async : false,
                    success : function(data) {
                            //for( var key in data ){
                            for(var i = 0; i < data.length; i++){
                                    xAxis.categories.push(key);
                                    //series[0].data.push(+data[key]);
                                    series[0].data.push(+data[i].elapsed);
                                    seriesCount[0].data.push(+data[i].failureCount);
                                    seriesCount[1].data.push(+data[i].successCount);

                            }
 
                             
                    }
            }); 



            var commandsUrl = "/api2.php?tb=result_"+tablename+"&si="+serviceInterface+"&me="+method;
            series[1].name = 'Days-' + method;
            $.ajax({
                    type : "get",
                    url : commandsUrl,
                    async : false,
                    success : function(data) {
                        for(var i = 0; i < data.length; i++){
                            //series[1].data.push(+data[key]);
                            series[1].data.push(+data[i].elapsed);
                            seriesCount[2].data.push(+data[i].failureCount);
                            seriesCount[3].data.push(+data[i].successCount);
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

           var jsonCount = {};
           json.title = title;
          // json.subtitle = subtitle;
           json.xAxis = xAxis;
           json.yAxis = yAxisCount;
           json.tooltip = tooltipCount;
           json.legend = legend;
           json.series = seriesCount;

           var tujson = [json,jsonCount];

           return tujson;

   
};

var hcharts = function(json,id){
        $('#container' + id ).highcharts(json);
};

</script>


<?php
$tb = $_REQUEST['tb'];
$si = $_REQUEST['si'];

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
#$SummarySql = 'select serviceInterface,method,elapsed from `avg_'.$tb.'_'.$postfix.'` group by serviceInterface,method';
$SummarySql = 'select method from `avg_'.$tb.'_'.$postfix.'` where serviceInterface = \''.$si.'\' group by method';
#echo $SummarySql;
$res = mysql_query($SummarySql);                           
$i = 0;
while ($row = mysql_fetch_assoc($res)) {
             #$SMAry[$i]['serviceInterface'] = $row['serviceInterface'];
             $SMAry[$i]['method'] = $row['method'];
             echo "<div id=\"container".$i."\" style=\"width: 1800px; height: 400px; margin: 0 auto\"></div>\n";
             $i++;
}

#print_r($SMAry);

//echo json_encode($SMAry);
mysql_close($mysql_conn);
$i=0;


foreach ( $SMAry as $value){
//        echo $value['serviceInterface'];
        echo "
        <script>
        var tutu = tu('". $tb ."','".$si."','".$value['method']."','".$postfix."');
        hcharts(tutu[0],'".$i."');
        hcharts(tutu[1],'".$i."');
        </script>";
        $i++;
}

?>






</body>
</html>
