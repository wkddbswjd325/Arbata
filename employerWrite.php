<?php
/*
	employeeBoard에 게시물을 작성하기 위한 페이지 (회원만 가능)

	지역 선택과 업종 선택을 위한 동적 select 구현 
*/
include_once('dbConnect.php');
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
<title>대타를 부탁해! 작성 페이지</title>
</head>
<body>
<?php
include('menuBar.php');

if(!isset($_SESSION['id'])) {
	echo"<script>
		alert('로그인이 필요한 기능입니다.');
		location.href='loginPage.php';
		</script>";
}
?>

<form name="employerWrite" method="POST" action="employerDB.php" onSubmit="return chk_write();">
<div id="boardDiv" align="center">
<table width="100%" bgcolor="#f4f5f9" align="center" cellpadding="5" cellspacing="10">
	<tr>
		<td width="15%" align="center"><p>제목</p></td>
		<td bgcolor="#ffffff" style="border-radius:7px;">
			<input type="text" name="er_title" style="border:0px;font-size:15px;width:100%">
		</td>
	</tr>
	<tr>
		<td width="15%" align="center"><p>구분</p></td>
		<td bgcolor="#ffffff" style="border-radius:7px;">
			<input type="radio" name="er_radio" id="er_radio" value="day" onclick="selectRadio()" checked > 하루만!  
			&nbsp;
			<input type="radio" name="er_radio" id="er_radio" value="days" onclick="selectRadio()"> 단기!
		</td>
	</tr>
	<tr>
		<td width="15%" align="center"><p>지역</p></td>
		<td bgcolor="#ffffff" style="border-radius:7px;">
				<select name="er_sido" id="er_sido" onChange="sidoChange();" style="border:0px">
					<option value=''>시/도</option>
				<?php
				$sql = "select distinct(sido) from area order by priority";
				$result = mysql_query($sql);

				while($row = mysql_fetch_array($result)) {
						echo "<option value='".$row['sido']."'>".$row['sido']."</option>"; 
				}
				?>
				</select>
				<select name="er_gugun" id="er_gugun" onChange="gugunChange()" style="border:0px" >
					<option value=''>구/군</option>
				</select>
				<select name="er_dong" id="er_dong" style="border:0px">
					<option value=''>동/면/읍</option>
				</select>
				<br>
				<input type="text" name="er_detail" id="er_detail" placeholder="지번 주소를 자세하게 입력해주세요." style="border:0px;font-size:15px;width:100%">
				<br>
				<font color="gray" size="1pt" style="margin-left:3px">(주소를 잘못 입력하시면 지도에 표시되지 않습니다.)</font>
		</td>
	</tr>
	<tr>
		<td width="15%" align="center"><p>업종</p></td>
		<td bgcolor="#ffffff" style="border-radius:7px;">
			<select name="er_jobs1" id="er_jobs1" onChange="jobs1Change();" style="border:0px;font-size:12px;">
				<option value=''>분류1</option>
				<?php
				$sql = "select distinct(jobs1) from jobs";
				$result = mysql_query($sql);

				while($row = mysql_fetch_array($result)) {
						echo "<option value='".$row['jobs1']."'>".$row['jobs1']."</option>"; 
				}
				?>
			</select>
			<select name="er_jobs2" id="er_jobs2" onChange="jobs2Change();" style="border:0px;font-size:12px;">
				<option value=''>분류2</option>
			</select>
			<select name="er_jobs3" id="er_jobs3" style="border:0px;font-size:12px;">
				<option value=''>분류3</option>
			</select>
		</td>
	</tr>
	<tr>
		<td width="15%" align="center"><p>시급</p></td>
		<td bgcolor="#ffffff" style="border-radius:7px;">
			<input type="number" name="er_money" maxlength="8" placeholder="숫자만 입력" onKeyUp="onlyNum(er_money)"
			style="border:0px;font-size:15px;width:40%">
			<font style="font-size:12pt;color:#252c41;">원</font>
		</td>
	</tr>
	<tr>
		<td width="15%" align="center"><p>기간</p></td>
		<td bgcolor="#ffffff" style="border-radius:7px;">
			<div id="date">
			<input type='date' name='er_day' id='er_day' value='<%er_day%>' maxlength='10' onkeyup='chkDate(this, event)' style='font-size:13px;width:40%;'>
			</div>
		</td>
	</tr>
	<tr>
		<td width="15%" align="center"><p>시간</p></td>
		<td bgcolor="#ffffff" style="border-radius:7px;">
			<div id="time">
			<input type="time" name="er_startTime" id="er_startTime" value="<%er_startTime%>" maxlength="10" style="font-size:13px;width:40%;"> -
			<input type="time" name="er_finishTime" id="er_finishTime" value="<%er_finishTime%>" maxlength="10" style="font-size:13px;width:40%">
			</div>
		</td>
	</tr>
	<tr>
		<td width="15%" align="center"><p>내용</p></td>
		<td bgcolor="#ffffff" style="border-radius:7px;">
		<textarea name="er_content" placeholder="세부사항을 입력해주세요." style="border:0px;resize:none;font-size:15px;width:100%;"rows="12"></textarea>
		</td>
	</tr>
	<tr>
		<td colspan="2" align="center">
			<input type="submit" value="올리기" name="writeOk" id="writeOk">
			<input type="button" value="취소" name="writeRst" id="writeRst" onclick="history.back();">
		</td>
	</tr>
</table>
</div>
</form>
<script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script><!-- 최신버전 제이쿼리 -->
<script type="text/javascript">

// 시/도 를 선택하면 그에 따라 구/군 변화
function sidoChange() {
	var sido = $("select[name='er_sido'] option:selected").val();
	$.ajax({
		type: "POST",
		url: "areaChange.php", //해당 페이지로 넘어간다
		data: "sido=" + sido,
		success: function(data) {
			$("#er_gugun").html(data);
			$("#er_dong").html("<option value=''>동/면/읍</option>");
		}
	});
}

// 구/군을 선택하면 그에 따라 동/면/읍 변화
function gugunChange() {
	var sido = $("select[name='er_sido'] option:selected").val();
	var gugun = $("select[name='er_gugun'] option:selected").val();
	$.ajax({
		type: "POST",
		url: "areaChange.php",
		data: "sido=" + sido + "&gugun=" + gugun,
		success: function(data) {
			$("#er_dong").html(data);
		}
	});
}

// 분류1을 선택하면 그에 따라 분류2 변화
function jobs1Change() {
	var jobs1 = $("select[name='er_jobs1'] option:selected").val();
	$.ajax({
		type: "POST",
		url: "jobsChange.php",
		data: "jobs1=" + jobs1,
		success: function(data) {
			$("#er_jobs2").html(data);
			$("#er_jobs3").html("<option value=''>분류3</option>");
		}
	});
}

// 분류2의 선택에 따라 분류3이 변화
function jobs2Change() {
	var jobs1 = $("select[name='er_jobs1'] option:selected").val();
	var jobs2 = $("select[name='er_jobs2'] option:selected").val();
	$.ajax({
		type: "POST",
		url: "jobsChange.php",
		data: "jobs1=" + jobs1 + "&jobs2=" + jobs2,
		success: function(data) {
			$("#er_jobs3").html(data);
		}
	});
}

// 기간 선택하는 함수
function selectRadio() {
	var r = $("#er_radio:checked").val();
	if (r == "day") {
		var str = "<input type='date' name='er_day' id='er_day' value='<%er_day%>' maxlength='10' onkeyup='chkDate(this, event)' style='font-size:13px;width:40%;'>";
		$("#date").html(str);
		
		str = "<input type='time' name='er_startTime' id='er_startTime' value='<%er_startTime%>' maxlength='10' style='font-size:13px;width:40%;'> - <input type='time' name='er_finishTime' id='er_finishTime' value='<%er_finishTime%>' maxlength='10' style='font-size:13px;width:40%'>";
		$("#time").html(str);
	}
	else {
		var str = "<input type='date' name='er_startDay' id='er_startDay' value='<%er_startDay%>' maxlength='10' onkeyup='chkDate(this, event)' style='font-size:13px;width:40%;'> ~ <input type='date' name='er_finishDay' id='er_finishDay' value='<%er_finishDay%>' maxlength='10' onkeyup='chkDate(this, event)' style='font-size:13px;width:40%'>";
		str += "<br><font style='font-size:8px'>&nbsp;</font>";
		str += "<br><input type='checkbox' name='er_box[]' id='er_box[]' value='mon' onclick='selectBox()'>월&nbsp;";
		str += "<input type='checkbox' name='er_box[]' id='er_box[]' value='tue' onclick='selectBox()'>화&nbsp;";
		str += "<input type='checkbox' name='er_box[]' id='er_box[]' value='wed' onclick='selectBox()'>수&nbsp;";
		str += "<input type='checkbox' name='er_box[]' id='er_box[]' value='thu' onclick='selectBox()'>목&nbsp;";
		str += "<input type='checkbox' name='er_box[]' id='er_box[]' value='fri' onclick='selectBox()'>금&nbsp;";
		str += "<input type='checkbox' name='er_box[]' id='er_box[]' value='sat' onclick='selectBox()'>토&nbsp;";
		str += "<input type='checkbox' name='er_box[]' id='er_box[]' value='sun' onclick='selectBox()'>일";
		$("#date").html(str);

		$("#time").html("");

	}
}

// 요일 선택시 옆에 textbox가 뜨게 하는 함수
function selectBox() {
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

function chk_write() {
	if(employerWrite.er_title.value=="") {
	  alert("제목을 입력하세요.");
	  employerWrite.er_title.focus();
	  return false;
	}

	else if(employerWrite.er_sido.value=="") {
	  alert("시/도를 선택하세요.");
	  employerWrite.er_sido.focus();
	  return false;
	}

	else if(employerWrite.er_gugun.value=="") {
	  alert("구/군을 선택하세요.");
	  employerWrite.er_gugun.focus();
	  return false;
	}

	else if(employerWrite.er_dong.value=="") {
	  alert("동을 선택하세요.");
	  employerWrite.er_dong.focus();
	  return false;
	}

	else if(employerWrite.er_detail.value=="") {
	  alert("세부 주소를 입력하세요.");
	  employerWrite.er_detail.focus();
	  return false;
	}
	
	else if(employerWrite.er_jobs1.value=="" || employerWrite.er_jobs2.value=="" || employerWrite.er_jobs3.value=="") {
	  alert("업종을 입력하세요.");
	  return false;
	}

	else if(employerWrite.er_money.value=="") {
	  alert("시급을 입력하세요.");
	  employerWrite.er_money.focus();
	  return false;
	}

	else if(employerWrite.er_content.value=="") {
	  alert("내용을 입력하세요.");
	  employerWrite.er_content.focus();
	  return false;
	}

	else {
		if (employerWrite.er_radio.value=="day") {
			if(employerWrite.er_day.value=="") {
				alert("기간을 입력하세요.");
				return false;
			}
			else if(employerWrite.er_startTime.value=="" || employerWrite.er_finishTime.value=="") {
				alert("시간을 입력하세요.");
				return false;
			}
		}	
		else {
			if(employerWrite.er_startDay.value=="" || employerWrite.er_finishDay.value=="") {
				alert("기간을 입력하세요.");
				return false;
			}
			else if(employerWrite.er_startDay.value > employerWrite.er_finishDay.value) {
				alert("기간을 다시 입력하세요.");
				return false;
			}
			else if($("input:checkbox[id='er_box[]']:checked").length == 0) {
				alert("요일을 선택하세요.");
				return false;
			}
			else {
				var dayBox = new Array();

				$("input:checkbox[id='er_box[]']:checked").each(function(){
					dayBox.push($(this).val());
				});
				
				for (var i in dayBox) {
					var day = dayBox[i];
					switch(day) {
						case "mon":
							if(employerWrite.er_monTime.value=="") {
							  alert("요일 별 근무시간을 입력하세요.");
							  employerWrite.er_monTime.focus();
							  return false;
							}
							break;
						case "tue":
							if(employerWrite.er_tueTime.value=="") {
							  alert("요일 별 근무시간을 입력하세요.");
							  employerWrite.er_tueTime.focus();
							  return false;
							}
							break;
						case "wed":
							if(employerWrite.er_wedTime.value=="") {
							  alert("요일 별 근무시간을 입력하세요.");
							  employerWrite.er_wedTime.focus();
							  return false;
							}
							break;
						case "thu":
							if(employerWrite.er_thuTime.value=="") {
							  alert("요일 별 근무시간을 입력하세요.");
							  employerWrite.er_thuTime.focus();
							  return false;
							}
							break;
						case "fri":
							if(employerWrite.er_friTime.value=="") {
							  alert("요일 별 근무시간을 입력하세요.");
							  employerWrite.er_friTime.focus();
							  return false;
							}
							break;
						case "sat":
							if(employerWrite.er_satTime.value=="") {
							  alert("요일 별 근무시간을 입력하세요.");
							  employerWrite.er_satTime.focus();
							  return false;
							}
							break;
						case "sun":
							if(employerWrite.er_sunTime.value=="") {
							  alert("요일 별 근무시간을 입력하세요.");
							  employerWrite.er_sunTime.focus();
							  return false;
							}
							break;
					}
				}
			}
		}
	}
	return true;
}
</script>
</body>
</html>
<?php
mysql_close($connect);
?>
