<?php
if (!defined("_crmfb")) die("Truy cập trái phép");
?>
<?php if ($taikhoan['level'] == 1) { ?>
    <?php
    //Danh sách Cookie
    $sql = "SELECT * FROM tb_setting WHERE id_user = 101";
    $sql .= " order by id desc";
    $d->query($sql);
    $khachhang = $d->result_array();
    //via,delay
    if (isset($_REQUEST['action']) && $_REQUEST['action'] == "via_post") {
        $via_post = (int)$_GET['via_post'];
        $delay = (int)$_GET['delay'];
        $d->query("UPDATE tb_user SET via_post = " . $via_post . ",delay=" . $delay . " WHERE taikhoan = '" . $taikhoan['taikhoan'] . "'");
        echo '<script>alert("CẬP NHẬT THÀNH CÔNG");window.location.href = "./setting";</script>';
    }
    //Add Cookie
    if (isset($_REQUEST['setting-cookieFB'])) {
        $cookie_token = addslashes($_REQUEST['setting-cookieFB']);
        $cookie_token = explode("\n", $cookie_token);
        for ($i = 0; $i < count($cookie_token); $i++) {
            $cookie = explode("|", $cookie_token[$i]);
            $name = explode("c_user=", $cookie[0]);
            $name = explode(";", $name[1])[0];
            $token = explode("|", $cookie_token[$i]);
            $proxy = preg_replace('/\s+/', '', explode("|", $cookie_token[$i])[2]);
            $proxy = ($proxy != "") ? substr(addslashes($proxy), 0, 100) : '';

            $sql = "SELECT count(id) as sl FROM tb_setting WHERE id_user = 101 and nameFB = '" . $name . "'";
            $d->query($sql);
            $ck_nameFB = $d->fetch_array();
            if ($ck_nameFB['sl'] == 0 && $name != "") {
                $data = array();
                $data['id_user'] = 101;
                $data['nameFB'] = substr(addslashes($name), 0, 200);
                $data['cookieFB'] = substr(addslashes(preg_replace('/\s+/', '', $cookie[0])), 0, 1000);
                $data['faFB'] = substr(addslashes($token[1]), 0, 500);
                $data['proxy'] = $proxy;
                $data['ngaycapnhat'] = time();
                $data['block'] = 1;
                // $data['tokenFB'] = $token;
                $d->setTable('setting');
                $d->insert($data);
            }
        }
        echo '<script>alert("THÊM THÀNH CÔNG");window.location.href = "setting";</script>';
    }
    //Edit Cookie
    if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit' && isset($_REQUEST['id'])) {
        $sql = "SELECT * FROM tb_setting WHERE id_user = 101 and id = " . $_REQUEST['id'];
        $sql .= " order by id asc limit 1";
        $d->query($sql);
        $cookieEDIT = $d->fetch_array();
        $idFB = $cookieEDIT['id'];
        $nameFB = $cookieEDIT['nameFB'];
        $cookieFB = $cookieEDIT['cookieFB'];
        $tokenFB = $cookieEDIT['tokenFB'];
    }
    //Delete Cookie
    if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'delete' && isset($_REQUEST['id'])) {
        $sql = "delete FROM tb_setting WHERE id_user = 101 and id = " . $_REQUEST['id'];
        $d->query($sql);
        echo '<script>window.location.href = "setting";</script>';
    }
    //Delete All Cookie Die
    if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'del_clone_die') {
        $sql = "delete FROM tb_setting WHERE cookie_die = 1";
        $d->query($sql);
        echo '<script>window.location.href = "setting";</script>';
    }
    //Block Cookie
    if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'block' && isset($_REQUEST['id'])) {
        $sql = "SELECT * FROM tb_setting WHERE id_user = 101 and id = " . $_REQUEST['id'];
        $d->query($sql);
        $cookieBlock = $d->fetch_array();
        if ($cookieBlock['block'] == 1) {
            $d->query("UPDATE tb_setting SET block = 0 WHERE id_user = 101 and id = " . $_REQUEST['id']);
        } else {
            $d->query("UPDATE tb_setting SET block = 1 WHERE id_user = 101 and id = " . $_REQUEST['id']);
        }
        echo '<script>window.location.href = "setting";</script>';
    }
    //
    if (isset($_REQUEST['setting-api-telegram'])) {
        $data = array();
        $data['api_telegram'] = substr(addslashes($_REQUEST['setting-api-telegram']), 0, 100);
        $data['id_telegram'] = substr(addslashes($_REQUEST['setting-id-telegram']), 0, 20);
        $data['cookie_facebook'] = addslashes($_REQUEST['setting-cookie-facebook']);
        $data['token_facebook'] = addslashes($_REQUEST['setting-token-facebook']);
        $d->query("UPDATE tb_user SET api_telegram = '" . $data['api_telegram'] . "',cookie_facebook = '" . $data['cookie_facebook'] . "',token_facebook = '" . $data['token_facebook'] . "',id_telegram = '" . $data['id_telegram'] . "' WHERE taikhoan = '" . $taikhoan['taikhoan'] . "'");
        if (mysqli_affected_rows($d->db)) {
            $notifer_setting = "Cập nhật thành công";
        } else {
            header("location:setting");
        }
    }
    ?>
    <div class="content">
        <div class="fird" style="display:none;width: 100%;">
            <h1>Nhận thông báo</h1>
            <div class="form-container" style="width:100%;">
                <form id="setting-setting" method="post" action="setting">
                    <div class="setting-container" style="margin-bottom:20px;">
                        <label for="setting-api-telegram">API Telegram</label>
                        <input type="text" name="setting-api-telegram" id="setting-api-telegram" value="<?= isset($_REQUEST['setting-api-telegram']) ? $_REQUEST['setting-api-telegram'] : $taikhoan['api_telegram'] ?>">
                    </div>
                    <div class="setting-container" style="margin-bottom:20px;">
                        <label for="setting-id-telegram">ID Telegram</label>
                        <input type="text" name="setting-id-telegram" id="setting-id-telegram" value="<?= isset($_REQUEST['setting-id-telegram']) ? $_REQUEST['setting-id-telegram'] : $taikhoan['id_telegram'] ?>">
                    </div>
                </form>
            </div>
        </div>
        <div class="fird">
            <h1>Thêm Cookie</h1>
            <div class="form-container" style="width:100%;">
                <form id="setting-setting" method="post" action="setting">

                    <!-- <div class="setting-container" style="margin-bottom:20px;">
                        <input type="text" name="setting-nameFB" id="setting-nameFB" value="<?= isset($nameFB) ? $nameFB : "" ?>" placeholder="Tên Facebook">
                    </div> -->

                    <div class="setting-container" style="margin-bottom:20px;">
                        <textarea name="setting-cookieFB" id="setting-cookieFB" cols="30" rows="10" placeholder="Cookie|2Fa|Proxy" required><?= isset($cookieFB) ? $cookieFB : "" ?></textarea>
                    </div>

                    <!-- <div class="setting-container">
                        <textarea name="setting-tokenFB" id="setting-tokenFB" cols="30" rows="2" placeholder="Token Facebook"><?= isset($tokenFB) ? $tokenFB : "" ?></textarea>
                    </div> -->
                    <input type="hidden" name="idFB" value="<?= isset($idFB) ? $idFB : "" ?>">

                    <button type="submit" style="background: rgb(0,120,246);background: linear-gradient(166deg, rgba(0,120,246,1) 39%, rgba(59,255,178,1) 100%);" onclick="if(!confirm('Muốn <?php if (isset($idFB)) { ?>Sửa<?php } else { ?>Thêm<?php } ?> cookie?')) return false;"><?php if (isset($idFB)) { ?>Sửa<?php } else { ?>Thêm<?php } ?> cookie</button>
                </form>
            </div>
        </div>
        <div class="fird">
            <h1>Danh sách Cookie</h1>
            <div class="mua" style="width:194px;float:left;display:none;">
                <input type="text" class="via_post" value="<?= $taikhoan['via_post'] ?>" style="width:60px;border-radius:0;margin-right:-6px;">
                <input type="text" class="time_sleep" value="<?= $taikhoan['delay'] ?>" style="width:60px;border-radius:0;">
                <button type="submit" onclick="via_post(this);" style="width:unset;margin:0;display:unset;">Cập nhật</button>
            </div>
            <button type="submit" onclick="dele_mutil_via();" style="width:unset;margin:0;display:unset;float:left;background:#f00;margin-bottom:5px;padding:10px 10px;">Xóa đã chọn</button>
            <div style="clear:both;"></div>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>STT</th>
                            <th>Name</th>
                            <th>Update</th>
                            <th>Block</th>
                            <th>Type</th>
                            <!--<th>Delete</th>-->
                            <th style="text-align:center;"><input type="checkbox" onclick="selectPostAll()" style="width:18px;height:18px;"></th>
                            <th style="width:100px;">Token</th>
                            <th>Cookie</th>
                            <th style="width:150px;">2Fa</th>
                            <th>Lưu</th>
                            <!-- <th style="width:200px;">Proxy</th> -->
                        </tr>
                    </thead>
                    <tbody>
                        <?php for ($i = 0; $i < count($khachhang); $i++) {
                            $stt = $i + 1;
                        ?>
                            <tr id="tr_<?= $khachhang[$i]['id'] ?>">
                                <td style="text-align:center;width:50px;"><?= $stt ?></td>
                                <td style="width:50px;"><?= $khachhang[$i]['nameFB'] ?></td>
                                <td style="width:150px;">
                                    <p style="width:150px;"><?= date("H:i:s - d/m/y", $khachhang[$i]['ngaycapnhat']) ?></p>
                                </td>

                                <td style="text-align:center;width:50px;">
                                    <button type="submit" id="button_<?= $khachhang[$i]['id'] ?>" onclick="block_via('button_<?= $khachhang[$i]['id'] ?>','<?= $khachhang[$i]['nameFB'] ?>')" style="display:inline-block;width:auto;padding:10px;margin-top:0;<?php if ($khachhang[$i]['block'] == 1) { ?>background-color:#4CAF50;<?php } else { ?>background-color:#f44336;<?php } ?>"><?php if ($khachhang[$i]['block'] == 1) { ?>On<?php } else { ?>Off<?php } ?></button>
                                </td>

                                <td style="width:100px;">
                                    <select id="select_<?= $khachhang[$i]['id'] ?>" onchange="type_via('select_<?= $khachhang[$i]['id'] ?>','<?= $khachhang[$i]['nameFB'] ?>')" style="padding:10px 10px;<?php if ($khachhang[$i]['type'] == 1) { ?>background:#FF9800;color:#fff;<?php } ?><?php if ($khachhang[$i]['type'] == 2) { ?>background:#2196F3;color:#fff;<?php } ?>">
                                        <option value="1" <?php if ($khachhang[$i]['type'] == 1) { ?>selected<?php } ?>>Scan</option>
                                        <option value="2" <?php if ($khachhang[$i]['type'] == 2) { ?>selected<?php } ?>>Real</option>
                                    </select>
                                </td>
                                <!--
                            <td style="text-align:center;width:50px;">
                                <button type="submit" onclick="if(confirm('Xóa Cookie này?')){xoa_via(<?= $khachhang[$i]['id'] ?>,'<?= $khachhang[$i]['nameFB'] ?>')}" style="display:inline-block;width:auto;padding:10px;margin-top:0;background-color:#f44336;">Delete</button>
                            </td>
                            -->
                                <td style="text-align:center;width:50px;">
                                    <input type="checkbox" id="<?= $khachhang[$i]['id'] ?>" class="checkbox_post" onchange="getCheckedCheckboxIds()" style="width:18px;height:18px;">
                                </td>
                                <td><input type="text" id="tokenFB_<?= $khachhang[$i]['id'] ?>" value="<?php if ($khachhang[$i]['tokenFB'] != "") {
                                                                                                            echo substr($khachhang[$i]['tokenFB'], 0, 3) . "******";
                                                                                                        } ?>" style="min-width:50px;width:100%;<?php if ($khachhang[$i]['token_die'] == 0) {
                                                                                                                                                    if ($khachhang[$i]['limit_token'] == 1) { ?>background-color:#198754;color:#fff;<?php } else { ?>background-color:#4CAF50;color:#fff;<?php }
                                                                                                                                                                                                                                                                                    } ?>"></td>
                                <td><input type="text" id="cookieFB_<?= $khachhang[$i]['id'] ?>" value="<?= $khachhang[$i]['cookieFB'] ?>" style="min-width:150px;width:100%;<?php if ($khachhang[$i]['cookie_die'] == 1) { ?>background-color:#198754;color:#fff;<?php } else { ?>background-color:#4CAF50;color:#fff;border-radius:10px;<?php } ?>"></td>
                                <td><input type="text" id="faFB_<?= $khachhang[$i]['id'] ?>" value="<?= $khachhang[$i]['faFB'] ?>" style="min-width:200px;width:250px;"></td>
                                <td style="text-align:center;width:50px;">
                                    <button type="submit" onclick="luu_via(<?= $khachhang[$i]['id'] ?>,'<?= $khachhang[$i]['nameFB'] ?>')" style="display:inline-block;width:70px;padding:10px;margin-top:0;background-color:#2196F3;">Lưu lại</button>
                                </td>

                                <td style="display:none;"><input type="text" id="proxyFB_<?= $khachhang[$i]['id'] ?>" value="<?= $khachhang[$i]['proxy'] ?>" style="min-width:200px;width:200px;"></td>


                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script>
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
        }

        function dele_mutil_via() {
            if (confirm('Muốn xóa via đã chọn?')) {
                if (checkedIds.length > 0) {
                    var formData = new FormData();
                    formData.append("action", 'dele_mutil_via');
                    formData.append("id", checkedIds);
                    var xhr = new XMLHttpRequest();
                    xhr.onreadystatechange = function() {
                        if (xhr.readyState === XMLHttpRequest.DONE) {
                            if (xhr.status === 200) {
                                var data = xhr.responseText;
                                if (data == '0') {
                                    $.notify("Thử lại", "error");
                                }
                                if (data == '1') {
                                    for (var i = 0; i < checkedIds.length; i++) {
                                        $('#tr_' + checkedIds[i]).remove();
                                    }
                                    $.notify("Đã xóa via ", "success");
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
        }

        function via_post(button) {
            var viaPostInput = button.parentElement.querySelector('.via_post');
            var timeSleepInput = button.parentElement.querySelector('.time_sleep');
            var viaPostValue = viaPostInput.value;
            var timeSleepValue = timeSleepInput.value;
            if (viaPostValue == "" || timeSleepValue == "") {
                alert("Hãy nhập số lượng via và thời gian chờ");
            } else {
                if (confirm("CẬP NHẬT CÀI ĐẶT\n- Số via chạy cùng lúc : " + viaPostValue + "\n- Thời gian delay : " + timeSleepValue)) {
                    window.location.href = "?action=via_post&via_post=" + viaPostValue + "&delay=" + timeSleepValue;
                }
            }
        }

        function block_via(IDbutton, name) {
            var button = document.getElementById(IDbutton);
            var formData = new FormData();
            formData.append("action", 'block_via');
            formData.append("IDbutton", IDbutton);
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        var data = xhr.responseText;
                        if (data == '0') {
                            $.notify("Đã tắt via [" + name + "]", "info");
                            button.textContent = 'Off';
                            button.style.backgroundColor = '#f44336';
                        }
                        if (data == '1') {
                            $.notify("Đã bật via [" + name + "]", "success");
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

        function luu_via(id, nameFB) {
            var tokenFB = document.getElementById('tokenFB_' + id).value;
            var cookieFB = document.getElementById('cookieFB_' + id).value;
            var faFB = document.getElementById('faFB_' + id).value;
            var proxyFB = document.getElementById('proxyFB_' + id).value;
            if (cookieFB === "") {
                $.notify("Chưa nhập Cookie [" + nameFB + "]", "info");
            } else {
                var formData = new FormData();
                formData.append("action", 'luu_via');
                formData.append("id", id);
                formData.append("cookieFB", cookieFB);
                formData.append("tokenFB", tokenFB);
                formData.append("faFB", faFB);
                formData.append("proxyFB", proxyFB);
                var xhr = new XMLHttpRequest();
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === XMLHttpRequest.DONE) {
                        if (xhr.status === 200) {
                            var data = xhr.responseText;
                            if (data == '0') {
                                $.notify("Thử lại [" + nameFB + "]", "error");
                            }
                            if (data == '1') {
                                $.notify("Đã lưu via [" + nameFB + "]", "success");
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

        function xoa_via(id, nameFB) {
            var formData = new FormData();
            formData.append("action", 'xoa_via');
            formData.append("id", id);
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        var data = xhr.responseText;
                        if (data == '0') {
                            $.notify("Thử lại [" + nameFB + "]", "error");
                        }
                        if (data == '1') {
                            $('#tr_' + id).remove();
                            $.notify("Đã xóa via [" + nameFB + "]", "success");
                        }
                    } else {
                        $.notify("Có lỗi xảy ra khi gửi yêu cầu đến máy chủ", "error");
                    }
                }
            };
            xhr.open("POST", "ajax/ajax.php", true);
            xhr.send(formData);
        }

        function type_via(IDselect, name) {
            var select = document.getElementById(IDselect).value;
            var formData = new FormData();
            formData.append("action", 'type_via');
            formData.append("IDselect", IDselect);
            formData.append("type", select);
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        var data = xhr.responseText;
                        $.notify("Thay đổi type via [" + name + "]", "success");
                    } else {
                        $.notify("Có lỗi xảy ra khi gửi yêu cầu đến máy chủ", "error");
                    }
                }
            };
            xhr.open("POST", "ajax/ajax.php", true);
            xhr.send(formData);
        }
    </script>
<?php } ?>
<?php if ($taikhoan['level'] == 5) { ?>

<?php } ?>