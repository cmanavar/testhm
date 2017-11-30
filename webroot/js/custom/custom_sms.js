/**********************************************/
// * FUNCTIONS : custom_sms.js
// * DETAILS   : FOR SMS MODULE
// * AUTHOR    : NAMRATA DUBEY
// * Date      : 10-JUN-2017
/*********************************************/
$(document).ready(function () {

    $('input[name="select_type"]').on('ifClicked', function (event) {
        var smstype = $(this).val();
        if (smstype == '1') {
            $('#customize').show();
            $('#selectdr').hide();
            $('#selectpt').hide();
        } else if (smstype == '2') {
            $('#selectdr').show();
            $('#customize').hide();
            $('#selectpt').hide();
        } else {
            $('#selectpt').show();
            $('#customize').hide();
            $('#selectdr').hide();
        }


    });

     $('.selectpatients').multiselect({
        enableFiltering: true,
        includeSelectAllOption: true,
        enableCaseInsensitiveFiltering: true,
        maxHeight: 400,
        dropUp: true,
    });

}); 