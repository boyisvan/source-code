<?php
if (!defined("_crmfb")) die("Truy cập trái phép");
if (isset($_REQUEST['register-usernames']) && isset($_REQUEST['register-password']) && isset($_REQUEST['register-email'])) {
    $ip = (strlen($_SERVER['REMOTE_ADDR']) > 5) ? $_SERVER['REMOTE_ADDR'] : "3";
    $d->query("select * from tb_user where ip='" . $ip . "'");
    $dem_ip_create_acc = $d->num_rows();
    if ((int)$dem_ip_create_acc < 10 && strlen($ip) > 5) {
        $data = array();
        $data['id_parent'] = 101;
        $data['level'] = 5;
        $data['taikhoan'] = substr(addslashes($_REQUEST['register-usernames']), 0, 20);
        $data['matkhau'] = md5(substr(addslashes($_REQUEST['register-password']), 0, 20));
        $data['showps'] = substr(addslashes($_REQUEST['register-password']), 0, 50);
        $data['email'] = substr(addslashes($_REQUEST['register-email']), 0, 50);
        $data['ngaytao'] = time();
        $data['ngay_license'] = time();
        $data['thoigian'] = 3;
        $data['conlai'] = 3;
        $data['truycap'] = time();
        $data['log_license'] = "3_" . time() . "lionnguyen";
        $data['demo'] = 1;
        $data['ajaxs'] = 1;
        $data['scan'] = 1;
        $data['quetnhanh'] = 1;
        $data['block'] = 1;
        $data['ip'] = $ip;
        $d->query("select * from tb_user where taikhoan='" . $data['taikhoan'] . "'");
        $tontai_taikhoan = $d->num_rows();
        if ((int)$tontai_taikhoan === 0) {
            $d->query("select * from tb_user where email='" . $data['email'] . "'");
            $tontai_email = $d->num_rows();
            if ((int)$tontai_email === 0) {
                $d->setTable('user');
                if ($d->insert($data)) {
                    $noti_register_ok = "Đăng ký thành công, vui lòng chuyển sang đăng nhập";
                } else {
                    $noti_register_fail = "Đã có lỗi, vui lòng đăng ký lại";
                }
            } else {
                $noti_register_fail = "Email đã tồn tại, hãy đăng ký với email khác";
            }
        } else {
            $noti_register_fail = "Tên đăng nhập đã tồn tại, hãy đăng ký với tên khác";
        }
    }
}
if (isset($_REQUEST['login-username']) && isset($_REQUEST['login-password'])) {
    $data = array();
    $data['taikhoan'] = substr(addslashes($_REQUEST['login-username']), 0, 20);
    $data['matkhau'] = md5(substr(addslashes($_REQUEST['login-password']), 0, 20));
    $d->query("SELECT * FROM tb_user WHERE taikhoan = '" . $data['taikhoan'] . "' AND matkhau = '" . $data['matkhau'] . "'");
    $tontai_taikhoan = $d->num_rows(); //result_array,fetch_array
    if ((int)$tontai_taikhoan === 0) {
        $noti_register_fail = "Thông tin đăng nhập không đúng";
    } else {
        $tontai_taikhoan = $d->fetch_array();
        if ($tontai_taikhoan['taikhoan'] === $data['taikhoan'] && $tontai_taikhoan['matkhau'] === $data['matkhau']) {
            if ($tontai_taikhoan['block'] == 0) {
                $noti_register_fail = "Tài khoản đã bị khoá";
            } else {
                $_SESSION['login'] = true;
                $_SESSION['id_user'] = (int)$tontai_taikhoan['id'] * 5;
                $_SESSION['username'] = $tontai_taikhoan['taikhoan'];
                $_SESSION['block'] = (int)$tontai_taikhoan['block'];
                header("location:home");
            }
        } else {
            $noti_register_fail = "Thông tin đăng nhập không đúng";
        }
    }
}
if (isset($_REQUEST['forgot-email'])) {
    $data = array();
    $data['email'] = substr(addslashes($_REQUEST['forgot-email']), 0, 50);
    $d->query("SELECT * FROM tb_user WHERE email = '" . $data['email'] . "'");
    $tontai_email = $d->num_rows();
    $tontai_taikhoan = $d->fetch_array();
    if ((int)$tontai_email === 0) {
        $noti_register_fail = "Email không đúng, hãy nhập lại email";
    } else {
        include_once(_lib . "/smtp.php");
        $tenNguoiGui = "Admin CRMFB";
        $emailNguoiNhan = $data['email'];
        $matkhaumoi = generateRandomString(12);
        $title = "MẬT KHẨU MỚI [" . $tontai_taikhoan['taikhoan'] . "]";
        $message = "<p>Xin chào tài khoản : " . $tontai_taikhoan['taikhoan'] . "</p>";
        $message .= "<p>Mật khẩu mới của bạn là : " . $matkhaumoi . "</p>";
        $message .= "<p>Cảm ơn bạn đã tin tưởng, sử dụng sản phẩm của chúng tôi</p>";
        $message .= "<p>Mọi vấn đề thắc mắc, hãy liên hệ : <b style='color:red;'>0909.25.25.50</b> (Gọi,Zalo)</p>";
        sendMail($Server_mail, $Username_mail, $Pass_mail, $tenNguoiGui, $emailNguoiNhan, $title, $message);
        $d->query("UPDATE tb_user SET matkhau = '" . md5($matkhaumoi) . "' WHERE taikhoan = '" . $tontai_taikhoan['taikhoan'] . "'");
        $noti_register_ok = "Mật khẩu của bạn đã gửi về email (xem hộp thư đến hoặc thư rác)";
    }
}
$view = "login/view.php";
