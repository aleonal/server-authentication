function validateUsername(field) {
    if (field == "") return "No username was entered.\n";
    else if (field.length < 5)
        return "Username must be at least 5 characters.\n";
    else if (/[^a-zA-Z0-9_-]/.test(field))
        return "Username contains invalid characters.\n";
    return ""
}
function validatePassword(field) {
    if (field == "") return "No passsword was entered.\n";
    else if (field.length < 5)
        return "Password must be at least 5 characters.\n";
    else if (!/[a-z]/.test(field) || !/[0-9]/.test(field))
        return "Passwords require at least 1 lowercase letter and number.\n";
    return ""
}
function validate(form) {
    fail = validateUsername(form.username.value)
    fail += validatePassword(form.password.value)
    if (fail == "") return true
    else {
        alert(fail)
        form.password.value = ""
        return false
    }
}

function validate_add(form) {
    if(validate(form)) {

        // check that the passwords match
        if (form.password.normalize() !== form.confirm_password.normalize()) {
            alert("Passwords do not match.\n")
            form.password.value = ""
            form.confirm_password = ""
            return false
        } else {
            return true
        }
    } else {
        return false
    }
}