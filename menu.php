<?php
//로그인 했을 경우, 메뉴

//만약 로그인이 안된 경우는 menu_nologin 페이지로 가게한다.
	session_start();
	if(!isset($_SESSION['id']) || !isset($_SESSION['passwd'])) {
		echo "<script>location.href='menu_nologin.php';</script>";
	}
?>
<html>
<head>
<title>메뉴</title>
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
<?php
include_once('dbConnect.php');

$que = "select * from image where mem_id='$_SESSION[id]'";
$res = mysql_query($que, $connect);
$data = mysql_fetch_array($res);


$sql = "select * from member where mem_id='$_SESSION[id]'";
$result = mysql_query($sql);
$area = mysql_fetch_array($result);

$sido = $area['mem_sido'];
$gugun = $area['mem_gugun'];


?>
<div align="center">
	<table align="center" width="100%" cellspacing="10px">
		<tr>
			<td width="55px" align="left">
			<img src="http://14.63.196.104<?php echo $data['img_path'];?>" width="55px" height="55px" id="myPhoto" onclick="location.href='myPage.php'">
			</td>
			<td align="left"><span style="font-weight:bold;font-size:18px;float:left;"><a href="myPage.php"><font color="grey">
			&nbsp;<?php echo($_SESSION['id']);?></font>
			</a></span></td>
			<td align="right">
			<img src="image/exit.png" width="30px" height="30px" id="exit" onclick="history.go(-1); return false;">
			</td>
		</tr>
	</table>
	<table align="center" width="100%" border="1px" cellpadding="20" style="border-collapse:collapse;border:1px solid #dddfe6;">
		<tr>
			<td align="center" colspan="3" width="50%" onclick="location.href='employerBoard.php?sido=<?php echo $sido; ?>&gugun=<?php echo $gugun; ?>&dong=전체'" style="border:1px solid #dddfe6;">
			<img src="image/board.png" width="40px" height="40px" id="board">
			<font size="3" color="gray"><br>대타를 부탁해!<br>게시판</font></td>
			<td align="center" colspan="3" width="50%" onclick="location.href='employeeBoard.php?sido=<?php echo $sido; ?>&gugun=<?php echo $gugun; ?>&dong=전체'" style="border:1px solid #dddfe6;">
			<img src="image/board.png" width="40px" height="40px" id="board">
			<font size="3" color="gray"><br>나에게 부탁해!<br>게시판</font></td>
		</tr>
		<tr>
		<!-- 밑의 메뉴들은 로그인했을 경우 지원되는 서비스 -->
			<td align="center" colspan="2" onclick="location.href='myPostList.php'" style="border:1px solid #dddfe6;"><img src="image/menu_matching.png" width="45px" id="board">
			<font size="3" color="gray"><br>매칭<br>현황</font></td>

			<td align="center" colspan="2" onclick="location.href='messageBox.php'" style="border:1px solid #dddfe6;"><img src="image/menu_msg.png" width="40px" id="board">
			<font size="3" color="gray"><br>쪽지<br>보관함</font></td>

			<td align="center" colspan="2" onclick="location.href='saveList.php'" style="border:1px solid #dddfe6;"><img src="image/saveList.png" width="40px" height="40px" id="board">
			<font size="3" color="gray"><br>대타<br>보관함</font></td>
		<!-- 여기까지 -->
		</tr>
		<tr>
			<td align='center' colspan="2" onclick="location.href='logout.php'" style="border:1px solid #dddfe6;"><img src='image/logout.png' width='40px' height='40px' id='board'>
			<font size="3" color='gray'><br>로그아웃</font></td>

			<td align="center" colspan="2" onclick="location.href='noticeBoard.php'" style="border:1px solid #dddfe6;"><img src="image/reportProblem.png" width="40px" height="40px" id="board">
			<font size="3" color="gray"><br>공지사항</font></td>

			<td align="center" colspan="2" onclick="location.href='developer.php'" style="border:1px solid #dddfe6;"><img src="image/developer.png" width="40px" height="40px" id="board">
			<font size="3" color="gray"><br>개발자<br>소개</font></td>
		</tr>
	</table>
</div>
<body>
</html>
