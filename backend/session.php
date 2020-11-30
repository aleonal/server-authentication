<?php
    session_start();
    
    // This will hold the status of a session, and is mapped to the user type. default=0
    $status = 0;

    // This detects a sesion
    if (isset($_SESSION['user_type'])) {

        // This prevents session hijacking, although only if the hijacker is in a different network than the user
        if ($_SESSION['check'] != hash('ripemd128', $_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT'])) {
            header("Location: ../backend/mainpage.php?error=40", true, 301);
            exit;
        }
        
        $status = $_SESSION['user_type'];
    }
?>