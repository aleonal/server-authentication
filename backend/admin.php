<?php
    require_once './session.php';
    require_once 'authentication.php';

    if ($status < 2) {
        header("Location: ../backend/mainpage.php?error=20", true, 301);
    }else {
        if (isset($_POST['confirm_password'])) {
            // Attempt to validate username and passwords
            $username = fix_input($_POST['username']);
            $password = fix_input($_POST['password']);
            $c_password = fix_input($_POST['confirm_password']);
            $result = "";
            $result .= validate_username($username);
            $result .= validate_password($password);
            $result .= validate_password($c_password);

            if ($result == "") {
                // Make sure passwords match
                $result = compare_passwords($password, $c_password);

                // To avoid processing this if passwords don't match, we nest another if-statement
                if ($result == "") {
                    // Make sure username is unique
                    $result = check_user_uniqueness($username);
                    
                    // If the username is unique, we can go ahead and insert them into the database
                    if ($result == "") {
                        $result = insert_user();
                        
                        if ($result == "") {
                            $user_data = get_user_data();
                        }
                    }
                }
            }
        }
        include("../frontend/admin.html");
    }

    exit;

    function get_user_data() {
        include 'login.php';
        $users = array();
        
        $connection = new mysqli($hn, $un, $pw, $db);
        if ($conn->connection_error) return "Fatal error attempting to connect to database.<br>";

        $q = "SELECT * FROM users";
        $result = $connection->query($q);
        if (!$result) die("Error fetching users.");

        $connection->close();
        $rows = $result->num_rows;

        for ($i = 0; $i < $rows; ++$i) {
            $user = $result->fetch_array(MYSQLI_ASSOC);
            unset($user['password']);
            array_push($users, $user);
        }
        $result->close();
        return $users;
    }

    function insert_user() {
        include 'login.php';

        $connection = new mysqli($hn, $un, $pw, $db);
        if ($conn->connection_error) return "Fatal error attempting to connect to database.<br>";

        if($stmt = $connection->prepare("INSERT INTO users VALUES (?,?,?,?,?,DEFAULT,DEFAULT)")) {
            $stmt->bind_param('issss', $type, $user, $pw, $name, $lname);
            
            $pw = password_hash($_POST['password'].S, PASSWORD_DEFAULT);
            $type = $_POST['type'];
            $user = $_POST['username'];
            $name = $_POST['first_name'];
            $lname = $_POST['last_name'];

            if(!$stmt->execute()) return "Error inserting user.<br>";
            
            $stmt->close();
            $connection->close();
            return "";
        } else {
            return "Error inserting user.<br>";
        }
    }
?>