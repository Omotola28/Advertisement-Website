/**
 * Validate form class helps keep track of error messages derived from user input, it also
 * checks for inputs of user are meeting constraints set for the form. This class also does check that
 * input is valid and then sends feedback to the user.
 *
 * **/

function ValidateForm(input) {
    this.invalid = []; //array for invalid messages
    this.checks = []; //array for all the checks done in order to raise invalid error message

    this.inputBox = input;

    //trigger method to attach the listener
    this.assignListener();
}

ValidateForm.prototype = {
    addInvalidError : function (message) {
        this.invalid.push(message);
    },
    
    getInvalidError : function () {
        return this.invalid.join('. \n')
    },
    
    checkValidity:function (input) {
       for(let i = 0; i < this.checks.length; i++){
           let isInvalid = this.checks[i].isInvalid(input);
           if(isInvalid) {  //if false
               this.addInvalidError(this.checks[i].invalidMessage); //add error message to invalid error list
           }
           let constraints = this.checks[i].element;
           if(constraints) {
               if (isInvalid) {
                   this.checks[i].element.classList.add("invalid");
                   this.checks[i].element.classList.remove("valid");
               }
               else {
                   this.checks[i].element.classList.remove("invalid");
                   this.checks[i].element.classList.add("valid");
               }
           }
       } //end for loop
    },

    checkInput:function () {
        this.inputBox.ValidateForm.invalid = [];
        this.checkValidity(this.inputBox);

        if(this.inputBox.ValidateForm.invalid.length === 0 && this.inputBox.value !== ""){
            this.inputBox.setCustomValidity("");
        }else {
            let message = this.inputBox.ValidateForm.getInvalidError();
            this.inputBox.setCustomValidity(message);
        }
    },

    assignListener: function() { //assign the listener here

        let CustomValidation = this;

        this.inputBox.addEventListener('keyup', function() {
            CustomValidation.checkInput();
        })
    }
};

/*

    VALIDATING REGISTRATION FORM STARTS HERE CHECKS INPUT
        -full name
        -password
        -phonenumber
        -address line 1
        -address line 2
        -captcha
                                                        */
/**
 * Array to check valid inputs for user full name input box
 * the array stores checks for the length of input, error message passed back
 * and feedback mechanism to help for the user
 */
let validFNameCheck = [
    {
        isInvalid: function (input) {
            return input.value.length < 3;
        },
        invalidMessage: 'Your fullName is less than 3 characters',
        element:document.querySelector('label[for="fullName"] li:nth-child(1)')
    },
    {
        isInvalid : function (input) {
            let invalidCharacters = input.value.match(/[^A-Za-z ]/g);
            return invalidCharacters ? true : false;
            
        },
        invalidMessage: "Only letters allowed",
        element: document.querySelector('label[for="fullName"] li:nth-child(2)')
    }
];

/**
 * Validates input for password
 *  1. Password must be at least 6 characters long but less than 20 characters
 *  2. Password should contain at least 1 upper case and at least 1 lowercase
 *  3. Password should contain a number
 *  4. Password should have at least one special character
 *
 */
let validPassWCheck = [
    {
        isInvalid: function (input) {
            return input.value.length < 6 || input.value.length > 20;
        },
        invalidMessage : 'Your password should be at least 6 characters',
        element:document.querySelector('label[for="password"] li:nth-child(1)')
    },
    {
        isInvalid : function (input) {
            return !input.value.match(/[A-Z]/g);
        },
        invalidMessage: "You are required to have at least 1 uppercase letter ",
        element: document.querySelector('label[for="password"] li:nth-child(4)')
    },
    {
        isInvalid : function (input) {
            return !input.value.match(/[a-z]/g);
        },
        invalidMessage: "You are required to have at least 1 lowercase letter ",
        element: document.querySelector('label[for="password"] li:nth-child(5)')
    },
    {
        isInvalid : function (input) {
            return !input.value.match(/[0-9]/g);
        },
        invalidMessage: "Password should contain a number  ",
        element: document.querySelector('label[for="password"] li:nth-child(2)')
    },
    {
        isInvalid : function (input) {
            return !input.value.match(/[\!\@\#\$\%\^\&\*]/g);
        },
        invalidMessage: "Special character required e.g @,$,! ",
        element: document.querySelector('label[for="password"] li:nth-child(3)')
    }

];

/**
 *
 * Validity checks for the phoneNumber input box
 * only numbers should be greater than 12 but less than or equal to 15
 */
let validPhoneNoCheck = [
   {
        isInvalid : function (input) {
            return !input.value.match(/^\+/);
        },
        invalidMessage: "Start with a plus followed by country calling code and then number",
        element: document.querySelector('label[for="phoneNumber"] li:nth-child(3)')
    },
    {
        isInvalid : function (input) {
            return  input.value.length < 12 && input.value.length < 15;
        },
        invalidMessage: "max length is 15 digits",
        element: document.querySelector('label[for="phoneNumber"] li:nth-child(2)')
    },
    {
        isInvalid : function (input) {
            return input.value.match(/[A-Za-z]/g);
        },
        invalidMessage: "Only numbers required ",
        element: document.querySelector('label[for="phoneNumber"] li:nth-child(1)')
    }
];

/**
 * Validate address input no special characters inside the input form
 */
let validAddress1Check =[
    {
        isInvalid : function (input) {
            return  input.value.match(/[\!\@\#\$\%\^\&\*]/g);
        },
        invalidMessage: "No special characters allowed",
        element: document.querySelector('label[for="addressLine1"] li:nth-child(1)')
    }
];

let validAddress2Check =[
    {
        isInvalid : function (input) {
            return  input.value.match(/[\!\@\#\$\%\^\&\*]/g);
        },
        invalidMessage: "No special characters allowed",
        element: document.querySelector('label[for="addressLine2"] li:nth-child(1)')
    }
];


let validCaptchaCheck =[
    {
        isInvalid : function (input) {
            return  input.value.match(/[\!\@\#\$\%\^\&\*\>\<]/g);
        },
        invalidMessage: "No special characters allowed",
        element: null
    }
];

/*
    VALIDATION FOR REGISTRATION FORM ENDS HERE
                                                */

/**
 * @param id of element you want ot retrieve
 * @returns {Element}
 * @private
 */
function _(id) {
    return document.getElementById(id);
}

let fullName = _("fullName");
let email = _("email");
let password = _("password");
let phoneNumber = _("phoneNumber");
let address1 = _("addressLine1");
let address2 =_("addressLine2");
let country = _("country");
let state = _("state");
let captcha = _("captchaTxt");
let register = _("register");
let errorM = _("errorM");
let successM = _("successM");



fullName.ValidateForm = new ValidateForm(fullName); //instantiates the ValidateForm class with default error array
fullName.ValidateForm.checks = validFNameCheck; //chooses which array to store in the checks array in ValidateForm

password.ValidateForm = new ValidateForm(password); //instantiates the ValidateForm class with default error array
password.ValidateForm.checks = validPassWCheck; //chooses which array to store in the checks array in ValidateForm

phoneNumber.ValidateForm = new ValidateForm(phoneNumber); //instantiates the ValidateForm class with default error array
phoneNumber.ValidateForm.checks = validPhoneNoCheck; //chooses which array to store in the checks array in ValidateForm

address1.ValidateForm = new ValidateForm(address1); //instantiates the ValidateForm class with default error array
address1.ValidateForm.checks = validAddress1Check; //chooses which array to store in the checks array in ValidateForm

address2.ValidateForm = new ValidateForm(address2); //instantiates the ValidateForm class with default error array
address2.ValidateForm.checks = validAddress2Check; //chooses which array to store in the checks array in ValidateForm

captcha.ValidateForm = new ValidateForm(captcha);
captcha.ValidateForm.checks = validCaptchaCheck;

function sendInput() {
    register.disabled = true;
    let formData = new FormData();
    formData.append("name", fullName.value);
    formData.append("email", email.value);
    formData.append("password", password.value);
    formData.append("no", phoneNumber.value);
    formData.append("addr1", address1.value);
    formData.append("addr2", address2.value);
    formData.append("country", country.value);
    formData.append("state", state.value);
    formData.append("captchaTxt", captcha.value);
    formData.append("register", register.value);
    let xhttp = new XMLHttpRequest();
    xhttp.open("POST", "register.php", true);
    xhttp.onreadystatechange = function () {
        if (xhttp.readyState === 4 && xhttp.status === 200) {
            if(xhttp.responseText === "Successfully registered"){
                successM.style.display = "block";
                successM.innerHTML = '<div><p>'+xhttp.responseText+ '</p>' +
                    '<span>Click here to <a href="login.php"> Login </a></span></div>';
                regForm.style.display = "none";
            }
            else {
                errorM.innerHTML = xhttp.responseText;
                register.disabled = false;
            }
        }
    };
    xhttp.send(formData);
}

/**
 *EventListeners for form input
 */
let inputs = document.querySelectorAll('input:not([type="submit"])');

let submit = document.querySelector('input[type="submit"]');

let regForm = _("regForm");

/**
 * Validate form input and send input to client side for further checks
 * against database.
 */
function validate() {
    let inputProcess = 0;
    for(let i = 0; i < inputs.length; i++){
        inputs[i].ValidateForm.checkInput();
        inputProcess+=i;
        if (inputProcess === inputs.length){break;}
        sendInput();
    }

}

submit.addEventListener('click', validate);
regForm.addEventListener('submit', validate);






















/*
 if(input.value.length < 3){
            this.addInvalidError("Your fullName is less than 3 characters");
            var element = document.querySelector('label[for="fullName"] li:nth-child(1)');
            element.classList.add("invalid");
            element.classList.remove("valid");
        }else {
            var element = document.querySelector('label[for="fullName"] li:nth-child(1)');
            element.classList.remove("invalid");
            element.classList.add("valid");
        }

        if(input.value.match(/[^A-Za-z]/g)){
            this.addInvalidError("Only letters allowed")
            var element = document.querySelector('label[for="fullName"] li:nth-child(2)');
            element.classList.add("invalid");
            element.classList.remove("valid");
        }else {
            var element = document.querySelector('label[for="fullName"] li:nth-child(2)');
            element.classList.remove("invalid");
            element.classList.add("valid");
        }
 */