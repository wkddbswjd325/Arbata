<?php
/*
	회원 탈퇴 하기 전 의사확인, 비밀번호 확인
*/
	include_once('dbConnect.php');
?>
<!DOCTYPE html>
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
<title>회원 탈퇴</title>
<link rel="stylesheet" href="inputStyle.css" type="text/css" />
</head>
<body>
<div align="center">
<?php
include('menuBar.php');
?>
<br><br><br><br><br>
<b>**회원탈퇴를 하기 전에 안내 사항을 꼭 확인해주세요</b><br>
<b>계정 복구 불가</b> / 사용하고 계신 아이디(<?php echo $_SESSION['id'] ?>)를 탈퇴하시면 복구가 불가하오니 신중하게 선택하시기 바랍니다.<br><br>
회원 정보 및 등록한 게시물, 매칭 한 게시물, 쪽지 등의 모든 정보가 지워집니다.<br><br>

<form name="deleteID" method="POST" action="deleteDB.php" onSubmit="return lastChk();">
	ID : <?php echo $_SESSION['id'] ?>
<br><br>
<input type="password" name="del_pwd" maxlength="40" placeholder="비밀번호" id="delPwd" maxlength="16">
<br><br><br><br>
<table align="center" border="0px" cellspacing="0px" cellpadding="0px" width="70%">
	<tr>
		<td width="30%" align="center"><input type="submit" style="color:#f6f6f6;" id="delete" value="탈퇴"></td>
	</tr>
</table>
<br><br><br>
</form>
</div>

<script type="text/javascript">
	function lastChk() { //마지막 확인
		if(deleteID.del_pwd.value=="") {
			alert("비밀번호를 입력하세요.");
			deleteID.del_pwd.focus();
			return false;
		}
		else {
			return confirm('정말 탈퇴하시겠습니까?');
		}
	}
</script>

</body>
</html>
