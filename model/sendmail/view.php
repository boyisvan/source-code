<?php
if(!defined("_crmfb")) die ("Truy cập trái phép");
    if(filter_var($_REQUEST['email'], FILTER_VALIDATE_EMAIL)){
        include_once(_lib."/smtp.php");
        $tenNguoiGui = "ADMIN CRMFB";
        $emailNguoiNhan = $_REQUEST['email'];
        $title = "GỬI THỬ EMAIL THÀNH CÔNG";
        $message = "<h1 style='color:green;'>Chức năng gửi email vẫn hoạt động tốt</h1>";
        sendMail($Server_mail,$Username_mail,$Pass_mail,$tenNguoiGui,$emailNguoiNhan,$title,$message);
        $notifer_test_email_ok = "Đã gửi email đi";
    }else{
        $notifer_test_email_fail = "Email người nhận không hợp lệ";
    }
?>
<div class="content">
    <h1>Trạng thái gửi email</h1>
    <div class="fird">
        <?php if(isset($notifer_test_email_fail)){?>
        <p style="background:#f00;color:#fff;padding:10px;border-radius:5px;font-size:15px;"><?=$notifer_test_email_fail?></p>
        <?php }?>
        <?php if(isset($notifer_test_email_ok)){?>
        <p style="background:green;color:#fff;padding:10px;border-radius:5px;font-size:15px;"><?=$notifer_test_email_ok?></p>
        <?php }?>
    </div>
</div>