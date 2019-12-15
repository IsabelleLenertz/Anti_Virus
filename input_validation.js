// restricts so it fits database
function validate_username(name){
    let length = name.toString().trim().length;
    if (length === 0) return "No Username was entered.\n";
    else if (length > 20) return "Usernames must be no longer than 20 characters.\n";
    else if (/[^a-zA-Z0-9_-]/.test(name))return "Only a-z, A-Z, 0-9, - and _ allowed in Usernames.\n";
    return ""
}

function validate_password(pass){
    let length = pass.toString().trim().length;
    if (length === 0) return "No password s entered.\n";
    else if (length < 10)return "Passwords must be at least 10 characters.\n";
    else if (!/[a-z]/.test(pass) || ! /[A-Z]/.test(pass) ||!/[0-9]/.test(pass)) return "Passwords require one each of a-z, A-Z and 0-9.\n";
    return "";   
}

function identical_passwords(pass1, pass2){
    if (pass1 !== pass2) return "Passwords do not match";
    return "";
}

function validate_signup(form){
    let fail = validate_username(form.username.value);
    fail += validate_password(form.password.value);
    fail += identical_passwords(form.password.value, form.confirm_passsord.value);
    if(fail === "") return true;
    else {
        window.alert(fail);
        return false;
    }
}


function real_time_identical_passwords(pass){
    if(document.getElementById("pass1").value !== pass){
        document.getElementById("pass_error").innerHTML = "Passwords do not match";
    } else {
        document.getElementById("pass_error").innerHTML = "";
    }
}

