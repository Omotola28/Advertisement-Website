/**
 * Validate form class helps keep track of error messages derived from user input, it also
 * checks for inputs of user are meeting constraints set for the form. This class also does check that
 * input is valid and then sends feedback to the user.
 *
 * **/

function ValidateForm() {
    this.invalid = [];
    this.checks = [];
}

ValidateForm.prototype = {
    addInvalidError : function (message) {
        this.invalid.push(message);
    },
    
    getInvalidError : function () {
        this.invalid.join('. \n')
    },
    
    checkValidity:function (input) {
       for(var i = 0; i < this.checks.length; i++){
           var isInvalid = this.checks[i].isInvalid(input);
           if(isInvalid){  //if false
               this.addInvalidError(this.checks[i].invalidMessage); //add error message to invalid error list
               this.checks[i].element.classList.add("invalid");
               this.checks[i].element.classList.remove("valid");
           }else { //if true
               this.checks[i].element.classList.remove("invalid");
               this.checks[i].element.classList.add("valid");
           }
       }
    }
};

/**
 * Array to check valid inputs for user full name input box
 * the array stores checks for the length of input, error message passed back
 * and feedback mechanism to help for the user
 */
var validFNameCheck = [
    {
        isInvalid: function (input) {
            return input.value.length < 3;
        },
        invalidMessage: 'Your fullName is less than 3 characters',
        element:document.querySelector('label[for="fullName"] li:nth-child(1)')
    },
    {
        isInvalid : function (input) {
            var invalidCharacters = input.value.match(/[^A-Za-z]/g);
            return invalidCharacters ? true : false;
            
        },
        invalidMessage: "Only letters allowed",
        element: document.querySelector('label[for="fullName"] li:nth-child(2)')
    }
];

/**
 * Validates input for password
 *  1. Password must be at least 6 characters long but less than 20 characters
 *  2. Password should contain atleast 1 upper case and atleast 1 lowercase
 *  3. Password should contain a number
 *  4. Password should have at least one special character
 *
 */
var validPassWCheck = [
    {
        isInvalid: function (input) {
            return input.value.length < 6 || input.value.length > 20;
        },
        invalidMessage : 'Your password should be atleast 6 characters',
        element:document.querySelector('label[for="password"] li:nth-child(1)')
    },
    {
        isInvalid : function (input) {
            return input.value.match(/[A-Z]/g);
        },
        invalidMessage: "You are required to have at least 1 uppercase letter ",
        element: document.querySelector('label[for="password"] li:nth-child(4)')
    },
    {
        isInvalid : function (input) {
            return !input.value.match(/[a-z]/g);
        },
        invalidMessage: "You are required to have at least 1 lowercase letter ",
        element: document.querySelector('label[for="password"] li:nth-child(4)')
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
var validPhoneNoCheck = [
    {
        isInvalid : function (input) {
            return !input.value.match(/[^A-Za-z-\!\@\#\$\%\^\&\*]/g);
        },
        invalidMessage: "Only numbers required ",
        element: document.querySelector('label[for="phoneNumber"] li:nth-child(1)')
    },
    {
        isInvalid : function (input) {
            return  input.value.length < 14 || input.value.length > 15;
        },
        invalidMessage: "max length is 15 digits",
        element: document.querySelector('label[for="phoneNumber"] li:nth-child(1)')
    },
    {
        isInvalid : function (input) {
            return  !input.value.match(/^\+/);
        },
        invalidMessage: "Start with a plus followed by country calling code and then number",
        element: document.querySelector('label[for="phoneNumber"] li:nth-child(2)')
    }
];

/**
 * Validate address input no special characters inside the input form
 */
var validAddress1Check =[
    {
        isInvalid : function (input) {
            return  input.value.match(/[\!\@\#\$\%\^\&\*]/g);
        },
        invalidMessage: "No special characters allowed",
        element: document.querySelector('label[for="inputAddress1"] li:nth-child(1)')
    }
];

var validAddress2Check =[
    {
        isInvalid : function (input) {
            return  input.value.match(/[\!\@\#\$\%\^\&\*]/g);
        },
        invalidMessage: "No special characters allowed",
        element: document.querySelector('label[for="inputAddress2"] li:nth-child(1)')
    }
];

var  validCountryCheck =[
    {
        isInvalid : function (input) {
            return  input.value != "";
        },
        invalidMessage: "Choose a country",
        element: document.querySelector('label[for="country"] li:nth-child(1)')
    }
];

var fullName = document.getElementById("fullName");
var password = document.getElementById("password");
var phoneNumber = document.getElementById("phoneNumber");
var address1 = document.getElementById("inputAddress1");
var address2 = document.getElementById("inputAddress2");
var country = document.getElementById("country");
var state = document.getElementById("state");

fullName.ValidateForm = new ValidateForm(); //instantiates the ValidateForm class with default error array
fullName.ValidateForm.checks = validFNameCheck; //chooses which array to store in the checks array in ValidateForm

password.ValidateForm = new ValidateForm(); //instantiates the ValidateForm class with default error array
password.ValidateForm.checks = validPassWCheck; //chooses which array to store in the checks array in ValidateForm

phoneNumber.ValidateForm = new ValidateForm(); //instantiates the ValidateForm class with default error array
phoneNumber.ValidateForm.checks = validPhoneNoCheck; //chooses which array to store in the checks array in ValidateForm

address1.ValidateForm = new ValidateForm(); //instantiates the ValidateForm class with default error array
address1.ValidateForm.checks = validAddress1Check; //chooses which array to store in the checks array in ValidateForm

address2.ValidateForm = new ValidateForm(); //instantiates the ValidateForm class with default error array
address2.ValidateForm.checks = validAddress2Check; //chooses which array to store in the checks array in ValidateForm

country.ValidateForm = new ValidateForm(); //instantiates the ValidateForm class with default error array
country.ValidateForm.checks = validCountryCheck; //chooses which array to store in the checks array in ValidateForm

//state.ValidateForm = new ValidateForm(); //instantiates the ValidateForm class with default error array
//state.ValidateForm.checks = validStateCheck; //chooses which array to store in the checks array in ValidateForm

var inputs = document.querySelectorAll('input:not([type="submit"])')
for(var i = 0; i < inputs.length; i++){
    inputs[i].addEventListener('keyup', function () {
        this.ValidateForm.checkValidity(this);
    })

}



















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