<?php
if (!defined("_crmfb")) die("Truy cập trái phép");
?>
<?php
$_SESSION['id_user'] = ($_GET['id']) * 5;
//Lay thong tin taikhoan
$d->query("SELECT * FROM tb_user WHERE id = '" . $_GET['id'] . "'");
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
    $tenFile = $taikhoan['taikhoan'] . date("dmY", time()) . ".xlsx";
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
    header("location:user?id=" . $taikhoan['id']);
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
    <h1>Thông tin tài khoản <a href="post?id=<?= $_GET['id'] ?>">Xem bài viết</a></h1>
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
            <button onclick="Export_Excel();" style=" white-space:nowrap;position:unset;border-radius:4px;padding:6px 8px;">Xuất ra Excel</button>
            <button onclick="Delete_all_cmt();" style=" white-space:nowrap;position:unset;border-radius:4px;padding:6px 8px;background-color:red;">Xoá tất cả</button>
        </div>
        <div style="margin:10px 0px;">
            <input type="search" id="txt_search" onkeypress="search_comment(event);" value="<?= isset($_REQUEST['keyword']) ? $_REQUEST['keyword'] : '' ?>" placeholder="Tìm kiếm theo ID bài viết, tiêu đề" style="max-width:550px;width:350px;border:1px solid #2196F3;">
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
                                <spam style="padding:5px;color:#000;display:inline;width:180px;white-space:nowrap;"><?= date("d/m/Y - H:i:s", $data[$i]['thoigian']) ?></span>
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
                            <td style="text-align:center;"><?= $phone ?></br><?= $phoneCMT ?></td>
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
            window.location.href = "/user?id=<?= $_GET['id'] ?>";
        } else {
            window.location.href = "/user?id=<?= $_GET['id'] ?>&phone=1";
        }
    }

    function search_comment(event) {
        if (event.key === 'Enter') {
            var searchValue = document.getElementById('txt_search').value;
            var currentParams = window.location.search;
            if (currentParams.includes('phone')) {
                window.location.href = '/user?id=<?= $_GET['id'] ?>&' + currentParams + '&keyword=' + encodeURIComponent(searchValue);
            } else {
                window.location.href = '/user?id=<?= $_GET['id'] ?>&keyword=' + encodeURIComponent(searchValue);
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
                        cell6.innerHTML = '<p style="text-align:center;">' + phone + '</br>' + phonCMT + '</p>';
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
        load_cmt_new();
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
            window.location.href = "?id=<?= $_GET['id'] ?>&action=delete_all_cmt&verify=" + inputValue;
        }
    }
</script>