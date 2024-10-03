<?php
if (!defined("_crmfb")) die("Truy cập trái phép");
$sql = "select * from tb_post where scan_user = 1 and id_user=" . ((int)$_SESSION['id_user'] / 5) . " order by id desc";
$d->query($sql);
$dem_data = $d->num_rows();
$data = $d->result_array();
?>
<div class="content">
    <div class="fird">
        <div style="text-align: center;margin-top: -10px;">
            <p style="">Tổng số <span id="dem_baiviet" style="color: #2196F3;font-weight: bold;"><?= $dem_data ?></span> bài viết</p>
        </div>
        <div class="table-container">
            <table id="table_post" style="zoom:0.85;">
                <thead>
                    <tr>
                        <th>Bài viết</th>
                        <th>Uid</th>
                        <th style="width:60px;">Scan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php for ($i = 0; $i < count($data); $i++) { ?>
                        <tr>
                            <td style=""><?= $data[$i]['ten'] ?></td>
                            <td style="width:10px;"><a href="https://facebook.com/<?= $data[$i]['uid'] ?>" target="_blank" style="color:#000;text-decoration:none1;display:block;padding:10px 5px;"><?= $data[$i]['uid'] ?></a></td>
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
</script>
<style>
    table {
        border-radius: 12px;
        overflow: hidden;
    }

    .table-container {
        width: 50%;
        margin: 0 auto;
        margin-top: -10px;
    }

    @media (max-width:768px) {
        .table-container {
            width: 97%;
        }
    }
</style>