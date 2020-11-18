<?php

    echo <<<_END
        <html>
            <head>
                <title>Sign In</title>
                <style>
                    .signup {
                        border:1px solid #FFFFFF;
                        color: #444444;
                        cellpadding: 2px;
                        cellspacing: 5px;
                        bgcolor: #EEEEEE;
                    }
                    table {
                        text-align: center;
                        margin-left: auto;
                        margin-right: auto;
                    }
                </style>
                <script type="text/javascript">
                    function validateUsername(field) {
                        if (field == "") return "No username was entered.\\n";
                        else if (field.length < 6)
                            return "Username must be at least 6 characters.\\n";
                        else if (/[^a-zA-Z0-9_-]/.test(field))
                            return "Username contains invalid characters.\\n";
                        return ""
                    }
                    function validatePassword(field) {
                        if (field == "") return "No passsword was entered.\\n";
                        else if (field.length < 6)
                            return "Password must be at least 6 characters.\\n";
                        else if (!/[a-z]/.test(field) || !/[A-Z]/.test(field) || !/[0-9]/.test(field))
                            return "Passwords require at least 1 lowercase letter, uppercase letter, and number.\\n";
                        return ""
                    }
                    function validate(form) {
                        fail = validateUsername(form.username.value)
                        fail += validatePassword(form.password.value)
                        if (fail == "") return true
                        else {
                            alert(fail);
                            form.password.
                            form.password.value = ""
                            return false
                        }
                    }
                </script>
            </head>
            <body>
                <table class="signup id="form">
                    <th colspan="2">Sign in</th>
                    <form method="post" action="user.php" onsubmit="return validate(this)">
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
?>