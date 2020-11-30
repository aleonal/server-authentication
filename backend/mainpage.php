<?php
    require_once './session.php';

    // If there is an error on the page, display the error page!
    if(isset($_GET['error'])) {
        switch ($_GET['error']) {
            case 40:
                $_GET['error'] = "Oops! You are not supposed to be here!";
            break;
            case 20:
                $_GET['error'] = "You are not authorized to access the admin page!";
            break;
            case 10:
                $_GET['error'] = "You are not authorized to access the user page!";
            break;
            default:
            $_GET['error'] = "An unknown error has occurred :(";
            
        }

        include("../frontend/notauthorized.html");
    } else {
        include("../frontend/mainpage.html");
    }
    exit;
?>