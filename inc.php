<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/1 0001
 * Time: 15:40
 */


function MysqlConn()
{

    $mysql_conf = array(
        'host' => '192.168.8.110:3306',
        'db' => 'dubbomonitor',
        'db_user' => 'root',
        'db_pwd' => 'xuele123',
    );
    $mysql_conn = @mysql_connect($mysql_conf['host'], $mysql_conf['db_user'], $mysql_conf['db_pwd']);
    if (!$mysql_conn) {
        die("could not connect to the database:\n" . mysql_error());
    }
    mysql_query("set names 'utf8'");//编码转化
    $select_db = mysql_select_db($mysql_conf['db']);
    if (!$select_db) {
        die("could not connect to the db:\n" . mysql_error());
    }

    return $mysql_conn;
}


function MysqlClose($conn){
    mysql_close($conn);
}
