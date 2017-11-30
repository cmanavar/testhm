/**********************************************/
// * FUNCTIONS : showrxplan.js
// * DETAILS   : FOR Rxplan  MODULE 
// * AUTHOR    : NAMRATA DUBEY
/*********************************************/
$(document).ready(function(){
   
   $('.rxplandate').datepicker({
        dateFormat: 'dd-mm-yy',
    });
    
    $.findEstimatebyTreatment = function(index){ 
        //alert('You have successfully defined the function!'+index); 
       
        var requesturl = $('#requesturl').val();
        var treatment = $("#select-treatment-"+index).val();
            
            $.ajax({
                type: "POST",
                url: requesturl,
                data: {treatment_id: treatment, },
                dataType: "json",
                success: function (data) {
                    
                     if (data.result) {
                         $('#estimate-'+index).val(data.result);
                         $('.sumestimate').trigger('change');
                        // $('#total').html($.findTotalTreatment());
                    } else {
                        
                    }
                },
                error: function () {
                    
                }
                    
            });
    }
    $.findEstimatebyTreatment();
    var $select = $('#select-treatment-0').selectize({
        //delimiter: ',',
        //persist: false,
        //create: true,
    

        onChange: function (value) {
               $.findEstimatebyTreatment(0);
              
        }
    });
    
    var $selectEdit = $('.select-edittreatment').selectize({
        onChange: function (value) {
           // $.findEstimatebyTreatment(this.options[value].value);      
        }
    });
    


    $('.rxplanadd').click(function () {
        
        var id = $(".counter-rxplan").attr("data-id");
        var addnew = parseInt(id) + 1;
        $(".counter-rxplan").attr("data-id", parseInt(id) + 1);
        var blankrow = jQuery("#blankrow").html();
        blankrow = blankrow.replace('select-treatment','select-treatment-'+addnew);
        blankrow = blankrow.replace('estimate-0','estimate-'+addnew);
       
        $('#newrxplan').append('<div class="col-md-12 rxplandetail new-rx' + addnew + '" style="visibility: visible;" >' + blankrow + '</div>');
        $(".new-rx" + addnew).find(".blank-rx-no").html(addnew);
        var selectize_new = $("#select-treatment-"+addnew).selectize({
            onChange: function (value) {
                 $.findEstimatebyTreatment(addnew);
                 
            }
        });
        
        
    });
    
    //FUNCTION FOR CALCULATING THE TOTAL OF ESTIMATE
    $(document).on('change','.sumestimate', function() {
        
        var sum=0;
        $(".sumestimate").each(function(){
            //alert($(this).val())
            if($(this).val() != "")
              sum += parseInt($(this).val());   

        });

        $("#total").html(sum);
    });
    
    
    
    /* script for Remove more Rxplan details */
    $("#form").on("click", '.rxplan-remove', function () {
        var counter = parseInt($(".counter-rxplan").attr("data-id"));

        if (counter < 0) {
            
            return false;
            
        }
       var result = 'removed';
        if (result) {
            
            //var id = $(this).attr("data-id");
            $(this).parents(".rxplandetail").remove();
            $(".counter-rxplan").attr("data-id", parseInt($(".counter-rxplan").attr("data-id")) - 1);
            $('.sumestimate').trigger('change');
            
            if (parseInt($(".counter-rxplan").attr("data-id")) == 0) {
               $('.rxplanadd').trigger('click');
            }
        } else
        {
            return false;
        }

    });


        
 }); 
 
 

