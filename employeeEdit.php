<?php
/*
	employeeRead에서 수정을 눌렀을 경우
	게시물을 수정하는 Form 이 있는 페이지
*/
include_once('dbConnect.php');
include('menuBar.php');
?>
<html lang="ko">
<head>
<meta charset="UTF-8">
<!-- 모바일 화면의 크기가 고정되게 하는 소스, 가로 스크롤 안생긴다. -->
<meta name="viewport" content="user-scalable=no, initial-scale=1.0, maximum-scale=1.0,minimum-scale=1.0, width=device-width" />

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
<link rel="stylesheet" href="inputStyle.css" type="text/css"/>
<link rel="stylesheet" href="boardStyle.css" type="text/css"/>
<style type="text/css">
  p {	color:#252c41;font-size:12pt;	}
</style>
<title>나에게 부탁해! 수정페이지</title>
</head>
<body>
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
} else {
// 받아온 게시물 번호를 가지는 게시물의 데이터를 불러온다
$query = "select * from employee where ee_no='$_GET[ee_no]'"; 
$result = mysql_query($query, $connect);
$data = mysql_fetch_array($result);

if($data['ee_radio'] == "day") { // 하루일 때
	$que = "select * from employee_day where ee_no='$_GET[ee_no]'";
	$res = mysql_query($que, $connect);
	$row1 = mysql_fetch_array($res);
}
else if($data['ee_radio'] == "days") { // 단기일 때
	$que = "select * from employee_days where ee_no='$_GET[ee_no]'";
	$res = mysql_query($que, $connect);
	$row2 = mysql_fetch_array($res);
}

?>

<form name="employeeEdit" method="POST" action="employeeEditDB.php" onSubmit="return chk_write2();">
<div id="boardDiv" align="center">
<table width="100%" bgcolor="#f4f5f9" align="center" cellpadding="5" cellspacing="10">
	<tr>
		<td width="15%" align="center"><p>제목</p></td>
		<td bgcolor="#ffffff" style="border-radius:7px;">
			<input type="text" name="ee_title" style="border:0px;font-size:15px;width:100%" value="<?php echo $data['ee_title'];?>">
		</td>
	</tr>
	<tr>
		<td width="15%" align="center"><p>구분</p></td>
		<td bgcolor="#ffffff" style="border-radius:7px;">
			<?php
			// 하루 또는 단기는 수정 페이지에서 수정할 수 없다.
			if($data['ee_radio'] == "day") {
			?>
				<input type="radio" name="ee_radio" id="ee_radio" value="day" onclick="selectRadio()" checked disabled> 하루만!
				&nbsp;
				<input type="radio" name="ee_radio" id="ee_radio" value="days" onclick="selectRadio()" disabled> 단기!
			<?php
			}
			else {
			?>
				<input type="radio" name="ee_radio" value="day" onclick="selectRadio()" disabled> 하루만!
				&nbsp;
				<input type="radio" name="ee_radio" value="days" onclick="selectRadio()" checked disabled> 단기!
			<?php
			}
			?>
		</td>
	</tr>
	<tr>
		<td width="15%" align="center"><p>사는 지역</p></td>
		<td bgcolor="#ffffff" style="border-radius:7px;">
			<div id="location">
			<select name="sido" id="sido" onChange="sidoChange();" style="border-color:#252c41">
				<option value="<?php echo $data['ee_sido'];?>" readonly><?php echo $data['ee_sido'];?></option>
			</select>
			<select name="gugun" id="gugun" onChange="gugunChange()" style="border-color:#252c41">
				<option value="<?php echo $data['ee_gugun'];?>" readonly><?php echo $data['ee_gugun'];?></option>
			</select>
			<select name="dong" id="dong" style="border-color:#252c41">
				<option value="<?php echo $data['ee_dong'];?>" readonly><?php echo $data['ee_dong'];?></option>
			</select>
			<input type="button" id="editArea" name="editArea" value="변경" onClick='edit_area2();' style="padding:3px 1px 3px 1px;margin-left:1px;width:20%">
			</div>
		</td>
	</tr>
	<tr>
		<td width="15%" align="center"><p>관심 업종</p></td>
		<td bgcolor="#ffffff" style="border-radius:7px;">
			<div id="jobs">
			<select name="jobs1" id="jobs1" onChange="jobs1Change();" style="border-color:#252c41">
				<option value="<?php echo $data['ee_jobs1'];?>" readonly><?php echo $data['ee_jobs1'];?></option>
			</select>
			<input type="button" id="editArea" name="editJobs" value="변경" onClick='edit_jobs();' style="padding:3px 1px 3px 1px;margin-left:1px;width:20%">
			</div>
		</td>
	</tr>
<?php //하루
if($data['ee_radio'] == "day") {
?>
	<tr>
		<td width="15%" align="center"><p>가능 기간</p></td>
		<td bgcolor="#ffffff" style="border-radius:7px;">
			<div id="date">
			<input type='date' name='ee_day' id='ee_day' value="<?php echo $row1['ee_day'];?>" maxlength='10' onkeyup='chkDate(this, event)' style='font-size:13px;width:40%;'>
			</div>
		</td>
	</tr>
	<tr>
		<td width="15%" align="center"><p>가능 시간</p></td>
		<td bgcolor="#ffffff" style="border-radius:7px;">
			<div id="time">
			<input type="time" name="ee_startTime" id="ee_startTime" value="<?php echo $row1['ee_startTime'];?>" maxlength="10" style="font-size:13px;width:40%;"> -
			<input type="time" name="ee_finishTime" id="ee_finishTime" value="<?php echo $row1['ee_finishTime'];?>" maxlength="10" style="font-size:13px;width:40%">
			</div>
		</td>
	</tr>
<?php //단기
} else if($data['ee_radio'] == "days"){
?>
<tr>
		<td width="15%" align="center"><p>가능 기간</p></td>
		<td bgcolor="#ffffff" style="border-radius:7px;">
			<font size="1pt">
			<div id="date">
			<input type='date' name='ee_startDay' id='ee_startDay' value='<?php echo $row2['ee_startDay'];?>' maxlength='10' onkeyup='chkDate(this, event)' style='font-size:13px;width:40%;'> ~ <input type='date' name='ee_finishDay' id='ee_finishDay' value='<?php echo $row2['ee_finishDay'];?>' maxlength='10' onkeyup='chkDate(this, event)' style='font-size:13px;width:40%'><br>
			<input type='checkbox' name='ee_box[]' id='ee_box[]' value='mon' onclick='selectBox2()' <?php if($row2['ee_monTime'] != null) echo 'checked';?>>월&nbsp;
			<input type='checkbox' name='ee_box[]' id='ee_box[]' value='tue' onclick='selectBox2()' <?php if($row2['ee_tueTime'] != null) echo 'checked';?>>화&nbsp;
			<input type='checkbox' name='ee_box[]' id='ee_box[]' value='wed' onclick='selectBox2()' <?php if($row2['ee_wedTime'] != null) echo 'checked';?>>수&nbsp;
			<input type='checkbox' name='ee_box[]' id='ee_box[]' value='thu' onclick='selectBox2()' <?php if($row2['ee_thuTime'] != null) echo 'checked';?>>목&nbsp;
			<input type='checkbox' name='ee_box[]' id='ee_box[]' value='fri' onclick='selectBox2()' <?php if($row2['ee_friTime'] != null) echo 'checked';?>>금&nbsp;
			<input type='checkbox' name='ee_box[]' id='ee_box[]' value='sat' onclick='selectBox2()' <?php if($row2['ee_satTime'] != null) echo 'checked';?>>토&nbsp;
			<input type='checkbox' name='ee_box[]' id='ee_box[]' value='sun' onclick='selectBox2()' <?php if($row2['ee_sunTime'] != null) echo 'checked';?>>일
			</div>
			</font>
		</td>
	</tr>
	<tr>
		<td width="15%" align="center"><p>가능 시간</p></td>
		<td bgcolor="#ffffff" style="border-radius:7px;">
			<div id="time">
			<?php
			for($i = 3; $i <= 9; $i++) {
				if($row2[$i] != null) {
					if($i == 3) echo "월 : <input type='text' name='ee_monTime' value='".$row2[$i]."' style='font-size:15px;width:80%'><br>";
					else if($i == 4) echo "화 : <input type='text' name='ee_tueTime' value='".$row2[$i]."' style='font-size:15px;width:80%'><br>";
					else if($i == 5) echo "수 : <input type='text' name='ee_wedTime' value='".$row2[$i]."' style='font-size:15px;width:80%'><br>";
					else if($i == 6) echo "목 : <input type='text' name='ee_thuTime' value='".$row2[$i]."' style='font-size:15px;width:80%'><br>";
					else if($i == 7) echo "금 : <input type='text' name='ee_friTime' value='".$row2[$i]."' style='font-size:15px;width:80%'><br>";
					else if($i == 8) echo "토 : <input type='text' name='ee_satTime' value='".$row2[$i]."' style='font-size:15px;width:80%'><br>";
					else echo "일 : <input type='text' name='ee_sunTime' value='".$row2[$i]."' style='font-size:15px;width:80%'><br>";
				}	
			}
			?>
			</div>
		</td>
	</tr>
<?php
}
?>
	<tr>
		<td width="15%" align="center"><p>경력</p></td>
		<td bgcolor="#ffffff" style="border-radius:7px;">
			<textarea name="ee_career" placeholder="경력이 있다면 작성해주세요." style="border:0px;resize:none;font-size:15px;width:100%;"rows="3">
<?php echo $data['ee_career'];?></textarea>
		</td>
	</tr>
	<tr>
		<td width="15%" align="center"><p>소개</p></td>
		<td bgcolor="#ffffff" style="border-radius:7px;">
		<textarea name="ee_content" placeholder="자기 소개를 작성해주세요." style="border:0px;resize:none;font-size:15px;width:100%;" rows="8">
<?php echo $data['ee_content'];?></textarea>
		</td>
	</tr>
	<tr>
		<td colspan="3" align="center">
			<input type="submit" value="수정하기" name="editOk" id="writeOk">
			<input type="hidden" name="ee_no" value="<?php echo $data['ee_no'];?>">
			<input type="hidden" name="ee_writeDay" value="<?php echo $data['ee_writeDay'];?>">
			<input type="button" value="취소" name="editRst" id="writeRst" onclick="history.back();">
		</td>
	</tr>
</table>
</div>
</form>
<script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script><!-- 최신버전 제이쿼리 -->
<script type="text/javascript">
function edit_area2() {
	$("#location").html("");
	$.ajax({
		type: "POST",
		url: "editInfo_area.php",
		success: function(data) {
			$("#location").html(data);
		}
	});
}
function edit_jobs() {
	$("#jobs").html("");
	$.ajax({
		type: "POST",
		url: "employeeEdit_jobs.php",
		success: function(data) {
			$("#jobs").html(data);
		}
	});
}
function sidoChange() {
	var sido = $("select[name='sido'] option:selected").val();
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
function gugunChange() {
	var sido = $("select[name='sido'] option:selected").val();
	var gugun = $("select[name='gugun'] option:selected").val();
	$.ajax({
		type: "POST",
		url: "areaChange.php",
		data: "sido=" + sido + "&gugun=" + gugun,
		success: function(data) {
			$("#dong").html(data);
		}
	});
}

// 요일 선택하고 근무시간은 입력하지 않았을 경우 경고
function selectBox2() {
	$("#time").html("");
	var dayBox = new Array();

	$("input:checkbox[name='ee_box[]']:checked").each(function(){
		dayBox.push($(this).val());
	});
	
	var str="";
	for (var i in dayBox) {
		var day = dayBox[i];
		switch(day) {
		case "mon":
			str += "월 : <input type='text' name='ee_monTime' placeholder='요일 별 근무시간을 입력해주세요.' style='font-size:15px;width:80%'><br>";
			break;
		case "tue":
			str += "화 : <input type='text' name='ee_tueTime' placeholder='요일 별 근무시간을 입력해주세요.' style='font-size:15px;width:80%'><br>";
			break;
		case "wed":
			str += "수 : <input type='text' name='ee_wedTime' placeholder='요일 별 근무시간을 입력해주세요.' style='font-size:15px;width:80%'><br>";
			break;
		case "thu":
			str += "목 : <input type='text' name='ee_thuTime' placeholder='요일 별 근무시간을 입력해주세요.' style='font-size:15px;width:80%'><br>";
			break;
		case "fri":
			str += "금 : <input type='text' name='ee_friTime' placeholder='요일 별 근무시간을 입력해주세요.' style='font-size:15px;width:80%'><br>";
			break;
		case "sat":
			str += "토 : <input type='text' name='ee_satTime' placeholder='요일 별 근무시간을 입력해주세요.' style='font-size:15px;width:80%'><br>";
			break;
		case "sun":
			str += "일 : <input type='text' name='ee_sunTime' placeholder='요일 별 근무시간을 입력해주세요.' style='font-size:15px;width:80%'><br>";
			break;
		}
	}

	$("#time").html(str);
}

function chk_write2() {
	if(employeeEdit.ee_title.value=="") {
	  alert("제목을 입력하세요.");
	  employeeWrite.ee_title.focus();
	  return false;
	}

	else if(employeeEdit.ee_sido.value=="") {
	  alert("시/도를 선택하세요.");
	  employeeWrite.ee_sido.focus();
	  return false;
	}

	else if(employeeEdit.ee_gugun.value=="") {
	  alert("구/군을 선택하세요.");
	  employeeWrite.ee_gugun.focus();
	  return false;
	}

	else if(employeeEdit.ee_dong.value=="") {
	  alert("동을 선택하세요.");
	  employeeWrite.ee_dong.focus();
	  return false;
	}

	else if(employeeEdit.ee_detail.value=="") {
	  alert("세부 주소를 입력하세요.");
	  employeeWrite.ee_detail.focus();
	  return false;
	}
	
	else if(employeeEdit.ee_jobs1.value=="" || employeeEdit.ee_jobs2.value=="" || employeeEdit.ee_jobs3.value=="") {
	  alert("업종을 입력하세요.");
	  return false;
	}

	else if(employeeEdit.ee_money.value=="") {
	  alert("시급을 입력하세요.");
	  employeeWrite.ee_money.focus();
	  return false;
	}

	else if(employeeEdit.ee_content.value=="") {
	  alert("내용을 입력하세요.");
	  employeeWrite.ee_content.focus();
	  return false;
	}

	else {
		if (employeeEdit.ee_radio.value=="day") {
			if(employeeEdit.ee_day.value=="") {
				alert("기간을 입력하세요.");
				return false;
			}
			else if(employeeEdit.ee_startTime.value=="" || employeeEdit.ee_finishTime.value=="") {
				alert("시간을 입력하세요.");
				return false;
			}
		}	
		else if(employeeEdit.ee_radio.value=="days"){
			if(employeeEdit.ee_startDay.value == "" || employeeEdit.ee_finishDay.value=="") {
				alert("기간을 입력하세요.");
				return false;
			}
			else if(employeeEdit.ee_startDay.value > employeeEdit.ee_finishDay.value) {
				alert("기간을 입력하세요.");
				return false;
			}
			else if($("#checkbox[name='ee_box[]']:checked").length == 0) {
				alert("요일을 한개 이상 체크하세요.");
				return false;
			}
		}
		return true;
	}
}
</script>
</body>
</html>
<?php
} //로그인 될 경우 else문
mysql_close($connect);
?>
