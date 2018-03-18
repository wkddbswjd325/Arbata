<?php
/* 
	비밀번호 찾기하면
	가입할 때 입력한 이메일로 비밀번호를 전송해주는 방식이다. 
*/
include('dbConnect.php');

$id = $_POST['findPwd_id'];
$name = $_POST['findPwd_name'];
$phone = $_POST['findPwd_phone'];

$sql = "select * from member where mem_id='$id' and mem_name='$name' and mem_phone='$phone';";
$result = mysql_query($sql);
$row = mysql_num_rows($result); 
$data = mysql_fetch_array($result);

// 입력한 이메일이 DB의 member 테이블의 mem_email 값과 일치하는 것이 존재하지 않을 경우 
if($row == 0) {
	echo"<script>alert('입력하신 정보와 일치하는 아이디가 존재하지 않습니다.');
	location.href='findPwd.php';</script>";
}

// 가입된 이메일이 있을 경우
else {
	$who = $data['mem_email'];
	$title = "딩동! [알바타]에서 요청하신 비밀번호가 도착했습니다~!"; // 메일 보낼 때, 제목 

	// 메일 보낼 때, 내용 
	$content = "안녕하세요~ 아르바이트 대타 구인사이트 [알바타]입니다.<br>저희 사이트를 이용해주셔서 감사합니다.<br><br>".$data['mem_name']."(".$data['mem_id'].")"."님이 요청하신 비밀번호는 ".$data['mem_pwd']."입니다.<br><br>추가로 로그인 시 비밀번호 변경을 권장드립니다.<br>행복한 하루 보내시고 앞으로도 많은 이용 부탁드립니다.<br><br>[알바타]";

	// 메일 보내는 주소
	$optionValue = 'From: 알바타<albata@albata.com> \r\n';

	mail($who, $title, $content, $optionValue);

	echo"<script>alert('".$data['mem_name']."님의 메일로 패스워드를 전송하였습니다.');
		location.href='loginPage.php';</script>";
}
mysql_close($connect);
?>