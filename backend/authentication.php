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
?>