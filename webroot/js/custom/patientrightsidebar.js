/**********************************************
 // * FUNCTIONS : patientrightsidebar.js
 // * DETAILS   : FOR UPDATING THE STATUS OF PATIENT RIGHT SIDE BAR
 // * AUTHOR    : NAMRATA DUBEY
 *********************************************/

$(document).ready(function () {


    $('input[name="rxplanstatus"]').on('ifClicked', function (event) {
        if ($(this).is(':checked')) {
            var status = '0';
        } else {
            var status = '1';
        }

        var id = $(this).attr('data-id');
        var requesturl = $('#rxstatusurl').val();

        $.ajax({
            type: "POST",
            url: requesturl,
            data: {
                rxplandetailid: id,
                status: status,
            },
            dataType: "json",
            success: function (data) {
                if (data.result) {
                    if(data.result == '1') {
                        $("#rxstatus-" + id).addClass("rxplandetailstatus");
                    } else {
                        $("#rxstatus-" + id).removeClass("rxplandetailstatus");
                    }
                } else {
                }
            },
            error: function () {
            }
        });
    });

});









