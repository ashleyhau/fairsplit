// Edit navbars to show which one is currently active
let navbarLinks = `
    <a class="nav-link active" aria-current="page" href="calculateReceipts.php">Calculate Receipts</a>
    <a class="nav-link" href="saved.php">Saved</a>
    <a class="nav-link" href="logout.php">Logout</a>
`;

let navbar = document.querySelector(".navbar-nav");
navbar.innerHTML = "";
navbar.innerHTML += navbarLinks;

// global variables
let participants = [];
let participantsTotal = 0;
var receiptName;
var receiptDate;
// continue button to advance into calculation portion
$(".continue-button").click(function(event) {
    event.preventDefault();

    // restart every time continue button is clicked
    participants = [];
    participantsTotal = 0;

    receiptName = document.querySelector("#name-of-receipt").value.trim();
    receiptDate = document.querySelector("#date-of-receipt").value.trim();
    if (!receiptName || !receiptDate) {
        console.log("need to produce error message here that name and date are needed");
        alert("Name and date are needed");
        return;
    }

    // console.log(receiptName + " " + receiptDate);

    let participantList = document.getElementsByClassName("participant-name");
    for (var i=0; i<participantList.length; i++) {
        let name = participantList.item(i).value.trim();
        if (name) { // check if input field is filled
            // console.log(name);
            participantsTotal++;
            participants.push(name);
        }
    }
    // console.log(participantsTotal);
    // console.log(participants);

    if (participantsTotal < 2) {
        console.log("error: must have at least 2 people on receipt");
        // print error here
        alert("error: must have at least 2 people on receipt");
        return;
    }

    // also maybe delete the input area?

    // otherwise move onto function to generate next page
    // must delete all values in select dropdown before adding participants@
    displayCalculateReceipt(participants, participantsTotal);
});

function displayCalculateReceipt(participants, participantsTotal) {
    $(".calculate-receipt-container").css("display", "block");

    $.scrollTo($('#calculate-receipt-container'), 500);

    // dynamically fill all selects with participants that were added in the section above
    let peopleListSelects = document.getElementsByClassName("people-list");
    removeSelectOptions(peopleListSelects);

    for (var i=0; i<peopleListSelects.length; i++) { // go through each select dropdown with the class people-list
        var select = peopleListSelects[i];
        for (var j=0; j<participantsTotal; j++) {
            var option = document.createElement("option");
            option.value = participants[j];
            option.text = participants[j].charAt(0).toUpperCase() + participants[j].slice(1); // make first character uppercase
            select.appendChild(option);
            $(".people-list").append(participants[j]);
        }
    }
}

function removeSelectOptions(peopleListSelects) {
    // go through each select dropdown with the all selectors inside peopleListSelects
    for (var i=0; i<peopleListSelects.length; i++) { 
        var select = peopleListSelects[i];
        // console.log("select.options.length: " + select.options.length);
        let L = select.options.length - 1;
        if (L > 0) { 
            // check if this is the first iteration (no selector option yet) or second iteration (ie. needs to be cleared)
            for (let j = L; j >= 0; j--) {
                select.remove(j); // removing options in current selector
            }
        } else {
            return;
        }
    }
}

$(".add-more-button").click(function(event) {
    event.preventDefault();

    // Edit navbars to show which one is currently active
    let newInputArea = `
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
    `;

    let calculateInputArea = document.querySelector(".calculate-input-area");
    // calculateInputArea.innerHTML += newInputArea;
    calculateInputArea.insertAdjacentHTML("beforeend", newInputArea); // fixes select being reset error
    // use 'beforeend' to append before div calculateInputArea ends

    populateLastSelect(participants, participantsTotal); // populate new select dropdown
});

function populateLastSelect(participants, participantsTotal) {
    let peopleListSelects = document.getElementsByClassName("people-list");
    // only fill the select dropdown on the most recently added input area
    var select = peopleListSelects[peopleListSelects.length-1];
    for (var j=0; j<participantsTotal; j++) {
        var option = document.createElement("option");
        option.value = participants[j];
        option.text = participants[j].charAt(0).toUpperCase() + participants[j].slice(1); // make first character uppercase
        select.appendChild(option);
        $(".people-list").append(participants[j]);
    }
}

// second parameter ".delete-input-button" is needed since this is referring to a dynamic element
$(".calculate-input-area").on("click", ".delete-input-button", function(event) {
    // remove an extra input area with the minus button
    event.preventDefault();
    event.stopPropagation(); // only concerned with input area directly next to the minus button

    $(this).closest(".item-person-row").remove();
});

$(".calculate-button").on("click", function(event) {
    event.preventDefault();

    // create a calculate function
    let personAndCost = calculate(participants, participantsTotal);
    console.log(personAndCost);
    let totalCost = personAndCost.get("totalCost");
    // console.log(personAndCost);

    // session storage personAndCost to use in Ajax later
    sessionStorage.setItem("personAndCost", JSON.stringify(Array.from(personAndCost.entries())));
    console.log(sessionStorage.getItem("personAndCost"));

    // function to show each person's due
    showEachPersonsDue(participants, participantsTotal, personAndCost, totalCost);
    $(".final-result-container").css("display", "block"); 
    // container must be shown before scrolling, otherwise it will scroll to the top

    // scroll to final result container
    $.scrollTo($('#final-result-container'), 500);
});

function calculate(participants, participantsTotal) {
    // go through each item person row and match it to each person
    // make sure everything is a number and not a string
    let itemCost = document.getElementsByClassName("item-cost");
    let itemCostArray = [];
    let totalCost = 0;
    for (let i=0; i<itemCost.length; i++) {
        let itemCostVal = itemCost[i].value;
        // console.log(div);
        // console.log(itemCostVal);
        if (!itemCostVal || isNaN(itemCostVal)) {
            console.log("alert!");
            alert("make sure all inputs are actual numbers");
            return;
        }
        itemCostArray.push(itemCostVal); // put into array for later
        totalCost += parseFloat(itemCostVal); // make sure it is not parsed as a string
    }
    totalCost = totalCost.toFixed(2);

    // go through each additional field and add them together, then divide by number of participants
    let additionalCosts = document.getElementsByClassName("additional-cost");
    let totalAdditionalCosts = 0;
    for (let i=0; i<additionalCosts.length; i++) {
        let additionalCost = additionalCosts[i].value;
        if (!additionalCost || isNaN(additionalCost)) {
            console.log("alert!");
            alert("make sure all inputs are actual numbers");
            return;
        }
        totalAdditionalCosts += parseFloat(additionalCost); // make sure it is not parsed as a string
        totalCost = parseFloat(totalCost) + parseFloat(additionalCost); // make sure adding it together doesn't make it a string
    }
    totalCost = totalCost.toFixed(2);
    let splitAdditionalCost = totalAdditionalCosts/participantsTotal; // split between number of participants
    splitAdditionalCost = splitAdditionalCost.toFixed(2);
    // alert(splitAdditionalCost);

    // key to value mapping
    let personAndCost = new Map();
    // set value for each key
    for (let i=0; i<participantsTotal; i++) {
        personAndCost.set(participants[i], splitAdditionalCost); // assign every one the additional cost
    }

    // go thru list of people in dropdown/select list
    // set the itemCostArray value and add to the value in map via key search
    let people = document.getElementsByClassName("people-list");
    for (let i=0; i<people.length; i++) {
        // people[i], check i via itemCostArray and put i into map
        let person = people[i].value;
        // console.log(person);

        let newCost = parseFloat(personAndCost.get(person)) + parseFloat(itemCostArray[i]);
        newCost = newCost.toFixed(2); // get new cost and fix to 2 decimals
        personAndCost.set(person, newCost); // set new cost to the person in map
        // console.log(personAndCost.get(person));
    }

    // set map of "totalCost" and totalCost value
    personAndCost.set("totalCost", totalCost); 

    return personAndCost;
}

function showEachPersonsDue(participants, participantsTotal, personAndCost, totalCost) {
    // show receipt details like name and date
    $(".receipt-name").html(receiptName);
    $(".receipt-date").html(receiptDate);

    // add each person to the final receipt result and show their dues
    $("#participant-list tbody").html(""); // clear participant list before adding
    for (let i=0; i<participantsTotal; i++) {
        let row = `
        <tr>
            <td class="text-right">${participants[i]}</td>
            <td>$${personAndCost.get(participants[i])}</td>
        </tr>
        `;
        $("#participant-list tbody").append(row);
    }

    $("#receipt-total").html("");
    $("#receipt-total").html(`total: $${personAndCost.get("totalCost")}`); // replace total cost
}

$(".save-button").on("click", function(event) {
    console.log("Save button clicked"); // backend work done here
    // window.location="saveConfirmation.php";
    
    // with new Map, it will only return array
    // need array to pass into php/ajax
    let personAndCost = sessionStorage.getItem("personAndCost");
    // console.log(personAndCost);

    $.ajax({
        url: "saveConfirmation.php",
        method: "POST",
        data: {
            "personAndCost": personAndCost
        }
    })
    .done(function(results) {
        // switch page
        // results = jsonObject.getJSONArray(results);
        console.log(results);
        window.location.href = "saveConfirmation.php?saved=" + true + "&receiptName=" + receiptName +
            "&receiptDate=" + receiptDate + "&personAndCost=" + personAndCost;
    })
    .fail(function(results) {
        console.log("ajax call failed");
    });
});