//for select multiple doctor from doctors drop-down

$(document).ready(function () {

    $('#doctor').multiselect({
        enableFiltering: true,
        includeSelectAllOption: true,
        enableCaseInsensitiveFiltering: true,
        maxHeight: 400,
        dropUp: true,
    });


    $('#rdoctor').multiselect({
        enableFiltering: true,
        includeSelectAllOption: true,
        enableCaseInsensitiveFiltering: true,
        maxHeight: 400,
        dropUp: true,
    });

    $('#lab').multiselect({
        enableFiltering: true,
        includeSelectAllOption: true,
        enableCaseInsensitiveFiltering: true,
        maxHeight: 400,
        dropUp: true,
    });

    $(".medicalevalution").click(function () {
        $('.medicalevalution').show();
        $('.dscroll').hide();
        return false;
    });


    var selectize_ne1 = $(".selectreferraldoctors").multiselect({
        enableFiltering: true,
        includeSelectAllOption: true,
        enableCaseInsensitiveFiltering: true,
        maxHeight: 400,
        dropUp: true,
    });



});
