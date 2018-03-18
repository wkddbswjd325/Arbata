<?php
/*
	비밀번호 찾는 페이지
	아이디, 이름과 휴대폰 번호를 입력하면
	sendEmail 페이지로 넘어가 회원가입시 입력한 이메일로 비밀번호를 보내주는 방식
*/
include('menuBar.php');
?>
<html>
<head>
<title>비밀번호 찾기</title>
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
<br><br><br><font style="font-size:20px;color:#f1404b">비밀번호</font><font style="font-size:20px;color:#252c41">를 모르시나요?</font>
<br><br><br><span style="line-height:50%"><br></span>
<form name="findPwd" action="sendEmail.php" method="post" onSubmit="return findPwdCheck();">
<input type="text" maxlength="40" placeholder="아이디" name="findPwd_id" style="font-size:18px; width:70%;">
<br><br>
<input type="text" maxlength="40" placeholder="이름" name="findPwd_name" style="font-size:18px; width:70%;">
<br><br>
<input type="number" maxlength="40" placeholder="휴대폰 번호 (숫자만 입력)" name="findPwd_phone" style="font-size:18px; width:70%;">
<br><br><br><span style="line-height:50%"><br></span>
<table align="center" border="0px" cellspacing="0px" cellpadding="0px" width="70%">
	<tr>
		<td width="30%" align="center"><input type="submit" id="btnFindPwd" value="패스워드 찾기" style="width:63%;font-size:18px"></td>
	</tr>
</table>
</form>
<font style="font-size:15px;color:#252c41">
<br><a href='findId.php'>아이디 찾기</a>
<br><span style="line-height:50%"><br></span><a href='loginPage.php'>로그인 하기</a>
</font>
</div>
<script type="text/javascript">
function findPwdCheck() {
	if(findPwd.findPwd_id.value=="") {
		alert("아이디를 입력하세요.");
		findPwd.findPwd_id.focus();
		return false;
	}
	else if(findPwd.findPwd_name.value=="") {
		alert("이름을 입력하세요.");
		findPwd.findPwd_name.focus();
		return false;
	}
	else if(findPwd.findPwd_phone.value=="") {
		alert("휴대폰 번호를 입력하세요.");
		findPwd.findPwd_phone.focus();
		return false;
	}
	else {
		return true;
	}
}
</script>
</body>
</html>

