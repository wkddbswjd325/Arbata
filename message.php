<?php
/*
	메세지를 보낼 때 사용되는 페이지
	submit하면 messageDB.php 로 넘어가서 DB에 데이터가 저장된다.
*/
include_once('dbConnect.php');
?>
<html>
<head>
<title>쪽지 보내기</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="user-scalable=no, initial-scale=1.0, maximum-scale=1.0, width=device-width" />
<link rel="apple-touch-icon" href="image/apple.jpg" />

<!-- Tell the browser where to find the manifest for your web app -->
<link rel="manifest" href="manifest.json" />


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
	// 기존에 메세지 보낼 사람을 받아올 경우
	// (상대방의 memPage 를 보고 쪽지를 보내는 경우 또는 게시물에서 바로 쪽지를 보내는 경우 아이디를 받아온다)
	if( isset($_GET['msg_sendId']) ){ 
		$msg_rcvId="value=".$_GET['msg_sendId'];
	}
	else{ //직접 messageBox.php 에서 '쪽지 보내기' 버튼을 누르는 경우
		$msg_rcvId="placeholder='받는사람'";
	}	
?>
<form name="messageForm" method="POST" action="messageDB.php" onSubmit="return chk_input();">
<br>
	<table align="center" border="0px" cellspacing="0px" cellpadding="0px" width="100%">
		<tr>
			<td align="center" colspan="3"><input type="text" name="msg_rcvId" <?php echo $msg_rcvId ?> id="inputRcv" style="margin-bottom:10px;"></td>
		</tr>
		<tr>
			<td align="center" colspan="3"><input type="text" name="msg_title" placeholder="제목" id="inputTitle" style="margin-bottom:10px;"></td>
		</tr>
		<tr>
			<td align="center" colspan="3"><textarea rows="15" name="msg_content" placeholder="쪽지의 내용을 입력하세요." id="msgTextarea" style="resize:none"></textarea></td>
		</tr>
	</table>
	<table align="center" border="0px" cellspacing="0px" cellpadding="0px" width="70%">
		<tr>
			<br>
			<td width="30%" align="center"><input type="submit" id="send" value="보내기" ></td>
			<td width="10%">&nbsp;</td>
			<td width="30%" align="center"><input type="reset" id="reset" value="취소" onclick="history.go(-1); return false;"></td>
		</tr>
	</table>
</form>
<?php
}
mysql_close($connect);
?>
</div>

<script type="text/javascript">

function chk_input() {
	if(messageForm.msg_rcvId.value == "") {
		alert("받는 사람의 아이디를 입력하세요.");
		messageForm.msg_rcvId.focus();
		return false;
	}

	else if(messageForm.msg_title.value == "") {
		alert("제목을 입력하세요.");
		messageForm.msg_title.focus();
		return false;
	}

	else if(messageForm.msg_content.value == "") {
		alert("내용을 입력하세요.");
		messageForm.msg_content.focus();
		return false;
	}
	
	else {
		return true;
	}
}
</script>
<body>
</html>

