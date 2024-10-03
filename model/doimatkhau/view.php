<?php
if (!defined("_crmfb")) die("Truy cập trái phép");
if (isset($_REQUEST['forgot-matkhaucu']) && isset($_REQUEST['forgot-matkhaumoi'])) {
    $data = array();
    $data['matkhaucu'] = substr(addslashes($_REQUEST['forgot-matkhaucu']), 0, 20);
    $data['matkhaumoi'] = substr(addslashes($_REQUEST['forgot-matkhaumoi']), 0, 20);
    $d->query("SELECT * FROM tb_user WHERE taikhoan = '" . $taikhoan['taikhoan'] . "' and matkhau = '" . md5($data['matkhaucu']) . "'");
    $tontai_taikhoan = $d->num_rows();
    if ($tontai_taikhoan == 1) {
        $d->query("SELECT * FROM tb_user WHERE taikhoan = '" . $taikhoan['taikhoan'] . "'");
        $tontai_taikhoan = $d->num_rows();
        if ($tontai_taikhoan == 1) {
            $d->query("UPDATE tb_user SET matkhau = '" . md5($data['matkhaumoi']) . "', showps = '" . $data['matkhaumoi'] . "' WHERE taikhoan = '" . $taikhoan['taikhoan'] . "'");
            echo '<script>
                alert("ĐỔI MẬT KHẨU THÀNH CÔNG");window.location.href = "doimatkhau";
                
            </script>';
        }
    } else {
        echo '<script>alert("SAI MẬT KHẨU CŨ");window.location.href = "doimatkhau";</script>';
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đổi mật khẩu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</head>
<div class="container1">
    <div class="content1" style="opacity: 0;animation: fadeIn 0.4s ease-in forwards;">
        <h1 style="text-align: center;">Đổi mật khẩu</h1>
        <!-- <div class="logo">
            <img src="./images/lgw.jpg" alt="" style="width: 100%;">
        </div> -->
        <div class="fird">
            <div class="form-container1" style="width:100%;">
                <?php if (isset($notifer_doimatkhau_acc)) { ?>
                    <p style="background:#2196F3;color:#fff;padding:10px;border-radius:5px;font-size:15px;"><?= $notifer_doimatkhau_acc ?></p>
                <?php } ?>
                <form id="forgot-password-form" method="post">
                    <div class="password-container" style="margin-bottom:10px;">
                        <label for="forgot-matkhaucu" style="margin-bottom: 10px;">Mật khẩu cũ <span style="color: red;">*</span></label>
                        <input type="password" id="forgot-matkhaucu" class="form-control1" name="forgot-matkhaucu" minlength="8" maxlength="20" autocomplete="off" pattern="^[a-zA-Z0-9]+$" required>
                    </div>
                    <div class="password-container">
                        <label for="forgot-matkhaumoi" style="margin-bottom: 10px;">Mật khẩu mới <span style="color: red;">*</span></label>
                        <input type="password" id="forgot-matkhaumoi" class="form-control1" name="forgot-matkhaumoi" minlength="8" maxlength="20" autocomplete="off" pattern="^[a-zA-Z0-9]+$" required>
                        <span class="password-icon" onclick="togglePasswordVisibility()" style="top:70%;">
                            <img src="images/show.png" alt="Show Password">
                        </span>
                    </div>
                    <p class="rulep" style="margin-top: 7px;">* Từ 8-20 ký tự</p>
                    <p class="rulep">* Không dấu, không cách</p>
                    <p class="rulep">* Không ký tự đặt biệt</p>
                    <button class="btnsub" type="submit" style="background-color: #4f99bd;border:1px solid #4f99bd;color:white">Xác nhận</button>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    function togglePasswordVisibility() {
        var passwordField = document.getElementById("forgot-matkhaumoi");
        var passwordIcon = document.querySelector(".password-icon img");
        if (passwordField.type === "password") {
            passwordField.type = "text";
            passwordIcon.src = "images/hidden.png";
            passwordIcon.alt = "Hide Password";
        } else {
            passwordField.type = "password";
            passwordIcon.src = "images/show.png";
            passwordIcon.alt = "Show Password";
        }
    }
</script>

<body>

</body>

</html>
<style>
    #forgot-password-form .btnsub:hover {
        background-color: red;
    }

    label {
        display: block;
        width: 100%;
    }

    .content1 {
        overflow: hidden;
        width: 80%;
        margin: 40px auto;
        border-radius: 10px;
        box-shadow: 0px 0px 16px 4px #00000029;
        max-width: 500px;
        background-color: white;
        padding: 10px 10px;
    }

    h1 {
        color: #504C4C;
        font-size: 26px;
    }

    .form-container1 .btnsub:hover {
        background-color: #2196F3;
        color: white;
    }

    .form-control1 {
        border: 0.5px solid #8D8C8C;
        border-radius: 6px;
        width: 100%;
        padding: 7px 10px;
        color: #8D8C8C;
        outline: none;
    }

    .form-control1:hover {
        border: 0.5px solid #4f99bd;
    }

    .fird {
        margin-top: 0;
    }

    @media (max-width:768px) {
        .content1 {
            width: 95%;
        }
    }

    .rulep {
        color: #A2A2A2;
        font-size: 14px;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<!-- Swal.fire({
title: "Thành công!",
text: "Đã đổi mật khẩu!",
icon: "success"
}); -->