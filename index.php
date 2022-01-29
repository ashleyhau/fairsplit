<?php

require 'config/config.php';

if ( !isset($_SESSION["logged_in"]) || !$_SESSION["logged_in"] ) {
	// check if this page has username and password passed to it via POST
	if ( isset($_POST['username']) && isset($_POST['password']) ) {
		// make sure user has entered username AND password
		if ( !empty($_POST['username']) || !empty($_POST['password']) ) {

			// connect to the database to see if user
			// check if correct credentials entered
			$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

			if($mysqli->connect_errno) {
				echo $mysqli->connect_error;
				exit();
			}

			// hash the user's password
			$password = hash("sha256", $_POST["password"]);

			$sql = "SELECT * FROM users WHERE username = '" . $_POST['username'] . "' AND password = '" . $password . "';";
			
			$results = $mysqli->query($sql);

			if(!$results) {
				echo $mysqli->error;
				exit();
			}

			// result back means username and password was correct
			if($results->num_rows > 0) {
				// Let's log them in
				$_SESSION["logged_in"] = true;
				$_SESSION["username"] = $_POST["username"];

				// if they have logged in, redirect them to the home page
				header("Location: calculateReceipts.php");
			}
			else {
				$error = "Invalid username or password.";
			}
		} 
	}
} else {
	// will get to this code if you are logged in
	// redirect them to the home page
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
    <link rel="stylesheet" href="css/index.css">
    <title>Login</title>
</head>
<body>
<?php include 'navbar.php'; ?>

<div class="container">
<p id="login-text">Login to continue into application</p>
<form id="login-form" action="./" method="POST">

    <div class="form-group">
        <label for="username-id">username <span class="text-danger">*</span></label>
        <input type="text" class="form-control" id="username-id" name="username">
        <small id="username-error" class="invalid-feedback">Username is required.</small>
    </div> <!-- .form-group -->

    <div class="form-group ">
        <label for="password-id">password <span class="text-danger">*</span></label>
        <input type="password" class="form-control" id="password-id" name="password">
        <small id="password-error" class="invalid-feedback">Password is required.</small>
    </div> <!-- .form-group -->

    <i><span id="error-message" class="text-danger"></span></i>

    <?php if ( isset($error) && !empty($error) ) : ?>
        <i><span id="error-message-2" class="text-danger"><?php echo $error; ?></span></i>
    <?php endif; ?>

    <div class="form-group">
        <button type="submit" class="btn">Login <i class="fa fa-arrow-right"></i></button>
        <br><a href="signup.php" id="create-account-link">Create an account</a>
    </div> <!-- .form-group -->

</form>
</div>
    
<?php include 'importJS.php'; ?>
<script src="js/index.js"></script>
</body>
</html>