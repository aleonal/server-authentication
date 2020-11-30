<?php    
    // Sanitization functions
    function fix_input($string) {
        if (get_magic_quotes_gpc()) {
            $string = stripslashes($string);
        }
        return htmlentities($string);
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
        if (strcmp($pw, $cpw) == 0) {
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
        $connection->close();

        // If no results, user was not found
        if (!$r) return "Error accessing database.<br>";
        elseif ($r->num_rows) {

            // We get the result contents to attempt to verify the password
            $row = $r->fetch_array(MYSQLI_NUM);
            $r->close();

            // If password is verified, we start session
            if(password_verify($password.S, $row[2])){
                $arr = array();
                array_push($arr, $row[0]);
                array_push($arr, $row[1]);
                array_push($arr, $row[2]);
                array_push($arr, $row[3]);
                array_push($arr, $row[4]);
                array_push($arr, $row[5]);
                array_push($arr, $row[6]);

                return $arr;
            }
            else return "Invalid username/password combination.<br>";
        }
        else return "Username not found.<br>";
    }

    function check_user_uniqueness($username) {
        require_once 'login.php';
        $connection = new mysqli($hn, $un, $pw, $db);
        if ($conn->connection_error) return "Fatal error attempting to connect to database.<br>";
        
        // Search for user
        $q = "SELECT * FROM users WHERE username='$username'";
        $r = $connection->query($q);
        $connection->close();

        // If no results, then this username is unique
        if (!$r) return "Error accessing database.<br>";
        elseif ($r->num_rows) {
            return "Username is not unique, please pick another.<br>";
        }
        else return "";
    }
?>