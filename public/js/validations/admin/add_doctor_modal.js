document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("form");
    form.addEventListener("submit", function (event) {
        event.preventDefault();
        if (validateForm()) {
            this.submit();
        }
    });

    function validateForm() {
        let isValid = true;
        const nom = document.getElementById("nom_input").value.trim();
        const birthday = document.getElementById("birthday_input").value;
        const city = document.getElementById("city_input").value.trim();
        const rue = document.getElementById("rue_input").value.trim();
        const email = document.getElementById("email_input").value.trim();
        const password = document.getElementById("password_input").value.trim();
        const phone = document.getElementById("phone_input").value.trim();
        const gender = document.getElementById("gender_input").value;
        const degree = document.getElementById("degree_input").value;
        const speciality = document.getElementById("speciality_input").value;

        // Reset error messages
        document
            .querySelectorAll(".error_input_span")
            .forEach(function (errorSpan) {
                errorSpan.innerText = "";
            });

        // Name validation
        if (nom === "") {
            isValid = false;
            document.getElementById("nom_error").innerText = "Name is required";
        } else if (nom.length < 3) {
            isValid = false;
            document.getElementById("nom_error").innerText =
                "Name must be at least 3 characters long";
        }

        // Birthday validation
        if (birthday === "") {
            isValid = false;
            document.getElementById("birthday_error").innerText =
                "Birthday is required";
        } else {
            const birthDate = new Date(birthday);
            const today = new Date();
            const age = today.getFullYear() - birthDate.getFullYear();
            if (age < 18) {
                isValid = false;
                document.getElementById("birthday_error").innerText =
                    "Doctor must be at least 18 years old";
            }
        }

        // City validation
        if (city === "") {
            isValid = false;
            document.getElementById("city_error").innerText =
                "City is required";
        }

        // Street validation
        if (rue === "") {
            isValid = false;
            document.getElementById("rue_error").innerText =
                "Street is required";
        }

        // Email validation
        if (email === "") {
            isValid = false;
            document.getElementById("email_error").innerText =
                "Email is required";
        } else if (!isValidEmail(email)) {
            isValid = false;
            document.getElementById("email_error").innerText =
                "Please enter a valid email address";
        }

        // Password validation
        if (password === "") {
            isValid = false;
            document.getElementById("password_error").innerText =
                "Password is required";
        } else if (password.length < 8) {
            isValid = false;
            document.getElementById("password_error").innerText =
                "Password must be at least 8 characters long";
        }

        // Phone validation
        if (phone === "") {
            isValid = false;
            document.getElementById("phone_error").innerText =
                "Phone number is required";
        } else if (!isValidPhone(phone)) {
            isValid = false;
            document.getElementById("phone_error").innerText =
                "Please enter a valid phone number (06/05) XX XX XX XX";
        }

        // Gender validation
        if (gender === "") {
            isValid = false;
            document.getElementById("gender_error").innerText =
                "Gender is required";
        }

        // Degree validation
        if (degree === "") {
            isValid = false;
            document.getElementById("degree_error").innerText =
                "Degree is required";
        }

        // Speciality validation
        if (speciality === "") {
            isValid = false;
            document.getElementById("speciality_error").innerText =
                "Speciality is required";
        }

        return isValid;
    }

    function isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    function isValidPhone(phone) {
        const phoneRegex = /^(06|05)\d{8}$/;
        return phoneRegex.test(phone.replace(/\s/g, ""));
    }

    const resetButton = form.querySelector('input[type="reset"]');
    resetButton.addEventListener("click", function () {
        clearErrors();
        resetForm();
    });

    function clearErrors() {
        document
            .querySelectorAll(".error_input_span")
            .forEach(function (errorSpan) {
                errorSpan.innerText = "";
            });
    }

    function resetForm() {
        const inputs = form.querySelectorAll(
            'input[type="text"], input[type="date"], input[type="email"], input[type="password"], input[type="number"], select'
        );
        inputs.forEach(function (input) {
            if (input.tagName === "SELECT") {
                input.selectedIndex = 0;
            } else {
                input.value = "";
            }
        });
    }
});
