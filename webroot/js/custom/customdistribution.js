//get calulation of percentage and amount
$(document).ready(function(){    
    
	$.ajax({
                type: "POST",
                url: $('.getdistributionURL').val(),
                dataType: 'JSON',
                data: { heading: '1'},
                success: function(data){ 
                  
                 $('.amnt').attr('data-length',data.length); 
                 
                var amnt = $('.amnt').attr('value');
                var length = $('.amnt').attr('data-length');
                 for (i = 0; i < length; i++) {
                    var j = i + 1;
                    var party = $('.party' + j).val();

                    var finalAmount = (amnt * party) / 100;
                    $('#amount' + j).val(finalAmount);
                }
                }
        }); 
         
	$('.amnt').change(function () {
            var amnt = $('.amnt').attr('value');
            var length = $('.amnt').attr('data-length');
            
            for (i = 0; i < length; i++) {
                var j = i + 1;
                var party = $('.party' + j).val();
               
                var finalAmount = (amnt * party) / 100;
               
                $('#amount' + j).val(finalAmount);
            }
        });
        
        $('.percentage').change(function () {
            var amnt = $('.amnt').attr('value');
            var length = $('.amnt').attr('data-length');
            for (i = 0; i < length; i++) {
                var j = i + 1;
                var party = $('.party' + j).val();
                var finalAmount = (amnt * party) / 100;
                $('#amount' + j).val(finalAmount);
            }
        });
       
       
    
 });
