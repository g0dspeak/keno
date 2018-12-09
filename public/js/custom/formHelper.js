let FormHelper = {
    combinationClass: '.combination',
    keyBoardCombinationClass: '.combination-keyboard',
    customCombinationId: '#custom_combination',

    pleaseWait: $('#pleaseWaitDialog'),

    keyBoardCombination: [],
    money_rate: null,
    games_amount: null,
    max_win: null,
    max_win_money: null,
    play_until: null,
    amount_of_random_numbers: null,
    custom_combination: null,

    initCustomCombinationCheckbox: function (checked) {
        FormHelper.enableDisableMaxWin();
        FormHelper.enableDisableKeyboard();

        if (!checked) {
            $('#amount_of_random_numbers_block').show();
            $(FormHelper.keyBoardCombinationClass).hide();
            $(FormHelper.combinationClass).hide();
        } else {
            $('#amount_of_random_numbers_block').hide();
            $(FormHelper.keyBoardCombinationClass).show();
            $(FormHelper.combinationClass).show();
        }
    },
    provideMaxWinOptions: function () {
        let amountOfNumbers = !$(FormHelper.customCombinationId).prop('checked')
            ? $('#amount_of_random_numbers').val()
            : FormHelper.keyBoardCombination.length;

        let winsOptions = WinsTable[amountOfNumbers];

        $('#max_win_options').html(promptOption);
        for (let o in winsOptions) {
            let optionVal = o + '/' + amountOfNumbers;
            let winVal = winsOptions[o];
            let option = '<option value="' + optionVal + '">' + optionVal + ' (x' + winVal + ')</option>';

            $('#max_win_options').append(option);
        }
    },
    enableDisableMaxWin: function () {
        let Form = $("[data-form-container]");

        if ($(FormHelper.customCombinationId).prop('checked') && FormHelper.keyBoardCombination.length < 1) {
            $('#max_win').attr('disabled', true);
            $('#max_win_money').attr('disabled', true);
            $('#games_amount').click();

            FormHelper.validateForm(Form);
        } else {
            $('#max_win').removeAttr('disabled');
            $('#max_win_money').removeAttr('disabled');

            FormHelper.provideMaxWinOptions();

            FormHelper.validateForm(Form);
        }
    },
    enableDisableKeyboard: function () {
        if (FormHelper.keyBoardCombination.length === 10 || !$(FormHelper.customCombinationId).prop('checked')) {
            $(FormHelper.keyBoardCombinationClass + ' input[type="checkbox"]').each(function () {
                if (!$(this).is(':checked')) {
                    $(this).attr('disabled', true);
                }
            });
        } else {
            $(FormHelper.keyBoardCombinationClass + ' input[type="checkbox"]').each(function () {
                if (!$(this).is(':checked')) {
                    $(this).removeAttr('disabled');
                }
            });
        }
    },
    resetForm: function (container) {
        container.find(".valid, .invalid").removeClass("valid invalid");
        container.css("background", "");
        container.css("color", "");

        $(FormHelper.keyBoardCombinationClass + ' input[type="checkbox"]').prop('checked', false).removeAttr('disabled');

        FormHelper.keyBoardCombination = [];

        $(FormHelper.combinationClass).html('');

        FormHelper.enableDisableKeyboard();

        $(FormHelper.keyBoardCombinationClass).removeClass('error');

        $('input[type="number"]').each(function () {
            $(this).val($(this).attr('min'));
        });

    },
    watchForCombination: function () {
        $(FormHelper.combinationClass).html('');
        if (FormHelper.keyBoardCombination.length) {
            let number;
            for (let index in FormHelper.keyBoardCombination) {
                number = FormHelper.keyBoardCombination[index];
                $(FormHelper.combinationClass).append("<b>" + number + "</b>&nbsp;");
            }
        }

        FormHelper.amount_of_random_numbers = $('#amount_of_random_numbers').val();
    },
    submitForm: function (e, target) {
        e.preventDefault();
        let Form = target.closest("[data-form-container]");

        let formIsValid = FormHelper.validateForm(Form);

        if (formIsValid) {
            FormHelper.countResultsAjax();
        }
    },
    countResultsAjax: function () {
        let formData = {
            _token: _token,
            combination: FormHelper.keyBoardCombination,
            money_rate: FormHelper.money_rate,
            games_amount: FormHelper.games_amount,
            max_win: FormHelper.max_win,
            max_win_money: FormHelper.max_win_money,
            play_until: FormHelper.play_until,
            amount_of_random_numbers: FormHelper.amount_of_random_numbers,
            custom_combination: FormHelper.custom_combination
        };

        let pleaseWait = $('#pleaseWaitDialog');

        FormHelper.switchFormActivation(false);
        pleaseWait.modal('show');

        $.ajax({
            xhr: function() {
                return FormHelper.progressWhileAjax();
            },
            url: '/keno/count_results',
            method: 'post',
            data: formData,
            success: function(data) {
                progressBar(100);

                setTimeout(function () {
                    FormHelper.switchFormActivation(true);
                    FormHelper.progressBar(false);
                    progressBar(0);

                    FormHelper.showResults(data);

                }, 500);
            },
            async: true
        });

    },
    showResults: function (results) {
        let winsTable = $('.wins_table');
        let winsResult = $('.wins_result');
        let winsCell = $('.win_cell');

        winsResult.find('.total_spent').text(results.totalSpent + ' $');
        winsResult.find('.total_win').text(results.totalWin + ' $');
        winsResult.find('.total_profit').text(results.totalProfit + ' $');

        winsCell.text('');

        let amountOfChosenNumbers = results.userData.countOfUserNumbers;
        let winIntersects = results.winIntersects;

        for (let a in winIntersects) {
            $('#win_cell_' + amountOfChosenNumbers + '_' + a).text(winIntersects[a].length);
        }

        if (results.timeoutError) {
            $('.error-info-helper-block').show();
        } else {
            $('.error-info-helper-block').hide();
        }
    },
    progressWhileAjax: function () {
        let xhr = new window.XMLHttpRequest();

        // Download progress
        xhr.addEventListener("progress", function(evt) {
            if (evt.lengthComputable) {
                let percentComplete = evt.loaded / evt.total * 100;
                if (percentComplete === 100) {
                    percentComplete = 92;
                }
                progressBar(percentComplete);
            }
        }, false);
        xhr.upload.addEventListener("progress", function(evt) {
            if (evt.lengthComputable) {
                let percentComplete = evt.loaded / evt.total * 100;
                if (percentComplete === 100) {
                    percentComplete = 92;
                }
                progressBar(percentComplete);
            }
        }, false);

        return xhr;
    },
    switchFormActivation: function (enable) {
        if (enable) {
            $('.form-container').css('opacity', 1);
            $('.form-container input, .form-container select, .form-container button').prop('disabled', false);
        } else {
            $('.form-container').css('opacity', 0.5);
            $('.form-container input, .form-container select, .form-container button').attr('disabled', true);
        }
    },
    progressBar: function (enable) {
        let pleaseWait = $('#pleaseWaitDialog');

        if (enable) {
            pleaseWait.modal('show');
        } else {
            pleaseWait.modal('hide');
        }
    },
    validateForm: function (target) {
        let error = 0;
        target.find(".req-input input, .req-input select, .combination-keyboard").each(function(){
            let type = $(this).attr("type");

            let Parent = $(this).parent();
            let val = $(this).val();
            let minLength;
            let numberIsValid;
            let selectedVal;

            switch(type) {
                case "text":
                case "number":
                    minLength = $(this).data("min-length");
                    if (typeof minLength === "undefined") {
                        minLength = 0;
                    }

                    numberIsValid = numberValid(val, $(this).attr('min'), $(this).attr('max'), minLength, null);

                    console.log(val, numberIsValid);

                    error += FormHelper.setRow(numberIsValid, Parent);
                    break;
                case "select":
                    selectedVal = !$('#max_win').is(':checked') || null != $(this).val();
                    error += FormHelper.setRow(selectedVal, Parent);
                    break;
                case "keyboard":
                    if ($(FormHelper.customCombinationId).prop('checked')) {
                        let countOfCheckedElements = $('.combination-keyboard input:checkbox:checked').length;
                        if (countOfCheckedElements < 1) {
                            $(FormHelper.keyBoardCombinationClass).addClass('error');
                            error++;
                        } else {
                            $(FormHelper.keyBoardCombinationClass).removeClass('error');
                        }
                    }
                    break;
            }
        });

        let formContainer = target;
        if (error === 0) {
            formContainer.css("background", "#C8E6C9");
            formContainer.css("color", "#2E7D32");

            FormHelper.money_rate = $('.money_rate input').val();
            FormHelper.games_amount = $('.games_amount input').val();
            FormHelper.max_win = $('#max_win_options').val();
            FormHelper.max_win_money = $('[name="max_win_money"]').val();
            FormHelper.play_until = $('.play_until input:checked').val();
            FormHelper.amount_of_random_numbers = $('#amount_of_random_numbers').val();
            FormHelper.custom_combination = $(FormHelper.customCombinationId).prop('checked') ? 1 : 0;

            $('.submit-form').prop('disabled', false);

            return true;
        } else {
            formContainer.css("background", "#FFCDD2");
            formContainer.css("color", "#C62828");

            $('.submit-form').prop('disabled', true);
            
            return false;
        }
    },
    setRow: function (valid, Parent) {
        if (valid) {
            Parent.addClass("valid");
            Parent.removeClass("invalid");

            return 0;
        }

        Parent.addClass("invalid");
        Parent.removeClass("valid");

        return 1;
    }
};
