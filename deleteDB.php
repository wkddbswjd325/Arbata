<?php
/*
	회원 탈퇴하면 DB에서 회원 데이터를 없애주는 페이지
*/
session_start();

include_once('dbConnect.php');

if(!isset($_POST['del_pwd'])) exit;
$mem_id = $_SESSION['id'];
$del_pwd = $_POST['del_pwd'];

$query = "select count(*) from member where mem_id='$mem_id' and mem_pwd='$del_pwd'"; 
$result = mysql_query($query); 
$data = mysql_fetch_array($result); 

if($data[0] == 0) {
	echo "<script>alert('패스워드가 잘못되었습니다.');history.go(-1);</script>";
	exit;
}

//** files/imgae 삭제
$query = "select * from image where mem_id='$mem_id'";
$result = mysql_query($query); 
$img = mysql_fetch_array($result); 

if($img['img_title'] != "female.png" && $img['img_title'] != "male.png") {
	if(is_file($_SERVER['DOCUMENT_ROOT'].$img['img_path'])) {
		unlink($_SERVER['DOCUMENT_ROOT'].$img['img_path']);
	}
}


//1. apply table
$apply = "delete from apply where a_applier='$mem_id'";
$result = mysql_query($apply, $connect);

//2. message table -> id의 받은 메세지함과 id의 보낸 메세지함 비우기
$msg1 = "delete from message where msg_rcvId='$mem_id' and msg_chk='0'";
$result = mysql_query($msg1, $connect);

$msg2 = "delete from message where msg_sendId='$mem_id' and msg_chk='1'";
$result = mysql_query($msg2, $connect);

//3. employer table -> 하루일 때, 단기일 때 나눠서 
$er1 = "delete from employer_day using employer join employer_day on employer.er_no = employer_day.er_no where er_writer='$mem_id'";
$result = mysql_query($er1, $connect);

$er2 = "delete from employer_days using employer join employer_days on employer.er_no = employer_days.er_no where er_writer='$mem_id'";
$result = mysql_query($er2, $connect);

$er = "delete from employer where er_writer='$mem_id'";
$result = mysql_query($er, $connect);

//4. employee table -> 하루일 때, 단기일 때 나눠서
$ee1 = "delete from employee_day using employee join employee_day on employee.ee_no = employee_day.ee_no where ee_writer='$mem_id'";
$result = mysql_query($ee1, $connect);

$ee2 = "delete from employee_days using employee join employee_days on employee.ee_no = employee_days.ee_no where ee_writer='$mem_id'";
$result = mysql_query($ee2, $connect);

$ee = "delete from employee where ee_writer='$mem_id'";
$result = mysql_query($ee, $connect);

//5. savelist table
$save = "delete from savelist where s_id='$mem_id'";
$result = mysql_query($save, $connect);

//6. review table
$view = "delete from review where r_recvId='$mem_id'";
$result = mysql_query($view, $connect);

//7. image table
$img = "delete from image where mem_id='$mem_id'";
$result = mysql_query($img, $connect);

//8. member table
$mem = "delete from member where mem_id='$mem_id'";
$result = mysql_query($mem, $connect);
if(mysql_affected_rows() > 0) {
	echo "<script>alert('회원탈퇴가 성공적으로 이루어졌습니다.');</script>";
}
else {
	exit($mem_id . " 삭제 실패");
}

mysql_close($connect);
session_destroy();

echo "<script>location.href='index.php';</script>";

?>
<meta http-equiv='refresh' content='0;url=index.php'>