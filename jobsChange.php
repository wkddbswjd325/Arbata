<?php
/*
	업종 분류 선택을 위한 페이지
	다른 페이지에서 업종 분류를 선택할 때,
	다중 select를 구현하기 위해서 사용

	이 페이지로 넘어와 처리하는 방식
*/
include_once('dbConnect.php');

$jobs1 = $_POST['jobs1'];
$jobs2 = $_POST['jobs2'];

if(!isset($jobs2)) {
	$sql = "select distinct(jobs2) from jobs where jobs1='$jobs1'";
	$result = mysql_query($sql);
	
	echo "<option value=''>분류2</option>";
	while($row = mysql_fetch_array($result)) {
		echo "<option value='".$row['jobs2']."'>".$row['jobs2']."</option>"; 
	}

} else {
	echo "<option value=''>분류3</option>";

	$sql = "select distinct(jobs3) from jobs where jobs1='$jobs1' and jobs2='$jobs2'";
	$result = mysql_query($sql);

	while($row = mysql_fetch_array($result)) {
		echo "<option value='".$row['jobs3']."'>".$row['jobs3']."</option>"; 
	}

}

mysql_close($connect);
?>