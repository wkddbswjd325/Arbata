<?php
/*
	DB 연결 코드
*/
$mysql_hostname = 'localhost';
$mysql_username = 'root';
$mysql_password = 'p0rtnumber1o1';
$mysql_database = 'Arbata';
$mysql_port = '3306';
$mysql_charset = 'utf8';

// 추후 앱확장을 위한 소스 (FCM)
define("GOOGLE_API_KEY", "AIzaSyBD5KpEUmzdVPZGXRQof_CC0agwlUSJ6xE");

//1. DB 연결
$connect = @mysql_connect($mysql_hostname.':'.$mysql_port, $mysql_username, $mysql_password); 

//2. DB 선택
@mysql_select_db($mysql_database, $connect) or die('DB 선택 실패');
//3. 문자셋 지정
mysql_query(' SET NAMES '.$mysql_charset);

?>