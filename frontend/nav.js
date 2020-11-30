function home_button(button) {
    button.innerHTML = "Home"
    button.href = "../backend/mainpage.php"
}

function account_button(button) { 
    if (status == '0') {
        button.style.display = "None"
    }
    else {
        button.innerHTML = "User Page"
        button.href = "../backend/user.php"

    }
}

function admin_button(button) {
    if (status == '2') {
        button.innerHTML = "Admin Page"
        button.href = "../backend/admin.php"
    } else {
        button.style.display = "None"
    }
}

function sign_button(button) {
    button.setAttribute("name", "sign-action")
    
    if (status == '0') {
        button.value = "Sign-In"
        button.innerHTML = "Sign-In"
    } else {
        button.value = "Sign-Out"
        button.innerHTML = "Sign-Out"
    }
}

function sign_action(button) {
    var form = document.createElement('form')
    form.action = "../backend/signin.php"
    form.method = 'post'

    login_action = document.createElement('input')
    login_action.type = 'hidden'
    login_action.name = button.name
    login_action.value = button.value

    form.appendChild(login_action)
    document.getElementById("hidden-form").appendChild(form)
    form.submit()
    document.close()
}