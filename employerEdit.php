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
<title>대타를 부탁해! 수정 페이지</title>
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
$query = "select * from employer where er_no='$_GET[er_no]'"; 
$result = mysql_query($query, $connect);
$data = mysql_fetch_array($result);

if($data['er_radio'] == "day") { // 하루일 때
	$que = "select * from employer_day where er_no='$_GET[er_no]'";
	$res = mysql_query($que, $connect);
	$row1 = mysql_fetch_array($res);
}
else if($data['er_radio'] == "days") { // 단기일 때
	$que = "select * from employer_days where er_no='$_GET[er_no]'";
	$res = mysql_query($que, $connect);
	$row2 = mysql_fetch_array($res);
}

?>

<form name="employerEdit" method="POST" action="employerEditDB.php" onSubmit="return chk_write2();">
<div id="boardDiv" align="center" width="100%">
<table width="100%" bgcolor="#f4f5f9" align="center" cellpadding="5" cellspacing="10" >
	<tr>
		<td width="15%" align="center"><p>제목</p></td>
		<td bgcolor="#ffffff" style="border-radius:7px;">
			<input type="text" name="er_title" style="border:0px;font-size:15px;width:100%" value="<?php echo $data['er_title'];?>">
		</td>
	</tr>
	<tr>
		<td width="15%" align="center"><p>구분</p></td>
		<td bgcolor="#ffffff" style="border-radius:7px;">
			<?php
			// 하루 또는 단기는 수정 페이지에서 수정할 수 없다.
			if($data['er_radio'] == "day") {
			?>
				<input type="radio" name="er_radio" id="er_radio" value="day" onclick="selectRadio()" checked disabled> 하루만!
				&nbsp;
				<input type="radio" name="er_radio" id="er_radio" value="days" onclick="selectRadio()" disabled> 단기!
			<?php
			}
			else {
			?>
				<input type="radio" name="er_radio" value="day" onclick="selectRadio()" disabled> 하루만!
				&nbsp;
				<input type="radio" name="er_radio" value="days" onclick="selectRadio()" checked disabled> 단기!
			<?php
			}
			?>
		</td>
	</tr>
	<tr>
		<td width="15%" align="center"><p>지역</p></td>
		<td bgcolor="#ffffff" style="border-radius:7px;">
			<div id="location">
			<select name="sido" id="sido" onChange="sidoChange();" style="border-color:#252c41">
				<option value="<?php echo $data['er_sido'];?>" readonly><?php echo $data['er_sido'];?></option>
			</select>
			<select name="gugun" id="gugun" onChange="gugunChange()" style="border-color:#252c41">
				<option value="<?php echo $data['er_gugun'];?>" readonly><?php echo $data['er_gugun'];?></option>
			</select>
			<select name="dong" id="dong" style="border-color:#252c41">
				<option value="<?php echo $data['er_dong'];?>" readonly><?php echo $data['er_dong'];?></option>
			</select>
			<input type="button" id="editArea" name="editArea" value="변경" onClick='edit_area2();' style="padding:3px 1px 3px 1px;margin-left:1px;width:20%">
			</div>
			<input type="text" name="er_detail" id="er_detail" value="<?php echo $data['er_detail'];?>" placeholder="자세한 위치를 입력해주세요." style="border:0px;font-size:15px;width:100%">
		</td>
	</tr>
	<tr>
		<td width="15%" align="center"><p>업종</p></td>
		<td bgcolor="#ffffff" style="border-radius:7px;">
			<div id="jobs">
			<select name="jobs1" id="jobs1" onChange="jobs1Change();" style="border-color:#252c41">
				<option value="<?php echo $data['er_jobs1'];?>" readonly><?php echo $data['er_jobs1'];?></option>
			</select>
			<select name="jobs2" id="jobs2" onChange="jobs2Change()" style="border-color:#252c41">
				<option value="<?php echo $data['er_jobs2'];?>" readonly><?php echo $data['er_jobs2'];?></option>
			</select>
			<select name="jobs3" id="jobs3" style="border-color:#252c41">
				<option value="<?php echo $data['er_jobs3'];?>" readonly><?php echo $data['er_jobs3'];?></option>
			</select>
			<input type="button" id="editArea" name="editJobs" value="변경" onClick='edit_jobs();' style="padding:3px 1px 3px 1px;margin-left:1px;width:20%">
			</div>
		</td>
	</tr>
	<tr>
		<td width="15%" align="center"><p>시급</p></td>
		<td bgcolor="#ffffff" style="border-radius:7px;">
			<input type="number" name="er_money" maxlength="8" placeholder="숫자만 입력" onKeyUp="onlyNum(er_money)"
			style="border:0px;font-size:15px;width:40%" value="<?php echo $data['er_money'];?>">
			<font style="font-size:12pt;color:#252c41;">원</font>
		</td>
	</tr>
<?php //하루
if($data['er_radio'] == "day") {
?>
	<tr>
		<td width="15%" align="center"><p>기간</p></td>
		<td bgcolor="#ffffff" style="border-radius:7px;">
			<div id="date">
			<input type='date' name='er_day' id='er_day' value="<?php echo $row1['er_day'];?>" maxlength='10' onkeyup='chkDate(this, event)' style='font-size:13px;width:40%;'>
			</div>
		</td>
	</tr>
	<tr>
		<td width="15%" align="center"><p>시간</p></td>
		<td bgcolor="#ffffff" style="border-radius:7px;">
			<div id="time">
			<input type="time" name="er_startTime" id="er_startTime" value="<?php echo $row1['er_startTime'];?>" maxlength="10" style="font-size:13px;width:40%;"> -
			<input type="time" name="er_finishTime" id="er_finishTime" value="<?php echo $row1['er_finishTime'];?>" maxlength="10" style="font-size:13px;width:40%">
			</div>
		</td>
	</tr>
<?php //단기
} else if($data['er_radio'] == "days"){
?>
<tr>
		<td width="15%" align="center"><p>기간</p></td>
		<td bgcolor="#ffffff" style="border-radius:7px;">
			<font size="2pt">
			<div id="date">
			<input type='date' name='er_startDay' id='er_startDay' value='<?php echo $row2['er_startDay'];?>' maxlength='10' onkeyup='chkDate(this, event)' style='font-size:13px;width:40%;'> ~ <input type='date' name='er_finishDay' id='er_finishDay' value='<?php echo $row2['er_finishDay'];?>' maxlength='10' onkeyup='chkDate(this, event)' style='font-size:13px;width:40%'><br>
			<input type='checkbox' name='er_box[]' id='er_box[]' value='mon' onclick='selectBox2()' <?php if($row2['er_monTime'] != null) echo 'checked';?>>월&nbsp;
			<input type='checkbox' name='er_box[]' id='er_box[]' value='tue' onclick='selectBox2()' <?php if($row2['er_tueTime'] != null) echo 'checked';?>>화&nbsp;
			<input type='checkbox' name='er_box[]' id='er_box[]' value='wed' onclick='selectBox2()' <?php if($row2['er_wedTime'] != null) echo 'checked';?>>수&nbsp;
			<input type='checkbox' name='er_box[]' id='er_box[]' value='thu' onclick='selectBox2()' <?php if($row2['er_thuTime'] != null) echo 'checked';?>>목&nbsp;
			<input type='checkbox' name='er_box[]' id='er_box[]' value='fri' onclick='selectBox2()' <?php if($row2['er_friTime'] != null) echo 'checked';?>>금&nbsp;
			<input type='checkbox' name='er_box[]' id='er_box[]' value='sat' onclick='selectBox2()' <?php if($row2['er_satTime'] != null) echo 'checked';?>>토&nbsp;
			<input type='checkbox' name='er_box[]' id='er_box[]' value='sun' onclick='selectBox2()' <?php if($row2['er_sunTime'] != null) echo 'checked';?>>일&nbsp;
			</div>
			</font>
		</td>
	</tr>
	<tr>
		<td width="15%" align="center"><p>시간</p></td>
		<td bgcolor="#ffffff" style="border-radius:7px;">
			<div id="time">
			<?php
			for($i = 3; $i <= 9; $i++) {
				if($row2[$i] != null) {
					if($i == 3) echo "월 : <input type='text' name='er_monTime' value='".$row2[$i]."' style='font-size:15px;width:80%'><br>";
					else if($i == 4) echo "화 : <input type='text' name='er_tueTime' value='".$row2[$i]."' style='font-size:15px;width:80%'><br>";
					else if($i == 5) echo "수 : <input type='text' name='er_wedTime' value='".$row2[$i]."' style='font-size:15px;width:80%'><br>";
					else if($i == 6) echo "목 : <input type='text' name='er_thuTime' value='".$row2[$i]."' style='font-size:15px;width:80%'><br>";
					else if($i == 7) echo "금 : <input type='text' name='er_friTime' value='".$row2[$i]."' style='font-size:15px;width:80%'><br>";
					else if($i == 8) echo "토 : <input type='text' name='er_satTime' value='".$row2[$i]."' style='font-size:15px;width:80%'><br>";
					else echo "일 : <input type='text' name='er_sunTime' value='".$row2[$i]."' style='font-size:15px;width:80%'><br>";
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
		<td width="15%" align="center"><p>내용</p></td>
		<td bgcolor="#ffffff" style="border-radius:7px;">
		<textarea name="er_content" placeholder="세부사항을 입력해주세요." style="border:0px;resize:none;font-size:15px;width:100%;" rows="12">
<?php echo $data['er_content'];?>
		</textarea>
		</td>
	</tr>
	<tr>
		<td colspan="3" align="center">
			<input type="submit" value="수정하기" name="editOk" id="writeOk">
			<input type="hidden" name="er_no" value="<?php echo $data['er_no'];?>">
			<input type="hidden" name="er_writeDay" value="<?php echo $data['er_writeDay'];?>">
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
		url: "employerEdit_jobs.php",
		success: function(data) {
			$("#jobs").html(data);
		}
	});
}

// 지역 선택을 위한 함수
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

// 업종 선택을 위한 함수
function jobs1Change() {
	var jobs1 = $("select[name='jobs1'] option:selected").val();
	$.ajax({
		type: "POST",
		url: "jobsChange.php",
		data: "jobs1=" + jobs1,
		success: function(data) {
			$("#jobs2").html(data);
			$("#jobs3").html("<option value=''>분류3</option>");
		}
	});
}
function jobs2Change() {
	var jobs1 = $("select[name='jobs1'] option:selected").val();
	var jobs2 = $("select[name='jobs2'] option:selected").val();
	$.ajax({
		type: "POST",
		url: "jobsChange.php",
		data: "jobs1=" + jobs1 + "&jobs2=" + jobs2,
		success: function(data) {
			$("#jobs3").html(data);
		}
	});
}

// 요일 선택하고 근무시간은 입력하지 않았을 경우 경고
function selectBox2() {
	$("#time").html("");
	var dayBox = new Array();

	$("input:checkbox[name='er_box[]']:checked").each(function(){
		dayBox.push($(this).val());
	});
	
	var str="";
	for (var i in dayBox) {
		var day = dayBox[i];
		switch(day) {
		case "mon":
			str += "월 : <input type='text' name='er_monTime' placeholder='요일 별 근무시간을 입력해주세요.' style='font-size:15px;width:80%'><br>";
			break;
		case "tue":
			str += "화 : <input type='text' name='er_tueTime' placeholder='요일 별 근무시간을 입력해주세요.' style='font-size:15px;width:80%'><br>";
			break;
		case "wed":
			str += "수 : <input type='text' name='er_wedTime' placeholder='요일 별 근무시간을 입력해주세요.' style='font-size:15px;width:80%'><br>";
			break;
		case "thu":
			str += "목 : <input type='text' name='er_thuTime' placeholder='요일 별 근무시간을 입력해주세요.' style='font-size:15px;width:80%'><br>";
			break;
		case "fri":
			str += "금 : <input type='text' name='er_friTime' placeholder='요일 별 근무시간을 입력해주세요.' style='font-size:15px;width:80%'><br>";
			break;
		case "sat":
			str += "토 : <input type='text' name='er_satTime' placeholder='요일 별 근무시간을 입력해주세요.' style='font-size:15px;width:80%'><br>";
			break;
		case "sun":
			str += "일 : <input type='text' name='er_sunTime' placeholder='요일 별 근무시간을 입력해주세요.' style='font-size:15px;width:80%'><br>";
			break;
		}
	}

	$("#time").html(str);
}

function onlyNum(obj) {
	var inText = obj.value;
	var ret;
	for (var i = 0; i < inText.length; i++) {
		ret = inText.charCodeAt(i);
		if (!(ret > 47 && ret < 58)) {
			alert("숫자만 입력할 수 있습니다.");
			obj.value = "";
			obj.focus();
			return false;
		}
	}
}

function chk_write2() {
	if(employerEdit.er_title.value=="") {
	  alert("제목을 입력하세요.");
	  employerWrite.er_title.focus();
	  return false;
	}

	else if(employerEdit.er_sido.value=="") {
	  alert("시/도를 선택하세요.");
	  employerWrite.er_sido.focus();
	  return false;
	}

	else if(employerEdit.er_gugun.value=="") {
	  alert("구/군을 선택하세요.");
	  employerWrite.er_gugun.focus();
	  return false;
	}

	else if(employerEdit.er_dong.value=="") {
	  alert("동을 선택하세요.");
	  employerWrite.er_dong.focus();
	  return false;
	}

	else if(employerEdit.er_detail.value=="") {
	  alert("세부 주소를 입력하세요.");
	  employerWrite.er_detail.focus();
	  return false;
	}
	
	else if(employerEdit.er_jobs1.value=="" || employerEdit.er_jobs2.value=="" || employerEdit.er_jobs3.value=="") {
	  alert("업종을 입력하세요.");
	  return false;
	}

	else if(employerEdit.er_money.value=="") {
	  alert("시급을 입력하세요.");
	  employerWrite.er_money.focus();
	  return false;
	}

	else if(employerEdit.er_content.value=="") {
	  alert("내용을 입력하세요.");
	  employerWrite.er_content.focus();
	  return false;
	}

	else {
		if (employerEdit.er_radio.value=="day") {
			if(employerEdit.er_day.value=="") {
				alert("기간을 입력하세요.");
				return false;
			}
			else if(employerEdit.er_startTime.value=="" || employerEdit.er_finishTime.value=="") {
				alert("시간을 입력하세요.");
				return false;
			}
		}	
		else if(employerEdit.er_radio.value=="days"){
			if(employerEdit.er_startDay.value == "" || employerEdit.er_finishDay.value=="") {
				alert("기간을 입력하세요.");
				return false;
			}
			else if(employerEdit.er_startDay.value > employerEdit.er_finishDay.value) {
				alert("기간을 입력하세요.");
				return false;
			}
			else if($("#checkbox[name='er_box[]']:checked").length == 0) {
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
}//로그인 될 경우 else문
mysql_close($connect);
?>
