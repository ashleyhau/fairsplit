<?php

require 'config/config.php';

// var_dump($_POST);

if ( !isset($_SESSION["logged_in"]) || !$_SESSION["logged_in"] ) {
    if ( isset($_POST['username']) && !empty($_POST['username']) 
        && isset($_POST['password']) && !empty($_POST['password'])
        && isset($_POST['confirm_password']) && !empty($_POST['confirm_password']) ) {
        
        $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

        if ( $mysqli->connect_errno ) {
            echo $mysqli->connect_error;
            exit();
        }

        // before inserting a new user, check if username or email already exists in the table
        $statement_registered = $mysqli->prepare("SELECT * FROM users WHERE username = ? OR password = ?");
        $statement_registered->bind_param("ss", $_POST["username"], $_POST["password"]);
        $executed_registered = $statement_registered->execute();
        if (!$executed_registered) {
            echo $mysqli->error;
        }

        // getting any results back means the username or email is already stored in the users table
        // here is how to get a number of rows with prepared statements
        $statement_registered->store_result();
        $numrows = $statement_registered->num_rows;
        $statement_registered->close();

        // echo $numrows;
        if ($numrows > 0) {
            $error = "Username has already been taken";
        } else {
            // hash the password
            $password = hash("sha256", $_POST["password"]);

            $statement = $mysqli->prepare("INSERT INTO users(username, password) VALUES (?,?)");
            $statement->bind_param("ss", $_POST['username'], $password);
            $executed = $statement->execute();
            if (!executed) {
                echo $mysqli->error;
            }

            header("Location: http://fairsplit-app.herokuapp.com/");
            $statement->close();
            $mysqli->close();
        }
    }
} else {
    header("Location: calculateReceipts.php");
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include 'importLinks.php'; ?>
    <link rel="stylesheet" href="css/signup.css">
    <title>Signup</title>
</head>
<body>
<?php include 'navbar.php'; ?>

<div class="container">
<p id="signup-text">Create an account</p>
<form id="signup-form" action="signup.php" method="POST">

    <div class="form-group">
        <label for="username-id">username <span class="text-danger">*</span></label>
        <input type="text" class="form-control" id="username-id" name="username">
    </div> <!-- .form-group -->

    <div class="form-group ">
        <label for="password-id">password <span class="text-danger">*</span></label>
        <input type="password" class="form-control" id="password-id" name="password">
    </div> <!-- .form-group -->

    <div class="form-group ">
        <label for="confirm-password-id">confirm password <span class="text-danger">*</span></label>
        <input type="password" class="form-control" id="confirm-password-id" name="confirm_password">
    </div> <!-- .form-group -->

    <i><span id="error-message" class="text-danger"></span></i>

    <?php if ( isset($error) && !empty($error) ) : ?>
        <i><span id="error-message-2" class="text-danger"><?php echo $error; ?></span></i>
    <?php endif; ?>
    
    <div class="form-group">
        <button type="submit" class="btn">Signup <i class="fa fa-arrow-right"></i></button>
        <br><a href="./" id="return-to-login-link">Return to login</a>
    </div> <!-- .form-group -->

</form>
</div>

<?php include 'importJS.php'; ?>
<script src="js/signup.js"></script>
</body>
</html>