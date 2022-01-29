// Edit navbars to show which one is currently active
let navbarLinks = `
    <a class="nav-link" href="./">Login</a>
    <a class="nav-link active" aria-current="page" href="signup.php">Signup</a>
`;

let navbar = document.querySelector(".navbar-nav");
navbar.innerHTML = "";
navbar.innerHTML += navbarLinks;

// form checking
document.querySelector('form').onsubmit = function() {
    // event.preventDefault(); 

    // clear everything inside the error messages span
    $("#error-message").html("");
    $("#error-message-2").html(""); // clear php error message
    
    // alert("form is being submitted");
    let username = $("#username-id").val();
    let password = $("#password-id").val();
    let confirm_password = $("#confirm-password-id").val();

    // alert(username + " " + password + " " + confirm_password);
    if (!username || !password || !confirm_password) {
        // one or more fields have not been filled
        let error = "One or more fields have not been filled";
        $("#error-message").html(error);
        return false; // make sure page does not advance to login if there are errors
    }

    if (password != confirm_password) {
        let error = "Password and confirm password do not match";
        $("#error-message").html(error);
        return false;
    }

    if (checkPassword(password)) {
        return true; // first line of defense passed
    }

    return false;
}

function checkPassword(password) {
    // ensure strong password
    var error;

    // at least 8 characters total, 1 lowercase, 1 number
    if (password.length < 6) {
        error = "Password must have at least 6 characters";
        $("#error-message").html(error);
        return false;
    }

    /*
    if (!password.match(/[A-Z]+/)) { // check for captial letter
        error = "Password needs at least 1 capital letter";
        return false;
    }*/

    if (!password.match(/[a-z]+/)) { // check for lowercase letter
        error = "Password needs at least 1 lowercase letter";
        $("#error-message").html(error);
        return false;
    }

    if (!password.match(/[0-9]+/)) { // check for number in password
        error = "Password needs at least 1 number";
        $("#error-message").html(error);
        return false;
    }

    /*
    if (!password.match(/[$@#&!]+/)) { // check for number in password
        error = "Password needs at least 1 special character in password";
        return false;
    }*/

    return true;
}