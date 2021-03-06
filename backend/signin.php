<?php
    require_once './session.php';
    require_once './authentication.php';

    // Logic to handle signin and signout
    if (isset($_POST['sign-action'])) {
        $_SESSION = array();

        if ($_POST['sign-action'] == 'Sign-Out') {
            session_destroy();
            header("Location: ./mainpage.php");
            exit;
        }
    }

    // Logic to handle an attempt to sign-in
    if (isset($_POST['username']) && isset($_POST['password'])) {
        // Attempt to validate username and password
        $username = fix_input($_POST['username']);
        $password = fix_input($_POST['password']);
        $result = "";
        $result .= validate_username($username);
        $result .= validate_password($password);

        /* If the credentials were validated, we re-direct them to the main page with a session,
           otherwise we re-print the form.
        */
        if($result == "") {
            $result = validate_credentials($username, $password);
            
            if(gettype($result) == "array") {
                $_SESSION['user_type'] = $result[0];
                $_SESSION['username'] = $result[1];
                $_SESSION['first name'] = $result[3];
                $_SESSION['last name'] = $result[4];
                $_SESSION['last login'] = $result[5];
                $_SESSION['date created'] = $result[6];
                $_SESSION['check'] = hash('ripemd128', $_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT']);

                // We update the last login time after setting session variables
                log_successful_signin($username);

                header("Location: ./mainpage.php", true, 301);
                exit;
            }
        }
    }

    // If already logged in, take us back to main page. This is in case people go back to previous page
    if ($status > 0) {
        header("Location: ./mainpage.php");
        exit;
    }

    include("../frontend/signin.html");
    exit;

    function log_successful_signin($username) {
        require_once 'login.php';
        $connection = new mysqli($hn, $un, $pw, $db);
        if ($conn->connection_error) return "Fatal error attempting to connect to database.<br>";

        $q = "UPDATE users SET last_login=now() WHERE username='$username'";
        $result = $connection->query($q);

        if (!$result) return "Updating last log-in failed.";
    }
?>