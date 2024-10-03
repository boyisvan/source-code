<?php
if (!defined("_crmfb")) die("Truy cập trái phép");
$sql = "select * from tb_post where del = 0 and id_user=" . ((int)$_SESSION['id_user'] / 5);
$d->query($sql);
$dem_data = $d->num_rows();
$page = (isset($_GET["page"])) ? (int)$_GET["page"] : 1;
$setLimit = (isset($_GET["limit"])) ? (int)$_GET["limit"] : 1000000;
$pageLimit = ($page * $setLimit) - $setLimit;
$phantrang = phantrang($setLimit, $page, $dem_data);
$sql .= " order by scan_user desc,ngaycapnhat desc limit " . $pageLimit . "," . $setLimit;
$d->query($sql);
$data = $d->result_array();
//Upload
require _lib . "/PHPExcel/Classes/PHPExcel.php";
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['excel_file']) && $_FILES['excel_file']['error'] == UPLOAD_ERR_OK) {
    $fileTmpPath = $_FILES['excel_file']['tmp_name'];
    try {
        // Đọc tệp XLSX
        $excelReader = PHPExcel_IOFactory::createReaderForFile($fileTmpPath);
        $PHPExcel = $excelReader->load($fileTmpPath);
        // Lấy sheet đầu tiên
        $sheet = $PHPExcel->getActiveSheet();
        // Lấy dữ liệu từ các ô
        $data = $sheet->toArray();
        // Hiển thị dữ liệu
        foreach ($data as $index => $row) {
            if (isset($row[0]) && isset($row[1])) {
                $id_user = ((int)$_SESSION['id_user'] / 5);
                $link = (int)htmlspecialchars($row[1]);
                $ten = htmlspecialchars($row[0]);
                $uid = (int)htmlspecialchars($row[1]);
                $ngaytao = time();
                $sql_1 = "INSERT INTO tb_post(id_user,link,ten,uid,ngaytao) VALUES ($id_user,'" . $link . "','" . $ten . "','" . $uid . "',$ngaytao) ON DUPLICATE KEY UPDATE del = 0";
                echo "INSERT INTO tb_post(id_user,link,ten,uid,ngaytao) VALUES ($id_user,'" . $link . "','" . $ten . "','" . $uid . "',$ngaytao) ON DUPLICATE KEY UPDATE del = 0" . "</br>";
                $d->query($sql_1);
            }
        }
        exit();
        echo '<script>alert("THÊM THÀNH CÔNG");window.location.href = "./realtime";</script>';
    } catch (Exception $e) {
        echo 'Error loading file: ', $e->getMessage();
    }
}
//Export file
if (isset($_REQUEST['action']) && $_REQUEST['action'] == "Export_Excel") {
    ob_clean();
    $tenFile = $taikhoan['taikhoan'] . "_" . date("dmY", time()) . ".xlsx";
    $PHPExcel = new PHPExcel();
    $PHPExcel->setActiveSheetIndex(0);
    $PHPExcel->getActiveSheet()->setTitle('Post');
    $PHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(50);
    $PHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
    $PHPExcel->getActiveSheet()->getStyle('A1:B1')->getFont()->setBold(true);
    $PHPExcel->getActiveSheet()->setCellValue('A1', 'Bài viết');
    $PHPExcel->getActiveSheet()->setCellValue('B1', 'Uid');
    $rowNumber = 2;
    foreach ($data as $index => $item) {
        $PHPExcel->getActiveSheet()->getStyle('C2:C1000')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER);
        $PHPExcel->getActiveSheet()->setCellValue('A' . $rowNumber, $item['ten']);
        $PHPExcel->getActiveSheet()->setCellValue('B' . $rowNumber, $item['uid'] . " ");
        $rowNumber++;
    }
    header('Content-type: application/vnd.ms-excel');
    header('Content-Disposition: attachment; filename=' . $tenFile);
    PHPExcel_IOFactory::createWriter($PHPExcel, 'Excel2007')->save('php://output');
    //PHPExcel_IOFactory::createWriter($PHPExcel, 'Excel2007')->save('data.xls');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>

</body>

</html>
<style>
    .cot_50 {
        width: 50%;
        display: inline-block;
    }

    tr:nth-child(even) {
        background-color: unset;
    }

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

    #autocomplete-list {
        width: 100%;
        max-height: 200px;
        background: #009688;
        color: #fff;
        overflow-y: scroll;
        border: 1px solid #009688;
        border-top: 0;
    }

    #autocomplete-list li {
        list-style: none;
        display: block;
        padding: 10px 10px;
        cursor: pointer;
    }

    #autocomplete-list li:hover {
        background: #ecefef;
    }
</style>
<div class="content">
    <div class="addPost">
        <div class="cot_50 ">
            <h1 style="font-size: 20px;text-align: center;">Thêm bài viết mới</h1>
            <div class="fird">
                <div class="form-container" style="width:100%;">
                    <div class="link-post-container" style="margin-bottom:10px;">
                        <label for="link-post" style="margin-bottom: 7px;">ID bài viết <span style="color:red">*</span></label>
                        <input type="text" id="link-post" name="link-post" maxlength="5000" placeholder="648250977342978" required>
                    </div>
                    <div class="ten-post-container">
                        <label for="ten-post">Tên bài viết <span style="color:red">*</span></label>
                        <input type="text" id="ten-post" name="ten-post" maxlength="500" required>
                        <ul id="autocomplete-list"></ul>
                    </div>
                    <button type="submit" id="btn-add-post">Thêm bài viết</button>
                </div>
            </div>
        </div>
        <div class="cot_50" style="display:none;">
            <h1 style="padding-left:10px;">Thêm nhiều bài viết</h1>
            <div class="fird">
                <div class="form-container" style="width:100%;">
                    <div class="link-post-container" style="margin-bottom:10px;">
                        <form action="" method="post" enctype="multipart/form-data">
                            <input type="file" name="excel_file" accept=".xlsx" required>
                            <input type="submit" value="Tải lên">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div style="clear:both;padding-bottom:20px;"></div>
    <div class="fird fird1">
        <h3 style="text-align: center;color: #817a7a;">Tổng số bài viết : <span id="dem_baiviet" style="color:#3a87ad"><?= $dem_data ?></span> </h3>
        <div>
            <input type="search" id="txt_search" oninput="search_post_user();" placeholder="Tìm kiếm ID, tiêu đề" style="max-width:180px;width:180px;border:1px solid #2196F3;">
            <select name="limit" id="limit" onchange="window.location.href='?limit='+$(this).val();" style="border:1px solid #2196F3;border-radius:5px;padding:10px 10px;">
                <option value="10000000" <?php if (isset($_GET["limit"]) && $_GET["limit"] == 10000000) { ?>selected<?php } ?>>Tất cả</option>
                <option value="100" <?php if (isset($_GET["limit"]) && $_GET["limit"] == 100) { ?>selected<?php } ?>>100</option>
                <option value="200" <?php if (isset($_GET["limit"]) && $_GET["limit"] == 200) { ?>selected<?php } ?>>200</option>
                <option value="500" <?php if (isset($_GET["limit"]) && $_GET["limit"] == 500) { ?>selected<?php } ?>>500</option>
                <option value="1000" <?php if (isset($_GET["limit"]) && $_GET["limit"] == 1000) { ?>selected<?php } ?>>1000</option>
            </select>
            <input type="date" id="timedate" class="form-control" value="<?php if (isset($_GET['static_date']) && $_GET['static_date'] != '') {
                                                                                echo $_GET['static_date'];
                                                                            } else {
                                                                                echo date('Y-m-d');
                                                                            } ?>" style="border: 1px solid #2196F3;border-radius: 5px;padding: 8px 5px;">
            <button type="submit" id="restore_post" onclick="restore_post($('#timedate').val())" style="background-color:#3a87ad;display:inline-block;width:auto;padding:10px;margin-top:0;">Khôi phục  <i class="fa-solid fa-window-restore"></i></button>
            <button type="submit" id="xoahet_post" onclick="deletePost_user()" style="background-color:#f00;display:inline-block;width:auto;padding:10px;margin-top:0;display:none;">Xóa hết</button>
            <!-- <button type="submit" onclick="if(confirm('Xuất ra file Excel?'))window.location.href='?action=Export_Excel'" style="background-color:#198754;color:#fff;border:0px;cursor:pointer;display:inline-block;width:auto;padding:10px;margin-top:0;margin-bottom:5px;">Xuất Excel  <i class="fa-solid fa-download"></i></button> -->
            <button type="button" id="exportExcelButton" style="background-color:#198754;color:#fff;border:0px;cursor:pointer;display:inline-block;width:auto;padding:10px;margin-top:0;margin-bottom:5px;">
                Xuất Excel <i class="fa-solid fa-download"></i>
            </button>
        </div>
        <div>
            <div class="clear"></div>
            <?= $phantrang ?>
        </div>
        <div class="table-container">
            <table id="table_post" style="zoom:0.85;">
                <thead>
                    <tr>
                        <!-- <th style="text-align:center;width: 20px;">STT</th> -->
                        <th>Tiêu đề</th>
                        <th style="text-align:center;"> UID Bài Viết</th>
                        <th style="text-align:center;width:20px;"><input type="checkbox" onclick="selectPostAll()" style="width:18px;height:18px;"></th>
                        <th style="text-align:center;width:150px;">Thời gian cuối <i class="fa-regular fa-clock"></i></th>
                        <th style="width:60px;width:100px;">Trạng thái</th>
                    </tr>
                </thead>
                <tbody>
                    <?php for ($i = 0; $i < count($data); $i++) {
                        $stt = $i + 1;
                        $time_elapsed = time() - $data[$i]['ngaycapnhat'];
                        $hours = ceil($time_elapsed / 3600);
                        $minutes = ceil($time_elapsed / 60);
                    ?>
                        <tr>
                            <!-- <td style="text-align: center;"><?= $stt ?></td> -->
                            <td style="padding: 05px 7px;">
                                <div class="tooltip" style="width:90%;">
                                    <textarea id="txtpost_<?= $data[$i]['id'] ?>" style="width:100%;min-width: 200px;;padding:5px;border:1px solid #817a7a;border-radius:4px;"><?= $data[$i]['ten'] ?></textarea>
                                    <span class="tooltiptext"><?= $data[$i]['uid'] ?></span>
                                </div>
                                <button type="submit" onclick="edit_post('txtpost_<?= $data[$i]['id'] ?>')" style="background-color:#ffc107;display:inline-block;width:60px;padding:10px;margin-top:0;vertical-align: top;">Sửa</button>
                            </td>
                            <td style="width:10px;"><a href="https://facebook.com/<?= $data[$i]['uid'] ?>" target="_blank" style="color:#000;text-decoration:none1;"><?= $data[$i]['uid'] ?></a></td>
                            <td style="text-align:center;">
                                <input type="checkbox" id="<?= $data[$i]['id'] ?>" class="checkbox_post" onchange="getCheckedCheckboxIds()" style="width:18px;height:18px;">
                            </td>
                            <td style="text-align:center;"><button type="submit" style="background-color:#03A9F4;display:inline-block;width:auto;padding:10px;margin-top:0;cursor:auto;"><?= ($time_elapsed > 3600) ? "{$hours} giờ" : "{$minutes} phút" ?></button></td>
                            <td style="text-align:center;">
                                <button type="submit" id="button_<?= $data[$i]['id'] ?>" onclick="scan_post_user('button_<?= $data[$i]['id'] ?>')" style="display:inline-block;width:auto;padding:10px;margin-top:0;<?php if ($data[$i]['scan_user'] == 1) { ?>background-color:#4CAF50;<?php } else { ?>background-color:#f44336;<?php } ?>"><?php if ($data[$i]['scan_user'] == 1) { ?>On<?php } else { ?>Off<?php } ?></button>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
        <div style="clear:both;"></div>
    </div>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var input = document.getElementById("ten-post");
        var list = document.getElementById("autocomplete-list");
        input.addEventListener("input", function() {
            var inputValue = input.value.toLowerCase();
            //var data = ["apple", "banana", "cherry", "grape", "orange", "pear"];
            var formData = new FormData();
            formData.append("action", 'data_ten_post_user');
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        var data = xhr.responseText;
                        var dataA = data.split('lionnguyen');
                        var filteredData = dataA.filter(item => item.toLowerCase().includes(inputValue));
                        displayAutocomplete(filteredData);
                    } else {
                        alert("Có lỗi xảy ra khi gửi yêu cầu đến máy chủ.");
                    }
                }
            };
            xhr.open("POST", "ajax/ajax.php", true);
            xhr.send(formData);
        });

        function displayAutocomplete(data) {
            list.innerHTML = "";
            if (data.length > 0) {
                data.forEach(item => {
                    var listItem = document.createElement("li");
                    listItem.textContent = item;
                    listItem.addEventListener("click", function() {
                        input.value = item;
                        list.style.display = "none";
                    });
                    list.appendChild(listItem);
                });
                list.style.display = "block";
            } else {
                list.style.display = "none";
            }
        }
        document.addEventListener("click", function(e) {
            if (e.target !== input && e.target !== list) {
                list.style.display = "none";
            }
        });
    });
    document.getElementById('btn-add-post').addEventListener('click', function() {
        var link = document.getElementById('link-post').value;
        var ten = document.getElementById('ten-post').value;
        if (link === "") {
            alert("Nhập đầy đủ");
            return false;
        }
        if (window.confirm('Thêm bài mới')) {
            document.getElementById("loading-container").style.display = "flex";
            var formData = new FormData();
            formData.append("action", 'add_post_user');
            formData.append("link", link);
            formData.append("ten", ten);
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    document.getElementById("loading-container").style.display = "none";
                    if (xhr.status === 200) {
                        var data = xhr.responseText;
                        if (data === 'limit') {
                            $.notify("Đạt tối đa số link cho phép", "warn");
                        }
                        if (data === 'fail') {
                            $.notify("Lỗi, hãy thử lại", "error");
                        }
                        if (data === 'ok') {
                            $.notify("Thêm thành công", "success");
                            loadPost_user('searchPost_user');
                        }
                        if (data === 'tontailink') {
                            $.notify("Link bài viết này đã tồn tại", "info");
                        }
                        if (data === 'die') {
                            $.notify("Lỗi, liên hệ Admin để được hỗ trợ", "error");
                        }
                    } else {
                        $.notify("Có lỗi xảy ra khi gửi yêu cầu đến máy chủ", "error");
                    }
                }
            };
            xhr.open("POST", "ajax/ajax.php", true);
            xhr.send(formData);
        }
    });
    var checkedIds = [];
    var isChecked = false;

    function selectPostAll() {
        var checkboxes = document.getElementsByClassName('checkbox_post');
        isChecked = !isChecked;
        for (var i = 0; i < checkboxes.length; i++) {
            checkboxes[i].checked = isChecked;
        }
        getCheckedCheckboxIds();
    }

    function getCheckedCheckboxIds() {
        checkedIds = [];
        var checkboxes = document.querySelectorAll('.checkbox_post');
        checkboxes.forEach(function(checkbox) {
            if (checkbox.checked) {
                checkedIds.push(checkbox.id);
            }
        });
        var xoahet_post = document.getElementById('xoahet_post');
        if (checkedIds.length > 0) {
            xoahet_post.style.display = 'inline-block';
        } else {
            xoahet_post.style.display = 'none';
        }
        console.log(checkedIds);
    }

    function deletePost_user() {
        if (confirm("Xóa các bài viết đã chọn")) {
            document.getElementById("loading-container").style.display = "flex";
            var formData = new FormData();
            formData.append("action", 'delete_post_user');
            formData.append("checkedIds", checkedIds);
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        var data = xhr.responseText;
                        if (data == 'ok') {
                            $.notify("Xóa thành công", "success");
                            loadPost_user('searchPost_user');
                            document.getElementById("loading-container").style.display = "none";
                        }
                    } else {
                        $.notify("Có lỗi xảy ra khi gửi yêu cầu đến máy chủ", "error");
                    }
                }
            };
            xhr.open("POST", "ajax/ajax.php", true);
            xhr.send(formData);
        }
    }

    function restore_post(a) {
        Swal.fire({
            title: 'Khôi phục dữ liệu từ ngày [' + a + ']?',
            text: 'Bạn có chắc chắn muốn khôi phục bài viết này?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3a87ad',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Có',
            cancelButtonText: 'Không'
        }).then((result) => {
            if (result.isConfirmed) {

                document.getElementById("loading-container").style.display = "flex";


                var formData = new FormData();
                formData.append("action", 'restore_post');
                formData.append("timedate", a);
                var xhr = new XMLHttpRequest();
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === XMLHttpRequest.DONE) {
                        if (xhr.status === 200) {
                            var data = xhr.responseText;
                            if (data == 'ok') {
                                $.notify("Khôi phục thành công", "success");
                                loadPost_user('searchPost_user');
                            } else {
                                $.notify("Có lỗi xảy ra khi khôi phục bài viết", "error");
                            }
                            document.getElementById("loading-container").style.display = "none";
                        } else {
                            $.notify("Có lỗi xảy ra khi gửi yêu cầu đến máy chủ", "error");
                        }
                    }
                };
                xhr.open("POST", "ajax/ajax.php", true);
                xhr.send(formData);
            }
        });
    }

    function restore_post(a) {
        if (confirm("Khôi phục ngày [" + a + "]")) {
            document.getElementById("loading-container").style.display = "flex";
            var formData = new FormData();
            formData.append("action", 'restore_post');
            formData.append("timedate", a);
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        var data = xhr.responseText;
                        if (data == 'ok') {
                            $.notify("Khôi phục thành công", "success");
                            loadPost_user('searchPost_user');
                            document.getElementById("loading-container").style.display = "none";
                        }
                    } else {
                        $.notify("Có lỗi xảy ra khi gửi yêu cầu đến máy chủ", "error");
                    }
                }
            };
            xhr.open("POST", "ajax/ajax.php", true);
            xhr.send(formData);
        }
    }

    function edit_post(IDtext) {
        if (window.confirm('Lưu chỉnh sửa?')) {
            var text_post = document.getElementById(IDtext);
            textValue = text_post.value;
            var formData = new FormData();
            formData.append("action", 'edit_post');
            formData.append("IDtext", IDtext);
            formData.append("textValue", textValue);
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        var data = xhr.responseText;
                        if (data == 'ok') {
                            $.notify("Sửa thành công", "success");
                        }
                    } else {
                        $.notify("Có lỗi xảy ra khi gửi yêu cầu đến máy chủ", "error");
                    }
                }
            };
            xhr.open("POST", "ajax/ajax.php", true);
            xhr.send(formData);
        }
    }

    function scan_post_user(IDbutton) {
        var button = document.getElementById(IDbutton);
        var formData = new FormData();
        formData.append("action", 'scan_user');
        formData.append("IDbutton", IDbutton);
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    var data = xhr.responseText;
                    //alert(data);
                    if (data === 'limit') {
                        $.notify("Đã bị giới hạn bài quét", "warn");
                    }
                    if (data == '0') {
                        $.notify("Đã tắt scan_user", "info");
                        button.textContent = 'Off';
                        button.style.backgroundColor = '#f44336';
                    }
                    if (data == '1') {
                        $.notify("Đã bật scan_user", "success");
                        button.textContent = 'On';
                        button.style.backgroundColor = '#4CAF50';
                    }
                } else {
                    $.notify("Có lỗi xảy ra khi gửi yêu cầu đến máy chủ", "error");
                }
            }
        };
        xhr.open("POST", "ajax/ajax.php", true);
        xhr.send(formData);
    }

    function search_post_user() {
        var keyword = document.getElementById('txt_search');
        keyword = keyword.value;
        loadPost_user('searchPost_user', keyword);
    }

    function loadPost_user(type, keyword = "") {
        var formData = new FormData();
        formData.append("action", 'loadPost_user');
        formData.append("type", type);
        formData.append("keyword", keyword);
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function() {
            if (xhr.status === 200) {
                var data = xhr.responseText;
                var table = document.getElementById("table_post");
                var tbody = table.querySelector('tbody');
                tbody.innerHTML = '';
                var data = data.split('-lionnguyen-').slice(0, -1);
                var dataString = "";
                for (var i = 0; i < data.length; i++) {
                    var id = data[i].split('-lion-')[0];
                    var link = data[i].split('-lion-')[1];
                    var ten = data[i].split('-lion-')[2];
                    var uid = data[i].split('-lion-')[3];
                    var scan_user = data[i].split('-lion-')[4];
                    var ngaycapnhat = data[i].split('-lion-')[5];
                    if (scan_user == 1) {
                        var strScanA = "background-color:#4CAF50;";
                        var strScanB = "On";
                    }
                    if (scan_user == 0) {
                        var strScanA = "background-color:#f44336;";
                        var strScanB = "Off";
                    }
                    dataString += '<tr>';
                    dataString += '<td><div class="tooltip" style="width:90%;"><textarea id="txtpost_' + id + '" style="width:100%;padding:5px;border:1px solid #817a7a;border-radius:4px;">' + ten + '</textarea><span class="tooltiptext">' + uid + '</span></div> <button type="submit" onclick="edit_post(\'txtpost_' + id + '\')" style="background-color:#FF9800;display:inline-block;width:auto;padding:10px;margin-top:0;vertical-align: top;">Sửa</button> </td>';
                    dataString += '<td style="width:10px;"><a href="https://facebook.com/' + uid + '" target="_blank" style="color:#000;text-decoration:none1;">' + uid + '</a></td>';
                    dataString += '<td style="text-align:center;"><input type="checkbox" id="' + id + '" class="checkbox_post" onchange="getCheckedCheckboxIds()" style="width:18px;height:18px;"></td>';
                    dataString += '<td style="text-align:center;"><button type="submit" style="background-color:#03A9F4;display:inline-block;width:auto;padding:10px;margin-top:0;cursor:auto;">' + ngaycapnhat + '</button></td>';
                    dataString += '<td style="text-align:center;"><button type="submit" id="button_' + id + '" onclick="scan_post_user(\'button_' + id + '\')" style="display:inline-block;width:auto;padding:10px;margin-top:0;' + strScanA + '">' + strScanB + '</button></td>';
                    dataString += '</tr>';
                }
                tbody.innerHTML = dataString;
                //tbody.insertAdjacentHTML('beforeend', dataString);
            }
        };
        xhr.open("POST", "ajax/ajax.php", true);
        xhr.send(formData);
    }
</script>

<style>
    table {
        border-radius: 12px;
        overflow: hidden;
        opacity: 0;
        animation: fadeIn 0.4s ease-in forwards;
    }

    .fird1 {
        width: 95%;
        margin: 0 auto;
        border-radius: 12px;
        overflow: hidden;
        /* box-shadow: 0 0 16px 4px #00000029; */
        animation: fadeIn 0.7s ease-in forwards;
    }

    #autocomplete-list {
        background-color: white;
        color: black;
        font-size: 14px;
    }

    .addPost {
        /* display: flex;
        justify-content: center;
        align-items: center; */
        gap: 10px;
        /* margin: 10px 0; */
        width: 95%;
        margin: 0 auto;
        animation: fadeIn 0.7s ease-in forwards;
    }

    .addPost .cot_50 {
        border-radius: 12px;
        box-shadow: 0 0 16px 4px #00000029;
        overflow: hidden;
    }

    #btn-add-post {
        background-color: #3a87ad;
        color: white;
        margin-top: 20px;
    }

    #btn-add-post:hover {
        background-color: #5da5c9;
    }

    @media (max-width:768px) {
        .addPost {
            flex-direction: column;
        }

        .addPost .cot_50 {
            width: 90%;
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


<script>
    document.getElementById('exportExcelButton').onclick = function() {
        Swal.fire({
            title: 'Xuất ra file Excel?',
            text: 'Bạn có chắc chắn muốn tiếp tục?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#198754',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Có',
            cancelButtonText: 'Không'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '?action=Export_Excel';
            }
        });
    }
</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>