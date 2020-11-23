<?php
    require_once './session.php';

    // If there is an error on the page, display the error page!
    if(isset($_GET['error'])) {
        if($_GET['error'] == 20) {
            $_GET['error'] = "You are not authorized to access the admin page!";
        }

        if($_GET['error'] == 10) {
            $_GET['error'] = "You are not authorized to access the user page!";
        }

        include("../frontend/notauthorized.html");
    } else {
        include("../frontend/mainpage.html");
    }
    exit;
?>