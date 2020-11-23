<?php
    require_once './session.php';

    if ($status < 2) {
        header("Location: ../backend/mainpage.php?error=20", true, 301);
    }else {
        include("../frontend/admin.html");
    }

    exit;
?>