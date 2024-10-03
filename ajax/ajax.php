<?php
date_default_timezone_set('Asia/Ho_Chi_Minh');
error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE);
session_start();
define("_crmfb", "CRMFB");
define("_lib", "../library");
include_once(_lib . "/config.php");
include_once(_lib . "/database.php");
include_once(_lib . "/function.php");
//------------------------------------------------------------------Phần Realtime
//Lấy tên bài post
if (isset($_POST["action"]) && ($_POST["action"] === 'data_ten_post_user')) {
    $id_user = (int)$_SESSION['id_user'] / 5;
    $sql = "SELECT DISTINCT(ten) FROM tb_post WHERE id_user = '" . $id_user . "'";
    $d->query($sql);
    $data = $d->result_array();
    $data_ten_post = [];
    for ($i = 0; $i < count($data); $i++) {
        $data_ten_post[] = $data[$i]['ten'];
    }
    if (count($data_ten_post) > 1) {
        echo implode('lionnguyen', $data_ten_post);
    }
    exit();
}

//Add bài post
if (isset($_POST["action"]) && ($_POST["action"] === 'add_post_user') && isset($_POST["link"]) && isset($_POST["ten"])) {

    $data = array();
    $data['id_user'] = (int)$_SESSION['id_user'] / 5;
    $data['link'] = addslashes($_REQUEST['link']);
    $data['ten'] = addslashes($_REQUEST['ten']);
    $data['ngaytao'] = time();
    $sql = "SELECT limit_post FROM tb_user WHERE id = '" . $data['id_user'] . "'";
    $d->query($sql);
    $limit_postA = $d->fetch_array();
    $limit_postA = 10000; //$limit_postA = $limit_postA['limit_post'];
    $sql = "SELECT count(id) as sl FROM tb_post WHERE id_user = '" . $data['id_user'] . "'";
    $d->query($sql);
    $limit_postB = $d->fetch_array();
    $limit_postB = $limit_postB['sl'];
    if ($limit_postB < $limit_postA) {
        $sql = "SELECT id FROM tb_post WHERE id_user = '" . $data['id_user'] . "' AND link = '" . $data['link'] . "'";
        $d->query($sql);
        $tontai_link = $d->num_rows();
        if ($tontai_link === 0) {
            if (strlen($data['link']) < 25) {
                $data['uid'] = $data['link'];
                $d->setTable('post');
                if ($d->insert($data)) {
                    echo 'ok';
                } else {
                    echo 'fail';
                }
            } else {
                $sql = "select * from tb_setting where id_user = 101 and token_die = 0 order by id asc limit 1";
                $d->query($sql);
                $administrator = $d->fetch_array();
                $Cookies_Fb = $administrator['cookieFB'];
                $accessToken =  $administrator['tokenFB'];
                $uid = "";
                if ($accessToken !== "") {
                    // $arrContextOptions = array(
                    //     'http' => array(
                    //         'method'  => 'GET',
                    //     )
                    // );
                    // $req = file_get_contents("https://api.facebook.com/method/links.preview?url=$url&fomat=json&access_token=$accessToken", false, stream_context_create($arrContextOptions));
                    // $uid = explode("</id>", explode("<id>", $req)[1])[0];
                    $url = $data['link'];
                    if (strpos($url, "permalink.php") !== false) {
                        $url = parse_url($url);
                        parse_str($url['query'], $query_params);
                        $url = "https://www.facebook.com/{$query_params['id']}/posts/{$query_params['story_fbid']}";
                    }
                    $req = file_get_contents("https://fbuid.mktsoftware.net/api/v1/fbprofile?url=$url");
                    $req = json_decode($req, true);
                    $uid = $req['uid'];
                }
                if ($uid !== "" && strlen($uid) > 5) {
                    $data['uid'] = $uid;
                    $d->setTable('post');
                    if ($d->insert($data)) {
                        echo 'ok';
                    } else {
                        echo 'fail';
                    }
                } else {
                    echo 'die';
                }
            }
        } else {
            echo 'tontailink';
        }
    } else {
        echo 'limit';
    }
    exit();
}
//Thêm/bỏ bài quét post
if (isset($_POST["action"]) && ($_POST["action"] === 'scan_user')) {
    $id_user = (int)$_SESSION['id_user'] / 5;
    $id = (int)(explode("_", $_POST["IDbutton"])[1]);
    $sql = "SELECT * FROM tb_post WHERE id_user = '" . $id_user . "' and id = '" . $id . "'";
    $d->query($sql);
    $result_post = $d->fetch_array();
    if ($result_post['scan_user'] == 1) {
        $d->query("UPDATE tb_post SET scan = 0, scan_user = 0 WHERE id_user = '" . $id_user . "' and id = '" . $id . "'");
        echo '0';
    } else {
        $sql = "SELECT limit_post FROM tb_user WHERE id = '" . $id_user . "'";
        $d->query($sql);
        $limit_postA = $d->fetch_array();
        $limit_postA = $limit_postA['limit_post'];
        $sql = "SELECT count(id) as sl FROM tb_post WHERE del = 0 and scan_user = 1 and id_user = '" . $id_user . "'";
        $d->query($sql);
        $limit_postB = $d->fetch_array();
        $limit_postB = $limit_postB['sl'];
        if ($limit_postB < $limit_postA) {
            $d->query("UPDATE tb_post SET scan = 1, scan_user = 1 WHERE id_user = '" . $id_user . "' and id = '" . $id . "'");
            echo '1';
        } else {
            echo 'limit';
        }
    }
    exit();
}
//Xoá bài post
if (isset($_POST["action"]) && ($_POST["action"] === 'delete_post_user')) {
    $id_user = (int)$_SESSION['id_user'] / 5;
    $checkedIds = $_POST["checkedIds"];
    //$d->query("delete from tb_post WHERE id_user = '".$id_user."' and id in (".$checkedIds.")");
    $d->query("update tb_post set del = " . time() . ",scan=0,scan_user=0 WHERE id_user = '" . $id_user . "' and id in (" . $checkedIds . ")");
    echo 'ok';
    exit();
}
//Khôi phục bài post
if (isset($_POST["action"]) && ($_POST["action"] === 'restore_post')) {
    $id_user = (int)$_SESSION['id_user'] / 5;
    $timedate = $_POST["timedate"];
    $d->query("update tb_post set del = 0 WHERE id_user = '" . $id_user . "' and DATE(FROM_UNIXTIME(del)) = '" . $timedate . "'");
    echo 'ok';
    exit();
}
//Tải bài post
if (isset($_POST["action"]) && ($_POST["action"] === 'loadPost_user')) {
    $id_user = (int)$_SESSION['id_user'] / 5;
    $sql = "SELECT * FROM tb_post WHERE del = 0 and id_user = '" . $id_user . "'";
    if ($_POST["type"] === 'searchPost_user') {
        $sql .= " and (ten like '%" . $_POST["keyword"] . "%' or uid like '%" . $_POST["keyword"] . "%')";
    }
    $sql .= " order by scan_user desc,ngaycapnhat desc";
    $d->query($sql);
    $data = $d->result_array();
    $data_ten_post = "";
    for ($i = 0; $i < count($data); $i++) {
        $time_elapsed = time() - $data[$i]['ngaycapnhat'];
        $hours = ceil($time_elapsed / 3600);
        $minutes = ceil($time_elapsed / 60);
        $time_elapsed = ($time_elapsed > 3600) ? "{$hours} giờ" : "{$minutes} phút";
        $data_ten_post .= $data[$i]['id'] . "-lion-" . $data[$i]['link'] . "-lion-" . $data[$i]['ten'] . "-lion-" . $data[$i]['uid'] . "-lion-" . $data[$i]['scan_user'] . "-lion-" . $time_elapsed . "-lionnguyen-";
    }
    if ($data_ten_post !== "" && strlen($data_ten_post) > 10) {
        echo $data_ten_post;
    }
    exit();
}
//------------------------------------------------------------------Phần Ads
//Add bài post
if (isset($_POST["link"]) && isset($_POST["ten"])) {
    $data = array();
    $data['id_user'] = (int)$_SESSION['id_user'] / 5;
    $data['link'] = addslashes($_REQUEST['link']);
    $data['ten'] = addslashes($_REQUEST['ten']);
    $data['scan'] = 1;
    $data['ngaytao'] = time();
    $sql = "SELECT limit_post FROM tb_user WHERE id = '" . $data['id_user'] . "'";
    $d->query($sql);
    $limit_postA = $d->fetch_array();
    $limit_postA = $limit_postA['limit_post'];
    $sql = "SELECT count(id) as sl FROM tb_post WHERE id_user = '" . $data['id_user'] . "'";
    $d->query($sql);
    $limit_postB = $d->fetch_array();
    $limit_postB = $limit_postB['sl'];
    if ($limit_postB < $limit_postA) {
        $sql = "SELECT id FROM tb_post WHERE id_user = '" . $data['id_user'] . "' AND link = '" . $data['link'] . "'";
        $d->query($sql);
        $tontai_link = $d->num_rows();
        if ($tontai_link === 0) {
            if (strlen($data['link']) < 25) {
                $data['uid'] = $data['link'];
                $d->setTable('post');
                if ($d->insert($data)) {
                    echo 'ok';
                } else {
                    echo 'fail';
                }
            } else {
                $sql = "select * from tb_setting where id_user = 101 and token_die = 0 order by id asc limit 1";
                $d->query($sql);
                $administrator = $d->fetch_array();
                $Cookies_Fb = $administrator['cookieFB'];
                $accessToken =  $administrator['tokenFB'];
                $uid = "";
                if ($accessToken !== "") {
                    //$arrContextOptions=array(
                    //'http' => array(
                    //'method'  => 'GET',
                    //)
                    //);
                    //$req = file_get_contents("https://api.facebook.com/method/links.preview?url=$url&fomat=json&access_token=$accessToken",false,stream_context_create($arrContextOptions));
                    //$uid = explode("</id>",explode("<id>",$req)[1])[0];
                    $url = $data['link'];
                    if (strpos($url, "permalink.php") !== false) {
                        $url = parse_url($url);
                        parse_str($url['query'], $query_params);
                        $url = "https://www.facebook.com/{$query_params['id']}/posts/{$query_params['story_fbid']}";
                    }
                    $req = file_get_contents("https://fbuid.mktsoftware.net/api/v1/fbprofile?url=$url");
                    $req = json_decode($req, true);
                    $uid = $req['uid'];
                }
                if ($uid !== "" && strlen($uid) > 5) {
                    $data['uid'] = $uid;
                    $d->setTable('post');
                    if ($d->insert($data)) {
                        echo 'ok';
                    } else {
                        echo 'fail';
                    }
                } else {
                    echo 'die';
                }
            }
        } else {
            echo 'tontailink';
        }
    } else {
        echo 'limit';
    }
    exit();
}
//Get tên bài post
if (isset($_POST["action"]) && ($_POST["action"] === 'data_ten_post')) {
    $id_user = (int)$_SESSION['id_user'] / 5;
    $sql = "SELECT DISTINCT(ten) FROM tb_post WHERE id_user = '" . $id_user . "'";
    $d->query($sql);
    $data = $d->result_array();
    $data_ten_post = [];
    for ($i = 0; $i < count($data); $i++) {
        $data_ten_post[] = $data[$i]['ten'];
    }
    if (count($data_ten_post) > 1) {
        echo implode('lionnguyen', $data_ten_post);
    }
    exit();
}
//Hide bài post
if (isset($_POST["action"]) && ($_POST["action"] === 'scan_post')) {
    $id_user = (int)$_SESSION['id_user'] / 5;
    $id = (int)(explode("_", $_POST["IDbutton"])[1]);
    $sql = "SELECT * FROM tb_post WHERE id_user = '" . $id_user . "' and id = '" . $id . "'";
    $d->query($sql);
    $result_post = $d->fetch_array();
    if ($result_post['scan'] == 1) {
        $d->query("UPDATE tb_post SET scan = 0 WHERE id_user = '" . $id_user . "' and id = '" . $id . "'");
        echo '0';
    } else {
        $d->query("UPDATE tb_post SET scan = 1 WHERE id_user = '" . $id_user . "' and id = '" . $id . "'");
        echo '1';
    }
    exit();
}
//Edit bài post
if (isset($_POST["action"]) && ($_POST["action"] === 'edit_post')) {
    $id_user = (int)$_SESSION['id_user'] / 5;
    $id = (int)(explode("_", $_POST["IDtext"])[1]);
    $textValue = substr(addslashes($_POST["textValue"]), 0, 500);
    $d->query("UPDATE tb_post SET ten = '" . $textValue . "' WHERE id_user = '" . $id_user . "' and id = '" . $id . "'");
    echo 'ok';
    exit();
}
//Delete bài post
if (isset($_POST["action"]) && ($_POST["action"] === 'delete_post')) {
    $id_user = (int)$_SESSION['id_user'] / 5;
    $checkedIds = $_POST["checkedIds"];
    $d->query("delete from tb_post WHERE id_user = '" . $id_user . "' and id in (" . $checkedIds . ")");
    echo 'ok';
    exit();
}
//Load bài post
if (isset($_POST["action"]) && ($_POST["action"] === 'loadPost')) {
    $id_user = (int)$_SESSION['id_user'] / 5;
    $sql = "SELECT * FROM tb_post WHERE id_user = '" . $id_user . "'";
    if ($_POST["type"] === 'searchPost') {
        $sql .= " and (ten like '%" . $_POST["keyword"] . "%' or uid like '%" . $_POST["keyword"] . "%')";
    }
    $sql .= " order by id desc";
    $d->query($sql);
    $data = $d->result_array();
    $data_ten_post = "";
    for ($i = 0; $i < count($data); $i++) {
        $data_ten_post .= $data[$i]['id'] . "-lion-" . $data[$i]['link'] . "-lion-" . $data[$i]['ten'] . "-lion-" . $data[$i]['uid'] . "-lion-" . $data[$i]['scan'] . "-lion-" . $data[$i]['data'] . "-lionnguyen-";
    }
    if ($data_ten_post !== "" && strlen($data_ten_post) > 10) {
        echo $data_ten_post;
    }
    exit();
}
//Load Comment
if (isset($_POST["action"]) && ($_POST["action"] === 'loadComment')) {
    $id_user = (int)$_SESSION['id_user'] / 5;
    $offset = (int)$_POST['offset'];
    $limit = 50;
    $sql = "SELECT * FROM tb_data WHERE id_user = '" . $id_user . "'";
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
    if (isset($_SESSION['keyword'])) {
        $sql .= " and (name_sp like '%" . $_SESSION['keyword'] . "%' or uid_post like '%" . $_SESSION['keyword'] . "%')";
    }
    $sql .= " GROUP BY comment_uid order by thoigian desc  LIMIT $offset, $limit";
    $d->query($sql);
    $data = $d->result_array();
    $data_ten_post = "";
    for ($i = 0; $i < count($data); $i++) {
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
        if ($_SESSION['block'] == 1) {
            $uid_fb = substr($uid_fb, 0, -3) . "xxx";
            $phone = ($phone !== "") ? (substr($phone, 0, 7) . "xxx") : "";
            $phoneCMT = ($phoneCMT !== "") ? (substr($phoneCMT, 0, 7) . "xxx") : "";
        }
        $thoigian = date("d/m/Y - H:i:s", $data[$i]['thoigian']);
        $data_ten_post .= $data[$i]['id'] . "-lion-" . $thoigian . "-lion-" . $data[$i]['uid_post'] . "-lion-" . $data[$i]['name_sp'] . "-lion-" . $uid_fb . "-lion-" . $data[$i]['name'] . "-lion-" . $data[$i]['gender'] . "-lion-" . $phone . "-lion-" . $phoneCMT . "-lion-" . $data[$i]['message'] . "-lionnguyen-";
    }
    if ($data_ten_post !== "" && strlen($data_ten_post) > 10) {
        echo $data_ten_post;
    }
    exit();
}
//Load Comment mới về
if (isset($_POST["action"]) && ($_POST["action"] === 'loadCommentNew')) {
    $id_user = (int)$_SESSION['id_user'] / 5;
    $cmt_new_fisrt = $_POST['cmt_new_fisrt'];
    $sql = "SELECT * FROM tb_data WHERE id_user = '" . $id_user . "'";
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
    $sql .= " and thoigian > $cmt_new_fisrt order by thoigian asc";
    $d->query($sql);
    $data = $d->result_array();
    $data_ten_post = "";
    for ($i = 0; $i < count($data); $i++) {
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
        if ($_SESSION['block'] == 1) {
            $uid_fb = substr($uid_fb, 0, -3) . "xxx";
            $phone = ($phone !== "") ? (substr($phone, 0, 7) . "xxx") : "";
            $phoneCMT = ($phoneCMT !== "") ? (substr($phoneCMT, 0, 7) . "xxx") : "";
        }
        $thoigian = date("d/m/Y - H:i:s", $data[$i]['thoigian']);
        $data_ten_post .= $data[$i]['id'] . "-lion-" . $thoigian . "-lion-" . $data[$i]['uid_post'] . "-lion-" . $data[$i]['name_sp'] . "-lion-" . $uid_fb . "-lion-" . $data[$i]['name'] . "-lion-" . $data[$i]['gender'] . "-lion-" . $phone . "-lion-" . $phoneCMT . "-lion-" . $data[$i]['message'] . "-lionnguyen-";
    }
    if ($data_ten_post !== "" && strlen($data_ten_post) > 10) {
        echo $data_ten_post;
    }
    exit();
}
//Kiểm tra bài viết bị lỗi
if (isset($_POST['action']) && $_POST['action'] == "check_id_post" && isset($_POST['list_id_post']) && isset($_POST['id'])) {
    $sql = "select * from tb_setting where id_user = 101 and token_die = 0 and status = 1 order by id asc limit 1";
    $d->query($sql);
    $administrator = $d->fetch_array();
    $cookieFB = $administrator['cookieFB'];
    $tokenFB =  $administrator['tokenFB'];
    $id_so_page = (isset($_POST['id_so_page']) && (int)$_POST['id_so_page'] >= 1) ? $_POST['id_so_page'] : 1;
    $list_id_post = explode(",", $_POST['list_id_post']);
    $str = "";
    $limit = 200;
    $dem123 = 0;
    foreach ($list_id_post as $value) {
        $dem123++;
        $id_post = explode("|", $value)[0];
        if ($dem123 > ($id_so_page * $limit - $limit) && $dem123 <= ($id_so_page * $limit)) {
            $arrContextOptions = array(
                'http' => array(
                    'method'  => 'GET',
                    'header' =>  'cookie:' . $cookieFB,
                )
            );
            $url = file_get_contents("https://graph.facebook.com/$id_post/comments?limit=2&fields=id&access_token=$tokenFB", false, stream_context_create($arrContextOptions));
            if ($url) {
                $str .= $id_post . "-Ok|";
            } else {
                $str .= $id_post . "-Fail|";
            }
        } else {
            $str .= $id_post . "-|";
        }
    }
    echo $str;
    exit();
}
//Setting----------------------------------
//Bật/Tắt Via
if (isset($_POST["action"]) && ($_POST["action"] === 'block_via')) {
    $id_user = 101;
    $id = (int)(explode("_", $_POST["IDbutton"])[1]);
    $sql = "SELECT * FROM tb_setting WHERE id_user = '" . $id_user . "' and id = '" . $id . "'";
    $d->query($sql);
    $result_post = $d->fetch_array();
    if ($result_post['block'] == 1) {
        $d->query("UPDATE tb_setting SET block = 0 WHERE id_user = '" . $id_user . "' and id = '" . $id . "'");
        echo '0';
    } else {
        $d->query("UPDATE tb_setting SET block = 1 WHERE id_user = '" . $id_user . "' and id = '" . $id . "'");
        echo '1';
    }
    exit();
}
//Edit Via
if (isset($_POST["action"]) && ($_POST["action"] === 'luu_via')) {
    $id_user = 101;
    $id = (int)$_POST["id"];
    $cookieFB = $_POST["cookieFB"];
    $tokenFB = $_POST["tokenFB"];
    $faFB = $_POST["faFB"];
    $proxy = $_POST["proxyFB"];
    if ($d->query("UPDATE tb_setting SET cookieFB = '" . $cookieFB . "',tokenFB = '" . $tokenFB . "',faFB = '" . $faFB . "',proxy = '" . $proxy . "' WHERE id_user = '" . $id_user . "' and id = '" . $id . "'")) {
        echo 1;
    } else {
        echo 0;
    }
    exit();
}
//Xóa Via
if (isset($_POST["action"]) && ($_POST["action"] === 'xoa_via')) {
    $id_user = 101;
    $id = (int)$_POST["id"];
    if ($d->query("DELETE FROM tb_setting WHERE id_user = 101 and id = " . $id)) {
        echo 1;
    } else {
        echo 0;
    }
    exit();
}
//Xóa nhiều Via
if (isset($_POST["action"]) && ($_POST["action"] === 'dele_mutil_via')) {
    $id_user = 101;
    $ids = $_POST["id"];
    $d->query("DELETE FROM tb_setting WHERE id_user = '" . $id_user . "' and id IN (" . $ids . ")");
    echo 1;
    exit();
}
//Type Via
if (isset($_POST["action"]) && ($_POST["action"] === 'type_via')) {
    $id_user = 101;
    $id = (int)(explode("_", $_POST["IDselect"])[1]);
    $type = (int)$_POST["type"];
    $d->query("UPDATE tb_setting SET type = " . $type . " WHERE id_user = '" . $id_user . "' and id = '" . $id . "'");
    echo $type;
    exit();
}

echo "Truy cập không hợp lệ";
