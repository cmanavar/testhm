/**********************************************/
// * FUNCTIONS : feereceipt.js
// * DETAILS   : FOR FEE RECEIPT MODULE 
// * AUTHOR    : NAMRATA DUBEY
// * Date      : 10-March-2017
/*********************************************/
$(document).ready(function () {

    $('.billdate').datepicker({
        dateFormat: 'dd-mm-yy',
    });

    $.findEstimatebyTreatment = function (index) {

        var requesturl = $('#requesturl').val();
        var treatment = $("#select-treatmentforbill-" + index).val();

        $.ajax({
            type: "POST",
            url: requesturl,
            data: {treatment_id: treatment, },
            dataType: "json",
            success: function (data) {

                if (data.result) {
                    $('#amount-' + index).val(data.result);
                    $('.sumamount').trigger('change');
                    // $('#total').html($.findTotalTreatment());
                } else {

                }
            },
            error: function () {

            }

        });
    }
    $.findEstimatebyTreatment();
    var $select = $('#select-treatmentforbill-0').selectize({
        delimiter: ',',
        persist: false,
        create: true,
        createOnBlur: true,
        openOnFocus: true,
        onChange: function (value) {
            $.findEstimatebyTreatment(0);
        }
    });

    var $selectEdit = $('.select-treatmentforbilledit').selectize({
        delimiter: ',',
        persist: false,
        create: true,
        createOnBlur: true,
        openOnFocus: true,
        onChange: function (value) {

        }
    });

    $('.AddFinalbillform').click(function () {

        var id = $(".counter-finalbill").attr("data-id");
        var addnew = parseInt(id) + 1;
        $(".counter-finalbill").attr("data-id", parseInt(id) + 1);
        var blankrow = jQuery("#blankfeebill").html();
        blankrow = blankrow.replace('select-treatmentforbill', 'select-treatmentforbill-' + addnew);
        blankrow = blankrow.replace('amount-0', 'amount-' + addnew);

        $('#newfeesbill').append('<div class="col-md-12 finalbilldetail addbotttommargin new-fee' + addnew + '" style="visibility: visible;" >' + blankrow + '</div>');
        $(".new-fee" + addnew).find(".blank_fee_no").html(addnew);
        var selectize_new = $("#select-treatmentforbill-" + addnew).selectize({
            delimiter: ',',
            persist: false,
            create: true,
            createOnBlur: true,
            openOnFocus: true,
            onChange: function (value) {
                $.findEstimatebyTreatment(addnew);
            }
        });


    });

    //FUNCTION FOR CALCULATING THE TOTAL OF ESTIMATE
    $(document).on('change', '.sumamount', function () {

        var sum = 0;
        $(".sumamount").each(function () {
            
            if ($(this).val() != "")
                sum += parseInt($(this).val());
        });

        $("#totalamount").html(sum);
    });



    /* script for Remove more Generatefinalbill details */
    $("#feebillform").on("click", '.fee-remove', function () {
        var counter = parseInt($(".counter-finalbill").attr("data-id"));
        if (counter < 0) {
            return false;
        }
        var result = 'removed';
        if (result) {

            $(this).parents(".finalbilldetail").remove();
            $(".counter-finalbill").attr("data-id", parseInt($(".counter-finalbill").attr("data-id")) - 1);
            $('.sumamount').trigger('change');

            if (parseInt($(".counter-finalbill").attr("data-id")) == 0) {
                $('.AddFinalbillform').trigger('click');
            }
        } else
        {
            return false;
        }

    });


}); 