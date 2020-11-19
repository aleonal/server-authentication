<?php
    session_start();
    
    // This will hold the status of a session, and is mapped to the user type. default=0
    $status = 0;

    if (isset($_SESSION['user_type'])) {
        $status = $_SESSION['user_type'];
    }
?>