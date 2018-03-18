<?php 
/*
	서로 매칭된 사용자끼리 서로에 대한 평가를 하는 평가 작성 페이지
	(매칭 후 급여는 잘 지급되었는지, 대타가 제 역할을 잘 해줬는지 등에 대한 평가)
*/
include_once('dbConnect.php'); 
?>
<html>
<head>
<title>평가 작성</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="user-scalable=no, initial-scale=1.0, maximum-scale=1.0, width=device-width" />
<link rel="apple-touch-icon" href="image/apple.jpg" />
<link rel="stylesheet"  href="star/star-rating2.css" rel="stylesheet" type="text/css"/>
<link rel="stylesheet" href="star/star-rating.css" media="all" rel="stylesheet" type="text/css"/>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="star/star-rating.js" type="text/javascript"></script>

<script language="javascript" type="text/javascript">
window.addEventListener("load", function() {
	setTimeout(loaded, 100);
}, false);

function loaded() {
	window.scrollTo(0, 1);
}
</script>
</head>
<body>
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
?>
<?php
$r_recvId = $_GET['recvId'];

if($_GET['role'] == "arbata")
	$role = "알바타";
else
	$role = "고용인";
?>
<center>
<br><br>
<font style="font-size:22px"><b>대타 후기를 남겨주세요!</b></font>
<br><br><br>
<form action="reviewDB.php" method="post" name="reviewForm" onSubmit="return review_chk();">
	<input type="hidden" name="r_recvId" value="<?php echo $r_recvId; ?>">
	<input type="hidden" name="r_radio" value="<?php echo $_GET['role']; ?>">
	<font style="font-size:16px">
	<b><?php echo $role." (".$r_recvId.")"; ?></b>님에 대한 별점 및 후기
	</font>
	<br><br><br>
    <input name="star" id="input-21d" value="5" type="number" class="rating" min=0 max=5 step=0.5 data-size="sm">
	<br>
    <textarea rows="7" name="r_content" placeholder="후기를 남겨주세요"style="resize:none;width:70%;font-size:17px;" ></textarea>
<hr>
<input type="submit" class="btn btn-primary" style="background-color:#f1404b;border:0px" value="올리기">
</form>
</center>

<script>
function review_chk() {
	if(reviewForm.r_content.value=="") {
		alert("내용을 작성해 주세요");
		reviewForm.r_content.focus();
		return false;
	}
	return true;
}
</script>
<?php
} ?>
</body>
</html>

<?php
mysql_close($connect);
?>
