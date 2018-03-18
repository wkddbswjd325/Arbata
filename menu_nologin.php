<?php
// 로그인 안했을 경우 메뉴
session_start();
if(isset($_SESSION['id']) || isset($_SESSION['passwd'])) {
	echo "<script>location.href='menu.php';</script>";
}
?>
<html>
<head>
<title>메뉴</title>
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
<div align="center">
	<table align="center" width="100%" cellspacing="10px">
		<tr>
			<td width="50px" align="left">
			<img src="image/nologin.png" width="50px" height="50px" id="myPhoto" onclick="location.href='loginPage.php'">
			</td>
			<td align="left"><span style="font-weight:bold;font-size:16px;float:left;"><a href="loginPage.php"><font color="grey">로그인이 필요합니다</font>
			</a></span></td>
			<td align="right">
			<img src="image/exit.png" width="30px" height="30px" id="exit" onclick="history.go(-1); return false;">
			</td>
		</tr>
	</table>
	<table align="center" width="100%" border="1px" cellpadding="20" style="border-collapse:collapse;border:1px solid #dddfe6;">
		<tr>
			<td align="center" colspan="3" width="50%" onclick="location.href='employerBoard.php'" style="border:1px solid #dddfe6;">
			<img src="image/board.png" width="40px" height="40px" id="board">
			<font size="3" color="gray"><br>대타를 부탁해!<br>게시판</font></td>
			<td align="center" colspan="3" width="50%" onclick="location.href='employeeBoard.php'" style="border:1px solid #dddfe6;">
			<img src="image/board.png" width="40px" height="40px" id="board">
			<font size="3" color="gray"><br>나에게 부탁해!<br>게시판</font></td>
		</tr>
		<tr>
			<td align='center' colspan="2" onclick="location.href='loginPage.php'" style="border:1px solid #dddfe6;"><img src='image/logout.png' width='40px' height='40px' id='board'>
			<font size="3" color='gray'><br>로그인</font></td>

			<td align="center" colspan="2" onclick="location.href='noticeBoard.php'" style="border:1px solid #dddfe6;"><img src="image/reportProblem.png" width="40px" height="40px" id="board">
			<font size="3" color="gray"><br>공지사항</font></td>

			<td align="center" colspan="2" onclick="location.href='developer.php'" style="border:1px solid #dddfe6;"><img src="image/developer.png" width="40px" height="40px" id="board">
			<font size="3" color="gray"><br>개발자<br>소개</font></td>
		</tr>
		<tr>
			<td align='center' colspan="6" onclick="location.href='agreeJoin.php'" style="border:1px solid #dddfe6;"><img src='image/join.png' width='40px' height='40px' id='board'>
			<font size="3" color='gray'><br>회원가입</font></td>
		</tr>
	</table>
</div>
<body>
</html>
