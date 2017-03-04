
jQuery(function($){
    var container = $(".form-container");

    FormHelper.enableDisableMaxWin();
    FormHelper.resetForm(container);

    FormHelper.switchFormActivation(true);
    FormHelper.progressBar(false);

    // tooltip
    $('[data-toggle="tooltip"]').tooltip();
    $("[data-toggle='tooltip']").on("mouseover", function(){
        if($(this).parent().hasClass("invalid")){
            $(".tooltip").addClass("tooltip-invalid").removeClass("tooltip-valid");
        } else if($(this).parent().hasClass("valid")){
            $(".tooltip").addClass("tooltip-valid").removeClass("tooltip-invalid");
        } else {
            $(".tooltip").removeClass("tooltip-invalid tooltip-valid");
        }
    });

    // submit
    $('button[type="submit"], .submit-form').on('click', function (e) {
        FormHelper.submitForm(e, $(this));
    });

    //Reset to form to the default state of the none of the fields were filled out
    $("button.reset-form").on("click", function(e) {
        FormHelper.resetForm(container);
    });


    $('#max_win').on('click', function () {
        $('.max_win').show(100);
        $('.games_amount').hide(100);
    });

    $('#games_amount').on('click', function () {
        $('.max_win').hide(100);
        $('.games_amount').show(100);
    });

    $(FormHelper.keyBoardCombinationClass + ' input[type="checkbox"]').on('click', function () {
        FormHelper.keyBoardCombination = [];

        $(FormHelper.keyBoardCombinationClass + ' input[type="checkbox"]:checked').each(function () {
            FormHelper.keyBoardCombination.push(parseInt($(this).val()));
        });

        FormHelper.keyBoardCombination.sort(function(a, b) {return a - b});

        FormHelper.watchForCombination();

        FormHelper.enableDisableMaxWin();

        FormHelper.enableDisableKeyboard();
    });

    /* validate listeners */
    $(document).on('change input', 'input', function () {
        FormHelper.validateForm(container);
    });
    /* /validate listeners */
});
