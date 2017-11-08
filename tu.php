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

           var titleFCount = {
                            text: '调用失败次数'
           };

           var titleSCount = {
               text: '调用成功次数'
           };

           var subtitle = {
                           text: 'Source: dubbo.neiwang.com'
                      };
           var xAxis = {
                         categories: []
                      };
           var xAxisFCount = {
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

           var yAxisFCount = {
               title: {
                   text: '单位：次数'
               },
               plotLines: [{
                   value: 0,
                   width: 1,
                   color: '#808080'
               }]
           };


           var tooltip = {
                         valueSuffix: 'ms'
                      };

           var tooltipFCount = {
               valueSuffix: 'times'
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


            var seriesFCount =  [
                {
                    name: '',
                    data:[]
                },
                {
                    name:'',
                    data:[]
                }
            ];

            var seriesSCount =  [
                {
                    name: '',
                    data:[]
                },
                {
                    name:'',
                    data:[]
                }
            ];


            var commandsUrl = "/api2.php?tb=avg_"+tablename+"_"+postfix+"&si="+serviceInterface+"&mt="+method;
            series[0].name = method;
            seriesFCount[0].name = method;
            seriesSCount[0].name = method;
            $.ajax({
                    type : "get",
                    url : commandsUrl,
                    async : false,
                    success : function(data) {
                            for( var key in data ){
                                    xAxis.categories.push(key);
                                    series[0].data.push(+data[key].elapsed);
                                    seriesFCount[0].data.push(+data[key].failureCount);
                                    seriesSCount[0].data.push(+data[key].successCount);
                           }
 
                             
                    }
            }); 



            var commandsUrl = "/api2.php?tb=result_"+tablename+"&si="+serviceInterface+"&mt="+method;
            series[1].name = 'Days-' + method;
            seriesFCount[1].name = 'Days-' + method;
            seriesSCount[1].name = 'Days-' + method;
            $.ajax({
                    type : "get",
                    url : commandsUrl,
                    async : false,
                    success : function(data) {
                            for( var key in data ){
                                    series[1].data.push(+data[key].elapsed);
                                    seriesFCount[1].data.push(+data[key].failureCount);
                                    seriesSCount[1].data.push(+data[key].successCount);
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

           var jsonFCount = {};
           jsonFCount.title = titleFCount;
           jsonFCount.subtitle = subtitle;
           jsonFCount.xAxis = xAxis;
           jsonFCount.yAxis = yAxisFCount;
           jsonFCount.tooltip = tooltipFCount;
           jsonFCount.legend = legend;
           jsonFCount.series = seriesFCount;

           var jsonSCount = {};
           jsonSCount.title = titleSCount;
           jsonSCount.subtitle = subtitle;
           jsonSCount.xAxis = xAxis;
           jsonSCount.yAxis = yAxisFCount;
           jsonSCount.tooltip = tooltipFCount;
           jsonSCount.legend = legend;
           jsonSCount.series = seriesSCount;

           return {json,jsonFCount,jsonSCount};

   
};

var hcharts = function(json,id){
        $('#container' + id ).highcharts(json);
};

</script>


<?php
include ("inc.php");

$tb = $_REQUEST['tb'];
$si = $_REQUEST['si'];
$mt = $_REQUEST['mt'];



$postfix = date("Ymd",time());

echo "<div id=\"container1\" style=\"width: 1800px; height: 400px; margin: 0 auto\"></div>\n";
echo "<div id=\"container2\" style=\"width: 1800px; height: 400px; margin: 0 auto\"></div>\n";
echo "<div id=\"container3\" style=\"width: 1800px; height: 400px; margin: 0 auto\"></div>\n";


echo "
        <script>
        var tutu = tu('". $tb ."','".$si."','".$mt."','".$postfix."');

        hcharts(tutu.json,'1');
        hcharts(tutu.jsonSCount,'2');
        hcharts(tutu.jsonFCount,'3');
        
        </script>";

?>






</body>
</html>
