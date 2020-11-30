<?php
    require_once './session.php';

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
            
            if($result == "") {
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

    // Input validation functions
    function validate_username($field) {
        if ($field == "") return "No username was entered.<br>";
    
        else if (strlen($field) < 5) {
            return "Username must be at least 5 characters.<br>";
        }
    
        else if (preg_match("/[^a-zA-Z0-9_-]/", $field)) {
            return "Username contains invalid characters.<br>";
        }
        return "";
    }
 
    function validate_password($field) {
        if ($field == "") return "No password was entered.<br>";
    
        else if (strlen($field) < 5) {
            return "Password must be at least 5 characters.<br>";
        }
    
        else if (!preg_match("/[a-z]/", $field) ||
                 !preg_match("/[0-9]/", $field)) {
                    return "Passwords require at least 1 lowercase letter and 1 number.<br>";
                }
        return "";
    }
 
    function validate_credentials($username, $password) {
        // Establish connection to database
        include 'login.php';
        $connection = new mysqli($hn, $un, $pw, $db);
        if ($conn->connection_error) return "Fatal error attempting to connect to database.<br>";
        
        // Search for user
        $q = "SELECT * FROM users WHERE username='$username'";
        $r = $connection->query($q);

        // If no results, user was not found
        if (!$r) return "User not found.<br>";
        elseif ($r->num_rows) {

            // We get the result contents to attempt to verify the password
            $row = $r->fetch_array(MYSQLI_NUM);
            $r->close();

            // If password is verified, we start session
            if(password_verify($password.S, $row[2])){
                $_SESSION['user_type'] = $row[0];
                $_SESSION['username'] = $row[1];
                $_SESSION['first name'] = $row[3];
                $_SESSION['last name'] = $row[4];
                $_SESSION['last login'] = $row[5];
                $_SESSION['date created'] = $row[6];
                $_SESSION['check'] = hash('ripemd128', $_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT']);

                // We update the last login time after setting session variables
                log_successful_signin($connection, $username);
                $connection->close();
                return "";
            }
            else return "Invalid username/password combination.<br>";
        }
        else return "Invalid username/password combination.<br>";
    }
    
    // Sanitization functions
    function fix_input($string) {
        if (get_magic_quotes_gpc()) {
            $string = stripslashes($string);
        }
        return htmlentities($string);
    }
    
    function fix_input_mysql($connection, $string) {
        if (get_magic_quotes_gpc()) {
            $string = stripslashes($string);
        }
        return htmlentities($conn->real_escape_string($string));
    }

    function log_successful_signin($connection, $username) {
        $q = "UPDATE users SET last_login=now() WHERE username='$username'";
        $result = $connection->query($q);

        if (!$result) die ("Updating last log-in failed.");
    }
?>