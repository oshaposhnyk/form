function validateField(field, errorMessage) {
    var fieldValue = field.val();

    if (fieldValue == '') {
        field.addClass('is-invalid');
        field.next('.invalid-feedback').text(errorMessage);
        return false;
    }

    if (field.attr('name') == 'email' && !isValidEmail(fieldValue)) {
        field.addClass('is-invalid');
        field.next('.invalid-feedback').text('Please enter a valid email.');
        return false;
    }

    if (field.attr('name') == 'password' && fieldValue.length < 6) {
        field.addClass('is-invalid');
        field.next('.invalid-feedback').text('Password must be at least 6 characters.');
        return false;
    }

    if (field.attr('name') == 'passwordConfirm' && fieldValue != $('input[name="password"]').val()) {
        field.addClass('is-invalid');
        field.next('.invalid-feedback').text('Passwords do not match.');
        return false;
    }

    field.removeClass('is-invalid');
    field.next('.invalid-feedback').empty();

    return true;
}

$(function() {
    $('input[name="firstname"], input[name="lastname"], input[name="email"], input[name="password"], input[name="passwordConfirm"]').blur(function() {
        validateField($(this), $(this).attr('placeholder') + ' is required.');
    });

    $('form').submit(function(event) {
        event.preventDefault();

        var firstname = $('input[name="firstname"]').val();
        var lastname = $('input[name="lastname"]').val();
        var email = $('input[name="email"]').val();
        var password = $('input[name="password"]').val();
        var passwordConfirm = $('input[name="passwordConfirm"]').val();

        var errors = [];

        if (!validateField($('input[name="firstname"]'), 'Please enter your first name.')) {
            errors.push('firstname');
        }

        if (!validateField($('input[name="lastname"]'), 'Please enter your last name.')) {
            errors.push('lastname');
        }

        if (!validateField($('input[name="email"]'), 'Please enter your email.')) {
            errors.push('email');
        }

        if (!validateField($('input[name="password"]'), 'Please enter a password.')) {
            errors.push('password');
        }

        if (!validateField($('input[name="passwordConfirm"]'), 'Please confirm your password.')) {
            errors.push('passwordConfirm');
        }

        if (errors.length > 0) {
            return false;
        }

        $.ajax({
            url: '/register',
            type: 'POST',
            data: {
                firstname: firstname,
                lastname: lastname,
                email: email,
                password: password,
                passwordConfirm: passwordConfirm
            },
            success: function(data) {
                data = JSON.parse(data);
                processResponse(data);
            }
        });
    });
});

function processResponse(response) {
    if (response.status == 'ok') {
        $('.registration-form').hide();
        $('h1').hide();
        $('.alert').removeAttr('hidden').find('p').text(response.message);
    } else if (response.status == 'error') {
        if (response.errors) {
            for (var fieldName in response.errors) {
                var errorMessage = response.errors[fieldName][0];
                var $field = $('input[name="'+fieldName+'"]');
                $field.addClass('is-invalid');
                $field.next('.invalid-feedback').text(errorMessage);
            }
        } else {
            location.reload();
        }
    }
}

function isValidEmail(email) {
    var pattern = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
    return pattern.test(email);
}
