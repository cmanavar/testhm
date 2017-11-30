/**********************************************
// * FUNCTIONS : showfield.js
// * DETAILS   : FOR ADDSPECIALCASE MODULE AND SPECIALCASE MODULE 
// * AUTHOR    : NAMRATA DUBEY
*********************************************/

$(document).ready(function(){
    
///CODE FOR FETCHING THE DETAILS OF PATIENT IN SPECIAL CASE 

    $('select[name=pt_id]').change(function () {
        
            var patient = $('#select-patient-specialcase option:selected').val();
            var requesturl = $('#requesturl').val();
            $.ajax({
                type: "POST",
                url: requesturl,
                data: {
                    patient_id: patient,
                },
                dataType: "json",
                success: function (data) {
                    if (data.result) {
                        var patientdetail = '#' + data.result.unique_no + " " + data.result.firstname + " " +  data.result.lastname + " " +  data.result.age + " " + data.result.sex; ;
                        $('#patientdetail').html(patientdetail);
                        $('#fetcheddetails').hide();
                        $("#specialcase_title").removeAttr('disabled');
                        $("#specialcase_description").removeAttr('disabled');
                        $('#specialcase_title').val(data.result.case_title);
                        $('#specialcase_description').val(data.result.case_description);
                        
                    } else {

                    }
                },
                error: function () {
                }
            });
        
    });


  
$('#myTab a').click(function(e) {
  e.preventDefault();
  $(this).tab('show');
});

// store the currently selected tab in the hash value
$("ul.nav-tabs > li > a").on("shown.bs.tab", function(e) {
  var id = $(e.target).attr("href").substr(1);
  window.location.hash = id;
});

// on load of the page: switch to the currently selected tab
var hash = window.location.hash;
$('#myTab a[href="' + hash + '"]').tab('show');




});









