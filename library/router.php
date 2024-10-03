<?php
if (!defined('_crmfb')) die("Truy cập trái phép");
$com = isset($_REQUEST['com']) ? $_REQUEST['com'] : "";
if (isset($_SESSION['login']) == false) {
	switch ($com) {
		case "login":
			$title = "Đăng nhập";
			break;
		case "register":
			$title = "Đăng ký";
			break;
		case "forgot_password":
			$title = "Lấy lại mật khẩu";
			break;
		default:
			header("location:login");
			break;
	}
	$model = "login/model.php";
	include_once(_mod . "/" . $model);
}
if (isset($_SESSION['login']) == true) {
	$d->query("SELECT * FROM tb_user WHERE taikhoan = '" . $_SESSION['username'] . "'");
	$taikhoan = $d->fetch_array();
	if ($taikhoan['level'] == 1) {
		switch ($com) {
			case "home":
				$title = "Khách hàng";
				break;
			case "user":
				$title = "Xem bình luận";
				break;
			case "post":
				$title = "Xem bài quảng cáo";
				break;
			case "setting":
				$title = "Cấu hình";
				break;
			case "doimatkhau":
				$title = "Đổi mật khẩu";
				break;
			case "logout":
				break;
			default:
				header("location:home");
				break;
		}
	}
	if ($taikhoan['level'] == 4) {
		switch ($com) {
			case "home":
				$title = "Khách hàng";
				break;
			case "comment":
				$title = "Bình luận";
				break;
			case "ads":
				$title = "Bài quảng cáo";
				break;
			case "realtime":
				$title = "Bài theo dõi";
				break;
			case "doimatkhau":
				$title = "Đổi mật khẩu";
				break;
			case "logout":
				break;
			default:
				header("location:home");
				break;
		}
	}
	if ($taikhoan['level'] == 5) {
		switch ($com) {
			case "comment":
				$title = "Bình luận";
				break;
			case "ads":
				$title = "Bài quảng cáo";
				break;
			case "realtime":
				$title = "Bài theo dõi";
				break;
			case "doimatkhau":
				$title = "Đổi mật khẩu";
				break;
			case "logout":
				break;
			default:
				header("location:comment");
				break;
		}
	}
	$model = $com . "/model.php";
	include_once(_mod . "/" . $model);
}
