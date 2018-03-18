<!-- 각 페이지마다 고정되는 맨 위의 메뉴바-->
<link rel="stylesheet" href="header.css" type="text/css"/>
<div style="padding-top:40px;">
</div>

<header class="headerFix">
<div class="hdr">
	<center>
	<span style="float:left;margin:5px 5px 5px 5px;width:30px;"><img height="30px" src="image/menu_home.png" alt="home" onclick="location.href='index.php'"></span>
<?php
session_start();
$file = basename($_SERVER["SCRIPT_NAME"]);

if($file == "employerBoard.php") {	// employerBoard.php 에서는 메뉴바 가운데의 로고가 다르게 바뀐다
?>
	<span style="margin:5px 5px 5px 5px;vertical-align:middle;"><img height="40px" src="image/menu_index1.png"></span>
<?php
} else if($file == "employeeBoard.php") {	// employeeBoard.php 에서는 메뉴바 가운데의 로고가 다르게 바뀐다
?>
	<span style="margin:5px 5px 5px 5px;vertical-align:middle;"><img height="40px" src="image/menu_index2.png"></span>
<?php
} else {
?>
	<span style="margin:5px 5px 5px 5px;vertical-align:middle;"><img height="40px" src="image/logo.png" alt="logo"></span>
<?php
}
//	로그인 했을 경우와 아닌 경우, 메뉴가 다르다.
if(!isset($_SESSION['id']) || !isset($_SESSION['passwd'])) {
?>
	<span style="float:right;margin:5px 5px 5px 5px;width:30px;"><img height="30px" src="image/menu.png" alt="menu"  onclick="location.href='menu_nologin.php'"></span>
<?php
}
else {
?>	
	<span style="float:right;margin:5px 5px 5px 5px;width:30px;"><img height="30px" src="image/menu.png" alt="menu"  onclick="location.href='menu.php'"></span>	
<?php
}
?>
	</center>
</div>
</header>