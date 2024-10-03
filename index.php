<?php
date_default_timezone_set('Asia/Ho_Chi_Minh');
error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE);
session_start();
define("_crmfb", "CRMFB");
define("_lib", "library");
define("_mod", "model");
include_once(_lib . "/config.php");
include_once(_lib . "/database.php");
include_once(_lib . "/function.php");
include_once(_lib . "/router.php");
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" sizes="256x256" href="../source-code/images/logoweb.jpg" type="image/x-icon" />
    <title><?= $title ?></title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <script type="text/javascript" src="js/jquery.min.js"></script>
    <script type="text/javascript" src="js/notify.min.js"></script>
</head>

<body>
    <?php if (isset($_SESSION['login']) == true) { ?>
        <div id="loading-container">
            <div class="loading-spinner"></div>
        </div>
        <div class="top-menu">
            <ul>
                <?php if ($taikhoan['level'] == 1) { ?>
                    <li><a href="./">Quản lý tài khoản</a></li>
                    <li><a href="setting">TN</a></li>
                    <li><a href="doimatkhau">Đổi mật khẩu</a></li>
                <?php } ?>
                <?php if ($taikhoan['level'] == 4) { ?>
                    <li><a href="./">Quản lý tài khoản</a></li>
                    <li><a href="comment">Bình luận</a></li>
                    <li><a href="ads">Quét comment và comment ẩn </a></li>
                    <li><a href="realtime?limit=500">Theo dõi thêm bài</a></li>
                    <li><a href="doimatkhau">Đổi mật khẩu</a></li>
                <?php } ?>
                <?php if ($taikhoan['level'] == 5) { ?>
                    <li><a href="comment">Bình luận <i class="fa-regular fa-comment"></i></a></li>
                    <li><a href="ads">Quét comment và comment ẩn </a></li>
                    <li><a href="realtime?limit=500">Theo dõi thêm bài</a></li>
                    <li><a href="doimatkhau">Đổi mật khẩu</a></li>
                <?php } ?>
            </ul>
            <div class="hu" style="float: right;"><a onclick="return confirmLogout()">Xin chào, <span style="color:white;font-weight: bold;"><?= $taikhoan['taikhoan'] ?></span></a></div>
        </div>
    <?php } ?>
    <?php include_once(_mod . "/" . $view); ?>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            window.addEventListener("load", function() {
                document.getElementById("loading-container").style.display = "none";
            });
        });
    </script>
</body>

</html>

<style>
    .top-menu {
        display: flex;
        justify-content: space-around;
        align-items: center;
        position: relative;
    }

    .top-menu ul li a {
        /* border-radius: 7px; */
    }

    .top-menu ul li a:hover {
        background-color: #4f99bd;
        /* border-bottom: 1px solid black; */
    }

    .hu {
        height: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .hu a {
        color: white;
        text-decoration: none;
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    async function confirmLogout() {
        const result = await Swal.fire({
            title: 'Bạn có chắc muốn đăng xuất?',
            text: "Thao tác này sẽ đưa bạn ra khỏi tài khoản.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Đăng xuất',
            cancelButtonText: 'Hủy'
        });

        if (result.isConfirmed) {
            window.location.href = "logout";
            return true;
        } else {
            return false;
        }
    }
</script>