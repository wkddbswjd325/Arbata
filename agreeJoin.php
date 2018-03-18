<?php
/*
	회원가입 동의서 작성 페이지
*/
include('menuBar.php');
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8;" />
  
<!-- 모바일 화면의 크기가 고정되게 하는 소스, 가로 스크롤 안생긴다. -->
<meta name="viewport" content="user-scalable=1, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, width=device-width, target-densitydpi=medium-dpi"  />

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
<link rel="stylesheet" href="inputStyle.css" type="text/css"/>
<title>회원가입 동의서</title>
</head>
<body>
<form name="agreeForm" onSubmit="return chk_agree();" action="join.php">
<center>
<div align="center" style="background-color:#f4f5f9;width:90%;">
<img src="image/logo.png" width="40%" valign="middle" style="margin-top:30px;margin-bottom:30px;max-width:250px">
<br>
<font color="#252c41"><b>개인정보 수집 및 이용에 대한 안내</b></font><br>
<textarea style="resize:none;margin-top:10px;margin-bottom:5px;border:none;width:90%" rows="10" readonly wrap>
정보통신망법 규정에 따라 알바타에 회원가입 신청하시는 분께 수집하는 개인정보의 항목, 개인정보의 수집 및 이용목적, 개인정보의 보유 및 이용기간을 안내 드리오니 자세히 읽은 후 동의하여 주시기 바랍니다.

1. 수집하는 개인정보
이용자는 회원가입을 하지 않아도 게시판에 올라오는 게시물은 볼 수 있습니다. 이용자가 매칭 서비스, 쪽지, 보관함과 같은 회원제 서비스를 이용하기 위해 회원가입을 할 경우, 알바타는 서비스 이용을 위해 필요한 최소한의 개인정보를 수집합니다.
회원가입 시점에 네이버가 이용자로부터 수집하는 개인정보는 아래와 같습니다.
- 회원 가입 시에 ‘아이디, 비밀번호, 이름, 성별, 생년월일, 휴대폰번호, 이메일, 주소’를 필수항목으로 수집합니다. 그리고 선택항목으로 이메일 주소를 수집합니다.

2. 수집한 개인정보의 이용
알바타는 회원관리, 서비스 개발・제공 및 향상, 안전한 인터넷 이용환경 구축 등 아래의 목적으로만 개인정보를 이용합니다.
- 아이디/비밀번호 찾기, 이용자 식별 등 회원관리를 위하여 개인정보를 이용합니다.
- 서비스 이용 시, 고객에게 맞춤형 서비스 제공하기 위하여 개인정보를 이용합니다.

3. 개인정보의 파기
알바타는 원칙적으로 이용자의 개인정보를 회원 탈퇴 시 자동적으로 파기됩니다.
</textarea>
<br>
<span style="float:right;margin-right:10px;">
<font color="#252c41">위에 동의합니다</font>
<input type="checkbox" name="agree1">
</span>
<br><br><br>

<font color="#252c41"><b>알바타 이용약관 동의</b></font><br>
<textarea style="resize:none;margin-top:10px;margin-bottom:5px;border:none;width:90%" rows="10" readonly wrap>
알바타는 사용자들의 자율적인 선택으로 원하는 상대와 매칭을 해주는 역할을 하는 사이트입니다.

매칭 이후에 생기는 아래와 같은 일들에 관해서는 책임지거나 관여하지 않습니다.
- 아르바이트 급여 지급에 관련된 금전 문제
- 상대의 약속 파기로 인한 사용자의 손해
- 상대의 행동으로 인한 아르바이트 장소에서 발생한 문제
- 사용자들 간에 일어나는 모든 문제

이 사이트에 올라오는 게시물과 매칭되는 상대방에 대한 주의를 기울여 위와 같은 문제가 발생하지 않도록 하시기 바랍니다.
</textarea>
<br>
<span style="float:right;margin-right:10px;">
<font color="#252c41">위에 동의합니다</font>
<input type="checkbox" name="agree2">
</span>
<br><br><br>
<input type="submit" value="동의" style="width:45%"> 
<input type="button" value="비동의" style="width:45%;border:solid;border-color:#f1404b ;background-color:#ffffff;color:#f1404b;padding: 5.5px 5.5px;" onclick="location.href='index.php'">
<br><br><br>
</div>
</center>
</form>
</body>
<script type="text/javascript">
function chk_agree() {
	if(agreeForm.agree1.checked == false || agreeForm.agree2.checked == false) {
		alert("모두 동의해주세요!");
		return false;
	}
	else
		return true;
}
</script>
</html>