<?php    
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

    function compare_passwords($pw, $cpw) {
        if (strcmp($pw, $cpw)) {
            return "";
        } else {
            return "Passwords do not match.<br>";
        }
    }

    function validate_credentials($username, $password) {
        // Establish connection to database
        require_once 'login.php';
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
                return "";
            }
            else return "Invalid username/password combination.<br>";
        }
        else return "Invalid username/password combination.<br>";
    }
?>