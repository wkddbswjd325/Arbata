<?php
/*
	회원 정보 수정하는 페이지
*/
include_once('dbConnect.php');
include('menuBar.php');
?>
<html>
<head>
<title>개인정보 수정</title>
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
<link rel="stylesheet" href="inputStyle.css" type="text/css"/>
</head>
<body>
<?php
//로그인 안됐을 경우..
if(!isset($_SESSION['id']) || !isset($_SESSION['passwd'])) {
?>
<table width="100%" cellpadding="5" cellspacing="0">
	<tr>
		<td>로그인이 필요한 기능 입니다.</td>
	</tr>	
</table>
<!--로그인이 되어있을 때 -->
<?php
} else {

$query = "select * from member where mem_id='$_SESSION[id]'"; 
$result = mysql_query($query, $connect);
$data = mysql_fetch_array($result);

$que = "select * from image where mem_id='$_SESSION[id]'"; 
$res = mysql_query($que, $connect);
$rows = mysql_fetch_array($res);
?>
<form name="EditInfoForm" enctype="multipart/form-data" method="post" action="editInfoDB.php" onSubmit="return chk_input2();">
<div align="center">
<table width="90%" border="0px" cellspacing="10px" cellpadding="0px">
	<tr><td><font style="color:#252c41;font-size:18px">기본 정보</font></td></tr>
</table>
<table bgcolor="#f4f5f9" width="90%" border="0px" cellspacing="10px" cellpadding="0px">
	<tr>
		<td width="25%"><font style="color:#252c41">&nbsp;아이디</font></td>
		<td><input type="text" placeholder="아이디" name="mem_id" id="EditId" style="border:0px;font-size:17px;width:100%" value="<?php echo($_SESSION['id']);?>" disabled></td>
	</tr>
	<tr>
		<td><font style="color:#252c41">&nbsp;이름</font></td>
		<td><input type="text" placeholder="이름" id="EditName" name="mem_name"style="border:0px;font-size:17px;width:100%" value="<?php echo $data['mem_name'];?>" disabled></td>
	</tr>
	<tr>
		<td><font style="color:#252c41">&nbsp;생년월일</font></td>
		<td><input type="date" id="birth" name="mem_birth" value="<?php echo $data['mem_birth'];?>" disabled></td>
	</tr>
	<tr>
		<td>&nbsp;성별</td>
		<td>
		<?php
		if($data['mem_sex'] == "m") {
		?>
			<input type="radio" name="mem_sex" value="m" checked disabled> 남자
			&nbsp;
			<input type="radio" name="mem_sex" value="f" disabled> 여자
		<?php
		}
		else {
		?>
			<input type="radio" name="mem_sex" value="m" disabled> 남자
			&nbsp;
			<input type="radio" name="mem_sex" value="f" checked disabled> 여자
		<?php
		}
		?>
		</td>
	</tr>
</table>
<br>
<table width="90%" border="0px" cellspacing="10px" cellpadding="0px">
	<tr><td><font style="color:#252c41;font-size:18px">수정 가능한 정보</font></td></tr>
</table>
<table bgcolor="#f4f5f9" width="90%" border="0px" cellspacing="10px" cellpadding="0px">
	<tr>
		<td><font style="color:#252c41">&nbsp;패스워드</font></td>
		<td><input type="password" placeholder="패스워드 (16자 이내)" name="mem_pwd" id="EditPwd" style="border:0px;font-size:17px;width:100%" value="<?php echo($_SESSION['passwd']);?>" maxlength="16"></td>
	</tr>
	<tr>
		<td><font style="color:#252c41">&nbsp;확인</font></td>
		<td><input type="password" placeholder="패스워드 (16자 이내)" id="EditPwd" name="mem_pwd_chk" style="border:0px;font-size:17px;width:100%" value="<?php echo($_SESSION['passwd']);?>" maxlength="16"></td>
	</tr>
	<tr>
		<td><font style="color:#252c41">&nbsp;휴대폰</font></td>
		<td>
			<input type="tel" id="phone" name="mem_phone" required style="border:0px;font-size:17px;width:100%" placeholder="휴대폰 번호를 - 없이 입력하세요"  value="<?php echo $data['mem_phone'];?>" onKeyUp="onlyNum(mem_phone)" maxlength="11"> 
		</td>
	</tr>
	<tr>
		<td><font style="color:#252c41">&nbsp;주소</font></td>
		<td align="right"><input type="button" id="editArea" name="editArea" value="변경" onClick='edit_area();' style="padding:3px 1px 3px 1px;margin-left:1px;width:20%"></td>
	</tr>
	<tr>
		<td colspan=2>
		<select name="sido" id="sido" onChange="sidoChange();">
			<option value="<?php echo $data['mem_sido'];?>" readonly><?php echo $data['mem_sido'];?></option>
		</select>
		<select name="gugun" id="gugun" onChange="gugunChange()" >
			<option value="<?php echo $data['mem_gugun'];?>" readonly><?php echo $data['mem_gugun'];?></option>
		</select>
		<select name="dong" id="dong" >
			<option value="<?php echo $data['mem_dong'];?>" readonly><?php echo $data['mem_dong'];?></option>
		</select>
		</td>
	</tr>
	<tr>
		<td><font style="color:#252c41">&nbsp;이메일</font></td>
		<td><input type="text" id="mem_email" name="mem_email" style="border:0px;font-size:17px;width:100%" value="<?php echo $data['mem_email']; ?>"  onKeyUp="chk_email(mem_email)"></td>
	</tr>
	<tr>
		<td>&nbsp;<font style="color:#252c41">사진 등록/수정</font></td>
		<td align="right">
			<font style="font-size:10pt;color:#252c41">사진을 삭제하려면 체크</font>
			<input type="checkbox" name="img_del" id="img_del" value="del" style="vertical-align:middle;">
		</td>
	</tr>
	<tr>
		<td colspan=2>
			<img src="http://14.63.196.104<?php echo $rows['img_path'];?>" width="115px"><br>
			<input type="hidden" name="MAX_FILE_SIZE" value="1000000" />
			<input type="hidden" name="mode" value="upload" /><br>
			<input type="file" id="editPic" name="upload"><br>
		</td>
	</tr>
</table>
</div>
<table width="90%" align="center" style="margin-bottom:10px;">
	<tr>
		<td align="right"><a href='deleteID.php' style='float:right;font-size:14px;'>회원탈퇴 하기</a></td>
	</tr>
</table>
<table align="center" border="0px" cellspacing="0px" cellpadding="0px" width="70%">
   <tr>
      <td width="30%" align="center"><input type="submit" id="login" value="수정"></td>
      <td width="10%">&nbsp;</td>
      <td width="30%" align="center"><input type="reset" id="join" value="취소" onclick="history.go(-1); return false;"></td>
   </tr>
</table>
</form>
<script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script><!-- 최신버전 제이쿼리 -->
<script type="text/javascript">

// 주소 변경을 위한 함수
function edit_area() {
	$.ajax({
		type: "POST",
		url: "editInfo_area.php", // 이 페이지로 넘어간다.
		success: function(data) {
			$("#sido").html(data);
		}
	});
	$("#gugun").html("<option value=''>구/군</option>");
	$("#dong").html("<option value=''>동/면/읍</option>");
}

// 시/도 변경하면 구/군이 그에 따라 변화
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
// 구/군 변경하면 동/면/읍이 그에 따라 변화
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

function onlyEng(obj) {
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

function onlyNum(obj) {
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

function chk_email(obj) {
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

function chk_input2() {
	if(EditInfoForm.mem_pwd.value == "") {
		alert("비밀번호를 입력하세요.");
		EditInfoForm.mem_pwd.focus();
		return false;
	}

	else if(EditInfoForm.mem_pwd_chk.value == "") {
		alert("비밀번호를 확인하세요.");
		EditInfoForm.mem_pwd_chk.focus();
		return false;
	}

	else if(EditInfoForm.mem_pwd.value.indexOf(" ") > -1 ) {
		alert("비밀번호를 공백없이 입력하세요.");
		EditInfoForm.mem_pwd.focus();
		return false;
	}

	else if(EditInfoForm.mem_pwd.value.length < 6 || EditInfoForm.mem_pwd.value.length > 16) {
		alert("비밀번호는 6 ~ 16자 이내입니다."); 
		EditInfoForm.mem_pwd.focus();
		return false;
	}

	else if(EditInfoForm.mem_phone.value == "") {
		alert("휴대폰 번호를 입력하세요.");
		EditInfoForm.mem_phone.focus();
		return false;
	}

	else if(EditInfoForm.mem_phone.value.length < 10 || EditInfoForm.mem_phone.value.length > 11) {
		alert("휴대폰 번호를 다시 확인하세요.");
		EditInfoForm.mem_phone.focus();
		return false;
	}

	else if(EditInfoForm.mem_phone.value.indexOf(".") > -1 || EditInfoForm.mem_phone.value.indexOf("-") > -1 ) {
		alert("휴대폰 번호에 번호만 입력하세요.");
		EditInfoForm.mem_phone.focus();
		return false;
	}

	else if(EditInfoForm.mem_email.value == "") {
		alert("이메일을 입력하세요.");
		EditInfoForm.mem_email.focus();
		return false;
	}

	else if(EditInfoForm.mem_email.value.indexOf("@") < 1 || EditInfoForm.mem_email.value.indexOf(".") < 3) {
		alert(message + "란의 이메일 주소가 올바르지 않습니다.");
		field_name.focus()
		return false;
	} 

	else if(EditInfoForm.sido.value == "") {
		alert("시/도를 선택하세요.");
		EditInfoForm.sido.focus();
		return false;
	}

	else if(EditInfoForm.gugun.value == "") {
		alert("구/군을 선택하세요.");
		EditInfoForm.gugun.focus();
		return false;
	}

	else if(EditInfoForm.dong.value == "") {
		alert("동을 선택하세요.");
		EditInfoForm.dong.focus();
		return false;
	}

	else if(EditInfoForm.mem_pwd.value != EditInfoForm.mem_pwd_chk.value) {
		EditInfoForm.mem_pwd.value="";
		EditInfoForm.mem_pwd_chk.value="";
		alert("비밀번호가 다릅니다.");
		EditInfoForm.mem_pwd.focus();
		return false;
	}
	
	else {
		return true;
	}
}
</script>
</body>
</html>
<?php
	mysql_close($connect);
}
?>