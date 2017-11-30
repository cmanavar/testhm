$(document).ready(function () {
    $.showPrescription = function (index) {
        //alert('You have successfully defined the function!'+index); 

        var requesturl = $('#requesturl').val();
        var drug = $("#select-drug-" + index).val();

        $.ajax({
            type: "POST",
            url: requesturl,
            data: {drug_id: drug, },
            dataType: "json",
            success: function (data) {

                if (data.result) {
                    var timigarray = data.result.timing.split(",");
                    
                    $('#select-prform-' + index)[0].selectize.setValue(data.result.drug_form, true);
                    $('#select-strength-' + index).val(data.result.strength);
                    $('#select-prfrequency-' + index)[0].selectize.setValue(data.result.frequency, true);
                    //$('#select-timing-'+index)[0].selectize.setValue(data.result.timing, true);
                    $('#select-timing-' + index).val(timigarray);
                    $('#nos-' + index).val(data.result.no_of_tablet);
                    $('#duration-' + index).val(data.result.no_of_days);
                    $('#select-instruction-' + index).val(data.result.regular_instruction);
                    $('#select-timing-' + index).multiselect("refresh");



                    // $('#total').html($.findTotalTreatment());
                } else {

                }
            },
            error: function () {

            }

        });
    }
    $.showPrescription();

    var $select = $('#select-prform-0').selectize({
        delimiter: ',',
        persist: false,
        create: false,
        openOnFocus: true,
    });

//var $select = $('#select-timing-0').selectize({
//    delimiter: ',',
//    persist: false,
//    create: false,
//    openOnFocus: true,
//});


    var $selectlab = $('#select-prfrequency-0').selectize({
        delimiter: ',',
        persist: false,
        create: false,
        openOnFocus: true,
    });

    var $selectlab = $('#select-drug-0').selectize({
        delimiter: ',',
        persist: false,
        create: false,
        openOnFocus: true,
        onChange: function (value) {
            $.showPrescription(0);

        }
    });

    var $selectlab = $('.select-edittiming').selectize({
        delimiter: ',',
        persist: false,
        create: false,
        openOnFocus: true,
    });

    var $select = $('.select-editprform').selectize({
        delimiter: ',',
        persist: false,
        create: false,
        openOnFocus: true,
    });

    var $selectlab = $('.select-editprfrequency').selectize({
        delimiter: ',',
        persist: false,
        create: false,
        openOnFocus: true,
    });

    var $selectlab = $('.select-editdrug').selectize({
        delimiter: ',',
        persist: false,
        create: false,
        openOnFocus: true,
        onChange: function (value) {
            $.showPrescription(0);

        }
    });

    var $selectlab0 = $('.select-edittime').selectize({
        delimiter: ',',
        persist: false,
        create: false,
        openOnFocus: true,
    });


    $('.prescriptionadd').click(function () {

        var id = $(".counter-prescription").attr("data-id");
        var addnew = parseInt(id) + 1;
        $(".counter-prescription").attr("data-id", parseInt(id) + 1);
        jQuery("#blankrow").find('.addMoreTiming').attr('name', 'DrugDiseasePrescriptionTemplates[timing][' + id + ']');
        var blankrow = jQuery("#blankrow").html();
        blankrow = blankrow.replace('select-drug', 'select-drug-' + addnew);
        blankrow = blankrow.replace('select-prform', 'select-prform-' + addnew);
        blankrow = blankrow.replace('select-prfrequency', 'select-prfrequency-' + addnew);
        blankrow = blankrow.replace('select-instruction', 'select-instruction-' + addnew);
        blankrow = blankrow.replace('select-timing', 'select-timing-' + addnew);
        blankrow = blankrow.replace('select-strength', 'select-strength-' + addnew);
        blankrow = blankrow.replace('nos', 'nos-' + addnew);
        blankrow = blankrow.replace('duration', 'duration-' + addnew);
        //blankrow = blankrow.replace('instruction', 'instruction-' + addnew);

        $('#diseaseprescription').append('<div class="row addpres prescription new-prescription' + addnew + '" style="visibility: visible;" >' + blankrow + '</div>');
        $(".new-prescription" + addnew).find(".blaankdrughead").html("DRUG-" + addnew);
        var selectize_new = $("#select-drug-" + addnew).selectize({
            onChange: function (value) {
                $.showPrescription(addnew);
            }
        });
        var selectize_new1 = $("#select-prform-" + addnew).selectize({
            delimiter: ',',
            persist: false,
            create: false,
            openOnFocus: true,
        });
        var selectize_new2 = $("#select-prfrequency-" + addnew).selectize({
            delimiter: ',',
            persist: false,
            create: false,
            openOnFocus: true,
        });

//    var selectize_new3 = $("#select-timing-" + addnew).selectize({
//        delimiter: ',',
//        persist: false,
//        create: false,
//        openOnFocus: true,
//    });
        var selectize_new3 = $("#select-timing-" + addnew).multiselect({
            enableFiltering: true,
            includeSelectAllOption: true,
            enableCaseInsensitiveFiltering: true,
            maxHeight: 400,
            dropUp: true,

        });





    });

    $("#form").on("click", '.prescription-remove', function () {
        var counter = parseInt($(".counter-prescription").attr("data-id"));

        if (counter < 0) {

            return false;

        }
        var result = 'removed';
        if (result) {

            //var id = $(this).attr("data-id");
            $(this).parents(".prescription").remove();
            $(".counter-prescription").attr("data-id", parseInt($(".counter-prescription").attr("data-id")) - 1);

        } else
        {
            return false;
        }

    });

    $("#form").on("click", '.prescription-remove-maintr', function () {

        //var id = $(this).attr("data-id");
        $('#maintr').remove();

    });


});

$(document).ready(function () {
    $('.timing').multiselect({
        enableFiltering: true,
        includeSelectAllOption: true,
        enableCaseInsensitiveFiltering: true,
        maxHeight: 400,
        dropUp: true,

    });
});
$(document).ready(function () {
    var selectize_ne = $("#select-timing-0").multiselect({
        enableFiltering: true,
        includeSelectAllOption: true,
        enableCaseInsensitiveFiltering: true,
        maxHeight: 400,
        dropUp: true,

    });
});

$(document).ready(function () {
    var selectize_ne1 = $(".selectdoctortiming").multiselect({
        enableFiltering: true,
        includeSelectAllOption: true,
        enableCaseInsensitiveFiltering: true,
        maxHeight: 400,
        dropUp: true,

    });
});