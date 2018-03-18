<?php
/*
	employeeBoard에 게시물을 작성하기 위한 페이지 (회원만 가능)

	지역 선택을 위한 동적 select 구현 
*/
include('menuBar.php');
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
<title>나에게 부탁해! 작성 페이지</title>
</head>
<body>
<?php

if(!isset($_SESSION['id'])) {
	echo"<script>
		alert('로그인이 필요한 기능입니다.');
		location.href='loginPage.php';
		</script>";
}
?>

<form name="employeeWrite" method="POST" action="employeeDB.php" onSubmit="return chk_write();">
<div id="boardDiv" align="center">
<table width="100%" bgcolor="#f4f5f9" align="center" cellpadding="5" cellspacing="10">
	<tr>
		<td width="15%" align="center"><p>제목</p></td>
		<td bgcolor="#ffffff" style="border-radius:7px;">
			<input type="text" name="ee_title" style="border:0px;font-size:15px;width:100%">
		</td>
	</tr>
	<tr>
		<td width="15%" align="center"><p>구분</p></td>
		<td bgcolor="#ffffff" style="border-radius:7px;">
			<input type="radio" name="ee_radio" id="ee_radio" value="day" onclick="selectRadio()" checked > 하루만!  
			&nbsp;
			<input type="radio" name="ee_radio" id="ee_radio" value="days" onclick="selectRadio()"> 단기!
		</td>
	</tr>
	<tr>
		<td width="15%" align="center"><p>사는 지역</p></td>
		<td bgcolor="#ffffff" style="border-radius:7px;">
			<select name="ee_sido" id="ee_sido" onChange="sidoChange();" style="border:0px">
				<option value=''>시/도</option>
			<?php
			$sql = "select distinct(sido) from area order by priority";
			$result = mysql_query($sql);

			while($row = mysql_fetch_array($result)) {
					echo "<option value='".$row['sido']."'>".$row['sido']."</option>"; 
			}
			
			?>
			</select>
			<select name="ee_gugun" id="ee_gugun" onChange="gugunChange()" style="border:0px" >
				<option value=''>구/군</option>
			</select>
			<select name="ee_dong" id="ee_dong" style="border:0px">
				<option value=''>동/면/읍</option>
			</select>
		</td>
	</tr>
	<tr>
		<td width="15%" align="center"><p>관심 업종</p></td>
		<td bgcolor="#ffffff" style="border-radius:7px;">
			<select name="ee_jobs1" id="ee_jobs1" onChange="jobs1Change();" style="border:0px;font-size:12px;">
				<option value=''>분류</option>
				<?php
				$sql = "select distinct(jobs1) from jobs";
				$result = mysql_query($sql);

				while($row = mysql_fetch_array($result)) {
						echo "<option value='".$row['jobs1']."'>".$row['jobs1']."</option>"; 
				}
				
				?>
			</select>
		</td>
	</tr>
	<tr>
		<td width="15%" align="center"><p>가능 기간</p></td>
		<td bgcolor="#ffffff" style="border-radius:7px;">
			<div id="date">
			<input type='date' name='ee_day' id='ee_day' value='<%ee_day%>' maxlength='10' onkeyup='chkDate(this, event)' style='font-size:13px;width:40%;'>
			</div>
		</td>
	</tr>
	<tr>
		<td width="15%" align="center"><p>가능 시간</p></td>
		<td bgcolor="#ffffff" style="border-radius:7px;">
			<div id="time">
			<input type="time" name="ee_startTime" id="ee_startTime" value="<%ee_startTime%>" maxlength="10" style="font-size:13px;width:40%;"> -
			<input type="time" name="ee_finishTime" id="ee_finishTime" value="<%ee_finishTime%>" maxlength="10" style="font-size:13px;width:40%">
			</div>
		</td>
	</tr>
	<tr>
		<td width="15%" align="center"><p>경력</p></td>
		<td bgcolor="#ffffff" style="border-radius:7px;">
			<textarea name="ee_career" placeholder="경력이 있다면 작성해주세요." style="border:0px;resize:none;font-size:15px;width:100%;"rows="3"></textarea>
		</td>
	</tr>
	<tr>
		<td width="15%" align="center"><p>소개</p></td>
		<td bgcolor="#ffffff" style="border-radius:7px;">
		<textarea name="ee_content" placeholder="자기 소개를 작성해주세요." style="border:0px;resize:none;font-size:15px;width:100%;"rows="8"></textarea>
		</td>
	</tr>
	<tr>
		<td colspan="2" align="center">
			<input type="submit" value="올리기" name="writeOk" id="writeOk">
			<input type="button" value="취소" name="writeRst" id="writeRst" onclick="location.href='employeeBoard.php'">
		</td>
	</tr>
</table>
</div>
</form>
<script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script><!-- 최신버전 제이쿼리 -->
<script type="text/javascript">

// 지역 선택을 위한 함수

// 시/도 를 선택하면 그에 따라 구/군 변화
function sidoChange() {
	var sido = $("select[name='ee_sido'] option:selected").val();
	$.ajax({
		type: "POST",
		url: "areaChange.php", //해당 페이지로 넘어간다.
		data: "sido=" + sido,
		success: function(data) {
			$("#ee_gugun").html(data);
			$("#ee_dong").html("<option value=''>동/면/읍</option>");
		}
	});
}

// 구/군을 선택하면 그에 따라 동/면/읍 변화
function gugunChange() {
	var sido = $("select[name='ee_sido'] option:selected").val();
	var gugun = $("select[name='ee_gugun'] option:selected").val();
	$.ajax({
		type: "POST",
		url: "areaChange.php",
		data: "sido=" + sido + "&gugun=" + gugun,
		success: function(data) {
			$("#ee_dong").html(data);
		}
	});
}

// 기간 선택하는 함수
function selectRadio() {
	var r = $("#ee_radio:checked").val();
	if (r == "day") {
		var str = "<input type='date' name='ee_day' id='ee_day' value='<%ee_day%>' maxlength='10' onkeyup='chkDate(this, event)' style='font-size:13px;width:40%;'>";
		$("#date").html(str);
		
		str = "<input type='time' name='ee_startTime' id='ee_startTime' value='<%ee_startTime%>' maxlength='10' style='font-size:13px;width:40%;'> - <input type='time' name='ee_finishTime' id='ee_finishTime' value='<%ee_finishTime%>' maxlength='10' style='font-size:13px;width:40%'>";
		$("#time").html(str);
	}
	else {
		var str = "<input type='date' name='ee_startDay' id='ee_startDay' value='<%ee_startDay%>' maxlength='10' onkeyup='chkDate(this, event)' style='font-size:13px;width:40%;'> ~ <input type='date' name='ee_finishDay' id='ee_finishDay' value='<%ee_finishDay%>' maxlength='10' onkeyup='chkDate(this, event)' style='font-size:13px;width:40%'>";
		str += "<br><font style='font-size:8px'>&nbsp;</font>";
		str += "<br><input type='checkbox' name='ee_box[]' id='ee_box[]' value='mon' onclick='selectBox()'>월&nbsp;";
		str += "<input type='checkbox' name='ee_box[]' id='ee_box[]' value='tue' onclick='selectBox()'>화&nbsp;";
		str += "<input type='checkbox' name='ee_box[]' id='ee_box[]' value='wed' onclick='selectBox()'>수&nbsp;";
		str += "<input type='checkbox' name='ee_box[]' id='ee_box[]' value='thu' onclick='selectBox()'>목&nbsp;";
		str += "<input type='checkbox' name='ee_box[]' id='ee_box[]' value='fri' onclick='selectBox()'>금&nbsp;";
		str += "<input type='checkbox' name='ee_box[]' id='ee_box[]' value='sat' onclick='selectBox()'>토&nbsp;";
		str += "<input type='checkbox' name='ee_box[]' id='ee_box[]' value='sun' onclick='selectBox()'>일";
		$("#date").html(str);

		$("#time").html("");

	}
}

// 요일 선택시 옆에 textbox가 뜨게 하는 함수
function selectBox() {
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
			str += "월 : <input type='text' name='ee_monTime' placeholder='요일별 근무 가능 시간을 입력해주세요.' style='font-size:15px;width:80%'><br>";
			break;
		case "tue":
			str += "화 : <input type='text' name='ee_tueTime' placeholder='요일별 근무 가능 시간을 입력해주세요.' style='font-size:15px;width:80%'><br>";
			break;
		case "wed":
			str += "수 : <input type='text' name='ee_wedTime' placeholder='요일별 근무 가능 시간을 입력해주세요.' style='font-size:15px;width:80%'><br>";
			break;
		case "thu":
			str += "목 : <input type='text' name='ee_thuTime' placeholder='요일별 근무 가능 시간을 입력해주세요.' style='font-size:15px;width:80%'><br>";
			break;
		case "fri":
			str += "금 : <input type='text' name='ee_friTime' placeholder='요일별 근무 가능 시간을 입력해주세요.' style='font-size:15px;width:80%'><br>";
			break;
		case "sat":
			str += "토 : <input type='text' name='ee_satTime' placeholder='요일별 근무 가능 시간을 입력해주세요.' style='font-size:15px;width:80%'><br>";
			break;
		case "sun":
			str += "일 : <input type='text' name='ee_sunTime' placeholder='요일별 근무 가능 시간을 입력해주세요.' style='font-size:15px;width:80%'><br>";
			break;
		}
	}

	$("#time").html(str);
}

function chk_write() {
	if(employeeWrite.ee_title.value=="") {
	  alert("제목을 입력하세요.");
	  employeeWrite.ee_title.focus();
	  return false;
	}

	else if(employeeWrite.ee_sido.value=="") {
	  alert("시/도를 선택하세요.");
	  employeeWrite.ee_sido.focus();
	  return false;
	}

	else if(employeeWrite.ee_gugun.value=="") {
	  alert("구/군을 선택하세요.");
	  employeeWrite.ee_gugun.focus();
	  return false;
	}

	else if(employeeWrite.ee_dong.value=="") {
	  alert("동을 선택하세요.");
	  employeeWrite.ee_dong.focus();
	  return false;
	}
	
	else if(employeeWrite.ee_jobs1.value=="" || employeeWrite.ee_jobs2.value=="" || employeeWrite.ee_jobs3.value=="") {
	  alert("관심 업종을 선택하세요.");
	  return false;
	}

	else if(employeeWrite.ee_content.value=="") {
	  alert("자기 소개를 입력하세요.");
	  employeeWrite.ee_content.focus();
	  return false;
	}

	else {
		if (employeeWrite.ee_radio.value=="day") {
			if(employeeWrite.ee_day.value=="") {
				alert("가능 기간을 입력하세요.");
				return false;
			}
			else if(employeeWrite.ee_startTime.value=="" || employeeWrite.ee_finishTime.value=="") {
				alert("가능 시간을 입력하세요.");
				return false;
			}
		}	
		else {
			if(employeeWrite.ee_startDay.value=="" || employeeWrite.ee_finishDay.value=="") {
				alert("가능 기간을 입력하세요.");
				return false;
			}
			else if(employeeWrite.ee_startDay.value > employeeWrite.ee_finishDay.value) {
				alert("가능 기간을 다시 입력하세요.");
				return false;
			}
			else if($("#ee_box[]:checked").length == 0) {
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
mysql_close($connect);
?>
