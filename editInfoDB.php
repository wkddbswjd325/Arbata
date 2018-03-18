<?php
/*
	editInfo에서 값을 받아와서 DB에 데이터 저장
	회원 정보 수정한 것을 DB에 저장하는 페이지
*/
header("Content-Type: text/html; charset= UTF-8 ");
session_start();

date_default_timezone_set("Asia/Seoul");

include_once('dbConnect.php');

// member 테이블에 있는 자신의 정보를 불러온다.
$query = "select * from member where mem_id='$_SESSION[id]'"; 
$result = mysql_query($query, $connect);
$data = mysql_fetch_array($result);

//imgae 테이블에 있는 자신의 사진정보를 불러온다.
$que = "select * from image where mem_id='$_SESSION[id]'"; 
$res = mysql_query($que, $connect);
$img = mysql_fetch_array($res);

$mem_id = $_SESSION['id'];
$mem_pwd = $_POST['mem_pwd'];
$mem_phone = $_POST['mem_phone'];
$mem_sido = $_POST['sido'];
$mem_gugun = $_POST['gugun'];
$mem_dong = $_POST['dong'];
$mem_email = $_POST['mem_email'];
$mem_sex = $data['mem_sex'];


// 이미지 삭제를 체크했을 경우 
if(isset($_POST['img_del'])) {

	// 기존 이미지가 서버에 저장된 기본 이미지인 female.png나 mal.png 가 아닐 경우
	if($img['img_title'] != "female.png" && $img['img_title'] != "male.png") {

		// DB에 저장된 파일 경로인 것이 실제로 있다면 
		if(is_file($_SERVER['DOCUMENT_ROOT'].$img['img_path'])) {

			// 해당 파일 경로의 파일을 삭제해라
			unlink($_SERVER['DOCUMENT_ROOT'].$img['img_path']);
		}
	}

	if($mem_sex == "m") {
			$folder = "male.png";
	}
	else if($mem_sex == "f") {
		$folder = "female.png";
	}

	// 남/여 기본사진을 경로에 저장
	$way = "/arbata/files/".$folder;

	// DB에도 해당 사항을 업데이트
	$query = "update image set img_path='$way', img_title='$folder' WHERE mem_id = '$mem_id'";

	$result = mysql_query($query, $connect);

	$sql="update member set mem_pwd='$mem_pwd', mem_phone='$mem_phone', mem_sido='$mem_sido', mem_gugun='$mem_gugun', mem_dong='$mem_dong', mem_email='$mem_email' WHERE mem_id = '$mem_id'";

	$res=mysql_query($sql, $connect);

	echo"<script>
		alert('수정이 완료되었습니다.');
		location.href='myPage.php';
		</script>";
	
	
}

// 이미지 삭제를 체크하지 않았을 경우
else if(!isset($_POST['img_del'])) {
	// 이미지를 업로드 했을 경우
	if(isset($_FILES['upload'])) {
		if($_FILES['upload']['error'] > 0) {
			switch($_FILES['upload']['error']) {
				case 1:
					echo"<script>
				alert('업로드 최대 용량 초과');
				history.go(-1);
				</script>";
				case 2:
					echo"<script>
				alert('업로드 최대 용량 초과');
				history.go(-1)
				</script>";
				case 3:
					echo"<script>
				alert('파일의 일부만 업로드 됨');
				history.go(-1)
				</script>";
				case 6:
					echo"<script>
				alert('사용가능한 임시폴더가 없음');
				history.go(-1)
				</script>";
				case 7:
					echo"<script>
				alert('디스크에 저장할수 없음');
				history.go(-1)
				</script>";
				case 8:
					echo"<script>
				alert('파일 업로드가 중지됨');
				history.go(-1)
				</script>";
				
			}
		}

		// 사진 용량이 너무 클 경우
		if($_FILES['upload']['size'] > 1000000) {
			echo"<script>
				alert('업로드 최대 용량이 초과되었습니다.');
				history.go(-1)
				</script>";
		}

		//기존 이미지 삭제
		if($img['img_title'] != "female.png" && $img['img_title'] != "male.png") {
			if(is_file($_SERVER['DOCUMENT_ROOT'].$img['img_path'])) {
				unlink($_SERVER['DOCUMENT_ROOT'].$img['img_path']);
			}
		}

		// 파일을 업로드할 임시폴더
		$tmpname = $_FILES['upload']['tmp_name'];

		if($tmpname) {
			$filename = strtolower($_FILES['upload']['name']);
			
			$img_size = getimagesize($_FILES['upload']['tmp_name']);
			$file_size = $_FILES['upload']['size'];

			$file_ext_arr = explode(".", $filename);
			$file_name = $file_ext_arr[0];
			$ext = end($file_ext_arr);
			$ext = strtolower($ext);

			// 파일이 사진일 경우에만
			if($ext == "jpg" || $ext == "jpeg" || $ext == "png" || $ext == "gif") {
				$folder = date("Y-m-d-h-i-s");

				// 임시폴더에서 내가 지정한 폴더로 옮긴다.
				move_uploaded_file($tmpname, $_SERVER['DOCUMENT_ROOT']."/arbata/files/".$folder.".".$ext);
				
				$way = "/arbata/files/".$folder.".".$ext;
				
				$query = "update image set img_path='$way', img_title='$filename', img_width='$img_size[0]', img_height='$img_size[1]', img_size='$file_size' WHERE mem_id = '$mem_id'";

				$result = mysql_query($query, $connect);
			}
		}

		// 이미지가 있다면 다시 업데이트
		else if(isset($img['img_path'])) {
			$query = "update image set img_path='$img[img_path]', img_title='$img[img_title]', 
			img_width='$img[img_width]',
			img_height='$img[img_height]', 
			img_size='$img[img_size]'
			WHERE mem_id = '$mem_id'";

			$result = mysql_query($query, $connect);
		}

		// 없다면 성별에 맞게 기본 이미지 등록
		else {
			if($mem_sex == "m") {
				$folder = "male.png";
			}
			else if($mem_sex == "f") {
				$folder = "female.png";
			}
			$way = "/arbata/files/".$folder;

			$query = "update image set img_path='$way', img_title='$folder' WHERE mem_id = '$mem_id'";

			$result = mysql_query($query, $connect);
		}
		
		$sql="update member set mem_pwd='$mem_pwd', mem_phone='$mem_phone', mem_sido='$mem_sido', mem_gugun='$mem_gugun', mem_dong='$mem_dong', mem_email='$mem_email' WHERE mem_id = '$mem_id'";

		$res=mysql_query($sql, $connect);

		echo"<script>
			alert('수정이 완료되었습니다.');
			location.href='myPage.php';
			</script>";
		
		mysql_close($connect);
	}
}
?>