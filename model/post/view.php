<?php
if(!defined("_crmfb")) die ("Truy cập trái phép");
$sql = "select * from tb_post where id_user=".((int)$_SESSION['id_user']/5)." order by ngaytao desc";
$d->query($sql);
$dem_data = $d->num_rows();
$data = $d->result_array();
?>
<style>
tr:nth-child(even){background-color:unset;}
.tooltip{position:relative;display:inline-block;}
.tooltip .tooltiptext{visibility:hidden;min-width:120px;background-color:#555;color:#fff;text-align:center;border-radius:6px;padding:5px 10px;position:absolute;z-index:1;bottom:100%;left:50%;margin-left:-60px;opacity:0;transition:opacity .3s}
.tooltip .tooltiptext::after{content:"";position:absolute;top:100%;left:50%;margin-left:-5px;border-width:5px;border-style:solid;border-color:#555 transparent transparent}
.tooltip:hover .tooltiptext{visibility:visible;opacity:1}
#autocomplete-list{width:100%;max-height:200px;background:#009688;color:#fff;overflow-y:scroll;border:1px solid #009688;border-top:0;}
#autocomplete-list li{list-style:none;display:block;padding:10px 10px;cursor:pointer;}
#autocomplete-list li:hover{background:#F44336;}
</style>
<div class="content">
    <h1>Thêm bài viết <a href="user?id=<?=$_GET['id']?>">Xem bình luận</a></h1>
    <div class="fird">
        <div class="form-container" style="width:100%;">
            <div class="link-post-container" style="margin-bottom:10px;">
                <label for="link-post">Link bài viết</label>
                <input type="text" id="link-post" name="link-post" maxlength="5000" placeholder="https://www.facebook.com/648250977342978 hoặc 648250977342978" required>
            </div>
            <div class="ten-post-container">
                <label for="ten-post">Tên bài viết</label>
                <input type="text" id="ten-post" name="ten-post" maxlength="500" required>
                <ul id="autocomplete-list"></ul>
            </div>
            <button type="submit" id="btn-add-post">Thêm bài viết</button>
        </div>
    </div>
    <div class="fird">
        <div style="">
            <p style="display:inline-block;border:1px solid #2196F3;padding:8px 10px;color:#2196F3"><span id="dem_baiviet"><?=$dem_data?></span> bài viết</p>
            <input type="search" id="txt_search" oninput="search_post();" placeholder="Tìm kiếm ID bài viết, tiêu đề" style="max-width:550px;width:350px;border:1px solid #2196F3;">
            <button type="submit" id="xoahet_post" onclick="deletePost()" style="background-color:#f00;display:inline-block;width:auto;padding:10px;margin-top:0;display:none;">Xóa hết</button>
        </div>
        <div class="table-container">
            <table id="table_post" style="zoom:0.85;">
                <thead>
                    <tr>
                        <th>Tiêu đề</th>
                        <th>Uid</th>
                        <th style="text-align:center;width:20px;"><input type="checkbox" onclick="selectPostAll()" style="width:18px;height:18px;"></th>
                        <th style="width:60px;">Scan</th>
                        <th style="width:100px;">Comment</th>
                    </tr>
                </thead>
                <tbody>
                    <?php for($i=0;$i<count($data);$i++){?>
                    <tr>
                        <td>
                            <div class="tooltip" style="width:90%;">
                                <textarea id="txtpost_<?=$data[$i]['id']?>" style="width:100%;padding:5px;border:1px solid #817a7a;border-radius:4px;"><?=$data[$i]['ten']?></textarea>
                                <span class="tooltiptext"><?=$data[$i]['uid']?></span>
                            </div>
                            <button type="submit" onclick="edit_post('txtpost_<?=$data[$i]['id']?>')" style="background-color:#FF9800;display:inline-block;width:auto;padding:10px;margin-top:0;vertical-align: top;">Sửa</button>
                        </td>
                        <td style="width:10px;"><a href="https://facebook.com/<?=$data[$i]['uid']?>" target="_blank" style="color:#000;text-decoration:none1;"><?=$data[$i]['uid']?></a></td>
                        <td style="text-align:center;">
                            <input type="checkbox" id="<?=$data[$i]['id']?>" class="checkbox_post" onchange="getCheckedCheckboxIds()" style="width:18px;height:18px;">
                        </td>
                        <td style="text-align:center;">
                            <button type="submit" id="button_<?=$data[$i]['id']?>" onclick="scan('button_<?=$data[$i]['id']?>')" style="display:inline-block;width:auto;padding:10px;margin-top:0;<?php if($data[$i]['scan']==1){?>background-color:#4CAF50;<?php }else{?>background-color:#f44336;<?php }?>"><?php if($data[$i]['scan']==1){?>On<?php }else{?>Off<?php }?></button>
                        </td>
                        <td style="text-align:center;"><button type="submit" style="background-color:#03A9F4;display:inline-block;width:auto;padding:10px;margin-top:0;cursor:auto;">&uarr; <?=$data[$i]['data']?></button></td>
                    </tr>
                    <?php }?>
                </tbody>
            </table>
        </div>
        <div style="clear:both;"></div>
    </div>
</div>
<script>
    var checkedIds = [];
    var isChecked = false;
    function selectPostAll(){
        var checkboxes = document.getElementsByClassName('checkbox_post');
            isChecked = !isChecked;
        for(var i=0;i<checkboxes.length;i++){
            checkboxes[i].checked = isChecked;
        }
        getCheckedCheckboxIds();
    }
    function getCheckedCheckboxIds(){
            checkedIds = [];
        var checkboxes = document.querySelectorAll('.checkbox_post');
        checkboxes.forEach(function(checkbox) {
        if(checkbox.checked) {
            checkedIds.push(checkbox.id);
        }
        });
        var xoahet_post = document.getElementById('xoahet_post');
        if(checkedIds.length>0){
            xoahet_post.style.display  = 'inline-block';
        }else{
            xoahet_post.style.display  = 'none';
        }
        console.log(checkedIds);
    }
    function edit_post(IDtext){
        if(window.confirm('Lưu chỉnh sửa?')){
            var text_post = document.getElementById(IDtext);
                textValue = text_post.value;
            var formData = new FormData();
                formData.append("action",'edit_post');
                formData.append("IDtext",IDtext);
                formData.append("textValue",textValue);
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function(){
                if(xhr.readyState === XMLHttpRequest.DONE){
                    if(xhr.status === 200){
                        var data = xhr.responseText;
                        if(data=='ok'){
                            $.notify("Sửa thành công","success");
                        }
                    }else{
                        $.notify("Có lỗi xảy ra khi gửi yêu cầu đến máy chủ","error");
                    }
                }
            };
            xhr.open("POST", "ajax/ajax.php",true);
            xhr.send(formData);
        }
    }
    function deletePost(){
        if(confirm("Xóa các bài viết đã chọn")){
            document.getElementById("loading-container").style.display = "flex";
            var formData = new FormData();
                formData.append("action",'delete_post');
                formData.append("checkedIds",checkedIds);
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function(){
                if(xhr.readyState === XMLHttpRequest.DONE){
                    if(xhr.status === 200){
                        var data = xhr.responseText;
                        if(data=='ok'){
                            $.notify("Xóa thành công","success");
                            loadPost('searchPost');
                            document.getElementById("loading-container").style.display = "none";
                        }
                    }else{
                        $.notify("Có lỗi xảy ra khi gửi yêu cầu đến máy chủ","error");
                    }
                }
            };
            xhr.open("POST", "ajax/ajax.php",true);
            xhr.send(formData);
        }
    }
    function scan(IDbutton){
        var button = document.getElementById(IDbutton);
        var formData = new FormData();
            formData.append("action",'scan_post');
            formData.append("IDbutton",IDbutton);
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function(){
            if(xhr.readyState === XMLHttpRequest.DONE){
                if(xhr.status === 200){
                    var data = xhr.responseText;
                    //alert(data);
                    if(data=='0'){
                        $.notify("Đã tắt scan","info");
                        button.textContent = 'Off';
                        button.style.backgroundColor  = '#f44336';
                    }
                    if(data=='1'){
                        $.notify("Đã bật scan","success");
                        button.textContent = 'On';
                        button.style.backgroundColor  = '#4CAF50';
                    }
                }else{
                    $.notify("Có lỗi xảy ra khi gửi yêu cầu đến máy chủ","error");
                }
            }
        };
        xhr.open("POST", "ajax/ajax.php",true);
        xhr.send(formData);
    }
    function search_post(){
        var keyword = document.getElementById('txt_search');
            keyword = keyword.value;
        loadPost('searchPost',keyword);
    }
    function loadPost(type,keyword=""){
        var formData = new FormData();
        formData.append("action",'loadPost');
            formData.append("type",type);
            formData.append("keyword",keyword);
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function() {
            if(xhr.status === 200){
                var data = xhr.responseText;
                var table = document.getElementById("table_post");
                var tbody = table.querySelector('tbody');
                    tbody.innerHTML = '';
                var data = data.split('-lionnguyen-').slice(0,-1);
                var dataString = "";
                for(var i=0;i<data.length;i++){
                    var id = data[i].split('-lion-')[0];
                    var link = data[i].split('-lion-')[1];
                    var ten = data[i].split('-lion-')[2];
                    var uid = data[i].split('-lion-')[3];
                    var scan = data[i].split('-lion-')[4];
                    var dem_cmt_ngay = data[i].split('-lion-')[5];
                    if(scan==1){var strScanA = "background-color:#4CAF50;";var strScanB = "On";}
                    if(scan==0){var strScanA = "background-color:#f44336;";var strScanB = "Off";}
                    dataString += '<tr>';
                    dataString += '<td><div class="tooltip" style="width:90%;"><textarea id="txtpost_'+id+'" style="width:100%;padding:5px;border:1px solid #817a7a;border-radius:4px;">'+ten+'</textarea><span class="tooltiptext">'+uid+'</span></div> <button type="submit" onclick="edit_post(\'txtpost_'+id+'\')" style="background-color:#FF9800;display:inline-block;width:auto;padding:10px;margin-top:0;vertical-align: top;">Sửa</button> </td>';
                    dataString += '<td style="width:10px;"><a href="https://facebook.com/'+uid+'" target="_blank" style="color:#000;text-decoration:none1;">'+uid+'</a></td>';
                    dataString += '<td style="text-align:center;"><input type="checkbox" id="'+id+'" class="checkbox_post" onchange="getCheckedCheckboxIds()" style="width:18px;height:18px;"></td>';
                    dataString += '<td style="text-align:center;"><button type="submit" id="button_'+id+'" onclick="scan(\'button_'+id+'\')" style="display:inline-block;width:auto;padding:10px;margin-top:0;'+strScanA+'">'+strScanB+'</button></td>';
                    dataString += '<td style="text-align:center;"><button type="submit" style="background-color:#03A9F4;display:inline-block;width:auto;padding:10px;margin-top:0;cursor:auto;">&uarr; '+dem_cmt_ngay+'</button></td>';
                    dataString += '</tr>';
                }
                tbody.innerHTML = dataString;
                //tbody.insertAdjacentHTML('beforeend', dataString);
            }
        };
        xhr.open("POST", "ajax/ajax.php",true);
        xhr.send(formData);
    }
    document.addEventListener("DOMContentLoaded", function() {
        var input = document.getElementById("ten-post");
        var list = document.getElementById("autocomplete-list");
        input.addEventListener("input", function() {
            var inputValue = input.value.toLowerCase();
            //var data = ["apple", "banana", "cherry", "grape", "orange", "pear"];
            var formData = new FormData();
                formData.append("action",'data_ten_post');
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function(){
                if(xhr.readyState === XMLHttpRequest.DONE){
                    if(xhr.status === 200){
                        var data = xhr.responseText;
                        var dataA = data.split('lionnguyen');
                        var filteredData = dataA.filter(item => item.toLowerCase().includes(inputValue));
                            displayAutocomplete(filteredData);
                    }else{
                        alert("Có lỗi xảy ra khi gửi yêu cầu đến máy chủ.");
                    }
                }
            };
            xhr.open("POST", "ajax/ajax.php",true);
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
    document.getElementById('btn-add-post').addEventListener('click', function(){
        var link = document.getElementById('link-post').value;
        var ten = document.getElementById('ten-post').value;
        if(link===""){
            alert("Nhập đầy đủ");
            return false;
        }
        if(window.confirm('Thêm bài mới')){
            document.getElementById("loading-container").style.display = "flex";
            var formData = new FormData();
                formData.append("link", link);
                formData.append("ten", ten);
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function(){
                if(xhr.readyState === XMLHttpRequest.DONE){
                    document.getElementById("loading-container").style.display = "none";
                    if(xhr.status === 200){
                        var data = xhr.responseText;
                        if(data==='limit'){
                            $.notify("Đạt tối đa số link cho phép","warn");
                        }
                        if(data==='fail'){
                            $.notify("Lỗi, hãy thử lại","error");
                        }
                        if(data==='ok'){
                            $.notify("Thêm thành công","success");
                            loadPost('searchPost');
                        }
                        if(data==='tontailink'){
                            $.notify("Link bài viết này đã tồn tại","info");
                        }
                        if(data==='die'){
                            $.notify("Lỗi, liên hệ Admin để được hỗ trợ","error");
                        }
                    }else{
                        $.notify("Có lỗi xảy ra khi gửi yêu cầu đến máy chủ","error");
                    }
                }
            };
            xhr.open("POST", "ajax/ajax.php",true);
            xhr.send(formData);
        }
    });
</script>