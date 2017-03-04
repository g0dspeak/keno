var FormHelper = {
    combinationClass: '.combination',
    keyBoardCombinationClass: '.combination-keyboard',

    pleaseWait: $('#pleaseWaitDialog'),

    keyBoardCombination: [],
    money_rate: null,
    games_amount: null,
    max_win: null,
    play_until: null,

    provideMaxWinOptions: function () {
        var amountOfNumbers = FormHelper.keyBoardCombination.length;

        var winsOptions = WinsTable[amountOfNumbers];

        $('#max_win_options').html(promptOption);
        for (var o in winsOptions) {
            var optionVal = o + '/' + amountOfNumbers;
            var winVal = winsOptions[o];
            var option = '<option value="' + optionVal + '">' + optionVal + ' (x' + winVal + ')</option>';

            $('#max_win_options').append(option);
        }
    },
    enableDisableMaxWin: function () {
        var Form = $("[data-form-container]");

        if (FormHelper.keyBoardCombination.length < 1) {
            $('#max_win').attr('disabled', true);
            $('#games_amount').click();

            FormHelper.validateForm(Form);
        } else {
            $('#max_win').removeAttr('disabled');

            FormHelper.provideMaxWinOptions();

            FormHelper.validateForm(Form);
        }
    },
    enableDisableKeyboard: function () {
        if (FormHelper.keyBoardCombination.length == 10) {
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
            var number;
            for (var index in FormHelper.keyBoardCombination) {
                number = FormHelper.keyBoardCombination[index];
                $(FormHelper.combinationClass).append("<b>" + number + "</b>&nbsp;");
            }
        }
    },
    submitForm: function (e, target) {
        e.preventDefault();
        var Form = target.closest("[data-form-container]");

        var formIsValid = FormHelper.validateForm(Form);

        if (formIsValid) {
            FormHelper.countResultsAjax();
        }
    },
    countResultsAjax: function () {
        var formData = {
            _token: _token,
            combination: FormHelper.keyBoardCombination,
            money_rate: FormHelper.money_rate,
            games_amount: FormHelper.games_amount,
            max_win: FormHelper.max_win,
            play_until: FormHelper.play_until
        };

        var pleaseWait = $('#pleaseWaitDialog');

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

                    var results = JSON.parse(data);

                    FormHelper.showResults(results);

                }, 500);
            },
            async: true
        });

    },
    showResults: function (results) {
        var winsTable = $('.wins_table');
        var winsResult = $('.wins_result');
        var winsCell = $('.win_cell');

        winsResult.find('.total_spent').text(results.totalSpent + ' $');
        winsResult.find('.total_win').text(results.totalWin + ' $');
        winsResult.find('.total_profit').text(results.totalProfit + ' $');

        winsCell.text('');

        var amountOfChosenNumbers = results.userData.countOfUserNumbers;
        var winIntersects = results.winIntersects;

        for (var a in winIntersects) {
            $('#win_cell_' + amountOfChosenNumbers + '_' + a).text(winIntersects[a].length);
        }
    },
    progressWhileAjax: function () {
        var xhr = new window.XMLHttpRequest();

        // Download progress
        xhr.addEventListener("progress", function(evt) {
            if (evt.lengthComputable) {
                var percentComplete = evt.loaded / evt.total * 100;
                if (percentComplete == 100) {
                    percentComplete = 92;
                }
                progressBar(percentComplete);
            }
        }, false);
        xhr.upload.addEventListener("progress", function(evt) {
            if (evt.lengthComputable) {
                var percentComplete = evt.loaded / evt.total * 100;
                if (percentComplete == 100) {
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
        var pleaseWait = $('#pleaseWaitDialog');

        if (enable) {
            pleaseWait.modal('show');
        } else {
            pleaseWait.modal('hide');
        }
    },
    validateForm: function (target) {
        var error = 0;
        target.find(".req-input input, .req-input select, .combination-keyboard").each(function(){
            var type = $(this).attr("type");

            var Parent = $(this).parent();
            var val = $(this).val();
            var minLength;
            var numberIsValid;
            var selectedVal;

            switch(type) {
                case "text":
                    minLength = $(this).data("min-length");
                    if (typeof minLength == "undefined") {
                        minLength = 0;
                    }

                    numberIsValid = numberValid(val, $(this).attr('min'), $(this).attr('max'), minLength, null);

                    error += FormHelper.setRow(numberIsValid, Parent);
                    break;
                case "number":
                    minLength = $(this).data("min-length");
                    if (typeof minLength == "undefined") {
                        minLength = 0;
                    }

                    numberIsValid = numberValid(val, $(this).attr('min'), $(this).attr('max'), minLength, null);

                    error += FormHelper.setRow(numberIsValid, Parent);
                    break;
                case "select":
                    selectedVal = !$('#max_win').is(':checked') || null != $(this).val();
                    error += FormHelper.setRow(selectedVal, Parent);
                    break;
                case "keyboard":
                    var countOfCheckedElements = $('.combination-keyboard input:checkbox:checked').length;
                    if (countOfCheckedElements < 1) {
                        $(FormHelper.keyBoardCombinationClass).addClass('error');
                        error++;
                    } else {
                        $(FormHelper.keyBoardCombinationClass).removeClass('error');
                    }
                    break;
            }
        });

        var formContainer = target;
        if (error == 0) {
            formContainer.css("background", "#C8E6C9");
            formContainer.css("color", "#2E7D32");

            FormHelper.money_rate = $('.money_rate input').val();
            FormHelper.games_amount = $('.games_amount input').val();
            FormHelper.max_win = $('#max_win_options').val();
            FormHelper.play_until = $('.play_until input:checked').val();

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
