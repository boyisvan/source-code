<?php
if(!defined("_crmfb")) die ("Truy cập trái phép");
session_destroy();
header("location:./");
?>