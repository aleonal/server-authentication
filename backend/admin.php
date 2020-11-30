<?php
    require_once './session.php';

    if ($status < 2) {
        header("Location: ../backend/mainpage.php?error=20", true, 301);
    }else {
        $user_data = get_user_data();
        // include("../frontend/admin.html");
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
        
    }
?>