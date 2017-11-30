/**********************************************/
// * FUNCTIONS : patient_photoalbum.js
// * DETAILS   : FOR PHOTO  MODULE 
// * AUTHOR    : NAMRATA DUBEY
/*********************************************/

$(document).ready(function(){

$(".PatientCreateAlbum").click(function () {

    var patient_id = $('#Patientid').val();
    var requesturl = $('#createalbumurl').val();
    
    $.ajax({
        type: "POST",
        url: requesturl,
        data: {
            patient_id: patient_id,
        },
        dataType: "json",
        success: function (data) {
           console.log(data)
            if (data.result) {
                window.location = $("#createalbumur2").val() + '/' + data.result.id;
            } else {
                
            }
        },
        error: function () {
        }
    });    
});


});




