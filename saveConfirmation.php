<?php

require 'config/config.php';

if ( !isset($_SESSION["logged_in"]) || !$_SESSION["logged_in"] ) {
    // redirect to login page if not logged in
    header("Location: ./");
}

// var_dump($_GET);
// echo $_GET["saved"];
// echo $_GET["personAndCost"];
$personAndCost = json_decode($_GET["personAndCost"]); // returns an object of type stdClass by default
// var_dump($personAndCost);
// echo "<hr>";
// var_dump(json_decode($_GET["personAndCost"], true));

if (isset($_GET["saved"])) { // check if saved button was clicked

    $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
	if ( $mysqli->connect_errno ) {
		echo $mysqli->connect_error;
		exit();
	}

    $receiptName = $_GET["receiptName"];
    $receiptDate = $_GET["receiptDate"];

    // get user_id with $_SESSION["username"]
    $user_id_sql = "SELECT id FROM users WHERE username = '" . $_SESSION['username'] . "'";
    $results = $mysqli->query($user_id_sql);
	if ( !$results ) {
		echo $mysqli->error;
		exit();
	}
    $user_id_result = $results->fetch_assoc();
    $user_id = $user_id_result["id"];

    // last item in array is total cost
    $totalAmount = $personAndCost[count($personAndCost)-1][1]; // set the total cost
    
    // insert into receipt table
	$receipt_statement = $mysqli->prepare("INSERT INTO receipt(name, date, total_amount, user_id) VALUES (?,?,?,?)");
    $receipt_statement->bind_param("ssdi", $receiptName, $receiptDate, $totalAmount, $user_id); // date = s
    $executed_receipt = $receipt_statement->execute();
    if(!$executed_receipt) {
    	echo $mysqli->error;
    } else {
        $receipt_id =  $receipt_statement->insert_id; // get receipt id
    }

    // insert into people_and_their_total table
    $pplAndTotal_statement = $mysqli->prepare("INSERT INTO people_and_their_total(person_name, amount, receipt_id) VALUES (?,?,?)");
    // loop through person and cost array
    for ($i = 0; $i < count($personAndCost)-1; $i++) {
        // insert each person and their total amount
        $pplAndTotal_statement->bind_param("sdi", $personAndCost[$i][0], $personAndCost[$i][1], $receipt_id); 
        $executed_pplAndTotal = $pplAndTotal_statement->execute();
        if (!$executed_pplAndTotal) {
            echo $mysqli->error;
        }
    }

    $receipt_statement->close();
    $pplAndTotal_statement->close();
    $mysqli->close();

    header("Location: saved.php"); // move to saved receipts page
} else {
    header("Location: saved.php");
}

?>