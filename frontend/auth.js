function validateUsername(field) {
    if (field == "") return "No username was entered.\n";
    else if (field.length < 6)
        return "Username must be at least 6 characters.\n";
    else if (/[^a-zA-Z0-9_-]/.test(field))
        return "Username contains invalid characters.\n";
    return ""
}
function validatePassword(field) {
    if (field == "") return "No passsword was entered.\n";
    else if (field.length < 6)
        return "Password must be at least 6 characters.\n";
    else if (!/[a-z]/.test(field) || !/[A-Z]/.test(field) || !/[0-9]/.test(field))
        return "Passwords require at least 1 lowercase letter, uppercase letter, and number.\n";
    return ""
}
function validate(form) {
    fail = validateUsername(form.username.value)
    fail += validatePassword(form.password.value)
    if (fail == "") return true
    else {
        form.password.value = ""
        return false
    }
}