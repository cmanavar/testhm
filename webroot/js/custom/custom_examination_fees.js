$(document).ready(function () {



    $('#select-examination').change(function () {
        var value = $('select#select-examination option:selected').val();

        $.ajax({
            type: "POST",
            url: $('.get_examination_fees').val(),
            dataType: 'JSON',
            data: {id: value},
            success: function (data) {
                if (typeof data[0] != "undefined") {
                    $('#fees').val(data[0].examination_fees);
                } else {
                    $('#fees').val(0);
                }
                //   $('#fees').val(data.fees);
            }
        });
    });
    
    $(".searchname").click(function(){
        $('.heading_list_patient_search').submit();
    });

   



});
