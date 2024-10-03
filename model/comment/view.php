<?php
if (!defined("_crmfb")) die("Truy cập trái phép");
?>

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
//
$d->query("SELECT COUNT(id) as sl FROM tb_post WHERE scan=1 and id_user='" . $taikhoan['id'] . "'");
$limit_post = $d->fetch_array();
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
<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</head>
<style>
    .tooltip1 {
        position: relative;
        display: inline-block;
    }

    .tooltip1 .tooltiptext {
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

    .tooltip1 .tooltiptext::after {
        content: "";
        position: absolute;
        top: 100%;
        left: 50%;
        margin-left: -5px;
        border-width: 5px;
        border-style: solid;
        border-color: #555 transparent transparent
    }

    .tooltip1:hover .tooltiptext {
        visibility: visible;
        opacity: 1
    }
</style>
<div class="container1">
    <marquee behavior="" direction="" style="font-size: 14px; margin-top: 5px;color:#4f99bd;font-weight: bold;"><img src="./images/vm1.png" alt="" style="width: 21px;margin-top: -10px;margin-right: 3px;"><img src="./images/vm1.png" alt="" style="width: 21px;margin-top: -10px;">Cảm ơn các bạn đã tin tưởng và sử dụng dịch vụ của chúng tôi ! Chúc các bạn ngày mới tốt lành nhiều niềm vui và năng lượng ~ ( SỰ RA ĐỜI TIÊN PHONG CỦA SẢN PHẨM LÀ QUÁ TRÌNH 2 NĂM NGHIÊN CỨU TẠO RA SẢN PHẨM TỐI ƯU VÀ CHẤT LƯỢNG NHẤT HIỆN NAY ) nắm bắt được tình hình khó khăn của việc chạy quảng cáo Facebook ADS vào những năm 2015 chạy quảng cáo là công cụ phổ biến nhất của người kinh doanh ở trên Facebook tuy nhiên chi phí chạy quảng cáo lại quá cao kèm theo đó là người kinh doanh không thể chắc chắn được hiệu quả của bài quảng cáo qua đó thu nhập không những không gia tăng mà còn để lại tâm lý chán nản cho mỗi người kinh doanh công cụ quét data Facebook Ads là giải pháp toàn diện với tính năng quét comment và comment ẩn của bài quảng cáo cập nhật data của bài viết liên tục trong 0,5s > lọc data comment thành data khách hàng niềm năng tối ưu chi phí và gia tăng lợi nhuận công cụ sẽ giúp người sử dụng quét tệp data bài quảng cáo của đối thủ với tỉ lệ chốt sale lên đến 80% với tính năng chất lượng và giao diện top 1 thị trường công cụ quét data Facebook ADS sẽ là trợ thủ đắc lực của người kinh doanh trong thị trường đầy cạnh tranh này ! Mọi thắc mắc về dịch vụ xin liên hệ ADMINISTRATOR > Hotline/Zalo 0899 363 969 📣📣</marquee>
    <div class="content">
        <div class="fird" style="opacity: 0;animation: fadeIn 0.5s ease-in forwards;">
            <!-- <h1 style="text-align: center;">Thông tin tài khoản</h1> -->
            <div class="table-container">
                <table style="font-size:13px;margin-bottom: 5px;">
                    <thead>
                        <tr>
                            <th style="text-align:center;">Tài khoản</th>
                            <th style="text-align:center;">Ngày tạo</th>
                            <th style="text-align:center;">Ngày mua</th>
                            <th style="text-align:center;">Ngày hết hạn</th>
                            <th style="text-align:center;">Bài đang quét / Bài mua</th>
                            <th style="text-align:center;">Sử dụng</th>
                            <th style="text-align:center;">Còn lại</th>
                            <th style="text-align:center;">Trạng thái</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td style="padding:5px 10px;font-size: 13px;font-weight: 400;">Thông tin tài khoản : <?= $taikhoan['taikhoan'] ?></br><?= $taikhoan['email'] ?></td>
                            <td style="white-space:nowrap;font-size: 13px;text-align: center;font-weight: 400;"><?= date("H:i:s - d/m/y", $taikhoan['ngaytao']) ?></td>
                            <td style="white-space:nowrap;font-size: 13px;text-align: center;font-weight: 400;"><?= date("H:i:s - d/m/y", $taikhoan['ngay_license']) ?></td>
                            <td style="white-space:nowrap;color:red;text-align: center;text-shadow: 0 10px 16px 4px red;">
                                <?= date("H:i:s - d/m/y", $taikhoan['ngay_license'] + ($taikhoan['thoigian'] * 24 * 60 * 60)) ?>
                            </td>
                            <td style="white-space:nowrap;text-align:center;font-size: 13px;font-weight: 400;"><?= $limit_post['sl'] ?>/<?= $taikhoan['limit_post'] ?></td>
                            <td style="white-space:nowrap;text-align:center;font-size: 13px;font-weight: 400;"><?= $taikhoan['thoigian'] ?></td>
                            <td style="white-space:nowrap;text-align:center;font-size: 13px;font-weight: 400;"><?= $taikhoan['conlai'] ?></td>
                            <td style="white-space:nowrap;text-align:center;"><?= $trangthai ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <?php if (isset($thongbao_hethandungthu)) { ?>
                <?= $thongbao_hethandungthu ?>
            <?php } ?>
        </div>

        <h1 style="text-align: center;margin: 20px 0;">Danh sách bình luận ( <span id="tongCMT" style="color:red;"><?= $dem ?></span> )</h1>
        <div class="fird" style="opacity: 0;animation: fadeIn 0.7s ease-in forwards;">

            <div class="btncontent">
                <div class="m1" style="display: flex;gap:20px">
                    <input type="search" id="txt_search" class="form-control inputSearch" onkeypress="search_comment(event);" value="<?= isset($_REQUEST['keyword']) ? $_REQUEST['keyword'] : '' ?>" placeholder="Tìm kiếm theo ID bài viết hoặc tiêu đề  &#128269;">
                    <div class="mua">
                        <span><input type="checkbox" id="show_cmt_phone" onchange="show_cmt_phone();" <?php if (isset($_SESSION['show_cmt_phone']) && $_SESSION['show_cmt_phone'] == "phone") { ?> checked <?php } ?>> Chỉ hiển thị bình luận có SĐT </span>
                    </div>
                </div>
                <div class="containerbtn">
                    <button onclick="Export_Excel();" class="btnExExcel">Xuất File Excel  <i class="fas fa-download"></i></button>
                    <button onclick="Delete_all_cmt();" class="btnExExcel btnExDel">Xoá tất cả  <i class="fa-solid fa-trash"></i></button>
                </div>
            </div>
            <div class="table-container">
                <table id="table_comment" style="font-size:13px;">
                    <thead>
                        <tr>
                            <th style="text-align:center;">ID</th>
                            <th style="text-align:center;">Thời gian</th>
                            <th style="text-align:center;">Tên bài</th>
                            <th style="text-align:center;">Uid</th>
                            <th style="text-align:center;">Facebook</th>
                            <th style="text-align:center;width: 100px;">Giới tính</th>
                            <th style="text-align:center;">SĐT</th>
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
                                <td style="text-align: center;"><?= $data[$i]['id'] ?></td>
                                <td style="text-align:center;">
                                    <spam style="padding:5px;color:#000;display:inline;width:180px;white-space:nowrap;"><?= date("d/m/Y - H:i:s", $data[$i]['thoigian']) ?></span>
                                </td>
                                <td>
                                    <div class="tooltip1">
                                        <a class="tooltipa" href="https://www.facebook.com/<?= $data[$i]['uid_post'] ?>" target="_blank" style="color:#0076d4;text-decoration:none;white-space:break-spaces;word-wrap: break-word;display:block;max-width:500px;"><?= $data[$i]['name_sp'] ?></a>
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
                                    <p>
                                        <?php
                                        if ($data[$i]['gender'] == 'male') {
                                            echo 'Nam';
                                        } elseif ($data[$i]['gender'] == 'female') {
                                            echo 'Nữ';
                                        } else {
                                            echo $data[$i]['gender'];
                                        }
                                        ?>
                                    </p>
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

        //chỉnh sửa link dẫn trỏ vào tệp /source-code, đưa lên host có thể bỏ qua
        function show_cmt_phone() {
            var queryString = window.location.search;
            var urlParams = new URLSearchParams(queryString);
            if (urlParams.get('phone') === '1') {
                window.location.href = "/comment";
            } else {
                window.location.href = "/comment?phone=1";
            }
        }

        function search_comment(event) {
            if (event.key === 'Enter') {
                var searchValue = document.getElementById('txt_search').value;
                var currentParams = window.location.search;
                if (currentParams.includes('phone')) {
                    window.location.href = '/comment' + currentParams + '&keyword=' + encodeURIComponent(searchValue);
                } else {
                    window.location.href = '/comment?keyword=' + encodeURIComponent(searchValue);
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
                        // var stt = i + 1;
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
                            var cell0 = newRow.insertCell(0);
                            var cell1 = newRow.insertCell(1);
                            var cell2 = newRow.insertCell(2);
                            var cell3 = newRow.insertCell(3);
                            var cell4 = newRow.insertCell(4);
                            var cell5 = newRow.insertCell(5);
                            var cell6 = newRow.insertCell(6);
                            var cell7 = newRow.insertCell(7);
                            cell0.innerHTML = '<p style="text-align:center;">' + id + '</span></p>';
                            cell1.innerHTML = '<p style="text-align:center;"><spam style="padding:5px;color:#000;display:inline;width:100px;">' + thoigian + '</span></p>';
                            cell2.innerHTML = '<p class="tooltip1"><a href="https://www.facebook.com/' + uid_post + '" target="_blank" style="color:#0076d4;text-decoration:none;white-space:break-spaces;word-wrap: break-word;display:block;width:300px;">' + tenbaiviet + '</a><span class="tooltiptext">' + uid_post + '</span></p>';
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
                            var cell1 = newRow.insertCell(0);
                            var cell2 = newRow.insertCell(1);
                            var cell3 = newRow.insertCell(2);
                            var cell4 = newRow.insertCell(3);
                            var cell5 = newRow.insertCell(4);
                            var cell6 = newRow.insertCell(5);
                            var cell7 = newRow.insertCell(6);
                            var cell8 = newRow.insertCell(7);
                            cell1.innerHTML = '<p style="text-align:center;">' + id + '</span></p>';
                            cell2.innerHTML = '<p style="text-align:center;"><span style="padding:5px;color:#000;display:inline;width:180px;">' + thoigian + '</span></p>';
                            cell3.innerHTML = '<p class="tooltip1"><a href="https://www.facebook.com/' + uid_post + '" target="_blank" style="color:#0076d4;text-decoration:none;white-space:break-spaces;display:block;width:350px;">' + tenbaiviet + '</a><span class="tooltiptext">' + uid_post + '</span></p>';
                            cell4.innerHTML = '<p style="text-align:center;">' + uid + '</p>';
                            cell5.innerHTML = '<p style="text-align:center;"><a href="https://www.facebook.com/' + uid + '" target="_blank" style="color:#0076d4;text-decoration:none;white-space:break-spaces;">' + facebook + '</a></p>';
                            cell6.innerHTML = '<p style="text-align:center;">' + gender + '</p>';
                            cell7.innerHTML = '<p style="text-align:center;">' + phone + ' ' + phonCMT + '</p>';
                            cell8.innerHTML = '<p><p style="width:200px;max-height:200px;white-space:normal;">' + comment + '</p></p>';
                        }
                    }
                }
            };
            xhr.open("POST", "ajax/ajax.php", true);
            xhr.send(formData);
            setTimeout(load_cmt_new, 5000);
        }

        // function Export_Excel() {
        //     if (confirm("Xuất ra file Excel?")) {
        //         var currentPage = window.location.href;
        //         if (currentPage.includes("keyword=") || currentPage.includes("phone=")) {
        //             currentPage = currentPage + "&action=Export_Excel";
        //         } else {
        //             currentPage = currentPage + "?action=Export_Excel";
        //         }
        //         window.location.href = currentPage;
        //     }
        // }
        function Export_Excel() {
            Swal.fire({
                title: 'Xác nhận xuất dữ liệu?',
                text: "Xuất dữ liệu ra file Excel?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Xuất',
                cancelButtonText: 'Hủy'
            }).then((result) => {
                if (result.isConfirmed) {
                    var currentPage = window.location.href;
                    if (currentPage.includes("keyword=") || currentPage.includes("phone=")) {
                        currentPage = currentPage + "&action=Export_Excel";
                    } else {
                        currentPage = currentPage + "?action=Export_Excel";
                    }
                    window.location.href = currentPage;
                }
            });
        }

        // function Delete_all_cmt() {
        //     var inputValue = prompt('Xoá tất cả cmt \nGõ từ [delete] để xác nhận xoá :');
        //     if (inputValue !== null) {
        //         window.location.href = "?action=delete_all_cmt&verify=" + inputValue;
        //     }
        // }
        function Delete_all_cmt() {
            Swal.fire({
                title: 'Xóa tất cả bình luận?',
                text: "Gõ từ [delete] để xác nhận xóa:",
                input: 'text',
                inputPlaceholder: 'Gõ "delete" để xác nhận',
                showCancelButton: true,
                confirmButtonText: 'Xác nhận',
                cancelButtonText: 'Hủy',
                preConfirm: (inputValue) => {
                    if (inputValue !== 'delete') {
                        Swal.showValidationMessage('Bạn phải gõ từ "delete" để xác nhận');
                    }
                    return inputValue;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "?action=delete_all_cmt&verify=" + result.value;
                }
            });
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
</div>


<style>
    table {
        border-radius: 7px;
        overflow: hidden;
    }

    .container {
        width: 100%;
        /* padding: 0 5%; */
        /* background-color: red; */
    }

    .btncontent {
        margin: 10px 0px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .fird {
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0px 0px 16px 4px #00000029;
    }

    .containerbtn {
        display: flex;
        justify-content: space-around;
        align-items: center;
        /* gap: 20px; */
    }

    .form-control {
        padding: 0px;
        margin: 0;
        outline: none;
    }

    .inputSearch {
        color: red;
        max-width: 350px;
        width: 250px;
        border: 1px solid #2196F3;
        color: #5a5959;
        font-size: 12px;
        margin: 0px;
    }

    .btnExExcel {
        padding: 5px 10px;
        border: none;
        font-size: 15px;
        outline: none;
        /* box-shadow: 0px 0px 16px 4px #a6f7ba; */
        border-radius: 7px;
        background-color: #198754;
        color: white;
        cursor: pointer;
    }

    .btnExExcel:hover {
        /* box-shadow: 0px 0px 16px 4px #f7c454; */
        background-color: #41c128;
    }

    .btnExDel {
        background-color: #f73c3c;
        /* box-shadow: 0px 0px 16px 4px #ff9a9a; */
        margin-left: 10px;
    }

    .btnExDel:hover {
        background-color: #ff4040;
        box-shadow: 0px 0px 16px 4px #ff9a9a;
    }

    .mua {
        padding: 6px;
        border: 1px solid #ccc;
        border-radius: 8px;
        background-color: #f9f9f9;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .mua input[type="checkbox"] {
        transform: scale(1.2);
    }

    .mua span {
        font-size: 13px;
        color: #333;
    }

    .mua:hover {
        background-color: #e6f7ff;
        border-color: #007bff;
        transition: background-color 0.3s ease, border-color 0.3s ease;
    }

    .tooltipa {
        z-index: 10;
        color: red;
    }

    @media (max-width: 768px) {
        .btncontent {
            display: flex;
            flex-direction: column;
        }

        .inputSearch {
            margin: 12px 0;
            padding: 3px 7px;
        }

        .btnExExcel {
            margin-bottom: 10px;
        }

        .btnExDel {
            margin-left: 0;
        }

        .containerbtn {
            width: 100%;
        }

        .ds {
            flex-direction: column;
            margin-bottom: 10px;
        }

        .m1 {
            flex-direction: column;
            margin-bottom: 10px;
        }
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


<!-- Popup thông báo -->
<div class="popup" id="popup">
    <div class="popup-content">
        <img src="./images/tenlua.png" style="width: 100px;" alt="">
        <h5>Chào mừng bạn đến với vua quét comment facebook ads!</h5>
        <div class="dimg" style="width: 100%;">
            <img src="./images/km1.png" alt="" style="width: 100%;">
        </div>
        <p style="color: #0056b3;margin-top: 7px;font-size: 13px;font-weight: bold;">Giảm giá 10-30% cho khách hàng mua với số lượng link bài viết lớn hơn 100 </p>
        <!-- <p style="color: #0056b3;margin-top: 7px;font-size: 13px;">✅ Cam kết tư vấn, hỗ trợ tận tình mọi thắc mắc của khách hàng</p>
        <p style="color: #0056b3;margin-top: 7px;font-size: 13px;">✅ Liên tục cải tiến sản phẩm, giúp khách hàng có trải nghiệm tốt nhất</p> -->
        <i style="display: block;font-size: 13px;">Mọi thắc mắc hay yêu cầu hỗ trợ xin vui lòng liên hệ sdt: 0899.363.969</i>
        <button id="closePopup">Đã hiểu</button>
    </div>
</div>

<body>

</body>

</html>



<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Kiểm tra nếu người dùng đã truy cập trang web trước đó hay chưa
    if (!localStorage.getItem('popupDisplayed')) {
        // Hiển thị popup
        document.getElementById('popup').style.display = 'flex';
        // Lưu trạng thái vào localStorage để không hiển thị lại lần nữa
        localStorage.setItem('popupDisplayed', 'true');
    }

    // Đóng popup khi người dùng bấm nút
    document.getElementById('closePopup').addEventListener('click', function() {
        document.getElementById('popup').style.display = 'none';
    });
</script>

<style>
    .popup {
        display: none;
        /* Ẩn popup mặc định */
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        /* Nền mờ */
        justify-content: center;
        align-items: center;
        z-index: 1000;
        animation: faceIn 4s ease-in-out forwards;
    }

    .popup-content {
        width: 95%;
        background-color: #fff;
        padding: 15px;
        border-radius: 8px;
        text-align: center;
        max-width: 400px;
        width: 100%;
    }

    .popup-content h5 {
        margin: 0 0 10px;
    }

    .popup-content button {
        margin-top: 20px;
        padding: 8px 20px;
        background-color: #007bff;
        color: #fff;
        border: none;
        border-radius: 9px;
        cursor: pointer;
    }

    .popup-content button:hover {
        background-color: #0056b3;
    }

    .popup-content p {
        font-size: 15px;
    }

    @keyframes faceIn {
        0% {
            opacity: 0;
            transform: translateY(20px);
        }

        65% {
            opacity: 0;
            transform: translateY(20px);
        }

        100% {
            opacity: 1;
            transform: translateY(0px);
        }
    }
</style>