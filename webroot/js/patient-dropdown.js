$(function () {
    var $wrapper = $('#wrapper');
    // display scripts on the page
    $('script', $wrapper).each(function () {
        var code = this.text;
        if (code && code.length) {
            var lines = code.split('\n');
            var indent = null;
            for (var i = 0; i < lines.length; i++) {
                if (/^[	 ]*$/.test(lines[i]))
                    continue;
                if (!indent) {
                    var lineindent = lines[i].match(/^([ 	]+)/);
                    if (!lineindent)
                        break;
                    indent = lineindent[1];
                }
                lines[i] = lines[i].replace(new RegExp('^' + indent), '');
            }
            var code = $.trim(lines.join('\n')).replace(/	/g, '    ');
            var $pre = $('<pre>').addClass('js').text(code);
            $pre.insertAfter(this);
        }
    });
    // show current input values
    $('select.selectized,input.selectized', $wrapper).each(function () {
        var $container = $('<div>').addClass('value').html('Current Value: ');
        var $value = $('<span>').appendTo($container);
        var $input = $(this);
        var update = function (e) {
            $value.text(JSON.stringify(data));
        }
        $(this).on('change', update);
        update();
        $container.insertAfter($input);
    });
    // referred by drop down
    var $select = $('#select-beast').selectize({
        allowEmptyOption: true,
        create: true,
        onChange: function (value) {
            $.ajax({
                type: "POST",
                url: $('.get_docotor_mobile').val(),
                dataType: 'JSON',
                data: {id: value},
                success: function (data) {
                    if (typeof data[0] != "undefined") {
                        $('#hdn_damID').val(data[0].id);
                        $('.doc_mobile').val(data[0].phone);
                        $('.doc_name').val(data[0].firstname);
                    } else {
                        $('.doc_mobile').val("");
                        $('.doc_name').val("");
                    }
                    //   $('#fees').val(data.fees);
                }
            });
        },
        onOptionAdd: function (value, data) {
            //var postid = $('.hourseid').val();
            $.ajax({
                url: $('.addpatientajax').val(),
                data: {
                    //   id: postid,
                    firstname: value,
                },
                error: function () {
                    $('#info').html('<p>An error has occurred</p>');
                },
                dataType: 'json',
                success: function (data) {
                    if (data.success) {
                        $('#hdn_damID').val(data.result.id);
                        $('.item').attr('data-value', data.result.id);
                    } else {
                        if(data.result == 'DUPLICATE') {
                            
                        }
                    }
                },
                type: 'POST'
            });
        },
        onItemAdd: function (value, $item) {




            //  $('#hdn_damID').val(value);
        },
        onDelete: function (values) {
            // if(confirm(values.length > 1 ? 'Are you sure you want to remove these ' + values.length + ' items?' : 'Are you sure you want to remove "' + values[0] + '"?')){

//			var selectize = $('#select-beast')[0].selectize;
//            selectize.clear();
            $('#hdn_damID').val("");
            var $select = $('#select-beast').selectize();
            var selectize = $select[0].selectize;
            selectize.clear();
            return true;
            //}

        }
    });
    /*Medical-History*/
    var $select = $('#MuliSelect-Medical-History').selectize({
        delimiter: ',',
        persist: false,
        create: true,
        onOptionAdd: function (value, data) {
            var url = $('.medicalhistoryajax').val();
            $.ajax({
                type: "POST",
                url: url,
                data: {
                    medical_history: value,
                },
                dataType: "json",
                success: function (data) {
                    if (data.success) {
                        $('#mdhistory_ID').val($('#mdhistory_ID').val() + "," + data.result.id); // to append the value in dropdown
                    } else {
                        if(data.result == 'DUPLICATE') {
                            
                        }
                    }
                },
                error: function () {

                }
            });
        },
    });
    /* type dropdown in worksheet(pt file) by Namrata Dubey*/
    var $select = $('#select-type').selectize({
        delimiter: ',',
        persist: false,
        create: true
    });


    /* doctor dropdown in worksheet(pt file) by Namrata Dubey*/
    var $select = $('#select-doctor').selectize({
        delimiter: ',',
        persist: false,
        create: false
    });

    /*status dropdown FOR SECOND record in lab orders(pt file)  by namrata dubey*/
    var $select = $('#select-status').selectize({
        delimiter: ',',
        persist: false,
        create: false
    });
    /*status dropdown FOR SECOND record in lab orders(pt file)  by namrata dubey*/
    var $select = $('#select-status1').selectize({
        delimiter: ',',
        persist: false,
        create: false
    });

    /*referred_by dropdown in profile(pt file)  by namrata dubey*/
    var $select = $('#select-referred-by').selectize({
        delimiter: ',',
        persist: false,
        create: false
    });
    /*bloodgroup dropdown in profile(pt file)  by namrata dubey*/
    var $select = $('#select-blood-group').selectize({
        delimiter: ',',
        persist: false,
        create: false
    });
    /*bloodgroup*/
    var $select = $('#select-bloodgroup').selectize({
        delimiter: ',',
        persist: false,
        create: false
    });
        /*user*/
    var $select = $('#select-user').selectize({
        allowEmptyOption: true,

    });
    /*month*/
    var $select = $('#select-month').selectize({
        delimiter: ',',
        persist: false,
        create: false,
        onChange: function () {

            var value = $('#select-month option:selected').val();
            /// alert(value);
            if (value == '') {

                $('#dfromdate').val(""); // or $(this).val()
                $('#dtodate').val("");

            } else {

                var date = new Date(), y = date.getFullYear(), m = value;
                var firstDay = new Date(y, m - 1, 1);
                var lastDay = new Date(y, m, 0);
                var startday = firstDay.getDate() < 10 ? '0' + firstDay.getDate() : firstDay.getDate();
                var startmonth = m < 10 ? '0' + m : m;
                var startyr = y < 10 ? '0' + y : y;
                var endday = lastDay.getDate() < 10 ? '0' + lastDay.getDate() : lastDay.getDate();
                var endmonth = m < 10 ? '0' + m : m;
                var endyr = y < 10 ? '0' + y : y;
                $('#dfromdate').val(startday + '-' + startmonth + '-' + startyr); // or $(this).val()
                $('#dtodate').val(endday + '-' + endmonth + '-' + endyr);

            }


        }



    });
    /*Search*/
    var $select = $('#select-search-patient').selectize({
        delimiter: ',',
        persist: false,
        create: false,
        openOnFocus: true,
        onDropdownOpen: function (id) {
            $('#oldpatient').iCheck('check');
        },
        onChange: function (id) {
            if (!id.length)
                return;
            var requesturl = $('#requesturl').val();
            $.ajax({
                type: "POST",
                url: requesturl,
                data: {id: id, },
                dataType: "json",
                success: function (data) {

                    if (data.success) {
                        $('#oldpatient').iCheck('check');
                        $("#unique_no").attr("disabled","disabled");
                        console.log(data.result);
                        $('#unique_no').val(data.result.unique_no);
                        $('.unique_no').val(data.result.unique_no);
                        $('#firstname').val(data.result.firstname);
                        $('#middlename').val(data.result.middlename);
                        $('#lastname').val(data.result.lastname);
                        $('#age').val(data.result.age);
                        if (data.result.sex == "") {
                            $('#sex-m').iCheck('uncheck');
                            $('#sex-f').iCheck('uncheck');
                        }
                        if (data.result.sex == "M")
                            $('#sex-m').iCheck('check');
                        if (data.result.sex == "F")
                            $('#sex-f').iCheck('check');
                        $('#address').val(data.result.address);

                        $('#phone').val(data.result.phone);
                        $('#email').val(data.result.email);
                        $("#select-bloodgroup")[0].selectize.setValue(data.result.bloodgroup, true);
                        $("#select-beast")[0].selectize.setValue(data.result.reff_by, true);
                        $("#select-doctor")[0].selectize.setValue(data.result.clinicdoc, true);


                        $('#MuliSelect-Medical-History').val(data.result.fees);
                        $('#oldhistory').val(data.result.other_medical_history);
                        console.log(data.result.medical_history_id);
                        var medical_history_id = data.result.medical_history_id.split(',');
                        $('#MuliSelect-Medical-History')[0].selectize.setValue(medical_history_id);
                        //$('#MuliSelect-Medical-History')[0].selectize.setValue(4);


                    } else {

                    }
                },
                error: function () {

                }
            });
        }

    });



// examination dropdown
    var $select = $('#select-examination').selectize({
        allowEmptyOption: true,
        create: false,
        onOptionAdd: function (value, data) {
            //var postid = $('.hourseid').val();
            $.ajax({
                url: $('.addexaminationajax').val(),
                data: {
                    //   id: postid,
                    examination_type: value,
                },
                error: function () {
                    $('#info').html('<p>An error has occurred</p>');
                },
                dataType: 'json',
                success: function (data) {

                    $('#hdn_exaID').val(data.id);
                    $('.item').attr('data-value', data.id);
                    return data;
                },
                type: 'POST'
            });
        },
        onItemAdd: function (value, $item) {

            //    alert(value);
            $('#hdn_exaID').val(value);
        },
        onDelete: function (values) {
            // if(confirm(values.length > 1 ? 'Are you sure you want to remove these ' + values.length + ' items?' : 'Are you sure you want to remove "' + values[0] + '"?')){

            return true;
            //}

        }
    });
});
$('#newpatient').on('ifChecked', function (event) {
    $("#newpatient").focus();
    $('#sex-m').iCheck('uncheck');
    $('#sex-f').iCheck('uncheck');
    $("#unique_no").removeAttr("disabled");
    $select = $('#select-search-patient').selectize();
    control = $select[0].selectize;
    control.clear(true);
    control.close();
    $select = $('#select-bloodgroup').selectize();
    control = $select[0].selectize;
    control.clear(true);
    control.close();
    $select = $('#select-beast').selectize();
    control = $select[0].selectize;
    control.clear(true);
    control.close();
    $select = $('#select-doctor').selectize();
    control = $select[0].selectize;
    control.clear(true);
    control.close();
    $select = $('#MuliSelect-Medical-History').selectize();
    control = $select[0].selectize;
    control.clear(true);
    control.close();
    $("#patient").trigger('reset');
    return true;
});
$("#btnappoinment").on("click", function () {
// get values
    var url = $(this).attr('url');
    var appurl = $(".appoinmentdata").val();
    //alert(appurl);
    $.ajax({
        type: "POST",
        url: url,
        data: $('form#patient').serialize(),
        dataType: "json",
        success: function (data) {
            // return false;

            if (data.sucess == true) {
                window.location = $(".appoinmentdata").val() + '/index/' + data.caseid;
            }
        },
        error: function () {

        }
    });
});


$("#btneditappoinment").on("click", function () {
// get values
    var url = $(this).attr('url');

    $.ajax({
        type: "POST",
        url: url,
        data: $('form#patient').serialize(),
        dataType: "json",
        success: function (data) {
            if (data.sucess) {
                window.location = $(".appoinmentdata").val() + '/index/' + data.ptcase;
                //  console.log(data.patient)
            } else {
            }

        },
        error: function () {

        }
    });
});

//add patients data and redirect to ptfile
$("#addptfile").on("click", function () {
// get values
    var url = $(this).attr('url');

    $.ajax({
        type: "POST",
        url: url,
        data: $('form#patient').serialize(),
        dataType: "json",
        success: function (data) {
            if (data.sucess) {
                window.location = $(".ptfileworksheet").val() + '/' + data.caseid;
                //  console.log(data.patient)
            } else {
            }

        },
        error: function () {

        }
    });
});


$("#editptfile").on("click", function () {
// get values
    var url = $(this).attr('url');

    $.ajax({
        type: "POST",
        url: url,
        data: $('form#patient').serialize(),
        dataType: "json",
        success: function (data) {
            if (data.sucess) {

                // location.reload();
                window.location = $(".ptfileworksheet").val() + '/' + data.ptcase;
                //  console.log(data.patient)
            } else {
            }

        },
        error: function () {

        }
    });
});

//FOR SHOWING POPUP IF THE PATIENT IS NOT SELECTED IN ADDING OLD PATIENT RECORD 
//$("#pt_name").keyup(function(){
$("#firstname").keyup(function(){
    var patient = $('#select-search-patient').val();
    var pt_type = $('#oldpatient').val();
    if($('#oldpatient'). prop("checked") == true && patient == ""){
        jQuery("#Selectoldpatient").modal('show'); //show popup for alert
    }
});


var $select = $('#select-searchbar-patient').selectize({

    persist: false,
    maxItems: true,
    allowEmptyOption: true,
    create: false,
});