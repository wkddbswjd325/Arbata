<?php
/*
	아이디 찾는 페이지
	이름과 휴대폰 번호를 입력하면 findIdResult 페이지로 넘어감
*/
include('menuBar.php');
?>
<html>
<head>
<title>아이디 찾기</title>
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
<br><br><br><br><font style="font-size:20px;color:#f1404b">아이디</font><font style="font-size:20px;color:#252c41">를 모르시나요?</font><br><br><br>
<form name="findId" action="findIdResult.php" method="post" onSubmit="return findIdCheck();">
<input type="text" maxlength="40" placeholder="이름" name="findId_name" style="font-size:18px; width:70%;">
<br><br>
<input type="number" maxlength="40" placeholder="휴대폰 번호 (숫자만 입력)" name="findId_phone" style="font-size:18px; width:70%;">
<br><br><br>
<table align="center" border="0px" cellspacing="0px" cellpadding="0px" width="70%">
	<tr>
		<td width="30%" align="center"><input type="submit" id="btnFindId" value="아이디 찾기" style="width:50%;font-size:18px"></td>
	</tr>
</table>
</form>
<font style="font-size:15px;color:#252c41">
<br><br><a href='findPwd.php'>비밀번호 찾기</a>
<br><span style="line-height:50%"><br></span><a href='loginPage.php'>로그인 하기</a>
</font>
</div>
<script type="text/javascript">
function findIdCheck() {
	if(findId.findId_name.value=="") {
		alert("이름을 입력하세요.");
		findId.findId_name.focus();
		return false;
	}
	else if(findId.findId_phone.value=="") {
		alert("휴대폰 번호를 입력하세요.");
		findId.findId_phone.focus();
		return false;
	}
	else {
		return true;
	}
}
</script>
</body>
</html>

