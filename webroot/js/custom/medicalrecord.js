/**********************************************/
// * FUNCTIONS : medicalrecord.js
// * DETAILS   : FOR MEDICAL RECORD MODULE 
// * AUTHOR    : NAMRATA DUBEY
/*********************************************/

$(document).ready(function () {
    $('#select-complaints').selectize({
        delimiter: ',',
        persist: false,
        create: true
    });

    $('#select-medicalhistory').selectize({
        delimiter: ',',
        persist: false,
        create: true
    });


    //patient_complaint
    //patient_medicalhistory

    //code for fetching the complaint details

    $('.patient_complaint').change(function () {

        var complaint = $('.patient_complaint option:selected').val();
        var requesturl1 = $('#requesturl1').val();
        
        $.ajax({
            type: "POST",
            url: requesturl1,
            data: {
                complaint_id: complaint,
            },
            dataType: "json",
            success: function (data) {
                if (data.result) {
                    $('#complaintdetail').val($('#complaintdetail').val() + " " + data.result); // to append the value in textarea
                    
                } else {

                }
            },
            error: function () {
            }
        });

    });
    
    //code for fetchinh the mdeical history details
    
    $('.patient_medicalhistory').change(function () {

        var medicalhistory = $('.patient_medicalhistory option:selected').val();
        var requesturl2 = $('#requesturl2').val();
      
        $.ajax({
            type: "POST",
            url: requesturl2,
            data: {
                medicalhistory_id: medicalhistory,
            },
            dataType: "json",
            success: function (data) {
                if (data.result) {
                   $('#medicalhistorydetail').val($('#medicalhistorydetail').val() + " " + data.result); // to append the value in textarea
                    //$('#medicalhistorydetail').append(data.result + " ");
                //#medicalhistorydetail

                } else {

                }
            },
            error: function () {
            }
        });

    });



});



