$(document).ready(function () {

    $('.dateField').datepicker({
        dateFormat: 'dd-mm-yy',
        minDate: 0,
    });
    $('.dateField').val($.datepicker.formatDate("dd-mm-yy", new Date()));

});

$(".orderstatus").on("change", function () {

    var orderid = $(this).attr('data-orderid');
    var url = $(this).attr('data-url');
    var value = $('.ordervalue-' + orderid).val();
    //alert(value +" "+orderid + " "+ url);
    $.ajax({
        type: "POST",
        url: url,
        data: {
            id: orderid,
            value: value
        },
        dataType: "json",
        success: function (data) {
            if (data.sucess) {
                //alert(data.result.title);


            } else {

            }
        },
        error: function () {

        }
    });

});

$(".serachlab").on("keyup", function () {
    var url = $(this).attr('data-url');
    var value = $('.serachlab').val();
   
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
                        html = html + '<tr><td><a href="' + url + '/index/' + data.result[i].id + '" id="laburl" class="laburl" data-value=" ' + url + ' " data-id="' + data.result[i].id + '" style="color: black;"><i class="fa fa-flask fa-fw "></i> ' + data.result[i].v_name + '</a></td><td><a href="' + url + '/delete/' + data.result[i].id + '" data-target="#delete"><i class="fa fa-trash-o fa-1x pull-right" style="color: red;" aria-hidden="true"></i></a><a href="' + url + '/edit/' + data.result[i].id + '"><i class="fa fa-pencil fa-1x pull-right" style="color: black; margin-right:10px; " aria-hidden="true"></i></a></td></tr>';
                    }
                    $('.lstLabTbl').find('tbody').html(html);
                }
            } else {
            }
        },
        error: function () {
        }
    });
});

var $select = $('#select-patient').selectize({
    delimiter: ',',
    persist: false,
    create: false,
    openOnFocus: true,
});

var $selectlab = $('#select-lab').selectize({
    delimiter: ',',
    persist: false,
    create: false,
    openOnFocus: true,
});
var $selectlab = $('#month').selectize({
    delimiter: ',',
    persist: false,
    create: false,
    openOnFocus: true,
});

var $selectl = $('select[name=dataTables-responsive_length]').selectize({
    delimiter: ',',
    persist: false,
    create: false,
    openOnFocus: true,
});



var $selectl = $('.orderstatus').selectize({
    delimiter: ',',
    persist: false,
    create: false,
    openOnFocus: true,
});



var $selectl = $('.lorder').selectize({
    delimiter: ',',
    persist: false,
    create: false,
    openOnFocus: true,
});