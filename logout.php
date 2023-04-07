<?php

@include 'config.php';

session_start();
//session_unset() và session_destroy() là hai hàm được sử dụng trong PHP để xóa toàn bộ các biến lưu trữ trong phiên làm việc (session) của người dùng.
session_unset();
session_destroy();

header('location:login.php');

?>