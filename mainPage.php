<?php
/*
	index.php 에서 다음으로 넘어가면 나오는 페이지
	게시판의 기능과 설명을 간단히 설명해놓은 페이지
	버튼을 누르면 해당 게시판으로 넘어간다.
*/
include('menuBar.php');
include_once('dbConnect.php');
?>
<html lang="ko">
<head>
<meta charset="UTF-8">
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
</head>
<body>
<?php
if(isset($_SESSION['id']) ) {
	$sql = "select * from member where mem_id='$_SESSION[id]'";
	$result = mysql_query($sql);
	$data = mysql_fetch_array($result);

	$sido = $data['mem_sido'];
	$gugun = $data['mem_gugun'];
}
?>
<div align="center" style="margin-top:30px;">
	<img src="image/explain.png" width="80%" style="max-width:500px;">
</div>
<div align="center" style="margin-top:30px;margin-bottom:50px;">
	<?php
	if( isset($_SESSION['id']) ) { // 로그인을 했을 경우
		echo "<img width='80%' src='image/indexbutton1.png' alt='1' onclick='location.href=\"employerBoard.php?sido=".$sido."&gugun=".$gugun."&dong=전체\"' style='border-radius:10px;max-width:500px;margin-bottom:10px;'/><br>";
		// 자신이 주소로 설정해놓은 시/도, 구/군을 기본 지역으로 설정해서 employerBoard로 간다.
	}
	else { // 로그인 안했을 경우
		echo "<img width='80%' src='image/indexbutton1.png' alt='1' onclick='location.href=\"employerBoard.php\"' style='border-radius:10px;max-width:500px;margin-bottom:10px;'/><br>";
		// 주소가 설정되지 않음(전국으로 설정)
	}
	?>
	<img src="image/index2.png" width="80%" style="max-width:500px;">
</div>

<div align="center" style="margin-top:50px;">
	<?php
	if( isset($_SESSION['id']) ) { // 로그인을 했을 경우
		echo "<img width='80%' src='image/indexbutton2.png' alt='2' onclick='location.href=\"employeeBoard.php?sido=".$sido."&gugun=".$gugun."&dong=전체\"' style='border-radius:10px;max-width:500px;margin-bottom:10px;'/><br>";
		// 자신이 주소로 설정해놓은 시/도, 구/군을 기본 지역으로 설정해서 employeeBoard로 간다.
	}
	else { // 로그인 안했을 경우
		echo "<img width='80%' src='image/indexbutton2.png' alt='2' onclick='location.href=\"employeeBoard.php\"' style='border-radius:10px;max-width:500px;margin-bottom:10px;'/><br>";
		// 주소가 설정되지 않음(전국으로 설정)
	}
	?>
	<img src="image/index3.png" width="80%" style="max-width:500px;">
</div>

<div align="center">
	<img src="image/out.png" width="50px" style="margin-top:50px;margin-bottom:20px" onClick="location.href='index.php'">
</div>

</body>
</html>
<?php
mysql_close($connect);
?>