function validate_signup(form){
    fail = validate_username(form.username.value);
    fail += validate_password(form.password.value);
    fail += indentical_passwords(form.password.value, form.passwrod.value);
    if(fail === "") return true;
    else {
        alert(fail);
        return false;
    }
}

// restricts so it fits database
function validate_username(name){
    let length = name.toString().trim().length;
    if (length === 0) return "No Username was entered.\n";
    else if (length > 20)return "Usernames must be no longer than 20 characters.\n"
    else if (/[^a-zA-Z0-9_-]/.test(name))return "Only a-z, A-Z, 0-9, - and _ allowed in Usernames.\n"
    return ""
}

function validate_password(pass){
    let length = pass.toString().trim().length;
    if (length === 0) return "No password was entered.\n";
else if (length < 10)return "Passwords must be at least 10 characters.\n"
else if (!/[a-z]/.test(pass) || ! /[A-Z]/.test(pass) ||!/[0-9]/.test(pass))return "Passwords require one each of a-z, A-Z and 0-9.\n"
return ""
    
}

