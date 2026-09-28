document.addEventListener('DOMContentLoaded', function () {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    const phoneRegex = /^[0-9+\-\s]{7,15}$/;

    function setupValidation(formId, submitButtonId, validators, errorMessages, submitLabel) {
        const form = document.getElementById(formId);
        const submitButton = submitButtonId ? document.getElementById(submitButtonId) : null;

        if (!form) {
            return;
        }

        function showError(fieldName, message) {
            const input = form.querySelector('[name="' + fieldName + '"]');
            const errorElement = form.querySelector('[data-error-for="' + fieldName + '"]');

            if (input) {
                input.setAttribute('aria-invalid', 'true');
                input.style.borderColor = '#dd1c77';
            }

            if (errorElement) {
                errorElement.textContent = message;
                errorElement.style.display = 'block';
            }
        }

        function clearError(fieldName) {
            const input = form.querySelector('[name="' + fieldName + '"]');
            const errorElement = form.querySelector('[data-error-for="' + fieldName + '"]');

            if (input) {
                input.setAttribute('aria-invalid', 'false');
                input.style.borderColor = '#d1d5db';
            }

            if (errorElement) {
                errorElement.textContent = '';
                errorElement.style.display = 'none';
            }
        }

        function validateField(fieldName) {
            const input = form.querySelector('[name="' + fieldName + '"]');
            if (!input) return true;

            const value = input.value;
            const valid = validators[fieldName] ? validators[fieldName](value) : true;

            if (valid) {
                clearError(fieldName);
                return true;
            }

            showError(fieldName, errorMessages[fieldName]);
            return false;
        }

        Object.keys(validators).forEach(function (fieldName) {
            const input = form.querySelector('[name="' + fieldName + '"]');
            if (!input) return;

            input.addEventListener('input', function () {
                validateField(fieldName);
            });
        });

        form.addEventListener('submit', function (event) {
            event.preventDefault();

            let isValid = true;
            Object.keys(validators).forEach(function (fieldName) {
                if (!validateField(fieldName)) {
                    isValid = false;
                }
            });

            if (!isValid) {
                return;
            }

            if (submitButton) {
                submitButton.disabled = true;
                submitButton.textContent = submitLabel || 'Submitting...';
            }

            form.submit();
        });
    }

    setupValidation('registerForm', 'registerButton', {
        customer_name: function (value) {
            return value.trim().length >= 2;
        },
        customer_email: function (value) {
            return emailRegex.test(value.trim());
        },
        customer_pass: function (value) {
            return /^(?=.*\d).{8,}$/.test(value);
        },
        customer_confirm_pass: function (value) {
            const password = document.getElementById('customer_pass');
            return password && value !== '' && value === password.value;
        },
        customer_country: function (value) {
            return value.trim() !== '';
        },
        customer_city: function (value) {
            return value.trim().length >= 2;
        },
        customer_contact: function (value) {
            return phoneRegex.test(value.trim());
        },
    }, {
        customer_name: 'Full name is required.',
        customer_email: 'Please enter a valid email address.',
        customer_pass: 'Password must be at least 8 characters and contain a number.',
        customer_confirm_pass: 'Passwords do not match.',
        customer_country: 'Please select a country.',
        customer_city: 'City is required.',
        customer_contact: 'Please enter a valid phone number.'
    }, 'Registering...');

    const registrationPassword = document.getElementById('customer_pass');
    const registrationConfirmation = document.getElementById('customer_confirm_pass');
    if (registrationPassword && registrationConfirmation) {
        registrationPassword.addEventListener('input', function () {
            if (registrationConfirmation.value !== '') {
                registrationConfirmation.dispatchEvent(new Event('input', { bubbles: true }));
            }
        });
    }

    setupValidation('loginForm', null, {
        customer_email: function (value) {
            return emailRegex.test(value.trim());
        },
        customer_pass: function (value) {
            return value.trim() !== '';
        }
    }, {
        customer_email: 'Please enter a valid email address.',
        customer_pass: 'Password is required.'
    }, 'Logging in...');
});
