/**********************************************/
// * FUNCTIONS : prescription.js
// * DETAILS   : FOR PRESCRIPTION MODULE 
// * AUTHOR    : NAMRATA DUBEY
/*********************************************/

$(document).ready(function () {
    $('#select-instruction').selectize({

        delimiter: ',',
        persist: false,
        create: true
    });

    $('#select-prfrequency').selectize({

        delimiter: ',',
        persist: false,
        create: true
    });


    $('#select-prform').selectize({

        delimiter: ',',
        persist: false,
        create: true
    });

    $('#select-timing').selectize({

        delimiter: ',',
        persist: false,
        create: true
    });

});



$(document).on("click", ".drug-prescription", function () {

    var drugid = $(this).attr('data-id');
    var url = $(this).attr('data-link');

    $.ajax({
        type: "POST",
        url: url,
        data: {
            drug_id: drugid,

        },
        dataType: "json",
        success: function (data) {
            if (data.result) {

                // $("#fade-div").toggle("highlight");
                $("#fade-div").addClass('highlight');
                setTimeout(function () {
                    $('#fade-div').removeClass('highlight');
                }, 1000);
                var timigarray=data.result.timing.split(",");
                $('#content').val(data.result.description);
                $('#drugname').val(data.result.trade_name);
                $('#select-strength').val(data.result.strength);
                $('#select-prfrequency')[0].selectize.setValue(data.result.frequency);
                $('#nos').val(data.result.no_of_tablet);
                $('#duration').val(data.result.no_of_days);
                $('#timing').val(timigarray);
                $('#instruction').val(data.result.regular_instruction);
                $('#select-prform')[0].selectize.setValue(data.result.drug_form);
                $("#timing").multiselect("refresh");
            } else {

            }
        },
        error: function () {

        }
    });
});


$(".searchdrug").on("keyup", function () {
    var url = $(this).attr('data-url');
    var value = $('.searchdrug').val();


    $.ajax({
        type: "POST",
        url: url,
        data: {

            value: value
        },
        dataType: "json",
        success: function (data) {
            if (data.sucess) {

                if (typeof data.result == "object") {
                    var html = "";
                    for (i = 0; i < data.result.length; i++) {
                        html = html + '<li><a href="#" class="drug-prescription" data-value="' + data.result[i].id + '" data-link="/clinicsoftware/admin/prescription/showprescription" data-id="' + data.result[i].id + '">' + data.result[i].trade_name + '</a></li>';
                    }
                    if (html != "")
                    {
                        var htmls = html;
                    } else {
                        htmls = '<li>NO DRUG FOUND.</li>';
                    }
                    $('.ListDrug').find('ul').html(htmls);

                }

            } else {


            }
        },
        error: function () {

        }
    });

});



$(document).on("click", ".disease-prescription", function () {
    var diseaseid = $(this).attr('data-id');
    var url = $(this).attr('data-link');
    var caseid = $(this).attr('data-caseid');
    var prescid = $(this).attr('data-prescription');
    var destinationurl =$(".addPrescription").val();
    $.ajax({
        type: "POST",
        url: url,
        data: {
            diseaseid: diseaseid,
            caseid: caseid,
            prescid: prescid
        },
        dataType: "json",
        success: function (data) {
            if (data.result) {
                window.location.href = destinationurl+ '/' +caseid;            
            } else {

            }
        },
        error: function () {

        }
    });
});





$(".searchdisease").on("keyup", function () {

    var url = $(this).attr('data-url');
    var value = $('.searchdisease').val();


    $.ajax({
        type: "POST",
        url: url,
        data: {

            value: value
        },
        dataType: "json",
        success: function (data) {
            if (data.sucess) {

                if (typeof data.result == "object") {
                    var html = "";
                    for (i = 0; i < data.result.length; i++) {
                        html = html + '<li><a href="#" class="disease-prescription" data-value="' + data.result[i].id + '" data-link="/clinicsoftware/admin/prescription/showprescription" data-id="' + data.result[i].id + '">' + data.result[i].disease_name + '</a></li>';
                    }
                    if (html != "")
                    {
                        var htmls = html;
                    } else {
                        htmls = '<li>NO DISEASE TEMPLATE FOUND.</li>';
                    }
                    $('.ListDisease').find('ul').html(htmls);

                }

            } else {


            }
        },
        error: function () {

        }
    });

});
$(document).ready(function () {
    $('#timing').multiselect({
        enableFiltering: true,
        includeSelectAllOption: true,
        enableCaseInsensitiveFiltering: true,
        maxHeight: 400,
        dropUp: true,

    });
});

