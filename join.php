<?php
/*
	회원가입하는 페이지
	각 항목에 맞는 제약조건을 스크립트로 걸어놓음
	주소 항목의 지역 선택을 위한 동적 select 구현
	중복 ID 확인을 위해 ajax 사용
*/
include('menuBar.php');
include_once('dbConnect.php');
?>
<html>
<head>
<title>회원가입</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="user-scalable=no, initial-scale=1.0, maximum-scale=1.0, width=device-width" />
<link rel="apple-touch-icon" href="image/apple.JPG" />
<script type="text/javascript">
window.addEventListener("load", function() {
	setTimeout(loaded, 100);
}, false);

function  loaded() {
	window.scrollTo(0, 1);
}
</script>
<link rel="stylesheet" href="inputStyle.css" type="text/css"/>
</head>
<body>
<br>
<form name="joinForm" enctype="multipart/form-data" method="POST" action="addMemDB.php" onSubmit="return chk_input();">
<div align="center">
<table bgcolor="#f4f5f9" width="90%" border="0px" cellspacing="10px" cellpadding="0px">
	<tr>
		<td><input type="text" name="mem_id" placeholder="아이디 (6 ~ 16자 이내) " id="joinId" style="border:0px;font-size:17px;width:100%" maxlength="16" onKeyUp="onlyEng(mem_id)"></td>
		<td width="3%"><input type="button" id="joinIdCheck" name="joinIdCheck" value="중복확인"></td>
	</tr>
	<tr>
		<td colspan="2"><input type="password" name="mem_pwd" placeholder="패스워드 (6 ~ 16자 이내)" id="joinPwd" style="border:0px;font-size:17px;width:100%" maxlength="16"></td>
	</tr>
	<tr>
		<td colspan="2"><input type="password" name="mem_pwd_chk" placeholder="패스워드 확인" id="joinPwdCheck" style="border:0px;font-size:17px;width:100%" maxlength="16"></td>
	</tr>
</table>
<br>
<table bgcolor="#f4f5f9" width="90%" border="0px" cellspacing="10px" cellpadding="0px">
	<tr>
		<td width="24%">&nbsp;이름</td>
		<td><input type="text" name="mem_name" id="joinName" style="border:0px;font-size:17px;width:100%" maxlength="5" onKeyUp="onlyKor(mem_name)"></td>
	</tr>
	<tr>
		<td>&nbsp;성별</td>
		<td>
			<input type="radio" value="m" name="mem_sex"> 남자
			&nbsp;
			<input type="radio" value="f" name="mem_sex" checked> 여자
		</td>
	</tr>
	<tr>
		<td>&nbsp;생년월일</td>
		<td><input type="date" name="mem_birth" id="birth"></td>
	</tr>
	<tr>
		<td>&nbsp;휴대폰</td>
		<td>
			<input type="tel" name="mem_phone" id="phone" required style="border:0px;font-size:17px;width:100%" placeholder="번호를 - 없이 입력하세요" maxlength="11" onKeyUp="onlyNum(mem_phone)"> 
		</td>
	</tr>
	<tr>
		<td>&nbsp;이메일</td>
		<td><input type="email" name="mem_email" id="joinEmail" placeholder="이메일을 정확히 입력하세요" style="border:0px;font-size:17px;width:100%" maxlength="30" onKeyUp="chk_email(mem_email)"></td>
	</tr>
	<tr>
		<td>&nbsp;주소</td>
	</tr>
	<tr>
		<td colspan=2>
			<select name="sido" id="sido" onChange="sidoChange()">
				<option value=''>시/도</option>
				<?php
				$sql = "select distinct(sido) from area order by priority";
				$result = mysql_query($sql);

				while($row = mysql_fetch_array($result)) {
						echo "<option value='".$row['sido']."'>".$row['sido']."</option>"; 
				}
				
				?>
			</select>
			<select name="gugun" id="gugun" onChange="gugunChange()" >
				<option value=''>구/군</option>
			</select>
			<select name="dong" id="dong">
				<option value=''>동/면/읍</option>
			</select>
		</td>
	</tr>
</table>
<br>
<table bgcolor="#f4f5f9" width="90%" border="0px" cellspacing="10px" cellpadding="0px">
	<tr>
		<td><font style="color:darkblue"> * 선택 항목</font></td>
	</tr> 
	<tr>
		<td>&nbsp;<font style="font-size:13px">사진 등록</font></td>
	</tr>
	<tr>
		<td>
			<input type="hidden" name="MAX_FILE_SIZE" value="1000000" />
			<input type="hidden" name="mode" value="upload" />
			<input type="file" name="upload" /><br>
		</td>
	</tr>
</table>
</div>
<br>
<table align="center" border="0px" cellspacing="0px" cellpadding="0px" width="70%">
   <tr>
      <td width="30%" align="center"><input type="submit" id="login" value="가입"/></td>
      <td width="10%">&nbsp;</td>
      <td width="30%" align="center"><input type="reset" id="join" value="취소" onclick="history.go(-1);"></td>
   </tr>
</table>
</form>
<script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script><!-- 최신버전 제이쿼리 -->
<script type="text/javascript">
var check = 0;
var dup = 1;
$("#joinIdCheck").click(function(){ // 중복 아이디 체크를 위한 함수
	var id = $('#joinId').val();
	if(id == '') {
		alert('아이디를 입력하세요.');
	}
	else {
		$.ajax({
		type: "POST",
		url: "checkId.php", // 이 페이지에서 중복체크를 한다
		data: "id="+ id,
		cache: false,
		success: function(data){
			dup=data;
			if(dup==1)
				alert(id+"는 이미 사용중인 아이디입니다.");
			else
				alert(id+"는 사용 가능한 아이디입니다.");
			check = 1;
		}
		});
	}
});

// 지역 선택을 위한 함수 (시/도가 바뀜에 따라 구/군이 바뀜)
function sidoChange() { 
	var sido = $("select[name='sido'] option:selected").val();
	$.ajax({
		type: "POST",
		url: "areaChange.php",
		data: "sido=" + sido,
		success: function(data) {
			$("#gugun").html(data);
			$("#dong").html("<option value=''>동/면/읍</option>");
		}
	});
}

// 지역 선택을 위한 함수 (구/군이 바뀜에 따라 동/면/읍이 바뀜)
function gugunChange() {
	var sido = $("select[name='sido'] option:selected").val();
	var gugun = $("select[name='gugun'] option:selected").val();
	$.ajax({
		type: "POST",
		url: "areaChange.php",
		data: "sido=" + sido + "&gugun=" + gugun,
		success: function(data) {
			$("#dong").html(data);
		}
	});
}

function onlyEng(obj) { // 아이디 항목을 위한 함수
	var inText = obj.value;
	var ret;
	for (var i = 0; i < inText.length; i++) {
		ret = inText.charCodeAt(i);
		if ((ret > 122) || (ret < 48) || (ret > 57 && ret < 65) || (ret > 90 && ret < 97)) { // 한글,특수문자 허용않음
			alert("영문자와 숫자만을 입력하세요.\n한글과 특수문자는 안됩니다.");
			obj.value = "";
			obj.focus();
			return false;
		}
	}
}

function onlyKor(obj) { // 이름 항목을 위한 함수
	var inText = obj.value;
	var ret;
	for (var i = 0; i < inText.length; i++) {
		ret = inText.charCodeAt(i);
		if (!(ret > 122)) {
			alert("한글만 입력할 수 있습니다.");
			obj.value = "";
			obj.focus();
			return false;
		}
	}
}

function onlyNum(obj) { // 전화번호 항목을 위한 함수
	var inText = obj.value;
	var ret;
	for (var i = 0; i < inText.length; i++) {
		ret = inText.charCodeAt(i);
		if (!(ret > 47 && ret < 58)) {
			alert("숫자만 입력할 수 있습니다.");
			obj.value = "";
			obj.focus();
			return false;
		}
	}
}
 
function chk_email(obj) { // 이메일 항목을 위한 함수
	var inText = obj.value;
	var ret;
	for (var i = 0; i < inText.length; i++) {
		ret = inText.charCodeAt(i);
		if (ret > 122) {
			alert("한글은 입력할 수 없습니다.");
			obj.value = "";
			obj.focus();
			return false;
		}
	}
}

function chk_birth() { // 미성년자 가입을 막기 위한 함수
	var str = joinForm.mem_birth.value;
	var str1 = str.substring(0,4);
	var birth = Number(str1);

	var today = new Date();
	var yyyy = today.getFullYear();

	if(yyyy - birth < 17) {
		return false;
	}
	else
		return true;
}

function chk_input() {
	if(joinForm.mem_id.value == "") {
		alert("아이디를 입력하세요.");
		joinForm.mem_id.focus();
		return false;
	}

	else if(joinForm.mem_id.value.indexOf(" ") > -1 ) {
		alert("아이디를 공백없이 입력하세요.");
		joinForm.mem_id.focus();
		return false;
	}

	else if(joinForm.mem_id.value.length < 6 || joinForm.mem_id.value.length > 16) {
		alert("아이디는 6 ~ 16자 이내입니다."); 
		joinForm.mem_id.focus();
		return false;
	}

	else if(joinForm.mem_pwd.value == "") {
		alert("비밀번호를 입력하세요.");
		joinForm.mem_pwd.focus();
		return false;
	}

	else if(joinForm.mem_pwd_chk.value == "") {
		alert("비밀번호를 확인하세요.");
		joinForm.mem_pwd_chk.focus();
		return false;
	}

	else if(joinForm.mem_pwd.value.indexOf(" ") > -1 ) {
		alert("비밀번호를 공백없이 입력하세요.");
		joinForm.mem_pwd.focus();
		return false;
	}

	else if(joinForm.mem_pwd.value.length < 6 || joinForm.mem_pwd.value.length > 16) {
		alert("비밀번호는 6 ~ 16자 이내입니다."); 
		joinForm.mem_pwd.focus();
		return false;
	}

	else if(joinForm.mem_pwd.value != joinForm.mem_pwd_chk.value) {
		joinForm.mem_pwd.value="";
		joinForm.mem_pwd_chk.value="";
		alert("비밀번호가 다릅니다.");
		joinForm.mem_pwd.focus();
		return false;
	}

	else if(joinForm.mem_name.value == "") {
		alert("이름을 입력하세요.");
		joinForm.mem_name.focus();
		return false;
	}

	else if(joinForm.mem_name.value.indexOf(" ") > -1 ) {
		alert("이름을 공백없이 입력하세요.");
		joinForm.mem_name.focus();
		return false;
	}

	else if(joinForm.mem_birth.value == "") {
		alert("생년월일을 입력하세요.");
		joinForm.mem_birth.focus();
		return false;
	}

	else if(joinForm.mem_phone.value == "") {
		alert("휴대폰 번호를 입력하세요.");
		joinForm.mem_phone.focus();
		return false;
	}

	else if(joinForm.mem_phone.value.length < 10 || joinForm.mem_phone.value.length > 11) {
		alert("휴대폰 번호를 다시 확인하세요.");
		joinForm.mem_phone.focus();
		return false;
	}

	else if(joinForm.mem_phone.value.indexOf(".") > -1 || joinForm.mem_phone.value.indexOf("-") > -1 ) {
		alert("휴대폰 번호에 번호만 입력하세요.");
		joinForm.mem_phone.focus();
		return false;
	}

	else if(joinForm.mem_email.value == "") {
		alert("이메일을 입력하세요.");
		joinForm.mem_email.focus();
		return false;
	}

	else if(joinForm.mem_email.value.indexOf("@") < 1 || joinForm.mem_email.value.indexOf(".") < 3) {
		alert(message + "란의 이메일 주소가 올바르지 않습니다.\n아이디 / 비밀번호 찾기에 사용되는 필드이니 정확히 입력해주세요.");
		field_name.focus()
		return false;
	} 

	else if(joinForm.sido.value == "") {
		alert("시/도를 선택하세요.");
		joinForm.sido.focus();
		return false;
	}

	else if(joinForm.gugun.value == "") {
		alert("구/군을 선택하세요.");
		joinForm.gugun.focus();
		return false;
	}

	else if(joinForm.dong.value == "") {
		alert("동을 선택하세요.");
		joinForm.dong.focus();
		return false;
	}

	else if(check == 0) {
		alert("아이디 중복확인을 하세요.");
		joinForm.mem_id.focus();
		return false;
	}

	else if(dup == 1) {
		alert("중복된 아이디입니다.");
		joinForm.mem_id.focus();
		check=0;
		return false;
	}
	else {
		if(chk_birth() == false) {
			alert("죄송합니다. 17세 미만은 가입 불가입니다.");
			return false;
		}
		return true;
	}
}
</script>
</body>
</html>