
function printState(country, state) {
    var selectedCountry = document.getElementById(country);
    var printState = document.getElementById(state);
    printState.innerHTML = "";
    if(selectedCountry.value == "Nigeria"){
        var optionArray = ["|", "abuja|Abuja", "lagos|Lagos", "bayelsa|Bayelsa"];
    }else if(selectedCountry.value == "Kenya"){
        var optionArray = ["|", "nairobi|Nairobi", "mombasa|Mombasa", "nakuru|Nakuru"];
    }else if(selectedCountry.value == "South Africa") {
        var optionArray = ["|", "nairobi|Nairobi", "mombasa|Mombasa", "nakuru|Nakuru"];
    }
    for(var option in optionArray){
        var pair = optionArray[option].split("|");
        var newOption = document.createElement("option");
        newOption.value = pair[0];
        newOption.innerHTML = pair[1];
        printState.options.add(newOption);
    }
}

var firstN, surName, email, password, addressL1, addressL2, country, state, number;
// validation function
function validate() {
    //COLLECT INPUT TEXT OBJECTS
    firstN = document.forms['regForm']['firstName'];
    surName = document.forms['regForm']['surName'];
    email = document.forms['regForm']['email'];
    password = document.forms['regForm']['password'];
    addressL1 = document.forms['regForm']['addressLine1'];
    addressL2 = document.forms['regForm']['addressLine2'];
    country = document.forms['regForm']['country'];
    state = document.forms['regForm']['state'];
    number = document.forms['regForm']['phoneNumber'];

    // SETTING ALL EVENT LISTENERS
    firstN.addEventListener('blur', verifyFirstName, true);
    surName.addEventListener('blur', verifySurName, true);
    email.addEventListener('blur', verifyEmail, true);
    password.addEventListener('blur', verifyPassword, true);
    addressL1.addEventListener('blur', verifyAddress, true);
    addressL2.addEventListener('blur', verifyAddress, true);
    country.addEventListener('blur',verifyCountry,true);
    state.addEventListener('blur',verifyState, true);
    number.addEventListener('blur',verifyNo, true);

    // validate email
    if (email.value == "") {
        email.style.border = "1px solid red";
        document.getElementById('email-div').style.color = "red";
        email.focus();
        return false;
    }
    // validate password
    if (password.value == "") {
        password.style.border = "1px solid red";
        document.getElementById('password-div').style.color = "red";
        password.focus();
        return false;
    }
    //validate address
    if (addressL1.value == "") {
        addressL1.style.border = "1px solid red";
        document.getElementById('address1-div').style.color = "red";
        addressL1.focus();
        return false;
    }
    if (addressL2.value == "") {
        addressL2.style.border = "1px solid red";
        document.getElementById('address2-div').style.color = "red";
        addressL2.focus();
        return false;
    }

    //validate country
    if (country.value == "") {
        country.style.border = "1px solid red";
        document.getElementById('country-div').style.color = "red";
        country.focus();
        return false;
    }
    //validate state
    if (state.value == "") {
        state.style.border = "1px solid red";
        document.getElementById('state-div').style.color = "red";
        state.focus();
        return false;
    }

    //validate phoneNumber
    if (number.value == "") {
        number.style.border = "1px solid red";
        document.getElementById('number-div').style.color = "red";
        number.focus();
        return false;
    }

    //validate firstName
    if (firstN.value == "") {
        firstN.style.border = "1px solid red";
        document.getElementById('firstName-div').style.color = "red";
        firstN.focus();
        return false;
    }

    //validate surname
    if (surName.value == "") {
        surName.style.border = "1px solid red";
        document.getElementById('surName-div').style.color = "red";
        surName.focus();
        return false;
    }

}
// event handler functions

function verifyFirstName() {
    if (firstN.value != "") {
        firstN.style.border = "1px solid #5e6e66";
        document.getElementById('firstName-div').style.color = "#5e6e66";
        return true;
    }
}

function verifySurName() {
    if (surName.value != "") {
        surName.style.border = "1px solid #5e6e66";
        document.getElementById('surName-div').style.color = "#5e6e66";
        return true;
    }
}

function verifyEmail() {
    if (email.value != "") {
        email.style.border = "1px solid #5e6e66";
        document.getElementById('email-div').style.color = "#5e6e66";
        return true;
    }
}

//validate password
function verifyPassword() {
    if (password.value != "") {
        password.style.border = "1px solid #5e6e66";
        document.getElementById('password-div').style.color = "#5e6e66";
        return true;
    }
}

//validate address
function verifyAddress() {
    if (addressL1.value != "" || addressL2.value!="") {
        addressL1.style.border = "1px solid #5e6e66";
        addressL2.style.border = "1px solid #5e6e66"
        document.getElementById('address1-div').style.color = "#5e6e66";
        document.getElementById('address2-div').style.color = "#5e6e66";
        return true;
    }
}

//validate country
function verifyCountry() {
    if (country.value != "") {
        country.style.border = "1px solid #5e6e66";
        document.getElementById('country-div').style.color = "#5e6e66";
        return true;
    }
}

//validate state
function verifyState() {
    if (state.value != "") {
        state.style.border = "1px solid #5e6e66";
        document.getElementById('state-div').style.color = "#5e6e66";
        return true;
    }
}

//validate no
function verifyNo() {
    if (number.value != "") {
        number.style.border = "1px solid #5e6e66";
        document.getElementById('number-div').style.color = "#5e6e66";
        return true;
    }
}

function select(category, size) {
    var category = document.getElementById(category);
    var size = document.getElementById(size);
    if(category.value == "Accessory" || category.value =="Jewelry"){
        size.disabled = true;
    }
    else
        size.disabled = false;

}

// When the user clicks on <div>, open the popup
function addToWatchList(buttonID, status) {
    var popUp = document.getElementById("itemPopUp"+buttonID);
       if(status == false){
           popUp.classList.toggle("show");
           buttonID.disabled = true;
           return false;
       }else if(status == true){
           popUp.style.display = "none";
           buttonID.disabled = false;
           return true;
       }

}

/**
 * @param x id of element we want to target
 * @returns {Element}
 */
function getId(x) {
    return document.getElementById(x);
}

/*function processFormPage() {
var title, des,image, color, size, country, state, nextBtn1, nextBtn2, firstPhase, secondPhase, thirdPhase;
title = getId('titleUpdate').value;
des = getId('desUpdate').value;
image = getId('fileUpdate').value;
color = getId('updateColor').value;
size = getId('updateSize').value;
country = getId('updateCountry').value;
state = getId('updateState').value;
nextBtn1 = getId('first').value;

if(nextBtn1){
    if(title != '' || des != ''){
        getId('formPage1').style.display = 'none';
        getId('formPage2').style.display = 'block';
    }else if(title == '') {
        title.style.border = "1px solid red";
        title.focus();
    }else if (des == ''){
        des.style.border = "1px solid red";
        des.focus();
    }
}

}*/

$(function(){
    $("#watchListBtn").click(function() {
        $(".sectionsWL").show();
        $(".myDetails").hide();
        $(".sectionList").hide();

    });

    $("#detailsBtn").click(function() {
        $(".myDetails").show();
        $(".sectionsWL").hide();
        $(".sectionList").hide();

    });

    $("#myListBtn").click(function() {
        $(".sectionList").show();
        $(".sectionsWL").hide();
        $(".myDetails").hide();


    });
});

