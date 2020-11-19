<?php
    // Establish connection to database
    include 'login.php';
    $connection = new mysqli($hn, $un, $pw, $db);
    if ($connection->connection_error){
        exit();
    }

    if($stmt = $connection->prepare("INSERT INTO users VALUES (?,?,?,?,?,DEFAULT,DEFAULT)")) {
        $stmt->bind_param('issss', $type, $user, $pw, $name, $lname);
        
        $pw = password_hash('nimda339'.S, PASSWORD_DEFAULT);

        if (!$pw) {
            echo "ERROR";
            exit();
        }

        $type = 2;
        $user = 'admin';
        $name = 'Paydirt';
        $lname = 'Pete';

        if(!$stmt->execute()) {
            echo "error";
            exit();
        }
        echo "Row inserted";
        $stmt->close();
    } else {
        echo $connection->errno. ' ' . $conncetion->error;
    }
?>