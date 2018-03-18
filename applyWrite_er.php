<?php
/*
	employerRead에서 '지원 신청'하면 보이는 페이지

	지원자의 알바 경력과 자기 소개를 받음
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
<!--로그인이 되지 않았을 때 -->
<?php
include('menuBar.php');

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
	$er_no = $_GET['er_no'];
	$sql = "select er_title, er_writer from employer where er_no='$er_no'";
	$result = mysql_query($sql);
	$data = mysql_fetch_array($result); 
	$er_title=$data['er_title'];
	$er_writer=$data['er_writer'];

	// 이미 지원했는지 안했는지 확인하기 위해서 지원자의 아이디로 확인함
	$sql2 = "select count(*) from apply where a_post_no='$er_no' and a_chk='0' and a_applier='$_SESSION[id]'"; 
	$result2 = mysql_query($sql2);
	$check = mysql_fetch_array($result2);
	
	// 중복 지원이라면
	if($check['count(*)'] != 0) {
		echo "<script>alert('이미 신청을 한 게시물입니다.');location.href='employerRead.php?er_no=".$er_no."';</script>";
		die;
	}	
?>

<div id="divBoard">
<form name="er_applyForm" method="POST" action="applyDB.php" onSubmit="return chk_apply_er();">
<input type="hidden" name="er_no" value="<?php echo $er_no; ?>">
<input type="hidden" name="apply_chk" value="0">
<input type="hidden" name="er_writer" value="<?php echo $er_writer; ?>">

<div id="ap_info">
	<h5 id="ap_info_title">지원글 제목</h5>
	<div id="ap_info_content"><?php echo $er_title; ?></div>
</div>

<div id="ap_info">
	<h5 id="ap_info_title">지원글 작성자</h5>
	<div id="ap_info_content"><?php echo $er_writer; ?></div>
</div>

<div id="ap_info">
	<h5 id="ap_info_title">신청서 제목</h5>
	<div id="ap_info_content">
		<input type="text" name="apply_title" placeholder="대타 지원서 제목" id="inputTitle" style="border:0px;font-size:16px;width:100%;height:25px;
		white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
	</div>
</div>

<div id="ap_info">
	<h5 id="ap_info_title">알바 경력</h5>
	<div id="ap_info_content">
		<textarea rows="3" name="apply_etc" placeholder="경력이 없다면 '없다'고 작성해주세요." style="border:0px;resize:none;font-size:16px;width:100%;"></textarea>
	</div>
</div>

<div id="ap_info">
	<h5 id="ap_info_title" style="position:relative;margin-bottom:10px;">내용</h5>
	<div style="position:relative;">
	<textarea name="apply_content" placeholder="지원동기/ 자기소개 등 알바 지원서의 내용을 입력해 주세요." id="msgTextarea" style="border:0px;resize:none;font-size:16px;width:100%;"rows="12"></textarea>
	</div>
</div>

<div id="ap_info" align="center">
	<input type="submit" value="매칭신청" name="matchBtn" id="matchBtn" style="width:40%">
	<input type="button" value="취소" name="rstBtn" id="rstBtn" onclick="history.go(-1); return false;" style="width:40%">
</div>
</form>
</div>

<script type="text/javascript">

function chk_apply_er() {
	 if(er_applyForm.apply_title.value == "") {
		alert("신청서의 제목을 입력하세요.");
		er_applyForm.apply_title.focus();
		return false;
	}

	else if(er_applyForm.apply_etc.value == "") {
		alert("경력이 없는 경우 '없음' 이라고 입력해주세요.");
		er_applyForm.apply_etc.focus();
		return false;
	}

	else if(er_applyForm.apply_content.value == "") {
		alert("신청서의 내용을 입력하세요.");
		er_applyForm.apply_content.focus();
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