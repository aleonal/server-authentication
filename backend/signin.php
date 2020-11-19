<?php
    if (isset($_POST['username']) && isset($_POST['password'])) {
        // TODO: Establish the connection to the server
        $connection = "";

        // Attempt to validate username and password
        $username = fix_input($_POST['username']);
        $password = fix_input($_POST['password']);
        $result = "";
        
        $result .= validate_username($username);
        $result .= validate_password($password);

        // If the result does not contain any lexical, we can attempt to validate credentials within database
        if($result == "") {
            $result = validate_credentials($connection, $username, $password);

        }

        /* If the credentials were validated in the database, we re-direct them to the main page with a session,
           otherwise we re-print the form. This could be avoided using DOM, but it's not required for this. Also,
           javascript may seem redundant, but it allows for error validation on the client side which theoretically
           avoids using system resources. 
        */ 
        if ($result == "") {
            // TODO: Create session
            echo file_get_contents("./mainpage.html");
            exit;
        } else {
            echo <<< _END
                <html>
                    <head>
                        <title>Sign In</title>
                        <style>
                            .signup {
                                border: 1px solid #999999;
                                color: #444444;
                            }
                            table {
                                text-align: center;
                                margin-left: auto;
                                margin-right: auto;
                            }
                        </style>
                        <script src="auth.js"></script>
                    </head>
                    <body>
                        <table border="0" cellpadding="2" cellspacing="5" bgcolor="#EEEEEE" class="signup" id="form">
                            <th colspan="2">Sign in</th>
                            <tr>
                                <td colspan="2"><font color=red size=2>
                                    $result
                                </font></td>
                            </tr>
                            <form method="post" action="signin.php">
                                <tr>
                                    <td>Username:</td>
                                    <td><input type="text" maxlength="50" name="username"></td>
                                </tr>
                                <tr>
                                    <td>Password:</td>
                                    <td><input type="password" maxlength="255" name="password"></td>
                                </tr>
                                <tr>
                                    <td colspan="2"><input type="submit" value="Sign In"></td>
                                </tr>
                            </form>
                        </table>
                    </body>
                </html>
            _END;
            exit;
        }

    } else {
        echo file_get_contents("./signin.html");
        exit;
    }

    // Input validation functions
    function validate_username($field) {
        if ($field == "") return "No username was entered.<br>";
    
        else if (strlen($field) < 6) {
            return "Username must be at least 6 characters.<br>";
        }
    
        else if (preg_match("/[^a-zA-Z0-9_-]/", $field)) {
            return "Username contains invalid characters.<br>";
        }
        return "";
    }
 
    function validate_password($field) {
        if ($field == "") return "No password was entered.<br>";
    
        else if (strlen($field) < 6) {
            return "Password must be at least 6 characters.<br>";
        }
    
        else if (!preg_match("/[a-z]/", $field) ||
                !preg_match("/[A-Z]/", $field) ||
                !preg_match("/[0-9]/", $field)) {
                    return "Passwords require at least 1 lowercase letter, uppercase letter, and number.<br>";
                }
        return "";
    }
 
    // TODO: Implement user validation when database is ready
    function validate_credentials($connection, $user, $password) {
        echo "Fix me.";
        return "";
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
?>