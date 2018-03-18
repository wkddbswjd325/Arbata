<?php 
/*
	지역 선택하는 form
	게시판에서 지역 변경을 누르면 보이는 페이지
*/
include_once('dbConnect.php'); 
?>
<html>
<head>
<title>지역 선택</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="user-scalable=no, initial-scale=1.0, maximum-scale=1.0, width=device-width" />
<link rel="apple-touch-icon" href="image/apple.jpg" />

<script language="javascript" type="text/javascript">
window.addEventListener("load", function() {
	setTimeout(loaded, 100);
}, false);

function loaded() {
	window.scrollTo(0, 1);
}
</script>
<link rel="stylesheet" href="inputStyle.css" type="text/css"/>
</head>
<body >
<?php
include('menuBar.php');
$which = $_GET['which'];
?>
<br><br><br>
<div align="center">
<h2><font style="color:#f1404b;font-size:30px;">지역 </font><font style="color:#252c41;font-size:30px;">선택</font></h2>
<br><br>
<?php
if($which == "employer")
	echo "<form action='employerBoard.php' name='selectArea' id='selectArea' method='get' onSubmit='return chk_area();'>";
else
	echo "<form action='employeeBoard.php' name='selectArea' id='selectArea' method='get' onSubmit='return chk_area();'>";
?>
<font style="color:#252c41;font-size:17px">시/도 선택&nbsp;</font>
<select name="sido" onChange="sidoChange();" style="font-size:14px;">
	<option value=''>시/도</option>
	<option value='전국'>전국</option>
	<?php
	$sql = "select distinct(sido) from area order by priority";
	$result = mysql_query($sql);

	while($row = mysql_fetch_array($result)) {
			echo "<option value='".$row['sido']."'>".$row['sido']."</option>"; 
	}
	
	?>
</select>
<br><br><br>
<font style="color:#252c41;font-size:17px">구/군 선택&nbsp;</font>
<select name="gugun" id="gugun" onChange="gugunChange()" style="font-size:14px;">
	<option value=''>구/군</option>
</select>
<br><br><br>
<font style="color:#252c41;font-size:17px">동/면/읍 선택&nbsp;</font>
<select name="dong" id="dong" style="font-size:14px;">
	<option value=''>동/면/읍</option>
</select>
<br><br><br><br>
<input type="submit" id="areaSelect" value="확인" style="width:20%;font-size:17px">
</form>
</div>
<script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script><!-- 최신버전 제이쿼리 -->
<script type="text/javascript">

// 시/도 변경하면 구/군이 그에 따라 변화함
function sidoChange() {
	var sido = $("select[name='sido'] option:selected").val();
	if(sido == "전국") {
		$("select[name='gugun']").attr("disabled",true);
		$("select[name='dong']").attr("disabled",true);
	}
	else {
		$("select[name='gugun']").attr("disabled",false);
		$("select[name='dong']").attr("disabled",false);
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
}

// 구/군 변경하면 동/면/읍이 그에 따라 변화함
function gugunChange() {
	var sido = $("select[name='sido'] option:selected").val();
	var gugun = $("select[name='gugun'] option:selected").val();
	$.ajax({
		type: "POST",
		url: "areaChange.php",
		data: "sido=" + sido + "&gugun=" + gugun + "&all=ok",
		success: function(data) {
			$("#dong").html(data);
		}
	});
}
function chk_area() {
	if(selectArea.sido.value=="") {
	  alert("시/도를 선택하세요.");
	  return false;
	}
	
	else if(selectArea.sido.value=="전국") {
		return true;
	}

	else if(selectArea.gugun.value=="") {
	  alert("구/군을 선택하세요.");
	  return false;
	}

	else if(selectArea.dong.value=="") {
	  alert("동을 선택하세요.");
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
?>