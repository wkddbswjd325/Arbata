<?php
/*
	employerRead에서 게시물 삭제를 눌렀을 경우 또는
	myPostList에서 게시물 삭제를 선택했을 경우에
	게시물에 대한 데이터를 DB에서 삭제해주는 페이지
*/
header("Content-Type: text/html; charset= UTF-8");
session_start();

extract($_REQUEST);
extract($_GET);
extract($_POST);
extract($_SERVER);

include_once('dbConnect.php');

$query = "select * from employer where er_no='$_GET[er_no]'"; 
$result = mysql_query($query, $connect);
$data = mysql_fetch_array($result);

$er_sido = $data['er_sido'];
$er_gugun = $data['er_gugun'];
$er_dong = $data['er_dong'];

if($data['er_radio'] == "day") { // 하루일 때
	$query = "delete from employer_day where er_no='$_GET[er_no]'";
	$result = mysql_query($query, $connect);
}
else if($data['er_radio'] == "days") { // 단기일 때
	$query = "delete from employer_days where er_no='$_GET[er_no]'";
	$result = mysql_query($query, $connect);
}

// 삭제할 게시물의 번호를 받아와 DB에서 삭제
$query = "delete from employer where er_no='$_GET[er_no]'"; 
$res= mysql_query($query, $connect);

if(!isset($_GET['from'])) { // employerRead에서 게시물 삭제를 눌렀을 경우 
	echo"<script>
		alert('선택한 게시물을 삭제하였습니다.');
		location.href='employerBoard.php?sido=".$er_sido."&gugun=".$er_gugun."&dong=".$er_dong."';
		</script>";
}

else { // myPostList에서 게시물 삭제를 선택했을 경우
	echo"<script>
		alert('선택한 게시물을 삭제하였습니다.');
		location.href='myPostList.php?value=1';
		</script>";

}

mysql_close($connect);
?>