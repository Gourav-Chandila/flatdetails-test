//jQuery code
$(document).ready(function () {
    $('#myForm').validate({
        errorElement: 'div',  // Use <div> for error messages
        errorClass: 'error-message',
        rules: {
            fullname: {
                required: true, // This rule ensures the field is not empty
                lettersOnly: true, // Custom rule for alphabetic characters
            },
            coapplicantname: {
                // required: true,
                lettersOnly: true,
            },

            phonenumber: {
                required: true,
                numbersOnly: true,
                length: true,
            },

            sec_phonenumber: {
                numbersOnly: true,
                length: true,
                // notEqualTo: "#phonenumber"
            },
            emailaddress: {
                required: true,
                email: true,
            },

            address1: {
                required: true,

            },
            address2: {
                required: true,

            },

            flatunitnumber: {
                required: true,

            },
        },
        messages: {
            fullname: {
                required: "Full name is required !", // Custom error message
                lettersOnly: "Only alphabetic characters are allowed !",
            },

            coapplicantname: {
                // required: "Coapplicant name is required !",
                lettersOnly: "Only alphabetic characters are allowed !",
            },
            phonenumber: {
                required: "Phone number is required !",
                numbersOnly: "Only numeric characters are allowed .",
                length: "Phone number should have exactly 10 digits.",
            },
            sec_phonenumber: {
                numbersOnly: "Only numeric characters are allowed .",
                length: "Secondary phone number should have exactly 10 digits.",
                // notEqualTo: "The secondary phone number must be different from the main phone number"
            },
            emailaddress: {
                required: "Email address is required !",
                email: "Invalid email address please enter a valid email address"
            },
            address1: {
                required: " Address1  is required !",

            },
            address2: {
                required: " Address2  is required !",

            },
            flatunitnumber: {
                required: " Flat unit number is required !",

            },
        },
    });

    $('#index2MyForm').validate({
        errorElement: 'div',  // Use <div> for error messages
        errorClass: 'error-message',
        rules: {
            firstname: {
                required: true, // This rule ensures the field is not empty
                lettersOnly: true, // Custom rule for alphabetic characters
            },
            lastname: {
                required: true,
                lettersOnly: true,
            },
            coapplicantname: {
                lettersOnly: true,
            },

            phonenumber: {
                required: true,
                numbersOnly: true,
                length: true,
            },

            sec_phonenumber: {
                numbersOnly: true,
                length: true,

            },
            emailaddress: {
                required: true,
                email: true,
            },

            address1: {
                required: true,

            },
            address2: {
                required: true,

            },

            flatunitnumber: {
                required: true,

            },
            password: {
                required: true,
                strong: true,

            },
            c_password: {
                required: true,
                equalTo: '#password',

            }
        },
        messages: {
            firstname: {
                required: "First name is required !", // Custom error message
                lettersOnly: "Only alphabetic characters are allowed !",
            },
            lastname: {
                required: "Last name is required !", // Custom error message
                lettersOnly: "Only alphabetic characters are allowed !",
            },

            coapplicantname: {
                // required: "Coapplicant name is required !",
                lettersOnly: "Only alphabetic characters are allowed !",
            },
            phonenumber: {
                required: "Phone number is required !",
                numbersOnly: "Only numeric characters are allowed .",
                length: "Phone number should have exactly 10 digits.",
            },
            sec_phonenumber: {
                numbersOnly: "Only numeric characters are allowed .",
                length: "Secondary phone number should have exactly 10 digits.",
                // notEqualTo: "The secondary phone number must be different from the main phone number"
            },
            emailaddress: {
                required: "Email address is required !",
                email: "Invalid email address please enter a valid email address"
            },
            address1: {
                required: " Address1  is required !",

            },
            address2: {
                required: " Address2  is required !",

            },
            flatunitnumber: {
                required: " Flat unit number is required !",

            },

            password: {
                required: " Password  is required       !",
                strong: "Please enter a strong password",

            },
            c_password: {
                required: " Confirm Password  is required !",
                equalTo: "Confirm password is not matching",

            }
        },
    });

    $('#updateAllotteeDetailsForm').validate({
        errorElement: 'div',  // Use <div> for error messages
        errorClass: 'error-message',
        rules: {
            firstname: {

                lettersOnly: true, // Custom rule for alphabetic characters
            },
            lastname: {

                lettersOnly: true,
            },
            coapplicantname: {
                lettersOnly: true,
            },
            phonenumber: {
                numbersOnly: true,
                length: true,
            },
            sec_phonenumber: {
                numbersOnly: true,
                length: true,
            }


        },
        messages: {
            firstname: {

                lettersOnly: "Only alphabetic characters are allowed !",
            },
            lastname: {

                lettersOnly: "Only alphabetic characters are allowed !",
            },
            coapplicantname: {
                lettersOnly: "Only alphabetic characters are allowed !",
            },
            phonenumber: {
                numbersOnly: "Only numeric characters are allowed .",
                length: "Phone number should have exactly 10 digits.",
            },
            sec_phonenumber: {
                numbersOnly: "Only numeric characters are allowed .",
                length: "Phone number should have exactly 10 digits.",
            },

        },
    });

    $('#login_page_Form').validate({
        errorElement: 'div',  // Use <div> for error messages
        errorClass: 'error-message',
        rules: {
            phonenumber: {
                numbersOnly: true,
                length: true,
            },
            password: {
                strong: true,
            }
        },
        messages: {
            phonenumber: {
                numbersOnly: "Only numeric characters are allowed .",
                length: "Phone number should have exactly 10 digits.",
            },
            password: {
                strong: "Please enter a strong password",
            },
        },

    });



    $.validator.addMethod("lettersOnly", function (value, element) {
        return this.optional(element) || /^[a-zA-Z]+$/.test(value);
    }, "Only alphabetic characters are allowed");
    $.validator.addMethod("length", function (value, element) {
        return this.optional(element) || value.length === 10;
    }, "Main contact number should have exactly 10 digits.");

    $.validator.addMethod("numbersOnly", function (value, element) {
        // Check if the field is empty, and if so, consider it valid
        if (value === "") {
            return true;
        }

        // Check if the value contains only numeric characters
        return /^\d+$/.test(value);
    }, "Only numeric characters are allowed in the Phone number.");

    $.validator.addMethod("strong", function (value, element) {
        return this.optional(element) || /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@#$%^&+=!]).{8,}$/.test(value);
    }, "Please enter a strong password ");

});//end of jQuery code