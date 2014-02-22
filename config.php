<?php
header("Content-Type:text/html;charset=utf-8");
date_default_timezone_set('Asia/Chongqing');  //PRC

//开启Session
session_start();

define('HOST','localhost');
define('USER','root');
define('PASS','root');
define('DB','photo_data');

function db_ini(){
	$link= mysql_connect(HOST,USER,PASS) or die('数据库呢？');
	mysql_select_db(DB,$link);
	mysql_set_charset('utf8',$link);
	return $link;
}




?>