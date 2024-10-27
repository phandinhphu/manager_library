function Validator(formSelector) {
    var _this = this;
    var formRules = {};

    var validatorRules = {
        required: function(value) {
            return value ? undefined : 'Vui lòng nhập trường này';
        },
        email: function(value) {
            var regex = /[A-Z0-9._%+-]+@[A-Z0-9-]+.+.[A-Z]{2,4}/igm;
            return regex.test(value) ? undefined : 'Trường này phải là email';
        },
        phone: function(value) {
            var regex = /^(0)[0-9]{9}$/;
            return regex.test(value) ? undefined : 'Trường này phải là số điện thoại';
        },
        confirmation: function(value) {
            return value === $(formSelector).querySelector('#password').value ? undefined : 'Mật khẩu không khớp';
        },
        min: function(min) {
            return function(value) {
                return value.length >= min ? undefined : `Vui lòng nhập ít nhất ${min} ký tự`;
            }
        }
    };

    var formElement = $(formSelector);

    if (formElement) {
        var inputs = formElement.querySelectorAll('[name][rules]');

        for (var input of inputs) {
            var rules = input.getAttribute('rules').split('|');

            for (var rule of rules) {
                if (rule.includes(':')) {
                    var ruleInfo = rule.split(':');
                    var ruleFunc = validatorRules[ruleInfo[0]](ruleInfo[1]);
                } else {
                    var ruleFunc = validatorRules[rule];
                }

                if (Array.isArray(formRules[input.name])) {
                    formRules[input.name].push(ruleFunc);
                } else {
                    formRules[input.name] = [ruleFunc];
                }
            }

            input.onblur = handleValidate;
            input.oninput = handleClearError;
        }

        function handleValidate(event) {
            var rules = formRules[event.target.name];
            var errorMessage;

            for (var rule of rules) {
                switch (event.target.type) {
                    case 'radio':
                    case 'checkbox':
                        errorMessage = rule(formElement.querySelector(`[name="${event.target.name}"]:checked`)
                            ? formElement.querySelector(`[name="${event.target.name}"]:checked`).value
                            : '');
                        break;
                    default:
                        errorMessage = rule(event.target.value);
                }

                if (errorMessage) break;
            }

            if (errorMessage) {
                var formGroup = event.target.closest('.form__auth-group');
                if (formGroup) {
                    formGroup.classList.add('invalid');
                    var errorElement = formGroup.querySelector('.form__auth-message');
                    if (errorElement) {
                        errorElement.innerText = errorMessage;
                    }
                }
            } else {
                var formGroup = event.target.closest('.form__auth-group');
                if (formGroup) {
                    formGroup.classList.remove('invalid');
                    var errorElement = formGroup.querySelector('.form__auth-message');
                    if (errorElement) {
                        errorElement.innerText = '';
                    }
                }
            }

            return !errorMessage;
        }

        function handleClearError(event) {
            var formGroup = event.target.closest('.form__auth-group');
            if (formGroup.classList.contains('invalid')) {
                formGroup.classList.remove('invalid');
                var errorElement = formGroup.querySelector('.form__auth-message');
                if (errorElement) {
                    errorElement.innerText = '';
                }
            }
        }
    }

    formElement.onsubmit = function(event) {
        event.preventDefault();

        var inputs = formElement.querySelectorAll('[name][rules]');
        var isValid = true;

        for (var input of inputs) {
            if (!handleValidate({ target: input })) {
                isValid = false;
            }
        }

        if (isValid) {
            if (typeof _this.onSubmit === 'function') {
                var enableInputs = formElement.querySelectorAll('[name]');

                var formValues = Array.from(enableInputs).reduce((values, input) => {
                    switch (input.type) {
                        case 'radio':
                            if (input.checked) {
                                values[input.name] = input.value;
                            }
                            break;
                        case 'checkbox':
                            if (!Array.isArray(values[input.name])) {
                                values[input.name] = [];
                            }

                            if (input.checked) {
                                values[input.name].push(input.value);
                            }
                            break;
                        case 'file':
                            values[input.name] = input.files;
                            break;
                        default:
                            values[input.name] = input.value;
                    }
                    return values;
                }, {});

                _this.onSubmit(formValues);
            } else {
                formElement.submit();
            }
        }
    }
}