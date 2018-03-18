<?php
/*
	employeeRead에서 '지원 신청'하면 보이는 페이지

	지원자의 급여와 알바 장소와 세부사항을 받음
*/
include_once('dbConnect.php');
?>
<html>
<head>
<title>지원서 작성</title>
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
<link rel="stylesheet" href="matchBoardStyle.css" type="text/css" />
</head>
<body>
<div align="center">
<?php
include('menuBar.php');
?>
<!--로그인이 되지 않았을 때 -->
<?php
	if(!isset($_SESSION['id']) || !isset($_SESSION['passwd'])) {
?>
<table width="100%" cellpadding="5" cellspacing="0">
	<tr>
		<td>로그인이 필요한 기능 입니다.</td>
	</tr>	
</table>

<!--로그인이 되어있을 때 -->
<?php
}
	else {
?>
<?php

	$ee_no = $_GET['ee_no'];
	$sql = "select ee_title, ee_writer from employee where ee_no='$ee_no'";
	$result = mysql_query($sql);
	$data = mysql_fetch_array($result); 
	$ee_title = $data['ee_title'];
	$ee_writer = $data['ee_writer'];

	// 이미 지원했는지 안했는지 확인하기 위해서 지원자의 아이디로 확인함
	$sql2 = "select count(*) from apply where a_post_no='$ee_no' and a_chk='1' and a_applier='$_SESSION[id]'"; 
	$result2 = mysql_query($sql2);
	$check = mysql_fetch_array($result2);
	
	// 이미 지원했다면
	if($check['count(*)'] != 0) {
		echo "<script>alert('이미 신청을 한 게시물입니다.');location.href='employeeRead.php?ee_no=".$ee_no."';</script>";
		die;
	}		
?>
<div id="divBoard">
<form name="ee_applyForm" method="POST" action="applyDB.php" onSubmit="return chk_apply_ee();">
<input type="hidden" name="ee_no" value="<?php echo $ee_no; ?>">
<input type="hidden" name="apply_chk" value="1">
<input type="hidden" name="ee_writer" value="<?php echo $ee_writer; ?>">

<div id="ap_info">
	<h5 id="ap_info_title">지원글 제목</h5>
	<div id="ap_info_content"><?php echo $ee_title; ?></div>
</div>

<div id="ap_info">
	<h5 id="ap_info_title">지원글 작성자</h5>
	<div id="ap_info_content"><?php echo $ee_writer; ?></div>
</div>

<div id="ap_info">
	<h5 id="ap_info_title">신청서 제목</h5>
	<div id="ap_info_content">
		<input type="text" name="apply_title" placeholder=" 대타 제안서 제목" id="inputTitle" style="border:0px;font-size:16px;width:100%;height:25px;
		white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
	</div>
</div>

<div id="ap_info">
	<h5 id="ap_info_title">시급</h5>
	<div id="ap_info_content">
		<input type="text" name="apply_etc" placeholder=" 대타 시 시급(숫자만 입력)" id="inputEtc" onKeyUp="onlyNum(apply_etc)" style="border:0px;font-size:16px;width:100%;height:25px;
		white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
	</div>
</div>

<div id="ap_info">
	<h5 id="ap_info_title">대타 장소</h5>
	<div id="ap_info_content">
		<select name='ap_sido' id='ap_sido' onChange="sidoChange();" style="font-size:14px;border:0px">
			<option value=''>시/도</option>
		<?php
		$sql = "select distinct(sido) from area order by priority";
		$result = mysql_query($sql);

		while($row = mysql_fetch_array($result)) {
				echo "<option value='".$row['sido']."'>".$row['sido']."</option>"; 
		}
		?>
		</select><br>
		<select name='ap_gugun' id='ap_gugun' onChange="gugunChange();" style="font-size:14px;border:0px;margin-top:5px;" >
			<option value=''>구/군</option>
		</select><br>
		<select name='ap_dong' id='ap_dong' style="font-size:14px;border:0px;margin-top:5px;"">
			<option value=''>동/면/읍</option>
		</select><br>
		<input type="text" name="apply_area" placeholder=" 상세 주소" id="inputArea" style="margin-top:5px;border:0px;font-size:16px;width:100%;height:25px;
		white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
	</div>
</div>

<div id="ap_info">
	<h5 id="ap_info_title" style="position:relative;margin-bottom:10px;">내용</h5>
	<div style="position:relative;">
	<textarea name="apply_content" placeholder="대타 시 업무 내용 / 주의사항 등 필요한 안내사항을 입력 해 주세요." id="msgTextarea" style="border:0px;resize:none;font-size:16px;width:100%;"rows="12"></textarea>
	</div>
</div>

<div id="ap_info" align="center">
	<input type="submit" value="매칭신청" name="matchBtn" id="matchBtn" style="width:40%">
	<input type="button" value="취소" name="rstBtn" id="rstBtn" onclick="history.go(-1); return false;" style="width:40%">
</div>
</form>
</div>

<script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script><!-- 최신버전 제이쿼리 -->
<script type="text/javascript">

// 다른 페이지의 지역 변경과 동일

function sidoChange() {
	var sido = $("select[name='ap_sido'] option:selected").val();
	$.ajax({
		type: "POST",
		url: "areaChange.php",
		data: "sido=" + sido,
		success: function(data) {
			$("#ap_gugun").html(data);
			$("#ap_dong").html("<option value=''>동/면/읍</option>");
		}
	});
}
function gugunChange() {
	var sido = $("select[name='ap_sido'] option:selected").val();
	var gugun = $("select[name='ap_gugun'] option:selected").val();
	$.ajax({
		type: "POST",
		url: "areaChange.php",
		data: "sido=" + sido + "&gugun=" + gugun,
		success: function(data) {
			$("#ap_dong").html(data);
		}
	});
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

function chk_apply_ee() {
	 if(ee_applyForm.apply_title.value == "") {
		alert("제안서의 제목을 입력하세요.");
		ee_applyForm.apply_title.focus();
		return false;
	}

	else if(ee_applyForm.apply_etc.value == "") {
		alert("제안하는 대타의 시급을 입력하세요.");
		ee_applyForm.apply_etc.focus();
		return false;
	}

	else if(ee_applyForm.ap_sido.value == "") {
		alert("제안하는 대타의 지역(시/도)을 입력하세요.");
		ee_applyForm.ap_sido.focus();
		return false;
	}
	
	else if(ee_applyForm.ap_gugun.value == "") {
		alert("제안하는 대타의 지역(구/군)을 입력하세요.");
		ee_applyForm.ap_gugun.focus();
		return false;
	}

	else if(ee_applyForm.ap_dong.value == "") {
		alert("제안하는 대타의 지역(동)을 입력하세요.");
		ee_applyForm.ap_dong.focus();
		return false;
	}

	else if(ee_applyForm.apply_area.value == "") {
		alert("제안하는 대타의 지역을 입력하세요.");
		ee_applyForm.apply_area.focus();
		return false;
	}

	else if(ee_applyForm.apply_content.value == "") {
		alert("제안서의 내용을 입력해 주세요.");
		ee_applyForm.apply_content.focus();
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
}
	mysql_close($connect);
?>