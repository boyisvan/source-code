<?php
if(!defined('_crmfb')) die ("Truy cập trái phép");
function generateRandomString($length) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    $charactersLength = strlen($characters);
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}
function url() {
    $pageURL = 'http';

    if (isset($_SERVER["HTTPS"])) {
        if ($_SERVER["HTTPS"] == "on") {
            $pageURL .= 's';
        }
    }

    $pageURL .= '://';

    if ($_SERVER["SERVER_PORT"] != "80") {
        $pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
    } else {
        $pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
    }

    $pageURL = explode("&p=", $pageURL);
    return $pageURL[0];
}
function phantrang($per_page, $page, $total) {
    if (strpos(url(), '?')) {
        $page_url = url() . "&";
        if (strpos(url(), 'page')) {
            $page_url = substr(url(), 0, -(strlen(url()) - strpos(url(), 'page')));
        }
    } else {
        $page_url = url() . "?";
    }

    $adjacents = 2;
    $page = ($page == 0 ? 1 : $page);
    $start = ($page - 1) * $per_page;
    $prev = $page - 1;
    $next = $page + 1;
    $setLastpage = ceil($total / $per_page);
    $lpm1 = $setLastpage - 1;
    $setPaginate = "";

    if ($setLastpage > 1) {
        $setPaginate .= "<ul class='setPaginate'>";
        $setPaginate .= "<li class='setPage'>$page / $setLastpage</li>";

        if ($setLastpage < 7 + ($adjacents * 2)) {
            for ($counter = 1; $counter <= $setLastpage; $counter++) {
                if ($counter == $page) {
                    $setPaginate .= "<li><a class='current_page'>$counter</a></li>";
                } else {
                    $setPaginate .= "<li><a href='{$page_url}page=$counter'>$counter</a></li>";
                }
            }
        } elseif ($setLastpage > 5 + ($adjacents * 2)) {
            if ($page < 1 + ($adjacents * 2)) {
                for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++) {
                    if ($counter == $page) {
                        $setPaginate .= "<li><a class='current_page'>$counter</a></li>";
                    } else {
                        $setPaginate .= "<li><a href='{$page_url}page=$counter'>$counter</a></li>";
                    }
                }
                $setPaginate .= "<li class='dot'>...</li>";
                $setPaginate .= "<li><a href='{$page_url}page=$lpm1'>$lpm1</a></li>";
                $setPaginate .= "<li><a href='{$page_url}page=$setLastpage'>$setLastpage</a></li>";
            } elseif ($setLastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2)) {
                $setPaginate .= "<li><a href='{$page_url}page=1'>1</a></li>";
                $setPaginate .= "<li><a href='{$page_url}page=2'>2</a></li>";
                $setPaginate .= "<li class='dot'>...</li>";

                for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++) {
                    if ($counter == $page) {
                        $setPaginate .= "<li><a class='current_page'>$counter</a></li>";
                    } else {
                        $setPaginate .= "<li><a href='{$page_url}page=$counter'>$counter</a></li>";
                    }
                }
                $setPaginate .= "<li class='dot'>..</li>";
                $setPaginate .= "<li><a href='{$page_url}page=$lpm1'>$lpm1</a></li>";
                $setPaginate .= "<li><a href='{$page_url}page=$setLastpage'>$setLastpage</a></li>";
            } else {
                $setPaginate .= "<li><a href='{$page_url}page=1'>1</a></li>";
                $setPaginate .= "<li><a href='{$page_url}page=2'>2</a></li>";
                $setPaginate .= "<li class='dot'>..</li>";

                for ($counter = $setLastpage - (2 + ($adjacents * 2)); $counter <= $setLastpage; $counter++) {
                    if ($counter == $page) {
                        $setPaginate .= "<li><a class='current_page'>$counter</a></li>";
                    } else {
                        $setPaginate .= "<li><a href='{$page_url}page=$counter'>$counter</a></li>";
                    }
                }
            }
        }
        if ($page < $counter - 1) {
            $setPaginate .= "<li><a href='{$page_url}page=$next'>&raquo;</a></li>";
            $setPaginate .= "<li><a href='{$page_url}page=$setLastpage'>Cuối</a></li>";
        } else {
            $setPaginate .= "<li><a class='current_page'>&raquo;</a></li>";
            $setPaginate .= "<li><a class='current_page'>Cuối</a></li>";
        }
        $setPaginate .= "</ul>\n";
    }
    return $setPaginate;
}
?>