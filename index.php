<!--
	프로그램 시작 시 첫화면
-->
<html>
<head>
<title>알바타</title>
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
<?php
include('menuBar.php');
?>
<body>
<div align="center">
	<img src="image/index.png" width="100%" style="margin-top:50px;margin-bottom:20px;max-width:600px"><br>
</div>

<div align="center">
	<img src="image/in.png" width="50px" style="margin-top:10px;margin-bottom:20px" onClick="location.href='mainPage.php'">
</div>
</body>
</html>