<?php
    include_once 'conf/config.inc.php';
    unset($_SESSION['user_detail']);
    unset($_SESSION['user_role']);
    unset($_SESSION['publisher']);
    setcookie("preserveKey", "", time() - (86400 * 30), "/");
    header("location:".PROJECT_URL);
?>