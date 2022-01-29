// Edit navbars to show which one is currently active
let navbarLinks = `
    <a class="nav-link active" aria-current="page" href="./">Login</a>
    <a class="nav-link" href="signup.php">Signup</a>
`;

let navbar = document.querySelector(".navbar-nav");
navbar.innerHTML = "";
navbar.innerHTML += navbarLinks;

// form checking
document.querySelector('form').onsubmit = function() {
    // clear everything inside the error messages span
    $("#error-message").html("");
    $("#error-message-2").html(""); // clear php error message

    let username = $("#username-id").val();
    let password = $("#password-id").val();
    // alert(username + " " + password);

    if (!username || !password) {
        let error = "One or more fields have not been filled";
        $("#error-message").html(error);
        return false; // make sure page does not advance to functionality page
    }

    return true;
}