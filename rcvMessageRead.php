<?php
/*
	messageBox.php 에서 메세지를 클릭하면 해당 메시지 번호를
	받아와 내용을 나타내는 페이지

	발신 메세지와 수신 메세지의 내용을 보여주는 페이지이다.
*/
include_once('dbConnect.php');
?>
<html>
<head>
<title>쪽지 읽기</title>
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
	$msg_no = $_GET['msg_no'];
	$msg_chk = $_GET['msg_chk'];

	$sql = "select msg_title, msg_sendId, msg_rcvId, msg_date, msg_time, msg_content, msg_chk
			from message 
			where msg_no=$msg_no and msg_chk=$msg_chk" ;		
	$result = mysql_query($sql);
	$row = mysql_fetch_array($result);
?>
<form name="rcvMessageRead" method="POST" action="messageRmvDB.php"> 
<br>
	<table align="center" border="0px" cellspacing="0px" cellpadding="0px" width="90%">
		<tr>
			<td width="80px">제목</td>
			<td align="left"><input type="text" id="msgTitle" value="<?php echo $row['msg_title']; ?>" readonly style="margin-bottom:5px;margin-top:5px;"></td>
		</tr>
		<tr>
			<td width="80px"><?php 
				// 수신 메세지일 경우
				if($row['msg_chk']==0) { 
					echo "발신인";
				} else { // 발신 메세지일 경우
					echo "수신인";} 
			?></td>
			<td align="left" colspan="2"><input type="text" id="msgSender" 
			<?php if($row['msg_chk']==0) { //수신 메세지일 경우 
							echo "onclick=location.href='memPage.php?rcvId=".$row['msg_sendId']."' value=".$row['msg_sendId'];} 
						 else { // 발신 메세지일 경우
							echo "onclick=location.href='memPage.php?rcvId=".$row['msg_rcvId']."' value=".$row['msg_rcvId'];} ?>
			readonly style="margin-bottom:5px;margin-top:5px;">
			</td>
		</tr>
		<tr>
			<td width="80px"><?php 
				// 수신 메세지일 경우
				if($row['msg_chk']==0) {
					echo "발신날짜";
				} else { // 발신 메세지일 경우
					echo "수신날짜";} 
			?></td>
			<td align="left" colspan="2"><input type="text" id="msgDate" value="<?php echo $row['msg_date']; ?> (<?php echo $row['msg_time']; ?>)" readonly style="margin-bottom:5px;margin-top:5px;"></td>
		</tr>
		<tr>
			<td colspan="3">
			<br>내용
			<textarea rows="15" style="width:100%;resize:none;margin-top:5px;font-size:15px;" readonly><?php echo $row['msg_content']; ?></textarea></td>
		</tr>
	</table>
	<table align="center" border="0px" cellspacing="0px" cellpadding="0px" width="70%">
		<tr>
			<br>
			<?php 
				// 수신 메세지
				if($row['msg_chk']=='0') {
						echo "<input type='hidden' name='msg_rcvList[0]' value=".$msg_no.">";
						echo "<td width='30%' align='center'><input type='button' id='rcv' value='답장하기' 
						onclick=location.href='message.php?msg_sendId=".$row['msg_sendId']."'></td>";
						echo "<td width='10%'>&nbsp;</td>";
						echo "<td width='30%' align='center'><input type='submit' id='remove' value='삭제하기'></td>";} 
				// 발신 메세지
				else 
					{	
						echo "<input type='hidden' name='msg_sendList[0]' value=".$msg_no.">";
						echo "<td width='20%'>&nbsp;</td>";
						echo "<td width='30%' align='center'><input type='submit' id='remove' value='삭제하기'></td>";
						echo "<td width='20%'>&nbsp;</td>";}
			?>
		</tr>
	</table>
</form>
<?php
}
mysql_close($connect);
?>
</div>
<body>
</html>
