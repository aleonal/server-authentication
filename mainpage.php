<?php
    echo $_POST['username'];
    echo "hi";

    
    <form method="post" action="signin.php">
    <input type="hidden" name="submit">
    <h1>Sign In</h1> <br>
    Username: 
    <input type="text" name="username"> <br>
    Password: 
    <input type="text" name="password"> <br>
    <input type="submit"> <br>
</form>
<p>$result_un<br>$result_pw<br></p>



if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    unset($_POST);
    header("Location: ".$_SERVER['PHP_SELF']);
    exit();
}


// TODO: Sort out database credentials and log-in
// $hn = 'localhost';
// $db = 'assignment';
// $un = 'root';
// $pw = 'password';
// $connection = new mysqli($hn, $un, $pw, $db);
// if ($conn ->connection_error) die("Fatal Error");

$username = $password = $result_un = $result_pw = "";

// Check if username was entered
if (isset($_POST['username'])) {
    $username = fix_input($_POST['username']);
    $result_un = validate_username($username);
}

// Check if password was entered
if (isset($_POST['password'])) {
    $password = fix_input($_POST['password']);
    $result_pw = validate_password($password);
}

// If username and password were entered, and there were no errors, we attempt to validate in database
if (isset($_POST['username']) && isset($_POST['password']) && $result_un == "" && $result_pw == "") {
    // $v = validate_credentials($username, $password);
    // $result_un = $v[0];
    // $result_pw = $v[1];

    // If user validation in database was successful, we log them in and redirect them to main
    if ($result_un == "" && $result_pw == "") {
        // TODO: CREATE COOKIE SESSION?????????
        unset($_POST);
        header("Location: ./mainpage.php");
        exit();
    }
}



unset($_POST['username']);
unset($_POST['password']);

// Input validation functions
function validate_username($field) {
   if ($field == "") return "No username was entered<br>";

   else if (strlen($field) < 6) {
       return "Username must be at least 6 characters<br>";
   }

   else if (preg_match("/[^a-zA-Z0-9_-]/", $field)) {
       return "Username contains invalid characters<br>";
   }
   return "";
}

function validate_password($field) {
    if ($field == "") return "No password was entered<br>";

    else if (strlen($field) < 6) {
        return "Password must be at least 6 characters<br>";
    }

    else if (!preg_match("/[a-z]/", $field) ||
             !preg_match("/[A-Z]/", $field) ||
             !preg_match("/[0-9]/", $field)) {
                 return "Passwords require at least 1 lowercase letter, uppercase letter, and number<br>";
             }
    return "";
}

// TODO: Implement user validation when database is ready
// function validate_credentials($user, $password) {

// }

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