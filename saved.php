<?php

require 'config/config.php';

if ( !isset($_SESSION["logged_in"]) || !$_SESSION["logged_in"] ) {
    // redirect to login page if not logged in
    header("Location: ./");
}

$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ( $mysqli->connect_errno ) {
    echo $mysqli->connect_error;
    exit();
}

$mysqli->set_charset('utf8');

if ( isset($_GET["receipt_id"]) && !empty($_GET["receipt_id"]) ) {
    // echo "receipt_id needs to be removed";
    // delete any row from people_and_their_total table that references the receipt id
    $delete_person_statement = $mysqli->prepare("DELETE FROM people_and_their_total WHERE receipt_id = ?");
	$delete_person_statement->bind_param("i", $_GET["receipt_id"]);
    $executed = $delete_person_statement->execute();
	if (!$executed) {
		echo $mysqli->error;
		exit();
	}

    // delete actual receipt
    $delete_receipt_statement = $mysqli->prepare("DELETE FROM receipt WHERE id = ?");
	$delete_receipt_statement->bind_param("i", $_GET["receipt_id"]);
	$executed = $delete_receipt_statement->execute();
	if (!$executed) {
		echo $mysqli->error;
		exit();
	}

    $delete_person_statement->close();
	$delete_receipt_statement->close();

    header("Location: saved.php"); // go to same page but make sure no receipt_id is being passed
}

// change receipt name
if ( isset($_GET["receiptID"]) && !empty($_GET["receiptID"]) && isset($_GET["newReceiptName"]) && !empty($_GET["newReceiptName"]) ) {
    // edit the receipt name here
    $edit_receipt_statement = $mysqli->prepare("UPDATE receipt SET name = ? WHERE id = ?");
    $edit_receipt_statement->bind_param("si", $_GET["newReceiptName"], $_GET["receiptID"]);
    
    $executed = $edit_receipt_statement->execute();
    if (!$executed) {
        echo $mysqli->error;
    }
    $edit_receipt_statement->close();

    header("Location: saved.php"); // go to same page but make sure no receiptID or newReceiptName is being passed
}

// get user_id with $_SESSION["username"]
$user_id_sql = "SELECT id FROM users WHERE username = '" . $_SESSION['username'] . "'";
$results = $mysqli->query($user_id_sql);
if ( !$results ) {
    echo $mysqli->error;
    exit();
}
$user_id_result = $results->fetch_assoc();
$user_id = $user_id_result["id"];

$sql = "SELECT users.id AS user_id, receipt.id AS receipt_id, receipt.name AS receipt_name, receipt.date AS receipt_date, 
    receipt.total_amount, people_and_their_total.person_name, people_and_their_total.amount AS person_amount
    FROM users
    LEFT JOIN receipt
        ON receipt.user_id = users.id
    LEFT JOIN people_and_their_total 
        ON people_and_their_total.receipt_id = receipt.id
    WHERE users.id = " . $user_id . " 
    GROUP BY receipt_id;";

$results_receipts = $mysqli->query($sql);
if ( !$results_receipts ) {
    echo $mysqli->error;
    exit();
}

// function that will create and call sql query to return people and their amounts
function getPeopleAmtSql($receipt_id, $mysqli) {
    $sql_people_amt = "SELECT people_and_their_total.person_name, people_and_their_total.amount AS person_amount
        FROM receipt
        LEFT JOIN people_and_their_total 
            ON people_and_their_total.receipt_id = receipt.id
        WHERE receipt_id = " . $receipt_id . ";";
    $results_people_amt = $mysqli->query($sql_people_amt);
    if ( !$results_people_amt ) {
        echo $mysqli->error;
        exit();
    }
    while ($row = $results_people_amt->fetch_assoc()) {
        // after getting the people and their amts, start appending rows to the card table
        appendTable($row);
    }
}

$mysqli->close();

?>

<?php function createCard(array $row) { ?>
    <div class="card">
        <div class="card-body">
            <div class="receipt-heading">
                <p class="receipt-name">
                    <button type="button" class="btn edit-receipt-name float-left">
                        <?= $row["receipt_name"] ?> &nbsp;
                        <p class="d-none receipt-id"><?= $row["receipt_id"] ?></p>
                        <i class='fa fa-pen'></i>
                    </button>
                </p>
                <p class="receipt-date"><?= $row["receipt_date"] ?></p>
                <div class="clearfloat"></div>
            </div>
            <div class="receipt-participants">
                <table class="table table-borderless">
                    <tbody>
                        <?php 
                            // need to redefine mysqli for function above
                            $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
                            if ( $mysqli->connect_errno ) {
                                echo $mysqli->connect_error;
                                exit();
                            }
                            // to create the rows, first call function that will return 
                            // all people with their amounts from that specific receipt id
                            getPeopleAmtSql($row["receipt_id"], $mysqli);
                            $mysqli->close();
                        ?>
                    </tbody>
                </table>
            </div>
            <div class="receipt-footer">
                <button type="button" class="btn remove-from-saved float-left">
                    <p class="d-none receipt-id"><?= $row["receipt_id"] ?></p>
                    <i class="fa fa-minus-circle"></i>
                    <i>remove from saved</i>
                </button>
                <p class="receipt-total">total: $<?= $row["total_amount"] ?></p>
            </div>
        </div>
    </div>
<?php } ?>

<?php function appendTable(array $row) { ?>
    <tr>
        <td class="text-right"><?= $row["person_name"] ?></td>
        <td>$<?= $row["person_amount"] ?></td>
    </tr>
<?php } ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include 'importLinks.php'; ?>
    <link rel="stylesheet" href="css/saved.css">
    <title>Saved Receipts</title>
</head>
<body>
<?php include 'navbar.php'; ?>

    <h2 id="saved-receipts-heading"><?php echo $_SESSION["username"]; ?>'s Saved Receipts</h2>

    <?php 
        $total_rows = mysqli_num_rows($results_receipts);
        while ($row = $results_receipts->fetch_assoc()) {
            if ($row["receipt_id"] && $row["receipt_name"] && $row["receipt_date"] && $row["total_amount"]) {
                // checking that user has saved receipts
                createCard($row); // call function to create each receipt
            } else {
                echo "<h4 class='no-saved-receipts'>No saved receipts</h4>";
                $rand_num = rand(1,2); // generate random gif
                if ($rand_num==1) {
                    echo "<img id='receipt-gif' class='w-25' src='img/receipt.gif'>";
                } else if ($rand_num==2) {
                    echo "<img id='receipt-gif' class='w-25' src='img/cvs.gif'>";
                }
            }
        }
    ?>
    
<?php include 'importJS.php'; ?>
<script src="js/saved.js"></script>
</body>
</html>