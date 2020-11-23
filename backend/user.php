<?php
    require_once './session.php';

    if ($status < 1) {
        header("Location: ../backend/mainpage.php?error=10", true, 301);
    }else {
        include("../frontend/user.html");
    }

    exit;
?>