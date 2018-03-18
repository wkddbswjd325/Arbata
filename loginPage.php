<!--
	로그인하는 페이지
	로그인하면 login_ok 페이지로 넘어감
	아이디 찾기 / 비밀번호 찾기와 연결되어있음
-->
<html lang="ko">
<head>
  <meta charset="UTF-8" http-equiv="Content-Type" />
  
  <!-- 모바일 화면의 크기가 고정되게 하는 소스, 가로 스크롤 안생긴다. -->
  <meta name="viewport" content="user-scalable=no, initial-scale=1.0, maximum-scale=1.0,
  minimum-scale=1.0, width=device-width" />

  <!-- 아이폰 바탕화면 아이콘 추가 -->
  <link rel="apple-touch-icon" href="image/apple.jpg" />
  
  <!-- 주소창 숨기기 -->
  <script type="text/javascript">
  window.addEventListener("load", function() {
	  setTimeout(loaded, 100);
  }, false);

  function loaded() {
  window.scrollTo(0,1);
  }
  </script>
<title>로그인 페이지</title>
<link rel="stylesheet" href="inputStyle.css" type="text/css" />
</head>
<body>
<div align="center">
<?php
include('menuBar.php');
?>
<br><br><br><br><br>

<form name="login" method="POST" action="login_ok.php">
<input type="text" name="mem_id" maxlength="40" placeholder="아이디" id="inputId" maxlength="16">
<br><br>
<input type="password" name="mem_pwd" maxlength="40" placeholder="비밀번호" id="inputPwd" maxlength="16">
<br><br><br><br>
<table align="center" border="0px" cellspacing="0px" cellpadding="0px" width="70%">
	<tr>
		<td width="30%" align="center"><input type="submit" id="login" value="로그인"></td>
		<td width="10%">&nbsp;</td>
		<td width="30%" align="center"><input type="button" id="join" value="회원가입" onclick="location.href='agreeJoin.php'" ></td>
	</tr>
</table>
<br><br><br>
<span id="finder">
<a href="findId.php">아이디 찾기</a><br>
<a href="findPwd.php">비밀번호 찾기</a>
</span>
</form>
</div>
</body>
</html>
