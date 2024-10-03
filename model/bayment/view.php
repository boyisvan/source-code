<?php
if(!defined("_crmfb")) die ("Truy cập trái phép");
?>
<div class="content">
    <h1><?=$title?></h1>
    <div class="fird">
        <div>
            
        </div>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th style="text-align:center;">1 Tháng</th>
                        <th style="text-align:center;">2 Tháng</th>
                        <th style="text-align:center;">3 Tháng</th>
                        <th style="text-align:center;">6 Tháng</th>
                        <th style="text-align:center;">12 Tháng</th>
                    </tr>
                </thead>
                <tbody>
                    <tr style="background:#fff!important;">
                        <td style="text-align:center;">
                            <p>300.000đ</p>
                            <p>Nội dung chuyển khoản</p>
                            <p class="blinking-text"><?=$taikhoan['taikhoan']?>T1</p>
                        </td>
                        <td style="text-align:center;">
                            <p>600.000đ <span style="color:red;">(Tặng 1 tháng)</span></p>
                            <p>Nội dung chuyển khoản</p>
                            <p class="blinking-text"><?=$taikhoan['taikhoan']?>T2</p>
                        </td>
                        <td style="text-align:center;">
                            <p>800.000đ <span style="color:red;">(Tặng 1 tháng)</span></p>
                            <p>Nội dung chuyển khoản</p>
                            <p class="blinking-text"><?=$taikhoan['taikhoan']?>T3</p>
                        </td>
                        <td style="text-align:center;">
                            <p>1.600.000đ</p>
                            <p>Nội dung chuyển khoản</p>
                            <p class="blinking-text"><?=$taikhoan['taikhoan']?>T6</p>
                        </td>
                        <td style="text-align:center;">
                            <p>3.000.000đ</p>
                            <p>Nội dung chuyển khoản</p>
                            <p class="blinking-text"><?=$taikhoan['taikhoan']?>T12</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>