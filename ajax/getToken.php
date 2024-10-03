<?php
date_default_timezone_set('Asia/Ho_Chi_Minh');
set_time_limit(0);
session_start();
define("_crmfb", "CRMFB");
include_once('/home/tool.vuaquetcommentfbads.com/public_html/library/config.php');
//Connect DB
$conn = mysqli_connect($config["database"]["servername"], $config["database"]["username"], $config["database"]["password"], $config["database"]["database"]) or die('Not Connect DB');
mysqli_set_charset($conn, "utf8");

//Cập nhật thời gian cho tất cá khách
$sql = "SELECT taikhoan,ngay_license,thoigian,block FROM tb_user WHERE level = 5";
$result = mysqli_query($conn, $sql);
while ($result_acc = mysqli_fetch_assoc($result)) {
    if ($result_acc['block'] != 0) {
        $date_current = time();
        $timeout = abs(floor(((int)$date_current - (int)$result_acc['ngay_license']) / (24 * 60 * 60)));
        $timeout = ($timeout > (int)$result_acc['thoigian']) ? 0 : (int)$result_acc['thoigian'] - $timeout;
        $sql = "UPDATE tb_user SET conlai = " . $timeout;
        if ($timeout == 0) {
            $sql .= ",scan = 0,ajaxs = 0,demo = 1,block = 1";
        }
        $sql .= " WHERE taikhoan = '" . $result_acc['taikhoan'] . "'";
        mysqli_query($conn, $sql);
    }
}
//Xoá bình luận cũ từ 2 ngày trước
$thoiGianHienTai = time();
$thoiGianTruoc2Ngay = strtotime('-2 days', $thoiGianHienTai);
$sql = "delete from tb_data WHERE ngaytao < $thoiGianTruoc2Ngay";
//mysqli_query($conn,$sql);
//
echo "ok ne";
