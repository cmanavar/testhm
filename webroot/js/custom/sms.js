$(document).ready(function () {

    var $remaining = $('#remaining'),
            $messages = $remaining.next();

    $('#text_sms').keyup(function () {

        var chars = this.value.length,
                messages = Math.ceil(chars / 160),
                remaining = messages * 160 - (chars % (messages * 160) || messages * 160);

        $remaining.text(remaining + ' CHARACTERS REMAINING');
        $messages.text(messages + ' MESSAGE(S)');
    });

    var $remaining_sms_template = $('#remaining_sms_template'),
            $messages_sms_template = $remaining_sms_template.next();

    $('#sms_text_template').keyup(function () {

        var chars = this.value.length,
                messages_sms_template = Math.ceil(chars / 160),
                remaining_sms_template = messages_sms_template * 160 - (chars % (messages_sms_template * 160) || messages_sms_template * 160);

        $remaining_sms_template.text(remaining_sms_template + ' CHARACTERS REMAINING');
        $messages_sms_template.text(messages_sms_template + ' MESSAGE(S)');
    });
//    delete all selected message
    $('.delete_all').click(function () {
        $('#deletesms').modal({
                show: 'false'
            });
//        if ($(".checkboxid").attr('checked')) { 
//        alert('hello');
//        }
//        return false;
//        if ($(".checkboxid").attr('checked')) {
//            $('#deletesms').modal({
//                show: 'false'
//            });
//        } else {
//          $('#warning').modal({
//                show: 'false'
//            });
//        }

    });
//   get checked data
    /*
     function : delete 
     description : check and uncheck all sms check box for delete
     auther : Bhakti Thakkar
     */
    



$(".del_check").click(function(){
        //alert("just for check");
        if(this.checked){
            $('.checkboxid').each(function(){
                this.checked = true;
            })
        }else{
            $('.checkboxid').each(function(){
                this.checked = false;
            })
        }
    });


//    $('.del_check').change(function () {
//        var ele = $(this).attr("checked", true);
//        if (ele.is(':checked')) {
//            $('.checkboxid').attr('checked', true);
//        } else {
//            $('.checkboxid').removeAttr('checked', true);
//        }
//    });




});

