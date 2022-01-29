// Edit navbars to show which one is currently active
let navbarLinks = `
    <a class="nav-link" href="calculateReceipts.php">Calculate Receipts</a>
    <a class="nav-link active" aria-current="page" href="saved.php">Saved</a>
    <a class="nav-link" href="logout.php">Logout</a>
`;

let navbar = document.querySelector(".navbar-nav");
navbar.innerHTML = "";
navbar.innerHTML += navbarLinks;

$(".card").on("click", ".remove-from-saved", function(event) {
    // delete saved receipt
    event.preventDefault();

    let message = "Are you sure you want to remove this receipt from your saved collection?";
    if (window.confirm(message)) {
        let receipt_id = $(this).find(".receipt-id").html();
    
        // call ajax
        $.ajax({
            url: "saved.php",
            method: "POST",
            data: {
                "receipt_id": receipt_id
            }
        })
        .done(function(results) {
            // remove card from view
            // $(this).closest(".card").remove(); // do not need after delete is executed
            // pass in receipt_id
            window.location.href = "saved.php?receipt_id=" + receipt_id;
        })
        .fail(function(results) {
            console.log("ajax call failed");
        });
    }
    
});

$(".card").on("click", ".edit-receipt-name", function(event) {
    // edit receipt name
    event.preventDefault();
    
    let receiptID = $(this).find(".receipt-id").html();
    var newReceiptName = prompt("Enter new receipt name: ");

    if (newReceiptName) {
        // call ajax
        $.ajax({
            url: "saved.php",
            method: "POST",
            data: {
                "receiptID": receiptID,
                "newReceiptName": newReceiptName
            }
        })
        .done(function(results) {
            // edit receipt name
            window.location.href = "saved.php?receiptID=" + receiptID + "&newReceiptName=" + newReceiptName;
        })
        .fail(function(results) {
            console.log("ajax call failed");
        });
    }
})