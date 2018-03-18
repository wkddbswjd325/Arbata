<?php
/*
	findId 페이지로부터 받은 이름과 휴대폰 번호를 가지고
	DB에 있는 데이터와 비교해
	이에 해당하는 아이디를 출력해주는 방식
*/
include('menuBar.php');
include('dbConnect.php');
?>
<html>
<head>
<title>아이디 찾기 결과</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="user-scalable=no, initial-scale=1.0, maximum-scale=1.0, width=device-width" />
<link rel="apple-touch-icon" href="image/apple.jpg" />
<script type="text/javascript">
window.addEventListener("load", function() {
	setTimeout(loaded, 100);
}, false);

function  loaded() {
	window.scrollTo(0, 1);
}
</script>
<link rel="stylesheet" href="inputStyle.css" type="text/css" />
</head>
<body>
<div align="center" valign="center">
<?php
$name = $_POST['findId_name'];
$phone = $_POST['findId_phone'];

$sql = "select mem_id from member where mem_name='$name' and mem_phone='$phone';";
$result = mysql_query($sql);  
$rows = mysql_num_rows($result); 

?>
<br><br><br><br>
<font style="font-size:20px;color:#252c41">입력하신 정보에 맞는 <font style="font-size:20px;color:#f1404b">아이디</font>가<br>
<font style="font-size:5px"><br></font>
<?php
if($rows==0) {
	echo "존재하지 않습니다.<br>";
?>
<br><br>
<img src="image\cry.png" />

<?php
}
else {
	echo "<font style='font-size:20px;color:#f1404b'>".$rows."개</font> 존재합니다.<br><br>";
	echo "<table style='padding-top:10px;padding-bottom:10px;width:70%;border:dashed;border-color:#dddfe6;font-size:18px;color:#252c41'>";
	while($data = mysql_fetch_array($result)) {
		echo "<tr align=center><td>".$data['mem_id']."</td></tr>";
	}
	echo "</table>";
}
?>
</font>
<br><br><br>
<form>
	<table align=center border="0px" cellspacing="0px" cellpadding="0px" width="70%">
	   <tr>
		  <td width="30%" align="center"><input type="button" id="mypage" value="로그인" style="font-size:18px" onclick="location.href='loginPage.php'"></td>
		  <td width="5%">&nbsp;</td>
		  <td width="30%" align="center"><input type="button" id="logout" value="비밀번호 찾기" style="font-size:18px" onclick="location.href='findPwd.php'"></td>
	   </tr>
	</table>
	</form>
</div>
<body>
</html>

