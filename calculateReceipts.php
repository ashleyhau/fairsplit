<?php

require 'config/config.php';

if ( !isset($_SESSION["logged_in"]) || !$_SESSION["logged_in"] ) {
    // redirect to login page if not logged in
    header("Location: ./");
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include 'importLinks.php'; ?>
    <link rel="stylesheet" href="css/calculateReceipts.css">
    <link rel="stylesheet" href="css/saved.css">
    <title>Calculate Receipts</title>
</head>
<body>
<?php include 'navbar.php'; ?>

<div class="receipt-details-container">
    <div class="row">
        <h2>Calculate your receipt, <?php echo $_SESSION["username"]; ?><h2>
        <h3 id="receipt-details">Receipt Details</h2>
        <div class="col-sm-12 col-md-8">
            <label for="name-of-receipt">name of receipt</label>
            <div class="input-group mb-3">
                <input type="text" class="form-control" id="name-of-receipt" aria-describedby="basic-addon3">
            </div>
        </div>
        <div class="col-sm-12 col-md-4">
            <label for="date-of-receipt">date of receipt</label>
            <div class="input-group mb-3">
                <input type="date" class="form-control" id="date-of-receipt" aria-describedby="basic-addon3">
            </div>
        </div>
    </div>

    <div class="row names-section">
        <h3 id="names">Add up to 5 people</h3>
        <!-- make sure to have at least 1 field filled below! -->
        <div class="col-12 col-sm-3 col-md-2">
            <label for="name-1">name 1</label>
        </div>  
        <div class="col-12 col-sm-9 col-md-10">
            <div class="input-group mb-3">
                <input type="text" class="form-control participant-name" id="name-1" aria-describedby="basic-addon3">
            </div>
        </div>

        <div class="col-12 col-sm-3 col-md-2">
            <label for="name-2">name 2</label>
        </div> 
        <div class="col-12 col-sm-9 col-md-10">
            <div class="input-group mb-3">
                <input type="text" class="form-control participant-name" id="name-2" aria-describedby="basic-addon3">
            </div>
        </div>

        <div class="col-12 col-sm-3 col-md-2">
            <label for="name-1">name 3</label>
        </div> 
        <div class="col-12 col-sm-9 col-md-10">
            <div class="input-group mb-3">
                <input type="text" class="form-control participant-name" id="name-3" aria-describedby="basic-addon3">
            </div>
        </div>

        <div class="col-12 col-sm-3 col-md-2">
            <label for="name-4">name 4</label>
        </div> 
        <div class="col-12 col-sm-9 col-md-10">
            <div class="input-group mb-3">
                <input type="text" class="form-control participant-name" id="name-4" aria-describedby="basic-addon3">
            </div>
        </div>

        <div class="col-12 col-sm-3 col-md-2">
            <label for="name-5">name 5</label>
        </div> 
        <div class="col-12 col-sm-9 col-md-10">
            <div class="input-group mb-3">
                <input type="text" class="form-control participant-name" id="name-5" aria-describedby="basic-addon3">
            </div>
        </div>
    </div>

    <button type="button" class="btn continue-button">Continue <i class="fa fa-arrow-right"></i></button>

    <div class="clearfloat"></div>
</div>

<div class="calculate-receipt-container" id="calculate-receipt-container">
    <h3 id="assign-people-costs">Assign costs</h3>
    <div class="calculate-input-area">
        <div class="row item-person-row">
            <div class="col-1 col-sm-1 col-md-1 col-lg-1">
                <button type="button" class="btn delete-input-button float-right">
                    <i class="fa fa-minus-circle delete-input-icon"></i>
                </button>
            </div>
            <div class="col-8 col-sm-8 col-md-7 col-lg-6">
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon1">$</span>
                    </div>
                    <input type="text" class="form-control item-cost" aria-describedby="basic-addon3" placeholder="Item cost">
                </div>
            </div>
            <div class="col-3 col-sm-3 col-md-4 col-lg-5">
                <select class="people-list">
                </select>
            </div>
        </div>
        <div class="row item-person-row">
            <div class="col-1 col-sm-1 col-md-1 col-lg-1">
                <button type="button" class="btn delete-input-button float-right">
                    <i class="fa fa-minus-circle delete-input-icon"></i>
                </button>
            </div>
            <div class="col-8 col-sm-8 col-md-7 col-lg-6">
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon1">$</span>
                    </div>
                    <input type="text" class="form-control item-cost" aria-describedby="basic-addon3" placeholder="Item cost">
                </div>
            </div>
            <div class="col-3 col-sm-3 col-md-4 col-lg-5">
                <select class="people-list">
                </select>
            </div>
        </div>
        <div class="row item-person-row">
            <div class="col-1 col-sm-1 col-md-1 col-lg-1">
                <button type="button" class="btn delete-input-button float-right">
                    <i class="fa fa-minus-circle delete-input-icon"></i>
                </button>
            </div>
            <div class="col-8 col-sm-8 col-md-7 col-lg-6">
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon1">$</span>
                    </div>
                    <input type="text" class="form-control item-cost" aria-describedby="basic-addon3" placeholder="Item cost">
                </div>
            </div>
            <div class="col-3 col-sm-3 col-md-4 col-lg-5">
                <select class="people-list">
                </select>
            </div>
        </div>
        <div class="row item-person-row">
            <div class="col-1 col-sm-1 col-md-1 col-lg-1">
                <button type="button" class="btn delete-input-button float-right">
                    <i class="fa fa-minus-circle delete-input-icon"></i>
                </button>
            </div>
            <div class="col-8 col-sm-8 col-md-7 col-lg-6">
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon1">$</span>
                    </div>
                    <input type="text" class="form-control item-cost" aria-describedby="basic-addon3" placeholder="Item cost">
                </div>
            </div>
            <div class="col-3 col-sm-3 col-md-4 col-lg-5">
                <select class="people-list">
                </select>
            </div>
        </div>
        <div class="row item-person-row">
            <div class="col-1 col-sm-1 col-md-1 col-lg-1">
                <button type="button" class="btn delete-input-button float-right">
                    <i class="fa fa-minus-circle delete-input-icon"></i>
                </button>
            </div>
            <div class="col-8 col-sm-8 col-md-7 col-lg-6">
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon1">$</span>
                    </div>
                    <input type="text" class="form-control item-cost" aria-describedby="basic-addon3" placeholder="Item cost">
                </div>
            </div>
            <div class="col-3 col-sm-3 col-md-4 col-lg-5">
                <select class="people-list">
                </select>
            </div>
        </div>
    </div>

    <div id="add-more-area">
        <button type="button" class="btn add-more-button"><i class="fa fa-plus circle-icon"></i></button> add more items
    </div>

    <!-- additional items? -->
    <div class="row additional-fields">
        <h3 id="additional-fields">additional fields</h3>
        <div class="col-6 col-sm-6 col-md-6 col-lg-3">
            <div class="input-group mb-3">
                <label for="tax">tax &nbsp;</label>
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1">$</span>
                </div>
                <input type="text" id="tax" class="form-control additional-cost" aria-describedby="basic-addon3" value="0.00">
            </div>
        </div>
        <div class="col-6 col-sm-6 col-md-6 col-lg-3">
            <div class="input-group mb-3">
                <label for="tips">tips &nbsp;</label>
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1">$</span>
                </div>
                <input type="text" id="tips" class="form-control additional-cost" aria-describedby="basic-addon3" value="0.00">
            </div>
        </div>
        <div class="col-6 col-sm-6 col-md-6 col-lg-3">
            <div class="input-group mb-3">
                <label for="gratuity">gratuity &nbsp;</label>
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1">$</span>
                </div>
                <input type="text" id="gratuity" class="form-control additional-cost" aria-describedby="basic-addon3" value="0.00">
            </div>
        </div>
        <div class="col-6 col-sm-6 col-md-6 col-lg-3">
            <div class="input-group mb-3">
                <label for="bag-fee">bag fee &nbsp;</label>
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1">$</span>
                </div>
                <input type="text" id="bag-fee" class="form-control additional-cost" aria-describedby="basic-addon3" value="0.00">
            </div>
        </div>
    </div>

    <button type="button" class="btn calculate-button">Calculate!</button>

    <div class="clearfloat"></div>
</div>

<div class="final-result-container" id="final-result-container">
    <div class="card">
        <div class="card-body">
            <div class="receipt-heading">
                <p class="receipt-name">receipt name</p>
                <p class="receipt-date">date</p>
                <div class="clearfloat"></div>
            </div>
            <div class="receipt-participants">
                <table class="table table-borderless" id="participant-list">
                    <tbody>
                        <!-- example of what gets appended:
                        <tr>
                            <td class="text-right">person 1</td>
                            <td>$xx.xx</td>
                        </tr>
                        -->
                    </tbody>
                </table>
            </div>
            <div class="receipt-footer">
                <p id="receipt-total" class="float-right">total: $xx.xx</p>
            </div>
        </div>
    </div>

    <button type="button" class="btn save-button">Save Receipt Details</button>

    <div class="clearfloat"></div>
</div>

<?php include 'importJS.php'; ?>
<script src="js/calculateReceipts.js"></script>
</body>
</html>