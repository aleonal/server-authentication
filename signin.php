<?php

    if (isset($_POST['username']) && isset($_POST['password'])) {
        //TODO: Do back-end processing
    } else {
        echo file_get_contents("./signin.html");
    }
?>