<?php
    require_once './session.php';
    require_once 'authentication.php';

    if ($status < 2) {
        header("Location: ../backend/mainpage.php?error=20", true, 301);
    }else {
        if (isset($_POST['input'])) {
            insert_user();
        }
        $user_data = get_user_data();
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

            if(!$stmt->execute()) die("Error inserting user.");
            
            echo "Row inserted";
            $stmt->close();
        } else {
            echo $connection->errno. ' ' . $conncetion->error;
        }
        $connection->close();
    }
?>