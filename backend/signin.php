<?php
    require_once './session.php';

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
            $result = validate_credentials($connection, $username, $password);
            
            if($result == "") {
                header("Location: ./mainpage.php");
                exit;
            }
        }

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
                    <script src="../frontend/auth.js"></script>
                </head>
                <body>
                    <table border="0" cellpadding="2" cellspacing="5" bgcolor="#EEEEEE" class="signup" id="form">
                        <th colspan="2">Sign in</th>
                        <tr>
                            <td colspan="2"><font color=red size=2>
                                $result
                            </font></td>
                        </tr>
                        <form method="post" action="signin.php" onsubmit="return validate(this)">
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

    } else {
        // if user is not logged in and tries to access account page or admin page,
        // then they are redirected to log in otherwise back to main page
        echo file_get_contents("../frontend/signin.html");
        exit;
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
 
    function validate_credentials($connection, $username, $password) {
        // Establish connection to database
        include 'login.php';
        $connection = new mysqli($hn, $un, $pw, $db);
        if ($conn->connection_error) return "Fatal error attempting to connect to database.<br>";
        
        // Search for user
        $q = "SELECT * FROM users WHERE username='$username'";
        $r = $connection->query($q);
        $connection->close();

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
                $_SESSION['first_name'] = $row[3];
                $_SESSION['last_name'] = $row[4];
                $_SESSION['last_login'] = $row[5];
                $_SESSION['date_created'] = $row[6];
                return "";
            }
            else return "Invalid username/password combination.<br>";
        }
        else return "'-$username-'<br>Invalid username/password combination.<br>";
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