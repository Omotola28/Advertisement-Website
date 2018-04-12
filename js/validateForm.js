/**
 * @param id of element you want ot retrieve
 * @returns {Element}
 * @private
 */
function _(id) {
    return document.getElementById(id);
}


Array.prototype.inArray = function (value)
{
    // Returns true if the passed value is found in the
    // array. Returns false if it is not.
    for (let i=0; i < this.length; i++)
    {
        if (this[i] === value)
        {
            return true;
        }
    }
    return false;
};

/**
 * Ajax connect XMLHttpRequest() initialization
 */
let xhttp = new XMLHttpRequest();

/*
    IDS' FOR FORMS
                                     */
/*
    IDS' FOR MESSAGES
                                    */
let errorM = _("errorM");
let successM = _("successM");

/*
    ID'S FOR REGISTRATION FORM
                                    */
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

/*
    ID'S FOR LOGIN FORM
                                        */
let emailInput = _("emailInput");
let inputPwd = _("inputPwd");
let loginBtn = _("loginBtn");

/*
        ID'S FOR PLACEAD FORM
                                                 */

let category = _("category");
let size = _("size");
let publishBtn = _("publish");
let adTitle = _("title");
let adDescription = _("description");
let adPrice = _("amount");
let adColor = _("color");
let adPicture = _("file");
let imgPreview = _("previewImg");

let fileReader = new FileReader();


/**
 *EventListeners for form input
 */
let inputs = document.querySelectorAll('input:not([type="submit"])');

let reg = document.querySelector('input[name="register"]');

let login = document.querySelector('input[name="loginBtn"]');

let placeAdBtn = document.querySelector('input[name="publish"]');

let regForm = _("regForm");
let loginForm = _("loginForm");
let placeAdForm = _("placeAdForm");






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
                                         END OF VALIDATE FORM CLASS
                                                                                                 */

/**
 * @constructor for Registration form used to send data via an ajax call to the client side
 * and also use Validate form to validate form inputs
 */
function RegistrationForm() {

}

RegistrationForm.prototype.sendRegister= function() {
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
    xhttp.open("POST", "register.php", true);
    xhttp.onreadystatechange = function () {
        if (xhttp.readyState === 4 && xhttp.status === 200) {
            switch (xhttp.responseText){
                case "Successfully registered":
                    successM.style.display = "block";
                    successM.innerHTML = '<div>'+xhttp.responseText+ '</div>'+
                        '<span>Click here to <a href="login.php">Login</a></span></div>';
                    regForm.style.display = "none";
                    break;
                default:
                    errorM.innerHTML = xhttp.responseText;
                    register.disabled = false;
            }

        }
    };
    xhttp.send(formData);
};

/**
 * Validate form input and send input to client side for further checks
 * against database.
 */

RegistrationForm.prototype.validate = function() {
    let inputProcess = 0;
    for(let i = 0; i < inputs.length; i++){
        inputs[i].ValidateForm.checkInput();
        inputProcess++;
        if(inputProcess === inputs.length){
            break;
        }
        alert(inputProcess);
        //callback();
        registration.sendRegister();
    }



};

/**
 * country collects the id for the country selected
 * state collects id for associated states of the country
 *
 * printState dynamically loads states depending on what country is chosen
 */
RegistrationForm.printState = function () {
    let optionArray;
    state.innerHTML = "";
    if(country.value === "Nigeria"){
        optionArray = ["|", "abuja|Abuja", "lagos|Lagos", "bayelsa|Bayelsa"];
    }else if(country.value === "Kenya"){
        optionArray = ["|", "nairobi|Nairobi", "mombasa|Mombasa", "nakuru|Nakuru"];
    }else if(country.value === "South Africa") {
        optionArray = ["|", "cape town|Cape Town", "johannesburg|Johannesburg", "durban|Durban"];
    }
    for(let option in optionArray){
        let pair = optionArray[option].split("|");
        let newOption = document.createElement("option");
        newOption.value = pair[0];
        newOption.innerHTML = pair[1];
        state.options.add(newOption);
    }
};

let registration = new RegistrationForm();

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
        INSTANTIATES THE VALIDATE FROM CLASS WITH A DEFAULT ERROR ARRAY AND CHOOSES WHICH ARRAY TO STORE
        IN THE CHECKS ARRAY IN VALIDATE FORM
                                                                                                            */
if(window.location.href === 'http://localhost/PhpstormProjects/CourseWork/register.php'){
    fullName.ValidateForm = new ValidateForm(fullName);
    fullName.ValidateForm.checks = validFNameCheck;

    password.ValidateForm = new ValidateForm(password);
    password.ValidateForm.checks = validPassWCheck;

    phoneNumber.ValidateForm = new ValidateForm(phoneNumber);
    phoneNumber.ValidateForm.checks = validPhoneNoCheck;

    address1.ValidateForm = new ValidateForm(address1);
    address1.ValidateForm.checks = validAddress1Check;

    address2.ValidateForm = new ValidateForm(address2);
    address2.ValidateForm.checks = validAddress2Check;

    captcha.ValidateForm = new ValidateForm(captcha);
    captcha.ValidateForm.checks = validCaptchaCheck;

    country.addEventListener('change',RegistrationForm.printState);

    reg.addEventListener('click', registration.validate);
    regForm.addEventListener('submit', registration.validate);
}

/*
                                END OF REGISTRATION FROM
                                                                                                     */

/**
 * @constructor for LoginForm used to validate and send login form details to client server and receieve responses
 * to display to users
 */
function LoginForm() {

}

LoginForm.prototype.sendLog = function () {
    loginBtn.disabled = true;
    let formData = new FormData();
    formData.append("emailInput", emailInput.value);
    formData.append("inputPwd", inputPwd.value);
    formData.append("loginBtn", loginBtn.value);
    let xhttp = new XMLHttpRequest();
    xhttp.open("POST", "login.php", true);
    xhttp.onreadystatechange = function () {
        if (xhttp.readyState === 4 && xhttp.status === 200) {
            switch (xhttp.responseText){
                case "success":
                    window.location.assign("index.php");
                    break;
                case "admin" :
                    window.location.assign("admin.php");
                    break;
                default:
                    errorM.innerHTML = xhttp.responseText;
                    loginBtn.disabled = false;
            }

        }
    };
    xhttp.send(formData);
};

LoginForm.prototype.checkLog = function () {
    let inputProcess = 0;
    for(let i = 0; i < inputs.length; i++){
        inputs[i].ValidateForm.checkInput();
        inputProcess++;
        if (inputProcess === inputs.length){break;}
        logs.sendLog();
    }
};

let logs = new LoginForm();
/*
    VALIDATING INPUTS FOR LOGIN FORM STARTS HERE
        -emailInput
        -inputPwd
                                                                                  */
let validLoginEmail =[
    {
        isInvalid : function (input) {
            return  input.value.match(/[\!\+\#\$\%\^\&\*\>\<]/g);
        },
        invalidMessage: "No special characters allowed",
        element: null
    }
];

let validLoginPwd =[
    {
        isInvalid : function (input) {
            return  input.value.match(/[\$\%\^\*\>\<]/g);
        },
        invalidMessage: "only these special characters allowed @!#&",
        element: null
    }
];

if(window.location.href === 'http://localhost/PhpstormProjects/CourseWork/login.php'){

    emailInput.ValidateForm = new ValidateForm(emailInput);
    emailInput.ValidateForm.checks = validLoginEmail;

    inputPwd.ValidateForm = new ValidateForm(inputPwd);
    inputPwd.ValidateForm.checks = validLoginPwd;


    login.addEventListener('click',logs.checkLog);
    loginForm.addEventListener('submit',logs.checkLog);
}


/*
             END OF VALIDATING LOGIN FORM
                                                                                     */
/**
 * @constructor for PlaceAd form, this form sends product data to the database,
 * it has methods selectCategory(), sendProducts() and validate() to check form inputs
 */
function PlaceAdForm() {

}

PlaceAdForm.prototype.sendProducts = function () {
    publishBtn.disabled = true;
    let formData = new FormData();
    formData.append("adCategory", category.value);
    formData.append("adTitle", adTitle.value);
    formData.append("adDescription", adDescription.value);
    formData.append("adPrice", adPrice.value);
    formData.append("adColor", adColor.value);
    formData.append("adSize", size.value);
    formData.append("adPicture", adPicture.files[0]);
    formData.append("adBtn", publishBtn.value);
    xhttp.open("POST", "placeAd.php", true);
    xhttp.onreadystatechange = function () {
        if (xhttp.readyState === 4 && xhttp.status === 200) {
            console.log(xhttp.responseText);
            switch (xhttp.responseText){
                case 'Image uploaded':
                    successM.style.display = "block";
                    successM.innerHTML = '<div>'+xhttp.responseText+ '</div>'+
                        '<span>Click here to place another <a href="placeAd.php">AD</a></span></div>';
                    placeAdForm.style.display = "none";
                    break;
                default:
                    errorM.innerHTML = xhttp.responseText;
                    publishBtn.disabled = false;
            }

        }
    };
    xhttp.send(formData);
};

PlaceAdForm.prototype.sendAd = function () {
    let inputProcess = 0;
    for(let i = 0; i < inputs.length; i++){
        inputs[i].ValidateForm.checkInput();
        inputProcess++;
        if (inputProcess === inputs.length){break;}
        placeAd.sendProducts();
    }


};

PlaceAdForm.prototype.selectCategory = function () {
    category.value === "Accessory" || category.value ==="Jewellery" ?
        size.disabled = true : size.disabled = false;

};

PlaceAdForm.prototype.filePreview = function () {
    let file = adPicture.files[0];
    fileReader.addEventListener("load", function () {
        imgPreview.src = fileReader.result;
    }, false);

    if(file){ //the file has been chosen
        imgPreview.style.display = "block";
        fileReader.readAsDataURL(file);
    }
};

let placeAd = new PlaceAdForm();


let validImageInput =[
    {
        isInvalid : function (input) {
            let adImageName = input.name;
            let adImgextension = adImageName.split(".").pop().toLowerCase();
            let extensions = ['gif', 'png', 'jpg', 'jpeg'];
            return extensions.inArray(adImgextension) === -1
        },
        invalidMessage: "Invalid image type uploaded",
        element: document.querySelector('div[id="fileDiv"] li:nth-child(2)')
    },
    {
        isInvalid : function (input) {
            let adImgSize = input.size;
            return  adImgSize > 500000;
        },
        invalidMessage: "size of image is too large",
        element: document.querySelector('div[id="fileDiv"] li:nth-child(1)')
    }
];


let validTitleInput =[
    {
        isInvalid : function (input) {
            return  input.value.match(/[\%\^\~\>\<]/g);
        },
        invalidMessage: "No html special characters allowed",
        element: document.querySelector('div[id="titleDiv"] li:nth-child(1)')
    },
];

let validPriceInput =[
    {
        isInvalid : function (input) {
            return  input.value.match(/[A-Za-z ]/g);
        },
        invalidMessage: "Only numbers allowed in the price input box",
        element: document.querySelector('div[id="priceDiv"] li:nth-child(1)')
    },
    {
        isInvalid : function (input) {
            return  input.value.match(/[\!\+\#\$\%\^\&\*\>\<\?\@\<\>\ ]/g);
        },
        invalidMessage: "No special characters allowed in the price input box",
        element: document.querySelector('div[id="priceDiv"] li:nth-child(2)')
    }
];



if(window.location.href === 'http://localhost/PhpstormProjects/CourseWork/placeAd.php') {

    adPicture.ValidateForm = new ValidateForm(adPicture);
    adPicture.ValidateForm.checks = validImageInput;

    adTitle.ValidateForm = new ValidateForm(adTitle);
    adTitle.ValidateForm.checks = validTitleInput;

    adPrice.ValidateForm = new ValidateForm(adPrice);
    adPrice.ValidateForm.checks = validPriceInput;

    placeAdBtn.addEventListener('click',placeAd.sendAd);
    placeAdForm.addEventListener('submit',placeAd.sendAd);

    category.addEventListener('change', placeAd.selectCategory);

    adPicture.addEventListener('change', placeAd.filePreview);


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