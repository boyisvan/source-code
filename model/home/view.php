<?php
if (!defined("_crmfb")) die("Truy cập trái phép");
?>
<?php if ($taikhoan['level'] == 1) { ?>
    <?php
    //Add acc
    if (isset($_REQUEST['register-taikhoan']) && isset($_REQUEST['register-matkhau']) && isset($_REQUEST['register-email'])) {
        $data = array();
        $data['id_parent'] =  (int)($_SESSION['id_user']) / 5;
        $data['level'] = addslashes($_REQUEST['register-level']);
        $data['taikhoan'] = substr(addslashes($_REQUEST['register-taikhoan']), 0, 20);
        $data['matkhau'] = md5(substr(addslashes($_REQUEST['register-matkhau']), 0, 20));
        $data['showps'] = substr(addslashes($_REQUEST['register-matkhau']), 0, 50);
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
        $d->query("select * from tb_user where taikhoan='" . $data['taikhoan'] . "'");
        $tontai_taikhoan = $d->num_rows();
        if ((int)$tontai_taikhoan === 0) {
            $d->query("select * from tb_user where email='" . $data['email'] . "'");
            $tontai_email = $d->num_rows();
            if ((int)$tontai_email === 0) {
                $d->setTable('user');
                if ($d->insert($data)) {
                    echo '<script>alert("THÊM THÀNH CÔNG");window.location.href = "./";</script>';
                } else {
                    echo '<script>alert("lỖI, HÃY THỬ LẠI");window.location.href = "./";</script>';
                }
            } else {
                echo '<script>alert("EMAIL ĐÃ TỒN TẠI");window.location.href = "./";</script>';
            }
        } else {
            echo '<script>alert("TÀI KHOẢN ĐÃ TỒN TẠI");window.location.href = "./";</script>';
        }
    }
    //Reset acc
    if (isset($_REQUEST['action']) && $_REQUEST['action'] == "reset_pass_acc" && isset($_REQUEST['verify']) && $_REQUEST['verify'] == "reset") {
        $matkhaumoi = generateRandomString(12);
        $d->query("UPDATE tb_user SET matkhau = '" . md5($matkhaumoi) . "', showps = '" . $matkhaumoi . "' WHERE id = " . (int)$_REQUEST['id']);
        if (mysqli_affected_rows($d->db)) {
            $notifer_delete_acc = "Mật khẩu mới của [" . $_REQUEST['email'] . "] là : " . $matkhaumoi;
        } else {
            header("location:./");
        }
    }
    //Xoá acc
    if (isset($_REQUEST['action']) && $_REQUEST['action'] == "delete_acc" && isset($_REQUEST['verify']) && $_REQUEST['verify'] == "delete") {
        $d->query("DELETE FROM tb_user WHERE id = " . (int)$_REQUEST['id']);
        if (mysqli_affected_rows($d->db)) {
            $notifer_delete_acc = "Đã xoá thành công : [" . $_REQUEST['email'] . "]";
        } else {
            header("location:./");
        }
    }
    //Demo acc
    if (isset($_REQUEST['action']) && $_REQUEST['action'] == "demo_acc") {
        $d->query("SELECT demo FROM tb_user WHERE id = " . (int)$_REQUEST['id']);
        $result_acc = $d->fetch_array();
        if ($result_acc['demo'] == 1) {
            $d->query("UPDATE tb_user SET demo = 0 WHERE id = " . (int)$_REQUEST['id']);
        } else {
            $d->query("UPDATE tb_user SET demo = 1 WHERE id = " . (int)$_REQUEST['id']);
        }
        header("location:./");
    }
    //Ajaxs acc
    if (isset($_REQUEST['action']) && $_REQUEST['action'] == "ajaxs_acc") {
        $d->query("SELECT ajaxs FROM tb_user WHERE id = " . (int)$_REQUEST['id']);
        $result_acc = $d->fetch_array();
        if ($result_acc['ajaxs'] == 1) {
            $d->query("UPDATE tb_user SET ajaxs = 0 WHERE id = " . (int)$_REQUEST['id']);
        } else {
            $d->query("UPDATE tb_user SET ajaxs = 1 WHERE id = " . (int)$_REQUEST['id']);
        }
        header("location:./");
    }
    //5s acc
    if (isset($_REQUEST['action']) && $_REQUEST['action'] == "5s_acc") {
        $d->query("SELECT quetnhanh FROM tb_user WHERE id = " . (int)$_REQUEST['id']);
        $result_acc = $d->fetch_array();
        if ($result_acc['quetnhanh'] == 1) {
            $d->query("UPDATE tb_user SET quetnhanh = 0 WHERE id = " . (int)$_REQUEST['id']);
        } else {
            $d->query("UPDATE tb_user SET quetnhanh = 1 WHERE id = " . (int)$_REQUEST['id']);
        }
        header("location:./");
    }
    //Scan acc
    if (isset($_REQUEST['action']) && $_REQUEST['action'] == "scan_acc") {
        $d->query("SELECT scan FROM tb_user WHERE id = " . (int)$_REQUEST['id']);
        $result_acc = $d->fetch_array();
        if ($result_acc['scan'] == 1) {
            $d->query("UPDATE tb_user SET scan = 0 WHERE id = " . (int)$_REQUEST['id']);
        } else {
            $d->query("UPDATE tb_user SET scan = 1 WHERE id = " . (int)$_REQUEST['id']);
        }
        header("location:./");
    }
    //Block acc
    if (isset($_REQUEST['action']) && $_REQUEST['action'] == "block_acc") {
        $d->query("SELECT block FROM tb_user WHERE id = " . (int)$_REQUEST['id']);
        $result_acc = $d->fetch_array();
        if ($result_acc['block'] == 1) {
            $d->query("UPDATE tb_user SET block = 0 WHERE id = " . (int)$_REQUEST['id']);
        } else {
            $d->query("UPDATE tb_user SET block = 1 WHERE id = " . (int)$_REQUEST['id']);
        }
        header("location:./");
    }
    //Package acc
    if (isset($_REQUEST['action']) && $_REQUEST['action'] == "Package") {
        $d->query("SELECT block FROM tb_user WHERE id = " . (int)$_REQUEST['id']);
        $result_acc = $d->fetch_array();
        $d->query("UPDATE tb_user SET block = " . (int)$_REQUEST['block'] . " WHERE id = " . (int)$_REQUEST['id']);
        echo '<script>alert("CẬP NHẬT THÀNH CÔNG\n- Tài khoản : ' . $_REQUEST["email"] . '");window.location.href = "./";</script>';
    }
    //Limit_Post acc
    if (isset($_REQUEST['action']) && $_REQUEST['action'] == "limit_post") {
        $d->query("SELECT * FROM tb_user WHERE id = " . (int)$_REQUEST['id']);
        $result_acc = $d->fetch_array();
        $date_current = time();
        $limit_post = (int)$_GET['limit_post'];
        $d->query("UPDATE tb_user SET limit_post = " . $limit_post . " WHERE id = " . (int)$_REQUEST['id']);
        echo '<script>alert("CẬP NHẬT SỐ LƯỢNG POST THÀNH CÔNG\n- Tài khoản : ' . $_REQUEST["email"] . '");window.location.href = "./";</script>';
    }
    //Limit_Post_Buy acc
    if (isset($_REQUEST['action']) && $_REQUEST['action'] == "limit_post_buy") {
        $d->query("SELECT * FROM tb_user WHERE id = " . (int)$_REQUEST['id']);
        $result_acc = $d->fetch_array();
        $date_current = time();
        $limit_post_buy = (int)$_GET['limit_post_buy'];
        $d->query("UPDATE tb_user SET limit_post_buy = " . $limit_post_buy . " WHERE id = " . (int)$_REQUEST['id']);
        echo '<script>alert("CẬP NHẬT SỐ LƯỢNG POST_BUY THÀNH CÔNG\n- Tài khoản : ' . $_REQUEST["email"] . '");window.location.href = "./";</script>';
    }

    //Delay_time acc
    if (isset($_REQUEST['action']) && $_REQUEST['action'] == "delay_time") {
        $d->query("SELECT * FROM tb_user WHERE id = " . (int)$_REQUEST['id']);
        $result_acc = $d->fetch_array();
        $date_current = time();
        $delay_time = (int)$_GET['delay_time'];
        $d->query("UPDATE tb_user SET delay = " . $delay_time . " WHERE id = " . (int)$_REQUEST['id']);
        echo '<script>alert("CẬP NHẬT DELAYT THÀNH CÔNG\n- Tài khoản : ' . $_REQUEST["email"] . '");window.location.href = "./";</script>';
    }

    //Thoigian acc
    if (isset($_REQUEST['action']) && $_REQUEST['action'] == "thoigian_acc") {
        $d->query("SELECT * FROM tb_user WHERE id = " . (int)$_REQUEST['id']);
        $result_acc = $d->fetch_array();
        $date_current = time();
        //$timeout = abs(floor(((int)$date_current - (int)$result_acc['ngay_license'])/(24*60*60)));
        //$timeout = ($timeout > (int)$result_acc['thoigian'])?0:(int)$result_acc['thoigian'] - $timeout;
        //$timeout = ((int)$_GET['thoigian']>0)?$timeout + ((int)$_GET["thoigian"]):0;
        $timeout = (int)$_GET['thoigian'];
        $log_license = $_GET["thoigian"] . "_" . $date_current . "lionnguyen";
        $d->query("UPDATE tb_user SET ngay_license = " . $date_current . ",thoigian = " . $timeout . ",conlai = " . $timeout . ",log_license = concat(log_license,'" . $log_license . "') WHERE id = " . (int)$_REQUEST['id']);
        if (mysqli_affected_rows($d->db)) {
            echo '<script>alert("CẬP NHẬT THỜI GIAN THÀNH CÔNG\n- Tài khoản : ' . $_REQUEST["email"] . '");window.location.href = "./";</script>';
        } else {
            header("location:./");
        }
    }
    //Update Thoigian All
    if (isset($_REQUEST['action']) && $_REQUEST['action'] == "update_thoigian_all") {
        $d->query("SELECT * FROM tb_user WHERE level = 5");
        $result_acc = $d->result_array();
        for ($i = 0; $i < count($result_acc); $i++) {
            $date_current = time();
            $timeout = abs(floor(((int)$date_current - (int)$result_acc[$i]['ngay_license']) / (24 * 60 * 60)));
            $timeout = ($timeout > (int)$result_acc[$i]['thoigian']) ? 0 : (int)$result_acc[$i]['thoigian'] - $timeout;
            $sql = "UPDATE tb_user SET conlai = " . $timeout;
            if ($timeout == 0) {
                $sql .= ",scan = 0,ajaxs = 0,demo = 1";
            }
            $sql .= " WHERE taikhoan = '" . $result_acc[$i]['taikhoan'] . "'";
            $d->query($sql);
            echo "<script>alert('Đã cập nhật tất cả thành công');window.location.href = './';</script>";
        }
    }
    //Tối ưu hóa dữ liệu
    if (isset($_REQUEST['action']) && $_REQUEST['action'] == "toiuuhoa") {
        $sql = "DELETE FROM tb_data WHERE id_user NOT IN (SELECT id FROM tb_user)";
        $d->query($sql);
        $sql = "DELETE FROM tb_post WHERE id_user NOT IN (SELECT id FROM tb_user)";
        $d->query($sql);
        $sql = "OPTIMIZE TABLE `tb_data`,`tb_user`";
        $d->query($sql);
        echo "<script>alert('Đã xóa và tối ưu dữ liệu thừa');window.location.href = './';</script>";
    }
    //Lấy dữ liệu danh sách khách sử dụng
    $sql = "SELECT * FROM tb_user WHERE id_parent = 101";
    if (isset($_REQUEST['han']) && $_REQUEST['han'] == 'hoatdong') {
        $sql .= " and conlai<>0";
    }
    if (isset($_REQUEST['han']) && $_REQUEST['han'] == 'hethan') {
        $sql .= " and conlai=0";
    }

    $sql .= " order by level desc";
    $d->query($sql);
    $khachhang = $d->result_array();
    //
    //Dữ liệu biểu đồ
    if (isset($_GET['static_date'])) {
        if ($_GET['static_date'] == '') {
            $static_date = date("Y-m-d");
        } else {
            $static_date = $_GET['static_date'];
        }
    } else {
        $static_date = date("Y-m-d");
    }

    //___UID_tổng/ngày
    $sql = "SELECT COUNT(DISTINCT comment_uid) as sl FROM tb_data WHERE ngaytao>=UNIX_TIMESTAMP('" . $static_date . "') AND ngaytao<=UNIX_TIMESTAMP('" . $static_date . " 23:59:59')";
    $d->query($sql);
    $dem_uid_ngay = $d->fetch_array();
    //---
    $sql = "SELECT from_unixtime(ngaytao,'%H') as h, COUNT(DISTINCT comment_uid) as sl FROM tb_data WHERE ngaytao>=UNIX_TIMESTAMP('" . $static_date . "') AND ngaytao<=UNIX_TIMESTAMP('" . $static_date . " 23:59:59') GROUP BY from_unixtime(ngaytao,'%d/%m/%Y-%H')";
    $d->query($sql);
    $tk_cmth = $d->result_array();
    $arr_tkcmt_h = array();
    $arr_a1 = array();
    foreach ($tk_cmth as $v) {
        $arr_tkcmt_h[] = $v['h'];
        $arr_a1[(int)$v['h']] = (int)$v['sl'];
    }
    //Phone tổng ngày
    $sql = "SELECT COUNT(DISTINCT phone) as sl FROM tb_data WHERE  phone!='0' AND ngaytao>=UNIX_TIMESTAMP('" . $static_date . "') AND ngaytao<=UNIX_TIMESTAMP('" . $static_date . " 23:59:59')";
    $d->query($sql);
    $dem_phone_ngay = $d->fetch_array();
    //---
    $sql = "SELECT from_unixtime(ngaytao,'%H') as h, COUNT(DISTINCT phone) as sl FROM tb_data WHERE phone!='0' AND ngaytao>=UNIX_TIMESTAMP('" . $static_date . "') AND ngaytao<=UNIX_TIMESTAMP('" . $static_date . " 23:59:59') GROUP BY from_unixtime(ngaytao,'%d/%m/%Y-%H')";
    $d->query($sql);
    $tk_phoneh = $d->result_array();
    $arr_b1 = array();
    foreach ($tk_phoneh as $v) {
        $arr_b1[(int)$v['h']] = (int)$v['sl'];
    }
    $arr_a2 = array();
    $arr_a3 = array();
    $arr_b3 = array();
    if (!empty($arr_tkcmt_h)) {
        $max_value = max(array_values($arr_tkcmt_h)) + 1;
    } else {
        $max_value = 1; // Bạn có thể đặt giá trị mặc định khác nếu cần
    }

    for ($i = 0; $i < $max_value; $i++) {
        if (array_key_exists($i, $arr_a1)) {
            $arr_a3[] = $arr_a1[$i];
        } else {
            $arr_a3[] = 0;
        }
        $list_arr_a3 = implode(",", $arr_a3);

        if (array_key_exists($i, $arr_b1)) {
            $arr_b3[] = $arr_b1[$i];
        } else {
            $arr_b3[] = 0;
        }
        $list_arr_b3 = implode(",", $arr_b3);

        $arr_a2[] = $i;
        $list_arr_a2 = implode(",", $arr_a2);
    }
    // for ($i = 0; $i < max(array_values($arr_tkcmt_h)) + 1; $i++) {
    //     if (array_key_exists($i, $arr_a1)) {
    //         $arr_a3[] = $arr_a1[$i];
    //     } else {
    //         $arr_a3[] = 0;
    //     }
    //     $list_arr_a3 = implode(",", $arr_a3);
    //     if (array_key_exists($i, $arr_b1)) {
    //         $arr_b3[] = $arr_b1[$i];
    //     } else {
    //         $arr_b3[] = 0;
    //     }
    //     $list_arr_b3 = implode(",", $arr_b3);
    //     $arr_a2[] = $i;
    //     $list_arr_a2 = implode(",", $arr_a2);
    // }
    ?>
    <style>
        .tkvathem {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
        }

        .thongke,
        .them {
            flex-basis: 50%;
            /* height: 500px; */
        }

        .fird {
            border-radius: 10px;
            box-shadow: 0 0 6px 4px #00000029;
            height: 400px;
        }

        .idataa {
            margin-top: 15px;
            margin-bottom: 10px;
        }

        .idataa:nth-child(1) {
            margin-top: 10px;
        }

        .content {
            width: 94%;
            margin: 0 auto;
        }

        @media (max-width:768px) {
            .tkvathem {
                flex-direction: column;
            }
        }
    </style>
    <div class="content">
        <div class="tkvathem">
            <div class="thongke">
                <h1>Thống kê</h1>
                <div class="fird">
                    <script src="./js/Chart.min.js"></script>
                    <input type="date" class="form-control" value="<?php if (isset($_GET['static_date']) && $_GET['static_date'] != '') {
                                                                        echo $_GET['static_date'];
                                                                    } else {
                                                                        echo date('Y-m-d');
                                                                    } ?>" onchange="window.location='home?static_date='+this.value">
                    <canvas id="myChart" width="800" height="150"></canvas>
                    <script>
                        var ctx = document.getElementById('myChart').getContext('2d');
                        var myChart = new Chart(ctx, {
                            type: 'line',
                            data: {
                                labels: [<?= $list_arr_a2 ?>],
                                datasets: [{
                                    label: 'CMT (<?= $dem_uid_ngay['sl'] ?>)',
                                    backgroundColor: "#5ca3ff",
                                    borderColor: "#5ca3ff",
                                    data: [<?= $list_arr_a3 ?>],
                                    fill: false,
                                }, {
                                    label: 'Phone (<?= $dem_phone_ngay['sl'] ?>)',
                                    backgroundColor: "#ffd443",
                                    borderColor: "#ffd443",
                                    data: [<?= $list_arr_b3 ?>],
                                    fill: false,
                                    //borderDash: [5, 5],
                                    //pointRadius: 15,
                                    //pointHoverRadius: 10,
                                }]
                            },
                            options: {
                                responsive: true,
                                title: {
                                    display: true,
                                    text: 'Thống kê (Ngày <?= $static_date ?>)',
                                },
                                tooltips: {
                                    mode: 'index',
                                    intersect: false,
                                },
                                hover: {
                                    mode: 'nearest',
                                    intersect: true
                                },
                                scales: {
                                    xAxes: [{
                                        display: true,
                                        scaleLabel: {
                                            display: true,
                                            labelString: 'Thời gian'
                                        }
                                    }],
                                    yAxes: [{
                                        ticks: {
                                            //min: 0,
                                            //max: 200
                                        }
                                    }]
                                }
                            }
                        });
                    </script>
                </div>
            </div>
            <div class="them">
                <h1 style="text-align: center;">Thêm tài khoản mới</h1>
                <div class="fird">
                    <div class="form-container" style="width:100%;">
                        <form id="register-password-form" method="post">
                            <label for="register-level">Loại tài khoản</label>
                            <select id="register-level" name="register-level">
                                <option value="4">CTV</option>
                                <option value="5" selected>Khách hàng</option>
                            </select>
                            <p style="margin:0;padding:0;font-size:13px;padding:5px 0px 10px 0px;color:red;"></p>
                            <div class="password-container">
                                <!-- <label for="register-taikhoan">Tài khoản</label> -->
                                <input type="text" placeholder="Tên đăng nhập" class="idataa" id="register-taikhoan" name="register-taikhoan" minlength="8" maxlength="20" autocomplete="off" pattern="^[a-zA-Z0-9]+$" required>
                            </div>
                            <!-- <p style="margin:0;padding:0;font-size:13px;padding:5px 0px 10px 0px;color:red;">(8-20 ký tự, không dấu, không cách, không ký tự đặt biệt)</p> -->
                            <div class="password-container">
                                <!-- <label for="register-matkhau">Mật khẩu</label> -->
                                <input type="password" placeholder="Mật khẩu" class="idataa" id="register-matkhau" name="register-matkhau" minlength="8" maxlength="20" autocomplete="off" pattern="^[a-zA-Z0-9]+$" required>
                                <!-- <span class="password-icon" onclick="togglePasswordVisibility()" style="top:70%;">
                                    <img src="images/show.png" alt="Show Password">
                                </span> -->
                            </div>
                            <!-- <label for="register-email">Email</label> -->
                            <input type="email" id="register-email" name="register-email" placeholder="Email" class="idataa" required>
                            <p style="margin:0;padding:0;font-size:13px;padding:5px 0px 10px 0px;color:#aaa;">* 8-20 ký tự, không dấu, không cách, không ký tự đặt biệt</p>
                            <button type="submit" style="font-size: 16px;border-radius: 11px;background: rgb(0,120,246);background: linear-gradient(166deg, rgba(0,120,246,1) 39%, rgba(59,255,178,1) 100%);">Thêm tài khoản</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <?php if (isset($notifer_delete_acc)) { ?>
            <p style="background:#3f9843;color:#fff;padding:10px;border-radius:5px;font-size:15px;"><?= $notifer_delete_acc ?></p>
        <?php } ?>
        <h1>Danh sách khách hàng</h1>
        <div class="fird" style="height: auto;margin-bottom: 20px;">
            <div class="mua" style="display:none;">
                <a href="?han=hoatdong"><button type="submit" style=" white-space:nowrap;position:unset;border-radius:4px;margin-bottom:20px;display:inline-block;width:90px;">Hoạt động</button></a>
                <a href="?han=hethan"><button type="submit" style=" white-space:nowrap;position:unset;border-radius:4px;margin-bottom:20px;background:#FF5722;display:inline-block;width:90px;">Hết hạn</button></a>
            </div>
            <div class="table-container">
                <table style="zoom:0.8;">
                    <thead>
                        <tr>
                            <th>STT</th>
                            <th style="width: 130px;">Loại</th>
                            <th>Tài khoản</th>
                            <th class="email_mobi">Email</th>
                            <th>Cmt / Ngày</th>
                            <th>Trạng thái</th>
                            <th>Giới hạn bài viết</th>
                            <th>Giới hạn mua bài viết</th>
                            <th>Sô ngày hết hạn</th>
                            <th>Thông tin thanh toán</th>
                            <th>Ngày tạo</th>
                            <th>Ngày mua</th>
                            <th>Hết hạn</th>
                            <th>Truy cập</th>
                            <th>Tuỳ chọn</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php for ($i = 0; $i < count($khachhang); $i++) {
                            $stt = $i + 1;
                            $d->query("SELECT uid FROM tb_post WHERE scan = 1 and id_user='" . $khachhang[$i]['id'] . "'");
                            $danhsachuid_acc = $d->result_array();
                            $danhsachuid_acc_new = array();
                            foreach ($danhsachuid_acc as $row) {
                                $danhsachuid_acc_new[] = $row['uid'];
                            }
                            $d->query("SELECT COUNT(id) as sl FROM tb_post WHERE scan=1 and id_user='" . $khachhang[$i]['id'] . "'");
                            $limit_post = $d->fetch_array();
                            $d->query("SELECT COALESCE(SUM(limit_post), 0) as sl FROM tb_user WHERE id_parent='" . $khachhang[$i]['id'] . "'");
                            $limit_post_buy = $d->fetch_array();
                            $d->query("SELECT COUNT(DISTINCT comment_uid) as sl FROM tb_data WHERE id_user='" . $khachhang[$i]['id'] . "' AND ngaytao>=UNIX_TIMESTAMP(CURDATE())");
                            $data_ngay = $d->fetch_array();
                            $d->query("SELECT COUNT(DISTINCT comment_uid) as sl FROM tb_data WHERE id_user='" . $khachhang[$i]['id'] . "'");
                            $data_tong = $d->fetch_array();
                        ?>
                            <tr>
                                <td style="text-align: center;"><?= $stt ?></td>
                                <td style="width: 130px;"><?= ($khachhang[$i]['id_parent'] == 101 && $khachhang[$i]['level'] == 5) ? 'Khách hàng' : (($khachhang[$i]['id_parent'] == 101) ? "Cộng tác viên" : $taikhoan_ctv['taikhoan']) ?></td>
                                <td><?= $khachhang[$i]['taikhoan'] ?></td>
                                <td class="email_mobi"><?= $khachhang[$i]['email'] ?></td>
                                <td style="text-align:center;">
                                    <?= $data_ngay['sl'] ?>/<?= $data_tong['sl'] ?>
                                </td>
                                <td style="width:200px;">
                                    <select name="Package" id="Package" onchange="Package(this,<?= $khachhang[$i]['id'] ?>,'<?= $khachhang[$i]['taikhoan'] ?>');" style="border:1px solid #ccc;border-radius:5px;padding:10px 10px;width:100px;<?php if ($khachhang[$i]['block'] === '1') { ?>background-color:#2196F3;color:#fff;<?php } ?><?php if ($khachhang[$i]['block'] === '0') { ?>background-color:red;color:#fff;<?php } ?>">
                                        <option value="1" <?php if ($khachhang[$i]['block'] === '1') { ?>selected<?php } ?>>Sử dụng thử</option>
                                        <option value="2" <?php if ($khachhang[$i]['block'] === '2') { ?>selected<?php } ?>>Hoạt động</option>
                                        <option value="0" <?php if ($khachhang[$i]['block'] === '0') { ?>selected<?php } ?>>Đóng tài khoản</option>
                                    </select>
                                </td>
                                <td>
                                    <div class="mua" style="width:180px;">
                                        <input type="text" disabled value="<?= $limit_post['sl'] ?> /" style="width:50px;margin-right:-6px;border-radius:4px 0px 0px 4px;">
                                        <input type="text" class="limit_post" value="<?= $khachhang[$i]['limit_post'] ?>" style="width:60px;border-radius:0;">
                                        <button type="submit" onclick="limit_post(this,<?= $khachhang[$i]['id'] ?>,'<?= $khachhang[$i]['taikhoan'] ?>');" style="width:unset;margin:0;display:unset;">Cập nhật</button>
                                    </div>
                                </td>
                                <td>
                                    <div class="mua" style="width:180px;">
                                        <input type="text" disabled value="<?= $limit_post_buy['sl'] ?> /" style="width:50px;margin-right:-6px;border-radius:4px 0px 0px 4px;">
                                        <input type="text" class="limit_post_buy" value="<?= $khachhang[$i]['limit_post_buy'] ?>" style="width:60px;border-radius:0;">
                                        <button type="submit" onclick="limit_post_buy(this,<?= $khachhang[$i]['id'] ?>,'<?= $khachhang[$i]['taikhoan'] ?>');" style="width:unset;margin:0;display:unset;">Cập nhật</button>
                                    </div>
                                </td>
                                <td>
                                    <div class="mua" style="width:130px;">
                                        <input type="text" class="inputThoigian" value="<?= $khachhang[$i]['conlai'] ?>" style="width:60px;<?php if ($khachhang[$i]['conlai'] < 3) { ?>background-color:red;color:#fff;<?php } ?>">
                                        <button type="submit" onclick="mua(this,<?= $khachhang[$i]['id'] ?>,'<?= $khachhang[$i]['taikhoan'] ?>');" style="width:unset;margin:0;display:unset;">Cập nhật</button>
                                    </div>
                                </td>
                                <td>
                                    <button type="submit" onclick="historyBayment('<?= $khachhang[$i]['log_license'] ?>');" style="width:unset;margin:0;display:unset;background-color: #0390ff;">Xem</button>
                                    <!--<button type="submit" onclick="check_post('<?= implode(',', $danhsachuid_acc_new) ?>',<?= $khachhang[$i]['id'] ?>);" style="width:unset;margin:0;display:unset;background-color:#FF5722;padding:10px;">Check</button>-->
                                </td>
                                <td><span style="text-wrap:nowrap;"><?= date("H:i:s - d/m/Y", $khachhang[$i]['ngaytao']) ?><span></td>
                                <td><span style="<?php if (count(explode('lionnguyen', $khachhang[$i]['log_license'])) > 2 && $khachhang[$i]['conlai'] > 0 && date("Y-m-d") === date("Y-m-d", substr($khachhang[$i]['ngay_license'], 0, 10))) { ?>background:#FF5722;padding:3px 3px;border-radius:3px;color:#fff;<?php } ?>text-wrap:nowrap;"><?= date("H:i:s - d/m/Y", $khachhang[$i]['ngay_license']) ?></span></td>
                                <td><span style="text-wrap:nowrap;"><?= date("H:i:s - d/m/Y", $khachhang[$i]['ngay_license'] + ($khachhang[$i]['conlai'] * 86400)) ?></span></td>
                                <td><span style="<?php if (date("Y-m-d") === date("Y-m-d", substr($khachhang[$i]['truycap'], 0, 10))) { ?>background:#4CAF50;padding:3px 3px;border-radius:3px;color:#fff;<?php } ?>text-wrap:nowrap;"><?= date("H:i:s - d/m/Y", $khachhang[$i]['truycap']) ?></span></td>
                                <td style="width:160px;">
                                    <a href="user?id=<?= $khachhang[$i]['id'] ?>" target="_blank"><img src="images/content-scalling.svg" alt="edit" width="25" style="cursor:pointer;background-color: #0090ff;padding:5px;box-sizing:content-box;margin-right:5px;" title="Xem tài khoản này"></a>
                                    <img onclick="view_pass_acc('<?= $khachhang[$i]['taikhoan'] ?>','<?= $khachhang[$i]['showps'] ?>','<?= $khachhang[$i]['taikhoan'] ?>');" title="Xen mật khẩu tài khoản này" src="images/lock.svg" alt="password" width="25" style="background:#4CAF50;cursor:pointer;border:1px solid #4CAF50;padding:5px;box-sizing:content-box;margin-right:5px;">
                                    <img onclick="delete_acc('<?= $khachhang[$i]['taikhoan'] ?>',<?= $khachhang[$i]['id'] ?>,'<?= $khachhang[$i]['taikhoan'] ?>');" title="Xoá tài khoản này" src="images/ui-delete.svg" alt="edit" width="25" style="background:#FF5722;cursor:pointer;border:1px solid #FF5722;padding:5px;box-sizing:content-box;margin-right:5px;">
                                </td>
                            </tr>
                            <?php
                            $sql = "SELECT * FROM tb_user WHERE id_parent = " . $khachhang[$i]['id'];
                            if (isset($_REQUEST['han']) && $_REQUEST['han'] == 'hoatdong') {
                                $sql .= " and conlai<>0";
                            }
                            if (isset($_REQUEST['han']) && $_REQUEST['han'] == 'hethan') {
                                $sql .= " and conlai=0";
                            }
                            $sql .= " order by id desc";
                            $d->query($sql);
                            $khachhangABC = $d->result_array();
                            for ($k = 0; $k < count($khachhangABC); $k++) {
                                $d->query("SELECT uid FROM tb_post WHERE scan = 1 and id_user='" . $khachhangABC[$k]['id'] . "'");
                                $danhsachuid_acc = $d->result_array();
                                $danhsachuid_acc_new = array();
                                foreach ($danhsachuid_acc as $row) {
                                    $danhsachuid_acc_new[] = $row['uid'];
                                }
                                $d->query("SELECT COUNT(id) as sl FROM tb_post WHERE scan=1 and id_user='" . $khachhangABC[$k]['id'] . "'");
                                $limit_post = $d->fetch_array();
                                $d->query("SELECT COALESCE(SUM(limit_post), 0) as sl FROM tb_user WHERE id_parent='" . $khachhangABC[$k]['id'] . "'");
                                $limit_post_buy = $d->fetch_array();
                                $d->query("SELECT COUNT(DISTINCT comment_uid) as sl FROM tb_data WHERE id_user='" . $khachhangABC[$k]['id'] . "' AND ngaytao>=UNIX_TIMESTAMP(CURDATE())");
                                $data_ngay = $d->fetch_array();
                                $d->query("SELECT COUNT(DISTINCT comment_uid) as sl FROM tb_data WHERE id_user='" . $khachhangABC[$k]['id'] . "'");
                                $data_tong = $d->fetch_array();
                                $d->query("SELECT taikhoan FROM tb_user WHERE id='" . $khachhangABC[$k]['id_parent'] . "'");
                                $taikhoan_ctv = $d->fetch_array();
                            ?>
                                <tr>
                                    <td><?= ($khachhangABC[$k]['id_parent'] == 101 && $khachhangABC[$k]['level'] == 5) ? 'Admin' : (($khachhangABC[$k]['id_parent'] == 101) ? "Ctv" : "") ?></td>
                                    <td><?= $khachhangABC[$k]['taikhoan'] ?></td>
                                    <td class="email_mobi"><?= $khachhangABC[$k]['email'] ?></td>
                                    <td style="text-align:center;">
                                        <?= $data_ngay['sl'] ?>/<?= $data_tong['sl'] ?>
                                    </td>
                                    <td>
                                        <select name="Package" id="Package" onchange="Package(this,<?= $khachhangABC[$k]['id'] ?>,'<?= $khachhangABC[$k]['taikhoan'] ?>');" style="border:1px solid #ccc;border-radius:5px;padding:10px 10px;width:100px;<?php if ($khachhangABC[$k]['block'] === '1') { ?>background-color:#2196F3;color:#fff;<?php } ?><?php if ($khachhangABC[$k]['block'] === '0') { ?>background-color:red;color:#fff;<?php } ?>">
                                            <option value="1" <?php if ($khachhangABC[$k]['block'] === '1') { ?>selected<?php } ?>>Test</option>
                                            <option value="2" <?php if ($khachhangABC[$k]['block'] === '2') { ?>selected<?php } ?>>Active</option>
                                            <option value="0" <?php if ($khachhangABC[$k]['block'] === '0') { ?>selected<?php } ?>>Block</option>
                                        </select>
                                    </td>
                                    <td>
                                        <div class="mua" style="width:180px;">
                                            <input type="text" disabled value="<?= $limit_post['sl'] ?> /" style="width:50px;margin-right:-6px;border-radius:4px 0px 0px 4px;">
                                            <input type="text" class="limit_post" value="<?= $khachhangABC[$k]['limit_post'] ?>" style="width:60px;border-radius:0;">
                                            <button type="submit" onclick="limit_post(this,<?= $khachhangABC[$k]['id'] ?>,'<?= $khachhangABC[$k]['taikhoan'] ?>');" style="width:unset;margin:0;display:unset;">Cập nhật</button>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="mua" style="width:180px;">
                                            <input type="text" disabled value="<?= $limit_post_buy['sl'] ?> /" style="width:50px;margin-right:-6px;border-radius:4px 0px 0px 4px;">
                                            <input type="text" class="limit_post_buy" value="<?= $khachhangABC[$k]['limit_post_buy'] ?>" style="width:60px;border-radius:0;">
                                            <button type="submit" onclick="limit_post_buy(this,<?= $khachhangABC[$k]['id'] ?>,'<?= $khachhangABC[$k]['taikhoan'] ?>');" style="width:unset;margin:0;display:unset;">Cập nhật</button>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="mua" style="width:130px;">
                                            <input type="text" class="inputThoigian" value="<?= $khachhangABC[$k]['conlai'] ?>" style="width:60px;<?php if ($khachhangABC[$k]['conlai'] < 3) { ?>background-color:red;color:#fff;<?php } ?>">
                                            <button type="submit" onclick="mua(this,<?= $khachhangABC[$k]['id'] ?>,'<?= $khachhangABC[$k]['taikhoan'] ?>');" style="width:unset;margin:0;display:unset;">Cập nhật</button>
                                        </div>
                                    </td>
                                    <td>
                                        <button type="submit" onclick="historyBayment('<?= $khachhangABC[$k]['log_license'] ?>');" style="width:unset;margin:0;display:unset;background-color: #0390ff;">Xem</button>
                                        <!--<button type="submit" onclick="check_post('<?= implode(',', $danhsachuid_acc_new) ?>',<?= $khachhangABC[$k]['id'] ?>);" style="width:unset;margin:0;display:unset;background-color:#FF5722;padding:10px;">Check</button>-->
                                    </td>
                                    <td><span style="text-wrap:nowrap;"><?= date("H:i:s - d/m/Y", $khachhangABC[$k]['ngaytao']) ?><span></td>
                                    <td><span style="<?php if (count(explode('lionnguyen', $khachhangABC[$k]['log_license'])) > 2 && $khachhangABC[$k]['conlai'] > 0 && date("Y-m-d") === date("Y-m-d", substr($khachhangABC[$k]['ngay_license'], 0, 10))) { ?>background:#FF5722;padding:3px 3px;border-radius:3px;color:#fff;<?php } ?>text-wrap:nowrap;"><?= date("H:i:s - d/m/Y", $khachhangABC[$k]['ngay_license']) ?></span></td>
                                    <td><span style="text-wrap:nowrap;"><?= date("H:i:s - d/m/Y", $khachhangABC[$k]['ngay_license'] + ($khachhangABC[$k]['conlai'] * 86400)) ?></span></td>
                                    <td><span style="<?php if (date("Y-m-d") === date("Y-m-d", substr($khachhangABC[$k]['truycap'], 0, 10))) { ?>background:#4CAF50;padding:3px 3px;border-radius:3px;color:#fff;<?php } ?>text-wrap:nowrap;"><?= date("H:i:s - d/m/Y", $khachhangABC[$k]['truycap']) ?></span></td>
                                    <td style="width:160px;">
                                        <!-- <a href="user?id=<?= $khachhangABC[$k]['id'] ?>" target="_blank"><img src="images/content-scalling.svg" alt="edit" width="25" style="cursor:pointer;background-color: #0090ff;padding:5px;box-sizing:content-box;margin-right:5px;" title="Xem tài khoản này"></a> -->
                                        <img onclick="view_pass_acc('<?= $khachhangABC[$k]['taikhoan'] ?>','<?= $khachhangABC[$k]['showps'] ?>','<?= $khachhangABC[$k]['taikhoan'] ?>');" title="Xen mật khẩu tài khoản này" src="images/lock.svg" alt="password" width="25" style="background:#4CAF50;cursor:pointer;border:1px solid #4CAF50;padding:5px;box-sizing:content-box;margin-right:5px;color: white;">
                                        <img onclick="delete_acc('<?= $khachhangABC[$k]['taikhoan'] ?>',<?= $khachhangABC[$k]['id'] ?>,'<?= $khachhangABC[$k]['taikhoan'] ?>');" title="Xoá tài khoản này" src="images/ui-delete.svg" alt="edit" width="25" style="background:#FF5722;cursor:pointer;border:1px solid #FF5722;padding:5px;box-sizing:content-box;margin-right:5px;">
                                    </td>
                                </tr>
                        <?php }
                        } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div id="historyBayment" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal(event)">&times;</span>
            <h2>Lịch sử thanh toán</h2>
            <div class="modal-body" style="max-height:450px;overflow-x:scroll;">
                <table class="table table-bordered tblHistoryBayment">
                    <thead>
                        <tr>
                            <th style="text-align:center;">Stt</th>
                            <th style="text-align:center;">Thời gian</th>
                            <th style="text-align:center;">Ngày thanh toán</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
    <div id="check_post" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal(event)">&times;</span>
            <h2>Danh sách bài viết</h2>
            <div class="modal-body" style="max-height:450px;overflow-x:scroll;">
                <table class="table table-bordered tblCheckPost">
                    <thead>
                        <tr>
                            <th style="text-align:center;">Stt</th>
                            <th style="text-align:center;">Uid</th>
                            <th style="text-align:center;">Fail</th>
                            <th style="text-align:center;">Act</th>
                            <th style="text-align:center;">Del</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
                <textarea id="check_id_post" cols="30" rows="10" style="display:none;"></textarea>
                <input type="hidden" id="id_accout">
                <div class="mua">
                    <input type="text" id="id_so_page" value="1">
                    <button id="btn_check_id_post" type="submit" onclick="check_id_post();" style=" white-space:nowrap;position:unset;border-radius:4px;margin-bottom:20px;background:#FF5722;">Kiểm tra</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        function Package(select, id, email) {
            var selectText = select.options[select.selectedIndex].text;
            var selectValue = select.value;
            if (confirm("THAY ĐỔI PACKAGE\n- Tài khoản : " + email + "\n- Package : " + selectText + " ")) {
                window.location.href = "?action=Package&block=" + selectValue + "&id=" + id + "&email=" + email;
            }
        }

        function limit_post(button, id, email) {
            var inputElement = button.previousElementSibling;
            var inputValue = inputElement.value;
            if (inputValue == "") {
                alert("Hãy nhập số lượng bài viết");
            } else {
                if (confirm("CẬP NHẬT SỐ LƯỢNG POST CHO TÀI KHOẢN\n- Tài khoản : " + email + "\n- Limit : " + inputValue + " ")) {
                    window.location.href = "?action=limit_post&limit_post=" + inputValue + "&id=" + id + "&email=" + email;
                }
            }
        }

        function limit_post_buy(button, id, email) {
            var inputElement = button.previousElementSibling;
            var inputValue = inputElement.value;
            if (inputValue == "") {
                alert("Hãy nhập số lượng bài viết");
            } else {
                if (confirm("CẬP NHẬT SỐ LƯỢNG POST_BUY CHO TÀI KHOẢN\n- Tài khoản : " + email + "\n- Limit : " + inputValue + " ")) {
                    window.location.href = "?action=limit_post_buy&limit_post_buy=" + inputValue + "&id=" + id + "&email=" + email;
                }
            }
        }

        function mua(button, id, email) {
            var inputElement = button.previousElementSibling;
            var inputValue = inputElement.value;
            if (inputValue == "") {
                alert("Hãy nhập thời gian vào");
            } else {
                if (confirm("CẬP NHẬT THỜI GIAN CHO TÀI KHOẢN\n- Tài khoản : " + email + "\n- Số ngày : " + inputValue + " Ngày")) {
                    window.location.href = "?action=thoigian_acc&thoigian=" + inputValue + "&id=" + id + "&email=" + email;
                }
            }
        }

        function delay_time(button, id, email) {
            var inputElement = button.previousElementSibling;
            var inputValue = inputElement.value;
            if (inputValue == "") {
                alert("Hãy nhập số thời gian cần delay");
            } else {
                if (confirm("CẬP NHẬT DELAY CHO TÀI KHOẢN\n- Tài khoản : " + email + "\n- Delay : " + inputValue + " ")) {
                    window.location.href = "?action=delay_time&delay_time=" + inputValue + "&id=" + id + "&email=" + email;
                }
            }
        }

        function update_Thoigian_All() {
            if (confirm("Cập nhật lại thời gian sử dụng cho tất cả khách hàng?")) {
                window.location.href = "?action=update_thoigian_all";
            }
        }

        function closeModal(event) {
            var modal = event.target.closest(".modal");
            modal.style.display = "none";
        }

        function historyBayment(a) {
            const table = document.querySelector(".tblHistoryBayment tbody");
            const rows = table.querySelectorAll("tr");
            rows.forEach(row => {
                row.remove();
            });
            var tbody = "";
            var tdbody = "";
            var dataB = a.split('lionnguyen');
            for (let k in dataB) {
                if (dataB[k].split('_')[0]) {
                    var thoigian = (parseInt(dataB[k].split('_')[1])) * 1000;
                    tdbody += "<tr><td style='text-align:center;padding:8px 0px;'>" + (parseInt(k) + 1) + "</td>";
                    tdbody += "<td style='text-align:center;'>" + dataB[k].split('_')[0] + "</td>";
                    tdbody += "<td style='text-align:center;'>" + new Date(thoigian).getHours() + ":" + new Date(thoigian).getMinutes() + ":" + new Date(thoigian).getSeconds() + " - " + new Date(thoigian).getDate() + "/" + (new Date(thoigian).getMonth() + 1) + "/" + new Date(thoigian).getFullYear() + "</td></tr>";
                }
            }
            tbody = tbody + tdbody;
            var tbodyElement = document.querySelector(".tblHistoryBayment tbody");
            tbodyElement.innerHTML += tbody;
            var modal = document.getElementById("historyBayment");
            modal.style.display = "block";
        }

        function check_post(a, b) {
            document.getElementById('check_id_post').value = a;
            document.getElementById('id_accout').value = b;
            const table = document.querySelector(".tblCheckPost tbody");
            const rows = table.querySelectorAll("tr");
            rows.forEach(row => {
                row.remove();
            });
            var tbody = "";
            var tdbody = "";
            var dataB = a.split(',');
            for (let k in dataB) {
                if (dataB[k]) {
                    tdbody += "<tr id='" + dataB[k].split('|')[0] + "'><td style='text-align:center;padding:8px 0px;'>" + (parseInt(k) + 1) + "</td>";
                    tdbody += "<td style='text-align:center;'>" + dataB[k].split('|')[0] + "</td>";
                    tdbody += "<td style='text-align:center;'></td>";
                    tdbody += "<td style='text-align:center;'><button type='submit' style='display:inline-block;width:auto;padding:6px;margin-top:0;background-color:#4CAF50;'>on</button></td>";
                    tdbody += "<td style='text-align:center;'><button type='submit' style='display:inline-block;width:auto;padding:6px;margin-top:0;background-color:red;'>del</button></td></tr>";
                }
            }
            tbody = tbody + tdbody;
            var tbodyElement = document.querySelector(".tblCheckPost tbody");
            tbodyElement.innerHTML += tbody;
            var modal = document.getElementById("check_post");
            modal.style.display = "block";
        }

        function check_id_post() {
            list_id_post = document.getElementById('check_id_post').value;
            id_account = document.getElementById('id_accout').value;
            id_so_page = document.getElementById('id_so_page').value;
            button = document.getElementById('btn_check_id_post');
            button.innerHTML = "Đang kiểm tra...";
            button.disabled = true;
            var formData = new FormData();
            formData.append("action", "check_id_post");
            formData.append("id", id_account);
            formData.append("list_id_post", list_id_post);
            formData.append("id_so_page", id_so_page);
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        var data = xhr.responseText;
                        const table = document.querySelector(".tblCheckPost tbody");
                        const rows = table.querySelectorAll("tr");
                        rows.forEach(row => {
                            row.remove();
                        });
                        var tbody = "";
                        var tdbody = "";
                        var dataB = data.split('|');
                        console.log(data);
                        for (let k in dataB) {
                            if (dataB[k].split('-')[0]) {
                                var str = "";
                                if (dataB[k].split('-')[1] === 'Fail') {
                                    str = "style='background-color:#ff2828;color:#fff;'";
                                }
                                tdbody += "<tr " + str + "><td style='text-align:center;padding:8px 0px;'>" + (parseInt(k) + 1) + "</td>";
                                tdbody += "<td style='text-align:center;'>" + dataB[k].split('-')[0] + "</td>";
                                tdbody += "<td style='text-align:center;'>" + dataB[k].split('-')[1] + "</td>";
                                tdbody += "<td style='text-align:center;'><button type='submit' style='display:inline-block;width:auto;padding:6px;margin-top:0;background-color:#4CAF50;'>on</button></td>";
                                tdbody += "<td style='text-align:center;'><button type='submit' style='display:inline-block;width:auto;padding:6px;margin-top:0;background-color:red;'>del</button></td></tr>";
                            }
                        }
                        tbody = tbody + tdbody;
                        var tbodyElement = document.querySelector(".tblCheckPost tbody");
                        tbodyElement.innerHTML += tbody;
                    } else {
                        console.log("Có lỗi xảy ra khi gửi yêu cầu đến máy chủ.");
                    }
                    button.innerHTML = "Kiểm tra";
                    button.disabled = false;
                }
            };
            xhr.open("POST", "ajax/ajax.php", true);
            xhr.send(formData);
        }

        function reset_pass_acc(taikhoan, id, email) {
            var inputValue = prompt('Tạo mật khẩu mới tài khoản : [' + email + ']\nGõ từ [reset] để xác nhận :');
            if (inputValue !== null) {
                window.location.href = "?action=reset_pass_acc&verify=" + inputValue + "&id=" + id + "&email=" + email;
            }
        }

        function view_pass_acc(taikhoan, id, email) {
            var inputValue = prompt('Mật khẩu của tài khoản : [' + email + '] là :', id);
        }

        function delete_acc(taikhoan, id, email) {
            var inputValue = prompt('Xoá tài khoản : [' + email + ']\nGõ từ [delete] để xác nhận xoá :');
            if (inputValue !== null) {
                window.location.href = "?action=delete_acc&verify=" + inputValue + "&id=" + id + "&email=" + email;
            }
        }
    </script>
<?php } ?>
<?php if ($taikhoan['level'] == 4) { ?>
    <?php
    $id_user_a = (int)($_SESSION['id_user']) / 5;
    $d->query("SELECT limit_post_buy FROM tb_user WHERE id = " . (int)$id_user_a);
    $taikhoanTCV = $d->fetch_array();
    //Add acc
    if (isset($_REQUEST['register-taikhoan']) && isset($_REQUEST['register-matkhau']) && isset($_REQUEST['register-email'])) {
        $data = array();
        $data['id_parent'] =   $id_user_a;
        $data['level'] = 5;
        $data['taikhoan'] = substr(addslashes($_REQUEST['register-taikhoan']), 0, 20);
        $data['matkhau'] = md5(substr(addslashes($_REQUEST['register-matkhau']), 0, 20));
        $data['showps'] = substr(addslashes($_REQUEST['register-matkhau']), 0, 50);
        $data['email'] = substr(addslashes($_REQUEST['register-email']), 0, 50);
        $data['limit_post'] = 0;
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
        $d->query("select * from tb_user where taikhoan='" . $data['taikhoan'] . "'");
        $tontai_taikhoan = $d->num_rows();
        if ((int)$tontai_taikhoan === 0) {
            $d->query("select * from tb_user where email='" . $data['email'] . "'");
            $tontai_email = $d->num_rows();
            if ((int)$tontai_email === 0) {
                $d->setTable('user');
                if ($d->insert($data)) {
                    echo '<script>alert("THÊM THÀNH CÔNG");window.location.href = "./";</script>';
                } else {
                    echo '<script>alert("lỖI, HÃY THỬ LẠI");window.location.href = "./";</script>';
                }
            } else {
                echo '<script>alert("EMAIL ĐÃ TỒN TẠI");window.location.href = "./";</script>';
            }
        } else {
            echo '<script>alert("TÀI KHOẢN ĐÃ TỒN TẠI");window.location.href = "./";</script>';
        }
    }
    //Xoá acc
    if (isset($_REQUEST['action']) && $_REQUEST['action'] == "delete_acc" && isset($_REQUEST['verify']) && $_REQUEST['verify'] == "delete") {
        $d->query("DELETE FROM tb_user WHERE id = " . (int)$_REQUEST['id']);
        if (mysqli_affected_rows($d->db)) {
            $notifer_delete_acc = "Đã xoá thành công : [" . $_REQUEST['email'] . "]";
        } else {
            header("location:./");
        }
    }
    //Package acc
    if (isset($_REQUEST['action']) && $_REQUEST['action'] == "Package") {
        $d->query("SELECT block FROM tb_user WHERE id = " . (int)$_REQUEST['id']);
        $result_acc = $d->fetch_array();
        $d->query("UPDATE tb_user SET block = " . (int)$_REQUEST['block'] . " WHERE id = " . (int)$_REQUEST['id']);
        echo '<script>alert("CẬP NHẬT THÀNH CÔNG\n- Tài khoản : ' . $_REQUEST["email"] . '");window.location.href = "./";</script>';
    }
    //Limit_Post acc
    if (isset($_REQUEST['action']) && $_REQUEST['action'] == "limit_post") {
        $limit_post = (int)$_GET['limit_post'];
        $d->query("SELECT COALESCE(SUM(limit_post), 0) as sl FROM tb_user WHERE id_parent='" . (int)$id_user_a . "' and id <> " . (int)$_REQUEST['id']);
        $limit_post_buy = $d->fetch_array();
        echo $limit_post_buy['sl'];
        if ((int)$limit_post_buy['sl'] + $limit_post > $taikhoanTCV['limit_post_buy']) {
            echo '<script>alert("Vượt quá số bài được phép");window.location.href = "./";</script>';
        } else {
            $d->query("SELECT * FROM tb_user WHERE id = " . (int)$_REQUEST['id']);
            $result_acc = $d->fetch_array();
            $date_current = time();
            $d->query("UPDATE tb_user SET limit_post = " . $limit_post . " WHERE id = " . (int)$_REQUEST['id']);
            //echo '<script>alert("CẬP NHẬT SỐ LƯỢNG POST THÀNH CÔNG\n- Tài khoản : ' . $_REQUEST["email"] . '");window.location.href = "./";</script>';
        }
    }
    //Thoigian acc
    if (isset($_REQUEST['action']) && $_REQUEST['action'] == "thoigian_acc") {
        $d->query("SELECT * FROM tb_user WHERE id = " . (int)$_REQUEST['id']);
        $result_acc = $d->fetch_array();
        $date_current = time();
        $timeout = (int)$_GET['thoigian'];
        $log_license = $_GET["thoigian"] . "_" . $date_current . "lionnguyen";
        $d->query("UPDATE tb_user SET ngay_license = " . $date_current . ",thoigian = " . $timeout . ",conlai = " . $timeout . ",log_license = concat(log_license,'" . $log_license . "') WHERE id = " . (int)$_REQUEST['id']);
        if (mysqli_affected_rows($d->db)) {
            echo '<script>alert("CẬP NHẬT THỜI GIAN THÀNH CÔNG\n- Tài khoản : ' . $_REQUEST["email"] . '");window.location.href = "./";</script>';
        } else {
            header("location:./");
        }
    }
    //Lấy dữ liệu danh sách khách sử dụng
    $sql = "SELECT * FROM tb_user WHERE id_parent =  $id_user_a";
    if (isset($_REQUEST['han']) && $_REQUEST['han'] == 'hoatdong') {
        $sql .= " and conlai<>0";
    }
    if (isset($_REQUEST['han']) && $_REQUEST['han'] == 'hethan') {
        $sql .= " and conlai=0";
    }
    $sql .= " order by id desc";
    $d->query($sql);
    $khachhang = $d->result_array();
    //
    ?>
    <style>
        .tkvathem {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
        }

        .thongke,
        .them {
            flex-basis: 50%;
            /* height: 500px; */
        }

        .fird {
            border-radius: 10px;
            box-shadow: 0 0 6px 4px #00000029;
            height: 350px;
        }

        .idataa {
            margin-top: 15px;
            margin-bottom: 10px;
        }

        .idataa:nth-child(1) {
            margin-top: 10px;
        }

        .content {
            width: 94%;
            margin: 0 auto;
        }

        @media (max-width:768px) {
            .tkvathem {
                flex-direction: column;
            }
        }
    </style>
    <div class="content">
        <div class="tkvathem">
            <div class="thongke">
                <h1>Thống kê</h1>
                <div class="fird">
                    <script src="./js/Chart.min.js"></script>
                    <input type="date" class="form-control" value="<?php if (isset($_GET['static_date']) && $_GET['static_date'] != '') {
                                                                        echo $_GET['static_date'];
                                                                    } else {
                                                                        echo date('Y-m-d');
                                                                    } ?>" onchange="window.location='home?static_date='+this.value">
                    <canvas id="myChart" width="800" height="150"></canvas>
                    <script>
                        var ctx = document.getElementById('myChart').getContext('2d');
                        var myChart = new Chart(ctx, {
                            type: 'line',
                            data: {
                                labels: [<?= $list_arr_a2 ?>],
                                datasets: [{
                                    label: 'Comment (<?= $dem_uid_ngay['sl'] ?>)',
                                    backgroundColor: "#3096F3",
                                    borderColor: "#3096F3",
                                    data: [<?= $list_arr_a3 ?>],
                                    fill: false,
                                }, {
                                    label: 'Số điện thoại (<?= $dem_phone_ngay['sl'] ?>)',
                                    backgroundColor: "#ffd443",
                                    borderColor: "#ffd443",
                                    data: [<?= $list_arr_b3 ?>],
                                    fill: false,
                                    //borderDash: [5, 5],
                                    //pointRadius: 15,
                                    //pointHoverRadius: 10,
                                }]
                            },
                            options: {
                                responsive: true,
                                title: {
                                    display: true,
                                    text: 'Thống kê (Ngày <?= $static_date ?>)',
                                },
                                tooltips: {
                                    mode: 'index',
                                    intersect: false,
                                },
                                hover: {
                                    mode: 'nearest',
                                    intersect: true
                                },
                                scales: {
                                    xAxes: [{
                                        display: true,
                                        scaleLabel: {
                                            display: true,
                                            labelString: 'Thời gian'
                                        }
                                    }],
                                    yAxes: [{
                                        ticks: {
                                            //min: 0,
                                            //max: 200
                                        }
                                    }]
                                }
                            }
                        });
                    </script>
                </div>
            </div>
            <div class="them">
                <h1>Thêm tài khoản</h1>
                <div class="fird">
                    <div class="form-container" style="width:100%;">
                        <form id="register-password-form" method="post">
                            <div class="password-container">
                                <!-- <label for="register-taikhoan">Tài khoản</label> -->
                                <input type="text" placeholder="Tên đăng nhập" class="idataa" id="register-taikhoan" name="register-taikhoan" minlength="8" maxlength="20" autocomplete="off" pattern="^[a-zA-Z0-9]+$" required>
                            </div>
                            <!-- <p style="margin:0;padding:0;font-size:13px;padding:5px 0px 10px 0px;color:red;">(8-20 ký tự, không dấu, không cách, không ký tự đặt biệt)</p> -->
                            <div class="password-container">
                                <!-- <label for="register-matkhau">Mật khẩu</label> -->
                                <input type="password" placeholder="Mật khẩu" class="idataa" id="register-matkhau" name="register-matkhau" minlength="8" maxlength="20" autocomplete="off" pattern="^[a-zA-Z0-9]+$" required>

                            </div>
                            <!-- <p style="margin:0;padding:0;font-size:13px;padding:5px 0px 10px 0px;color:red;">(8-20 ký tự, không dấu, không cách, không ký tự đặt biệt)</p> -->
                            <!-- <label for="register-email">Email</label> -->
                            <input type="email" placeholder="Email" class="idataa" id="register-email" name="register-email" required>
                            <p style="margin:0;padding:0;font-size:13px;padding:5px 0px 10px 0px;color:#aaa;">* 8-20 ký tự, không dấu, không cách, không ký tự đặt biệt</p>
                            <button type="submit" style="font-size: 16px;border-radius: 11px;background: rgb(0,120,246);background: linear-gradient(166deg, rgba(0,120,246,1) 39%, rgba(59,255,178,1) 100%);">Thêm tài khoản</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <h1>Danh sách khách hàng</h1>
        <div class="fird">
            <div class="mua" style="display:none;">
                <a href="?han=hoatdong"><button type="submit" style=" white-space:nowrap;position:unset;border-radius:4px;margin-bottom:20px;display:inline-block;width:90px;">Hoạt động</button></a>
                <a href="?han=hethan"><button type="submit" style=" white-space:nowrap;position:unset;border-radius:4px;margin-bottom:20px;background:#FF5722;display:inline-block;width:90px;">Hết hạn</button></a>
            </div>
            <div class="table-container">
                <table style="zoom:0.9;">
                    <thead>
                        <tr>
                            <th>STT</th>
                            <th>Tài khoản</th>
                            <th class="email_mobi">Email</th>
                            <th>Số bình luận</th>
                            <th>Trạng thái</th>
                            <th>Bài viết</th>
                            <th>Số ngày hết hạn</th>
                            <th>Xem</th>
                            <th>Ngày tạo</th>
                            <th>Ngày mua</th>
                            <th>Truy cập</th>
                            <th>Tuỳ chọn</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php for ($i = 0; $i < count($khachhang); $i++) {
                            $stt = $i + 1;
                            $d->query("SELECT uid FROM tb_post WHERE scan = 1 and id_user='" . $khachhang[$i]['id'] . "'");
                            $danhsachuid_acc = $d->result_array();
                            $danhsachuid_acc_new = array();
                            foreach ($danhsachuid_acc as $row) {
                                $danhsachuid_acc_new[] = $row['uid'];
                            }
                            $d->query("SELECT COUNT(id) as sl FROM tb_post WHERE scan=1 and id_user='" . $khachhang[$i]['id'] . "'");
                            $limit_post = $d->fetch_array();
                            $d->query("SELECT COALESCE(SUM(limit_post), 0) as sl FROM tb_user WHERE id_parent='" . $khachhang[$i]['id'] . "'");
                            $limit_post_buy = $d->fetch_array();
                            $d->query("SELECT COUNT(DISTINCT comment_uid) as sl FROM tb_data WHERE id_user='" . $khachhang[$i]['id'] . "' AND ngaytao>=UNIX_TIMESTAMP(CURDATE())");
                            $data_ngay = $d->fetch_array();
                            $d->query("SELECT COUNT(DISTINCT comment_uid) as sl FROM tb_data WHERE id_user='" . $khachhang[$i]['id'] . "'");
                            $data_tong = $d->fetch_array();
                        ?>
                            <tr>
                                <td style="text-align:center;"><?= $stt ?></td>
                                <td><?= $khachhang[$i]['taikhoan'] ?></td>
                                <td class="email_mobi"><?= $khachhang[$i]['email'] ?></td>
                                <td style="text-align:center;">
                                    <?= $data_ngay['sl'] ?>/<?= $data_tong['sl'] ?>
                                </td>
                                <td>
                                    <select name="Package" id="Package" onchange="Package(this,<?= $khachhang[$i]['id'] ?>,'<?= $khachhang[$i]['taikhoan'] ?>');" style="border:1px solid #ccc;border-radius:5px;padding:10px 10px;width:100px;<?php if ($khachhang[$i]['block'] === '1') { ?>background-color:#2196F3;color:#fff;<?php } ?><?php if ($khachhang[$i]['block'] === '0') { ?>background-color:red;color:#fff;<?php } ?>">
                                        <option value="1" <?php if ($khachhang[$i]['block'] === '1') { ?>selected<?php } ?>>Test</option>
                                        <option value="2" <?php if ($khachhang[$i]['block'] === '2') { ?>selected<?php } ?>>Active</option>
                                        <option value="0" <?php if ($khachhang[$i]['block'] === '0') { ?>selected<?php } ?>>Block</option>
                                    </select>
                                </td>
                                <td>
                                    <div class="mua" style="width:180px;">
                                        <input type="text" disabled value="<?= $limit_post['sl'] ?> /" style="width:50px;margin-right:-6px;border-radius:4px 0px 0px 4px;">
                                        <input type="text" class="limit_post" value="<?= $khachhang[$i]['limit_post'] ?>" style="width:60px;border-radius:0;">
                                        <button type="submit" onclick="limit_post(this,<?= $khachhang[$i]['id'] ?>,'<?= $khachhang[$i]['taikhoan'] ?>');" style="width:unset;margin:0;display:unset;">Cập nhật</button>
                                    </div>
                                </td>
                                <td>
                                    <div class="mua" style="width:130px;">
                                        <input type="text" class="inputThoigian" value="<?= $khachhang[$i]['conlai'] ?>" style="width:60px;<?php if ($khachhang[$i]['conlai'] < 3) { ?>background-color:red;color:#fff;<?php } ?>">
                                        <button type="submit" onclick="mua(this,<?= $khachhang[$i]['id'] ?>,'<?= $khachhang[$i]['taikhoan'] ?>');" style="width:unset;margin:0;display:unset;">Cập nhật</button>
                                    </div>
                                </td>
                                <td>
                                    <button type="submit" onclick="historyBayment('<?= $khachhang[$i]['log_license'] ?>');" style="width:unset;margin:0;display:unset;background-color: #0390ff;">Xem</button>
                                    <!--<button type="submit" onclick="check_post('<?= implode(',', $danhsachuid_acc_new) ?>',<?= $khachhang[$i]['id'] ?>);" style="width:unset;margin:0;display:unset;background-color:#FF5722;padding:10px;">Check</button>-->
                                </td>
                                <td><span style="text-wrap:nowrap;"><?= date("H:i:s - d/m/Y", $khachhang[$i]['ngaytao']) ?><span></td>
                                <td><span style="<?php if (count(explode('lionnguyen', $khachhang[$i]['log_license'])) > 2 && $khachhang[$i]['conlai'] > 0 && date("Y-m-d") === date("Y-m-d", substr($khachhang[$i]['ngay_license'], 0, 10))) { ?>background:#FF5722;padding:3px 3px;border-radius:3px;color:#fff;<?php } ?>text-wrap:nowrap;"><?= date("H:i:s - d/m/Y", $khachhang[$i]['ngay_license']) ?></span></td>
                                <td><span style="<?php if (date("Y-m-d") === date("Y-m-d", substr($khachhang[$i]['truycap'], 0, 10))) { ?>background:#4CAF50;padding:3px 3px;border-radius:3px;color:#fff;<?php } ?>text-wrap:nowrap;"><?= date("H:i:s - d/m/Y", $khachhang[$i]['truycap']) ?></span></td>
                                <td style="width:160px;">
                                    <!--<a href="user?id=<?= $khachhang[$i]['id'] ?>" target="_blank"><img src="images/content-scalling.svg" alt="edit" width="25" style="cursor:pointer;background-color: #0090ff;padding:5px;box-sizing:content-box;margin-right:5px;" title="Xem tài khoản này"></a>-->
                                    <img onclick="view_pass_acc('<?= $khachhang[$i]['taikhoan'] ?>','<?= $khachhang[$i]['showps'] ?>','<?= $khachhang[$i]['taikhoan'] ?>');" title="Xen mật khẩu tài khoản này" src="images/lock.svg" alt="password" width="25" style="background:#4CAF50;cursor:pointer;border:1px solid #4CAF50;padding:5px;box-sizing:content-box;margin-right:5px;">
                                    <img onclick="delete_acc('<?= $khachhang[$i]['taikhoan'] ?>',<?= $khachhang[$i]['id'] ?>,'<?= $khachhang[$i]['taikhoan'] ?>');" title="Xoá tài khoản này" src="images/ui-delete.svg" alt="edit" width="25" style="background:#FF5722;cursor:pointer;border:1px solid #FF5722;padding:5px;box-sizing:content-box;margin-right:5px;">
                                </td>
                            </tr>
                            <?php
                            $sql = "SELECT * FROM tb_user WHERE id_parent = " . $khachhang[$i]['id'];
                            if (isset($_REQUEST['han']) && $_REQUEST['han'] == 'hoatdong') {
                                $sql .= " and conlai<>0";
                            }
                            if (isset($_REQUEST['han']) && $_REQUEST['han'] == 'hethan') {
                                $sql .= " and conlai=0";
                            }
                            $sql .= " order by id desc";
                            $d->query($sql);
                            $khachhangABC = $d->result_array();
                            for ($k = 0; $k < count($khachhangABC); $k++) {
                                $d->query("SELECT uid FROM tb_post WHERE scan = 1 and id_user='" . $khachhangABC[$k]['id'] . "'");
                                $danhsachuid_acc = $d->result_array();
                                $danhsachuid_acc_new = array();
                                foreach ($danhsachuid_acc as $row) {
                                    $danhsachuid_acc_new[] = $row['uid'];
                                }
                                $d->query("SELECT COUNT(id) as sl FROM tb_post WHERE scan=1 and id_user='" . $khachhangABC[$k]['id'] . "'");
                                $limit_post = $d->fetch_array();
                                $d->query("SELECT COALESCE(SUM(limit_post), 0) as sl FROM tb_user WHERE id_parent='" . $khachhangABC[$k]['id'] . "'");
                                $limit_post_buy = $d->fetch_array();
                                $d->query("SELECT COUNT(DISTINCT comment_uid) as sl FROM tb_data WHERE id_user='" . $khachhangABC[$k]['id'] . "' AND ngaytao>=UNIX_TIMESTAMP(CURDATE())");
                                $data_ngay = $d->fetch_array();
                                $d->query("SELECT COUNT(DISTINCT comment_uid) as sl FROM tb_data WHERE id_user='" . $khachhangABC[$k]['id'] . "'");
                                $data_tong = $d->fetch_array();
                                $d->query("SELECT taikhoan FROM tb_user WHERE id='" . $khachhangABC[$k]['id_parent'] . "'");
                                $taikhoan_ctv = $d->fetch_array();
                            ?>
                                <tr>
                                    <td><?= ($khachhangABC[$k]['id_parent'] == 101 && $khachhangABC[$k]['level'] == 5) ? 'Admin' : (($khachhangABC[$k]['id_parent'] == 101) ? "Ctv" : "") ?></td>
                                    <td><?= $khachhangABC[$k]['taikhoan'] ?></td>
                                    <td class="email_mobi"><?= $khachhangABC[$k]['email'] ?></td>
                                    <td style="text-align:center;">
                                        <?= $data_ngay['sl'] ?>/<?= $data_tong['sl'] ?>
                                    </td>
                                    <td>
                                        <select name="Package" id="Package" onchange="Package(this,<?= $khachhangABC[$k]['id'] ?>,'<?= $khachhangABC[$k]['taikhoan'] ?>');" style="border:1px solid #ccc;border-radius:5px;padding:10px 10px;width:100px;<?php if ($khachhangABC[$k]['block'] === '1') { ?>background-color:#2196F3;color:#fff;<?php } ?><?php if ($khachhangABC[$k]['block'] === '0') { ?>background-color:red;color:#fff;<?php } ?>">
                                            <option value="1" <?php if ($khachhangABC[$k]['block'] === '1') { ?>selected<?php } ?>>Test</option>
                                            <option value="2" <?php if ($khachhangABC[$k]['block'] === '2') { ?>selected<?php } ?>>Active</option>
                                            <option value="0" <?php if ($khachhangABC[$k]['block'] === '0') { ?>selected<?php } ?>>Block</option>
                                        </select>
                                    </td>
                                    <td>
                                        <div class="mua" style="width:180px;">
                                            <input type="text" disabled value="<?= $limit_post['sl'] ?> /" style="width:50px;margin-right:-6px;border-radius:4px 0px 0px 4px;">
                                            <input type="text" class="limit_post" value="<?= $khachhangABC[$k]['limit_post'] ?>" style="width:60px;border-radius:0;">
                                            <button type="submit" onclick="limit_post(this,<?= $khachhangABC[$k]['id'] ?>,'<?= $khachhangABC[$k]['taikhoan'] ?>');" style="width:unset;margin:0;display:unset;">Cập nhật</button>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="mua" style="width:180px;">
                                            <input type="text" disabled value="<?= $limit_post_buy['sl'] ?> /" style="width:50px;margin-right:-6px;border-radius:4px 0px 0px 4px;">
                                            <input type="text" class="limit_post_buy" value="<?= $khachhangABC[$k]['limit_post_buy'] ?>" style="width:60px;border-radius:0;">
                                            <button type="submit" onclick="limit_post_buy(this,<?= $khachhangABC[$k]['id'] ?>,'<?= $khachhangABC[$k]['taikhoan'] ?>');" style="width:unset;margin:0;display:unset;">Cập nhật</button>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="mua" style="width:130px;">
                                            <input type="text" class="inputThoigian" value="<?= $khachhangABC[$k]['conlai'] ?>" style="width:60px;<?php if ($khachhangABC[$k]['conlai'] < 3) { ?>background-color:red;color:#fff;<?php } ?>">
                                            <button type="submit" onclick="mua(this,<?= $khachhangABC[$k]['id'] ?>,'<?= $khachhangABC[$k]['taikhoan'] ?>');" style="width:unset;margin:0;display:unset;">Cập nhật</button>
                                        </div>
                                    </td>
                                    <td>
                                        <button type="submit" onclick="historyBayment('<?= $khachhangABC[$k]['log_license'] ?>');" style="width:unset;margin:0;display:unset;background-color: #0390ff;">Xem</button>
                                        <!--<button type="submit" onclick="check_post('<?= implode(',', $danhsachuid_acc_new) ?>',<?= $khachhangABC[$k]['id'] ?>);" style="width:unset;margin:0;display:unset;background-color:#FF5722;padding:10px;">Check</button>-->
                                    </td>
                                    <td><span style="text-wrap:nowrap;"><?= date("H:i:s - d/m/Y", $khachhangABC[$k]['ngaytao']) ?><span></td>
                                    <td><span style="<?php if (count(explode('lionnguyen', $khachhangABC[$k]['log_license'])) > 2 && $khachhangABC[$k]['conlai'] > 0 && date("Y-m-d") === date("Y-m-d", substr($khachhangABC[$k]['ngay_license'], 0, 10))) { ?>background:#FF5722;padding:3px 3px;border-radius:3px;color:#fff;<?php } ?>text-wrap:nowrap;"><?= date("H:i:s - d/m/Y", $khachhangABC[$k]['ngay_license']) ?></span></td>
                                    <td><span style="<?php if (date("Y-m-d") === date("Y-m-d", substr($khachhangABC[$k]['truycap'], 0, 10))) { ?>background:#4CAF50;padding:3px 3px;border-radius:3px;color:#fff;<?php } ?>text-wrap:nowrap;"><?= date("H:i:s - d/m/Y", $khachhangABC[$k]['truycap']) ?></span></td>
                                    <td style="width:160px;">
                                        <a href="user?id=<?= $khachhangABC[$k]['id'] ?>" target="_blank"><img src="images/content-scalling.svg" alt="edit" width="25" style="cursor:pointer;background-color: #0090ff;padding:5px;box-sizing:content-box;margin-right:5px;" title="Xem tài khoản này"></a>
                                        <img onclick="view_pass_acc('<?= $khachhangABC[$k]['taikhoan'] ?>','<?= $khachhangABC[$k]['showps'] ?>','<?= $khachhangABC[$k]['taikhoan'] ?>');" title="Xen mật khẩu tài khoản này" src="images/lock.svg" alt="password" width="25" style="background:#4CAF50;cursor:pointer;border:1px solid #4CAF50;padding:5px;box-sizing:content-box;margin-right:5px;">
                                        <img onclick="delete_acc('<?= $khachhangABC[$k]['taikhoan'] ?>',<?= $khachhangABC[$k]['id'] ?>,'<?= $khachhangABC[$k]['taikhoan'] ?>');" title="Xoá tài khoản này" src="images/ui-delete.svg" alt="edit" width="25" style="background:#FF5722;cursor:pointer;border:1px solid #FF5722;padding:5px;box-sizing:content-box;margin-right:5px;">
                                    </td>
                                </tr>
                        <?php }
                        } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div id="historyBayment" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal(event)">&times;</span>
            <h2>Lịch sử thanh toán</h2>
            <div class="modal-body" style="max-height:450px;overflow-x:scroll;">
                <table class="table table-bordered tblHistoryBayment">
                    <thead>
                        <tr>
                            <th style="text-align:center;">Stt</th>
                            <th style="text-align:center;">Thời gian</th>
                            <th style="text-align:center;">Ngày thanh toán</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
    <div id="check_post" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal(event)">&times;</span>
            <h2>Danh sách bài viết</h2>
            <div class="modal-body" style="max-height:450px;overflow-x:scroll;">
                <table class="table table-bordered tblCheckPost">
                    <thead>
                        <tr>
                            <th style="text-align:center;">Stt</th>
                            <th style="text-align:center;">Uid</th>
                            <th style="text-align:center;">Fail</th>
                            <th style="text-align:center;">Act</th>
                            <th style="text-align:center;">Del</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
                <textarea id="check_id_post" cols="30" rows="10" style="display:none;"></textarea>
                <input type="hidden" id="id_accout">
                <div class="mua">
                    <input type="text" id="id_so_page" value="1">
                    <button id="btn_check_id_post" type="submit" onclick="check_id_post();" style=" white-space:nowrap;position:unset;border-radius:4px;margin-bottom:20px;background:#FF5722;">Kiểm tra</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        function Package(select, id, email) {
            var selectText = select.options[select.selectedIndex].text;
            var selectValue = select.value;
            if (confirm("THAY ĐỔI PACKAGE\n- Tài khoản : " + email + "\n- Package : " + selectText + " ")) {
                window.location.href = "?action=Package&block=" + selectValue + "&id=" + id + "&email=" + email;
            }
        }

        function limit_post(button, id, email) {
            var inputElement = button.previousElementSibling;
            var inputValue = inputElement.value;
            if (inputValue == "") {
                alert("Hãy nhập số lượng bài viết");
            } else {
                if (confirm("CẬP NHẬT SỐ LƯỢNG POST CHO TÀI KHOẢN\n- Tài khoản : " + email + "\n- Limit : " + inputValue + " ")) {
                    window.location.href = "?action=limit_post&limit_post=" + inputValue + "&id=" + id + "&email=" + email;
                }
            }
        }

        function limit_post_buy(button, id, email) {
            var inputElement = button.previousElementSibling;
            var inputValue = inputElement.value;
            if (inputValue == "") {
                alert("Hãy nhập số lượng bài viết");
            } else {
                if (confirm("CẬP NHẬT SỐ LƯỢNG POST_BUY CHO TÀI KHOẢN\n- Tài khoản : " + email + "\n- Limit : " + inputValue + " ")) {
                    window.location.href = "?action=limit_post_buy&limit_post_buy=" + inputValue + "&id=" + id + "&email=" + email;
                }
            }
        }

        function mua(button, id, email) {
            var inputElement = button.previousElementSibling;
            var inputValue = inputElement.value;
            if (inputValue == "") {
                alert("Hãy nhập thời gian vào");
            } else {
                if (confirm("CẬP NHẬT THỜI GIAN CHO TÀI KHOẢN\n- Tài khoản : " + email + "\n- Số ngày : " + inputValue + " Ngày")) {
                    window.location.href = "?action=thoigian_acc&thoigian=" + inputValue + "&id=" + id + "&email=" + email;
                }
            }
        }

        function delay_time(button, id, email) {
            var inputElement = button.previousElementSibling;
            var inputValue = inputElement.value;
            if (inputValue == "") {
                alert("Hãy nhập số thời gian cần delay");
            } else {
                if (confirm("CẬP NHẬT DELAY CHO TÀI KHOẢN\n- Tài khoản : " + email + "\n- Delay : " + inputValue + " ")) {
                    window.location.href = "?action=delay_time&delay_time=" + inputValue + "&id=" + id + "&email=" + email;
                }
            }
        }

        function update_Thoigian_All() {
            if (confirm("Cập nhật lại thời gian sử dụng cho tất cả khách hàng?")) {
                window.location.href = "?action=update_thoigian_all";
            }
        }

        function closeModal(event) {
            var modal = event.target.closest(".modal");
            modal.style.display = "none";
        }

        function historyBayment(a) {
            const table = document.querySelector(".tblHistoryBayment tbody");
            const rows = table.querySelectorAll("tr");
            rows.forEach(row => {
                row.remove();
            });
            var tbody = "";
            var tdbody = "";
            var dataB = a.split('lionnguyen');
            for (let k in dataB) {
                if (dataB[k].split('_')[0]) {
                    var thoigian = (parseInt(dataB[k].split('_')[1])) * 1000;
                    tdbody += "<tr><td style='text-align:center;padding:8px 0px;'>" + (parseInt(k) + 1) + "</td>";
                    tdbody += "<td style='text-align:center;'>" + dataB[k].split('_')[0] + "</td>";
                    tdbody += "<td style='text-align:center;'>" + new Date(thoigian).getHours() + ":" + new Date(thoigian).getMinutes() + ":" + new Date(thoigian).getSeconds() + " - " + new Date(thoigian).getDate() + "/" + (new Date(thoigian).getMonth() + 1) + "/" + new Date(thoigian).getFullYear() + "</td></tr>";
                }
            }
            tbody = tbody + tdbody;
            var tbodyElement = document.querySelector(".tblHistoryBayment tbody");
            tbodyElement.innerHTML += tbody;
            var modal = document.getElementById("historyBayment");
            modal.style.display = "block";
        }

        function check_post(a, b) {
            document.getElementById('check_id_post').value = a;
            document.getElementById('id_accout').value = b;
            const table = document.querySelector(".tblCheckPost tbody");
            const rows = table.querySelectorAll("tr");
            rows.forEach(row => {
                row.remove();
            });
            var tbody = "";
            var tdbody = "";
            var dataB = a.split(',');
            for (let k in dataB) {
                if (dataB[k]) {
                    tdbody += "<tr id='" + dataB[k].split('|')[0] + "'><td style='text-align:center;padding:8px 0px;'>" + (parseInt(k) + 1) + "</td>";
                    tdbody += "<td style='text-align:center;'>" + dataB[k].split('|')[0] + "</td>";
                    tdbody += "<td style='text-align:center;'></td>";
                    tdbody += "<td style='text-align:center;'><button type='submit' style='display:inline-block;width:auto;padding:6px;margin-top:0;background-color:#4CAF50;'>on</button></td>";
                    tdbody += "<td style='text-align:center;'><button type='submit' style='display:inline-block;width:auto;padding:6px;margin-top:0;background-color:red;'>del</button></td></tr>";
                }
            }
            tbody = tbody + tdbody;
            var tbodyElement = document.querySelector(".tblCheckPost tbody");
            tbodyElement.innerHTML += tbody;
            var modal = document.getElementById("check_post");
            modal.style.display = "block";
        }

        function check_id_post() {
            list_id_post = document.getElementById('check_id_post').value;
            id_account = document.getElementById('id_accout').value;
            id_so_page = document.getElementById('id_so_page').value;
            button = document.getElementById('btn_check_id_post');
            button.innerHTML = "Đang kiểm tra...";
            button.disabled = true;
            var formData = new FormData();
            formData.append("action", "check_id_post");
            formData.append("id", id_account);
            formData.append("list_id_post", list_id_post);
            formData.append("id_so_page", id_so_page);
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        var data = xhr.responseText;
                        const table = document.querySelector(".tblCheckPost tbody");
                        const rows = table.querySelectorAll("tr");
                        rows.forEach(row => {
                            row.remove();
                        });
                        var tbody = "";
                        var tdbody = "";
                        var dataB = data.split('|');
                        console.log(data);
                        for (let k in dataB) {
                            if (dataB[k].split('-')[0]) {
                                var str = "";
                                if (dataB[k].split('-')[1] === 'Fail') {
                                    str = "style='background-color:#ff2828;color:#fff;'";
                                }
                                tdbody += "<tr " + str + "><td style='text-align:center;padding:8px 0px;'>" + (parseInt(k) + 1) + "</td>";
                                tdbody += "<td style='text-align:center;'>" + dataB[k].split('-')[0] + "</td>";
                                tdbody += "<td style='text-align:center;'>" + dataB[k].split('-')[1] + "</td>";
                                tdbody += "<td style='text-align:center;'><button type='submit' style='display:inline-block;width:auto;padding:6px;margin-top:0;background-color:#4CAF50;'>on</button></td>";
                                tdbody += "<td style='text-align:center;'><button type='submit' style='display:inline-block;width:auto;padding:6px;margin-top:0;background-color:red;'>del</button></td></tr>";
                            }
                        }
                        tbody = tbody + tdbody;
                        var tbodyElement = document.querySelector(".tblCheckPost tbody");
                        tbodyElement.innerHTML += tbody;
                    } else {
                        console.log("Có lỗi xảy ra khi gửi yêu cầu đến máy chủ.");
                    }
                    button.innerHTML = "Kiểm tra";
                    button.disabled = false;
                }
            };
            xhr.open("POST", "ajax/ajax.php", true);
            xhr.send(formData);
        }

        function reset_pass_acc(taikhoan, id, email) {
            var inputValue = prompt('Tạo mật khẩu mới tài khoản : [' + email + ']\nGõ từ [reset] để xác nhận :');
            if (inputValue !== null) {
                window.location.href = "?action=reset_pass_acc&verify=" + inputValue + "&id=" + id + "&email=" + email;
            }
        }

        function view_pass_acc(taikhoan, id, email) {
            var inputValue = prompt('Mật khẩu của tài khoản : [' + email + '] là :', id);
        }

        function delete_acc(taikhoan, id, email) {
            var inputValue = prompt('Xoá tài khoản : [' + email + ']\nGõ từ [delete] để xác nhận xoá :');
            if (inputValue !== null) {
                window.location.href = "?action=delete_acc&verify=" + inputValue + "&id=" + id + "&email=" + email;
            }
        }
    </script>
<?php } ?>
<?php if ($taikhoan['level'] == 5) { ?>
    <?php
    //Update thoi gian su dung
    $date_current = time();
    $timeout = abs(floor(((int)$date_current - (int)$taikhoan['ngay_license']) / (24 * 60 * 60)));
    $timeout = ($timeout > (int)$taikhoan['thoigian']) ? 0 : (int)$taikhoan['thoigian'] - $timeout;
    $sql = "UPDATE tb_user SET truycap = " . time() . ",conlai = " . $timeout;
    if ($timeout == 0) {
        $sql .= ",scan = 0,ajaxs = 0,demo = 1,block = 1";
    }
    $sql .= " WHERE taikhoan = '" . $_SESSION['username'] . "'";
    $d->query($sql);
    //Lay taikhoan
    $d->query("SELECT * FROM tb_user WHERE taikhoan = '" . $_SESSION['username'] . "'");
    $taikhoan = $d->fetch_array();
    $_SESSION['block'] = (int)$taikhoan['block'];
    //Thong bao trang thai
    if (count(explode('lionnguyen', $taikhoan['log_license'])) == 2 && $taikhoan['conlai'] > 0) {
        $trangthai = "<b style='color:red;'>Dùng thử</b>";
        $thongbao_hethandungthu = "<p style='color:red;font-size:14px;'>- Tài khoản dùng thử sẽ bị khoá 1 phần thông tin của khách hàng</p>";
    }
    if (count(explode('lionnguyen', $taikhoan['log_license'])) == 2 && $taikhoan['conlai'] == 0) {
        $trangthai = "<b style='color:red;'>Hết hạn dùng thử</b>";
        $thongbao_hethandungthu = "<p style='color:red;font-size:14px;'>- Tài khoản của bạn đã bị khoá tính năng quét tự động</p>";
    }
    if (count(explode('lionnguyen', $taikhoan['log_license'])) > 2 && $taikhoan['conlai'] > 0) {
        $trangthai = "<b style='color:green;'>Hoạt động</b>";
    }
    if (count(explode('lionnguyen', $taikhoan['log_license'])) > 2 && $taikhoan['conlai'] == 0) {
        $trangthai = "<b style='color:red;'>Cần gia hạn thêm</b>";
    }
    //Lấy data
    $sql = "SELECT * FROM tb_data WHERE id_user = " . $taikhoan['id'];
    if (isset($_REQUEST['phone']) && $_REQUEST['phone'] == '1') {
        $_SESSION['show_cmt_phone'] = "phone";
    } else {
        $_SESSION['show_cmt_phone'] = "all";
    }
    if (isset($_SESSION['show_cmt_phone'])) {
        if ($_SESSION['show_cmt_phone'] == "phone") {
            $sql .= " AND (
                LENGTH(phone) > 5 OR
                message LIKE '%086%' OR
                message LIKE '%096%' OR
                message LIKE '%097%' OR
                message LIKE '%098%' OR
                message LIKE '%039%' OR
                message LIKE '%038%' OR
                message LIKE '%037%' OR
                message LIKE '%036%' OR
                message LIKE '%035%' OR
                message LIKE '%034%' OR
                message LIKE '%033%' OR
                message LIKE '%032%' OR
                message LIKE '%091%' OR
                message LIKE '%094%' OR
                message LIKE '%088%' OR
                message LIKE '%083%' OR
                message LIKE '%084%' OR
                message LIKE '%085%' OR
                message LIKE '%081%' OR
                message LIKE '%082%' OR
                message LIKE '%070%' OR
                message LIKE '%079%' OR
                message LIKE '%077%' OR
                message LIKE '%076%' OR
                message LIKE '%078%' OR
                message LIKE '%089%' OR
                message LIKE '%090%' OR
                message LIKE '%093%' OR
                message LIKE '%092%' OR
                message LIKE '%052%' OR
                message LIKE '%056%' OR
                message LIKE '%058%' OR
                message LIKE '%099%' OR
                message LIKE '%059%' OR
                message LIKE '%087%')";
        }
    }
    if (isset($_REQUEST['keyword']) && $_REQUEST['keyword'] != '') {
        $_SESSION['keyword'] = $_REQUEST['keyword'];
    } else {
        $_SESSION['keyword'] = "";
    }
    if (isset($_SESSION['keyword'])) {
        $sql .= " and (name_sp like '%" . $_SESSION['keyword'] . "%' or uid_post like '%" . $_SESSION['keyword'] . "%')";
    }
    $sql .= " GROUP BY comment_uid order by thoigian desc";
    $sqlABC = $sql;
    $d->query($sql);
    $data_Excel = $d->result_array();
    $dem = $d->num_rows();
    $sql .= " limit 0,50";
    $d->query($sql);
    $data = $d->result_array();
    //Lấy ra dòng mới nhất
    $sqlABC .= " limit 1";
    $d->query($sqlABC);
    $cmt_new_fisrt = $d->fetch_array();
    //Xuat file
    if (isset($_REQUEST['action']) && $_REQUEST['action'] == "Export_Excel") {
        ob_clean();
        $tenFile = $taikhoan['taikhoan'] . "_" . date("dmY", time()) . ".xlsx";
        require _lib . "/PHPExcel/Classes/PHPExcel.php";
        $PHPExcel = new PHPExcel();
        $PHPExcel->setActiveSheetIndex(0);
        $PHPExcel->getActiveSheet()->setTitle('Data');
        $PHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(50);
        $PHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
        $PHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
        $PHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
        $PHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
        $PHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(50);
        $PHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
        $PHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
        //$PHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(50);
        $PHPExcel->getActiveSheet()->getStyle('A1:H1')->getFont()->setBold(true);
        $PHPExcel->getActiveSheet()->setCellValue('A1', 'Bài viết');
        $PHPExcel->getActiveSheet()->setCellValue('B1', 'Tên');
        $PHPExcel->getActiveSheet()->setCellValue('C1', 'Gender');
        $PHPExcel->getActiveSheet()->setCellValue('D1', 'Uid');
        $PHPExcel->getActiveSheet()->setCellValue('E1', 'Phone');
        $PHPExcel->getActiveSheet()->setCellValue('F1', 'PhoneCMT');
        $PHPExcel->getActiveSheet()->setCellValue('G1', 'Comment');
        $PHPExcel->getActiveSheet()->setCellValue('H1', 'Thời gian');
        //$PHPExcel->getActiveSheet()->setCellValue('I1', 'Ghi chú');
        $rowNumber = 2;
        $data = $data_Excel;
        foreach ($data as $index => $item) {
            $uid_fb = $item['uid'];
            $phone = ($item['phone'] !== "0") ? $item['phone'] : "";
            $phoneCMT = "";
            $pattern = '/[0-9.\s-]+/';
            preg_match_all($pattern, $item['message'], $matches);
            foreach ($matches[0] as $phoneNumber) {
                $cleanedPhoneNumber = preg_replace('/[\s.]/', '', $phoneNumber);
                if (!empty($cleanedPhoneNumber) && strlen($cleanedPhoneNumber) >= 10) {
                    $phoneCMT = $cleanedPhoneNumber;
                    break;
                }
            }
            if ($taikhoan['block'] == 1) {
                $uid_fb = substr($uid_fb, 0, -3) . "xxx";
                $phone = ($phone !== "") ? (substr($phone, 0, 7) . "xxx") : "";
                $phoneCMT = ($phoneCMT !== "") ? (substr($phoneCMT, 0, 7) . "xxx") : "";
            }
            $PHPExcel->getActiveSheet()->getStyle('C2:C1000')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER);
            $PHPExcel->getActiveSheet()->setCellValue('A' . $rowNumber, $item['name_sp']);
            $PHPExcel->getActiveSheet()->setCellValue('B' . $rowNumber, $item['name']);
            $PHPExcel->getActiveSheet()->setCellValue('C' . $rowNumber, $item['gender']);
            $PHPExcel->getActiveSheet()->setCellValue('D' . $rowNumber, $uid_fb . " ");
            $PHPExcel->getActiveSheet()->setCellValue('E' . $rowNumber, $phone);
            $PHPExcel->getActiveSheet()->setCellValue('F' . $rowNumber, $phoneCMT);
            $PHPExcel->getActiveSheet()->setCellValue('G' . $rowNumber, $item['message']);
            $PHPExcel->getActiveSheet()->setCellValue('H' . $rowNumber, date("d/m/Y H:s:i", $item['thoigian']));
            //$PHPExcel->getActiveSheet()->setCellValue('I' . $rowNumber, $item['note']);
            $rowNumber++;
        }
        header('Content-type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename=' . $tenFile);
        PHPExcel_IOFactory::createWriter($PHPExcel, 'Excel2007')->save('php://output');
        //PHPExcel_IOFactory::createWriter($PHPExcel, 'Excel2007')->save('data.xls');
        exit();
    }
    //Delete_all_cmt
    if (isset($_REQUEST['action']) && $_REQUEST['action'] == "delete_all_cmt" && $_REQUEST['verify'] == "delete") {
        $sql = "delete from tb_data where id_user = " . $taikhoan['id'];
        $d->query($sql);
        echo "<script>alert('Đã xoá tất cả cmt cũ');</script>";
        header("location:./");
    }
    ?>
    <style>
        .tooltip {
            position: relative;
            display: inline-block;
        }

        .tooltip .tooltiptext {
            visibility: hidden;
            min-width: 120px;
            background-color: #555;
            color: #fff;
            text-align: center;
            border-radius: 6px;
            padding: 5px 10px;
            position: absolute;
            z-index: 1;
            bottom: 100%;
            left: 50%;
            margin-left: -60px;
            opacity: 0;
            transition: opacity .3s
        }

        .tooltip .tooltiptext::after {
            content: "";
            position: absolute;
            top: 100%;
            left: 50%;
            margin-left: -5px;
            border-width: 5px;
            border-style: solid;
            border-color: #555 transparent transparent
        }

        .tooltip:hover .tooltiptext {
            visibility: visible;
            opacity: 1
        }
    </style>
    <div class="content">
        <h1>Thông tin tài khoản</h1>
        <div class="fird">
            <div class="table-container">
                <table style="font-size:13px;">
                    <thead>
                        <tr>
                            <th>Tài khoản</th>
                            <th>Ngày tạo</th>
                            <th>Ngày mua</th>
                            <th>Ngày hết hạn</th>
                            <th style="text-align:center;">Sử dụng</th>
                            <th style="text-align:center;">Còn lại</th>
                            <th>Trạng thái</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td style="padding:5px 10px;"><?= $taikhoan['taikhoan'] ?></br><?= $taikhoan['email'] ?></td>
                            <td style="white-space:nowrap;"><?= date("H:i:s - d/m/y", $taikhoan['ngaytao']) ?></td>
                            <td style="white-space:nowrap;"><?= date("H:i:s - d/m/y", $taikhoan['ngay_license']) ?></td>
                            <td style="white-space:nowrap;"><?= date("H:i:s - d/m/y", $taikhoan['ngay_license'] + ($taikhoan['thoigian'] * 24 * 60 * 60)) ?></td>
                            <td style="white-space:nowrap;text-align:center;"><?= $taikhoan['thoigian'] ?></td>
                            <td style="white-space:nowrap;text-align:center;"><?= $taikhoan['conlai'] ?></td>
                            <td style="white-space:nowrap;"><?= $trangthai ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <?php if (isset($thongbao_hethandungthu)) { ?>
                <?= $thongbao_hethandungthu ?>
            <?php } ?>
        </div>
        <h1>Danh sách bình luận (<span id="tongCMT" style="color:red;"><?= $dem ?></span>)</h1>
        <div class="fird">
            <div class="mua">
                <span><input type="checkbox" id="show_cmt_phone" onchange="show_cmt_phone();" <?php if (isset($_SESSION['show_cmt_phone']) && $_SESSION['show_cmt_phone'] == "phone") { ?> checked <?php } ?>> Chỉ hiển thị bình luận có SĐT </span>
                <button onclick="Export_Excel();" style=" white-space:nowrap;position:unset;border-radius:4px;padding:6px 8px;">Xuất Excel</button>
                <button onclick="Delete_all_cmt();" style=" white-space:nowrap;position:unset;border-radius:4px;padding:6px 8px;background-color:red;">Xoá tất cả</button>
            </div>
            <div style="margin:10px 0px;">
                <input type="search" id="txt_search" onkeypress="search_comment(event);" value="<?= isset($_REQUEST['keyword']) ? $_REQUEST['keyword'] : '' ?>" placeholder="Tìm kiếm theo ID bài viết, Tiêu đề" style="max-width:250px;width:250px;border:1px solid #2196F3;">
            </div>
            <div class="table-container">
                <table id="table_comment" style="font-size:13px;">
                    <thead>
                        <tr>
                            <th style="text-align:center;">Thời gian</th>
                            <th style="text-align:center;">Tên bài</th>
                            <th style="text-align:center;">Uid</th>
                            <th style="text-align:center;">Facebook</th>
                            <th style="text-align:center;">Gender</th>
                            <th style="text-align:center;">Phone</th>
                            <th style="text-align:center;">Comment</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php for ($i = 0; $i < count($data); $i++) {
                            $stt = $i + 1;
                            $uid_fb = $data[$i]['uid'];
                            $phone = ($data[$i]['phone'] !== "0") ? $data[$i]['phone'] : "";
                            $phoneCMT = "";
                            $pattern = '/[0-9.\s-]+/';
                            preg_match_all($pattern, $data[$i]['message'], $matches);
                            foreach ($matches[0] as $phoneNumber) {
                                $cleanedPhoneNumber = preg_replace('/[\s.]/', '', $phoneNumber);
                                if (!empty($cleanedPhoneNumber) && strlen($cleanedPhoneNumber) >= 10) {
                                    $phoneCMT = $cleanedPhoneNumber;
                                    break;
                                }
                            }
                            if ($taikhoan['block'] == 1) {
                                $uid_fb = substr($uid_fb, 0, -3) . "xxx";
                                $phone = ($phone !== "") ? (substr($phone, 0, 7) . "xxx") : "";
                                $phoneCMT = ($phoneCMT !== "") ? (substr($phoneCMT, 0, 7) . "xxx") : "";
                            }
                        ?>
                            <tr>
                                <td style="text-align:center;">
                                    <spam style="border:1px solid #000;padding:5px;color:#000;display:inline;width:180px;white-space:nowrap;"><?= date("d/m/Y - H:i:s", $data[$i]['thoigian']) ?></span>
                                </td>
                                <td>
                                    <div class="tooltip">
                                        <a href="https://www.facebook.com/<?= $data[$i]['uid_post'] ?>" target="_blank" style="color:#0076d4;text-decoration:none;white-space:break-spaces;word-wrap: break-word;;display:block;width:500px;"><?= $data[$i]['name_sp'] ?></a>
                                        <span class="tooltiptext"><?= $data[$i]['uid_post'] ?></span>
                                    </div>
                                </td>
                                <td style="text-align:center;">
                                    <p><?= $uid_fb ?></p>
                                </td>
                                <td style="text-align:center;">
                                    <a href="https://www.facebook.com/<?= $uid_fb ?>" target="_blank" style="color:#0076d4;text-decoration:none;white-space:nowrap;display:inline;width:200px;"><?= $data[$i]['name'] ?> </a>
                                </td>
                                <td style="text-align:center;">
                                    <p><?= $data[$i]['gender'] ?></p>
                                </td>
                                <td style="text-align:center;"><?= $phone ?> <?= $phoneCMT ?></td>
                                <td>
                                    <p style="width:400px;max-height:200px;white-space:normal;word-wrap:break-word;"><?= $data[$i]['message'] ?></p>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script>
        var offset = 50;
        var cmt_new_fisrt = '<?= $cmt_new_fisrt['thoigian'] ?>';
        var tongCMT = '<?= $dem ?>';
        var array_CMT = [];
        <?php for ($i = 0; $i < count($data); $i++) { ?>
            array_CMT.push('<?= $data[$i]['id'] ?>');
        <?php } ?>

        function isScrolledToBottom() {
            var scrollTop = document.documentElement.scrollTop || document.body.scrollTop;
            var windowHeight = window.innerHeight;
            var documentHeight = document.documentElement.offsetHeight;
            return scrollTop + windowHeight + 1 >= documentHeight;
        }
        window.onscroll = function() {
            if (offset < tongCMT) {
                if (isScrolledToBottom()) {
                    document.getElementById("loading-container").style.display = "flex";
                    loadMoreData();
                    offset += 50;
                }
            }
        };

        function show_cmt_phone() {
            var queryString = window.location.search;
            var urlParams = new URLSearchParams(queryString);
            if (urlParams.get('phone') === '1') {
                window.location.href = "/home";
            } else {
                window.location.href = "/home?phone=1";
            }
        }

        function search_comment(event) {
            if (event.key === 'Enter') {
                var searchValue = document.getElementById('txt_search').value;
                var currentParams = window.location.search;
                if (currentParams.includes('phone')) {
                    window.location.href = '/home' + currentParams + '&keyword=' + encodeURIComponent(searchValue);
                } else {
                    window.location.href = '/home?keyword=' + encodeURIComponent(searchValue);
                }
            }
        }

        function loadMoreData() {
            var formData = new FormData();
            formData.append("action", 'loadComment');
            formData.append("offset", offset);
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (xhr.status === 200) {
                    var data = xhr.responseText;
                    var table = document.getElementById("table_comment").getElementsByTagName('tbody')[0];
                    var data = data.split('-lionnguyen-').slice(0, -1);
                    for (var i = 0; i < data.length; i++) {
                        var id = data[i].split('-lion-')[0];
                        var thoigian = data[i].split('-lion-')[1];
                        var uid_post = data[i].split('-lion-')[2];
                        var tenbaiviet = data[i].split('-lion-')[3];
                        var uid = data[i].split('-lion-')[4];
                        var facebook = data[i].split('-lion-')[5];
                        var gender = data[i].split('-lion-')[6];
                        var phone = data[i].split('-lion-')[7];
                        var phonCMT = data[i].split('-lion-')[8];
                        var comment = data[i].split('-lion-')[9];
                        if (array_CMT.indexOf(id) === -1) {
                            array_CMT.push(id);
                            var newRow = table.insertRow(table.rows.length);
                            var cell1 = newRow.insertCell(0);
                            var cell2 = newRow.insertCell(1);
                            var cell3 = newRow.insertCell(2);
                            var cell4 = newRow.insertCell(3);
                            var cell5 = newRow.insertCell(4);
                            var cell6 = newRow.insertCell(5);
                            var cell7 = newRow.insertCell(6);
                            cell1.innerHTML = '<p style="text-align:center;"><spam style="border:1px solid #000;padding:5px;color:#000;display:inline;width:180px;">' + thoigian + '</span></p>';
                            cell2.innerHTML = '<p class="tooltip"><a href="https://www.facebook.com/' + uid_post + '" target="_blank" style="color:#0076d4;text-decoration:none;white-space:break-spaces;word-wrap: break-word;display:block;width:500px;">' + tenbaiviet + '</a><span class="tooltiptext">' + uid_post + '</span></p>';
                            cell3.innerHTML = '<p style="text-align:center;">' + uid + '</p>';
                            cell4.innerHTML = '<p style="text-align:center;"><a href="https://www.facebook.com/' + uid + '" target="_blank" style="color:#0076d4;text-decoration:none;white-space:break-spaces;">' + facebook + '</a></p>';
                            cell5.innerHTML = '<p style="text-align:center;">' + gender + '</p>';
                            cell6.innerHTML = '<p style="text-align:center;">' + phone + ' ' + phonCMT + '</p>';
                            cell7.innerHTML = '<p><p style="width:400px;max-height:200px;white-space:normal;word-wrap:break-word;">' + comment + '</p></p>';
                        }
                    }
                    setTimeout(function() {
                        document.getElementById("loading-container").style.display = "none";
                    }, 1);
                }
            };
            xhr.open("POST", "ajax/ajax.php", true);
            xhr.send(formData);
        }
        setTimeout(function() {
            //load_cmt_new();
        }, 5000);

        function load_cmt_new() {
            var formData = new FormData();
            formData.append("action", 'loadCommentNew');
            formData.append("cmt_new_fisrt", cmt_new_fisrt);
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (xhr.status === 200) {
                    var data = xhr.responseText;
                    var table = document.getElementById("table_comment");
                    var tbody = table.querySelector("tbody");
                    var data = data.split('-lionnguyen-').slice(0, -1);
                    for (var i = 0; i < data.length; i++) {
                        var id = data[i].split('-lion-')[0];
                        var thoigian = data[i].split('-lion-')[1];
                        var uid_post = data[i].split('-lion-')[2];
                        var tenbaiviet = data[i].split('-lion-')[3];
                        var uid = data[i].split('-lion-')[4];
                        var facebook = data[i].split('-lion-')[5];
                        var gender = data[i].split('-lion-')[6];
                        var phone = data[i].split('-lion-')[7];
                        var phonCMT = data[i].split('-lion-')[8];
                        var comment = data[i].split('-lion-')[9];
                        if (array_CMT.indexOf(id) === -1) {
                            array_CMT.push(id);
                            document.getElementById("tongCMT").textContent = parseInt(document.getElementById("tongCMT").textContent) + parseInt(data.length);
                            var newRow = tbody.insertRow(0);
                            var cell1 = newRow.insertCell(0);
                            var cell2 = newRow.insertCell(1);
                            var cell3 = newRow.insertCell(2);
                            var cell4 = newRow.insertCell(3);
                            var cell5 = newRow.insertCell(4);
                            var cell6 = newRow.insertCell(5);
                            var cell7 = newRow.insertCell(6);
                            cell1.innerHTML = '<p style="text-align:center;"><spam style="border:1px solid #000;padding:5px;color:#000;display:inline;width:180px;">' + thoigian + '</span></p>';
                            cell2.innerHTML = '<p class="tooltip"><a href="https://www.facebook.com/' + uid_post + '" target="_blank" style="color:#0076d4;text-decoration:none;white-space:break-spaces;display:block;width:500px;">' + tenbaiviet + '</a><span class="tooltiptext">' + uid_post + '</span></p>';
                            cell3.innerHTML = '<p style="text-align:center;">' + uid + '</p>';
                            cell4.innerHTML = '<p style="text-align:center;"><a href="https://www.facebook.com/' + uid + '" target="_blank" style="color:#0076d4;text-decoration:none;white-space:break-spaces;">' + facebook + '</a></p>';
                            cell5.innerHTML = '<p style="text-align:center;">' + gender + '</p>';
                            cell6.innerHTML = '<p style="text-align:center;">' + phone + ' ' + phonCMT + '</p>';
                            cell7.innerHTML = '<p><p style="width:200px;max-height:200px;white-space:normal;">' + comment + '</p></p>';
                        }
                    }
                }
            };
            xhr.open("POST", "ajax/ajax.php", true);
            xhr.send(formData);
            setTimeout(load_cmt_new, 5000);
        }

        function Export_Excel() {
            if (confirm("Xuất ra file Excel?")) {
                var currentPage = window.location.href;
                if (currentPage.includes("keyword=") || currentPage.includes("phone=")) {
                    currentPage = currentPage + "&action=Export_Excel";
                } else {
                    currentPage = currentPage + "?action=Export_Excel";
                }
                window.location.href = currentPage;
            }
        }

        function Delete_all_cmt() {
            var inputValue = prompt('Xoá tất cả cmt \nGõ từ [delete] để xác nhận xoá :');
            if (inputValue !== null) {
                window.location.href = "?action=delete_all_cmt&verify=" + inputValue;
            }
        }
        document.addEventListener("input", function(event) {
            if (event.target.classList.contains("txtNote")) {
                var id = event.target.dataset.id;
                var user = event.target.dataset.user;
                var note = event.target.value;
                var formData = new FormData();
                formData.append("user", user);
                formData.append("id", id);
                formData.append("note", note);
                var xhr = new XMLHttpRequest();
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === XMLHttpRequest.DONE) {
                        if (xhr.status === 200) {
                            var data = xhr.responseText;
                            if (data === "1") {
                                event.target.style.background = "#009688";
                                event.target.style.color = "#fff";
                            } else {
                                event.target.style.background = "unset";
                                event.target.style.color = "unset";
                            }
                        } else {
                            alert("Có lỗi xảy ra khi gửi yêu cầu đến máy chủ.");
                        }
                    }
                };
                xhr.open("POST", "ajax/ajax.php", true);
                xhr.send(formData);
            };
        });
    </script>
    <style>
        #messZalo {
            animation: 1s ease-in-out 0s normal none infinite running ring-messZalo;
        }

        @keyframes ring-messZalo {
            0% {
                transform: rotate(0deg) scale(1) skew(1deg);
            }

            10% {
                transform: rotate(-25deg) scale(1) skew(1deg);
            }

            20% {
                transform: rotate(25deg) scale(1) skew(1deg);
            }

            30% {
                transform: rotate(-25deg) scale(1) skew(1deg);
            }

            40% {
                transform: rotate(25deg) scale(1) skew(1deg);
            }

            50% {
                transform: rotate(0deg) scale(1) skew(1deg);
            }

            100% {
                transform: rotate(0deg) scale(1) skew(1deg);
            }
        }
    </style>
    <a href="https://zalo.me/0909252550" target="_blank" style="display:none">
        <img src="https://laysdt.top/img/icon-zalo-nbk.png" id="messZalo" style="position:fixed;z-index:997;bottom:30px;right:20px;width:65px;" />
        <span style="position:fixed;z-index:997;bottom:80px;right:25px;color:#fff;background:#ff3d3b;font-weight:bold;font-size:18px;border-radius:100px;padding:2px 7px;font-family: arial;">1</span>
    </a>
<?php } ?>