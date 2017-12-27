$(document).ready(function () {

    /*
     function : delete
     description : Pass id to delete url
     author : Arpit bind event with body
     */
    $("body").on("click", ".delete", function () {
        var dataval = $(this).attr('data-value');
        $('.confirmyes').attr('data-value', dataval);
        var dataurl = $(this).attr('url');
        $('.confirmyes').attr('url', dataurl);
    });
    /*
     function : confirmyes
     description : delete record from table
     */
    $(".confirmyes").on("click", function () {
        var val = $('.confirmyes').attr('data-value');
        var url = $('.confirmyes').attr('url');
        $.ajax({
            type: "POST",
            url: url,
            data: {value: val},
            success: function (data) {
                console.log(data);
                location.reload();
            }
        });
    });

    /*
     function : delete_all
     description : delete all selected sms
     */
    $(".deletesms_cnfirm").on("click", function () {
        $('#delete_all').submit();
    });
    /*
     function : validate
     description : Validate all the form
     */

    $(".validate").validate({ignore: ':hidden:not([class~=selectized]),:hidden > .selectized, .selectize-control .selectize-input input'});

});


$(function () {
    var $select = $('#select-category').selectize({
        allowEmptyOption: true,
        create: false,
    });

    var $select = $('#select-type').selectize({
        allowEmptyOption: true,
        create: false,
    });

    var $select = $('#answer-type').selectize({
        allowEmptyOption: true,
        create: false,
    });

    var $select = $('#qauntity').selectize({
        allowEmptyOption: true,
        create: false,
    });

    var $select = $('#parent_questions').selectize({
        allowEmptyOption: true,
        create: false,
    });

    var $select = $('#parent_questions_answer').selectize({
        allowEmptyOption: true,
        create: false,
    });

    var $select = $('#parent_questions_answer').selectize({
        allowEmptyOption: true,
        create: false,
    });

    var $select = $('#parent_questions_answer').selectize({
        allowEmptyOption: true,
        create: false,
    });

    var $select = $('#parent_questions_answer').selectize({
        allowEmptyOption: true,
        create: false,
    });

    var $select = $('#parent_questions_answer').selectize({
        allowEmptyOption: true,
        create: false,
    });


    var $select = $('.answer_quantity').selectize({
        allowEmptyOption: true,
        create: false,
    });

    var $select = $('#question_type').selectize({
        allowEmptyOption: true,
        create: false,
    });

    var $select = $('#parent_questions_answer1').selectize({
        allowEmptyOption: true,
        create: false,
    });




});

// Questions Sections Code
$(document).ready(function () {
    $("#questions-type-parent").click(function () {
        if ($("#questions-type-parent").prop("checked")) {
            $('.parents-info').css('display', 'none');
        }
    });
    $("#questions-type-child").click(function () {
        if ($("#questions-type-child").prop("checked")) {
            $('.parents-info').css('display', 'block');
        }
    });

    $("#parent_questions").change(function () {
        var values = $(this).val();
        var requesturl = $('#ajaxUrlforAnswers').val();
        $.ajax({
            type: "GET",
            url: requesturl + '/' + values,
            data: '',
            dataType: "json",
            success: function (rslt) {
                if (rslt.status == "success") {
                    var selectize = $('#parent_questions_answer1')[0].selectize;
                    rslt.data.forEach(function (element) {
                        selectize.addOption({value: element.key, text: element.val});
                    });
                    selectize.refreshOptions();
                    //$('#parent_questions_answer1').selectize.enable();
                } else {
                    alert(rslt.msg);
                }
            },
            error: function () {

            }

        });
    });

    $("#answer-type").change(function () {
        var values = $(this).val();
        if (values != 't') {
            room = 0;
            $('#field_trigger').show();
            var container = document.getElementById("answer_val_section");
            while (container.hasChildNodes()) {
                container.removeChild(container.lastChild);
            }
            var container_text = document.getElementById("text_section");
            while (container_text.hasChildNodes()) {
                container_text.removeChild(container_text.lastChild);
            }
            add_fields();
        } else {
            $('#field_trigger').hide();
            var container_text = document.getElementById("text_section");
            while (container_text.hasChildNodes()) {
                container_text.removeChild(container_text.lastChild);
            }
            var container = document.getElementById("answer_val_section");
            while (container.hasChildNodes()) {
                container.removeChild(container.lastChild);
            }
            add_text_field();
        }
    });


    $("#qauntity").change(function () {
        var values = $(this).val();
        if (values == 'YES') {
            room = 0;
            $('#field_trigger').show();
            var container = document.getElementById("answer_val_section");
            while (container.hasChildNodes()) {
                container.removeChild(container.lastChild);
            }
            var container_text = document.getElementById("text_section");
            while (container_text.hasChildNodes()) {
                container_text.removeChild(container_text.lastChild);
            }
            add_rates();
        }
        if (values == 'NO') {
            $('#field_trigger').hide();
            var container_text = document.getElementById("text_section");
            while (container_text.hasChildNodes()) {
                container_text.removeChild(container_text.lastChild);
            }
            var container = document.getElementById("answer_val_section");
            while (container.hasChildNodes()) {
                container.removeChild(container.lastChild);
            }
            add_rate();
        }
        if (values == 'ON_INSPECTION') {
            $('#field_trigger').hide();
            var container_text = document.getElementById("text_section");
            while (container_text.hasChildNodes()) {
                container_text.removeChild(container_text.lastChild);
            }
            var container = document.getElementById("answer_val_section");
            while (container.hasChildNodes()) {
                container.removeChild(container.lastChild);
            }
        }
    });

    function add_fields() {
        //alert('!'); //return;
        room++;
        var objTo = document.getElementById('answer_val_section')
        //var divtest = document.createElement("div");
        var divtest = document.createElement("div");
        divtest.className = "form-group row-" + room;
        divtest.innerHTML +=
                '<label class="col-md-3 control-label padng_rgtrmv">ANSWER DETAILS ' + room + '<span class="text-maroon"> *</span></label>\n\
                 <div class="col-md-2"><input class="form-control" name="answers[' + room + '][label]" id="answer_val" placeholder="Label ' + room + '"></div>\n\
                 <div class="col-sm-3">\n\
                 <div class="col-sm-2 pull-left"><input class="required imgpreview" name="answers[' + room + '][icon]" id="icons' + room +'" type="file" /></div>\n\
                 <div class="imageblock pull-right"><div class="form-group hover-element scanimgblock"><div class="col-sm-1"><img src="/hmen/img/upload_image.png" class="icon_upload" alt="Your image" id="icons'+room+'_upload_preview" height="30"></div></div></div>\n\
                 </div> \n\
                 <div class="col-md-1"><select class="" name="answers[' + room + '][quantity]" id="answer_quantity' + room + '"><option value="YES">YES</option><option value="NO">NO</option><option value="ON_INSPECTION">INSPECTION</option></select></div>\n\
                 <div class="col-md-1"><input class="form-control" type="number" name="answers[' + room + '][price]" id="answer_val" placeholder="Price"></div>';
        if (room == 1) {
            divtest.innerHTML += '<div class="col-md-2"><input type="button" class="btn btn-primary" id="more_fields" value="+" /></div></div>';
        } else {
            divtest.innerHTML += '<div class="col-md-2"><input type="button" class="btn btn-danger remove_fields" data-rowid = ' + room + ' id="remove_fields' + room + '" value="-" /></div></div>';
        }
        objTo.appendChild(divtest);

        var $select = $('#answer_quantity' + room).selectize({
            allowEmptyOption: true,
            create: false,
        });
        
        $("#icons"+room).change(function () {
            readURL(this);
        });
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    //icons1_upload_preview
                    $('#icons'+room+'_upload_preview').attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    }

    function add_rates() {
        room++;
        var objTo = document.getElementById('answer_val_section')
        //var divtest = document.createElement("div");
        var divtest = document.createElement("div");
        divtest.className = "form-group row-" + room;
        divtest.innerHTML +=
                '<label class="col-md-3 control-label padng_rgtrmv">RATE ' + room + '<span class="text-maroon"> *</span></label>\n\
                 <div class="col-md-4"><input class="form-control required" name="rate[' + room + '][label]" id="answer_val" placeholder="Rate Title ' + room + '"></div>\n\
                 <div class="col-md-2"><input class="form-control required" type="number" name="rate[' + room + '][price]" id="answer_val" placeholder="Price(Rs.)"></div>';
        if (room == 1) {
            divtest.innerHTML += '<div class="col-md-2"><input type="button" class="btn btn-primary" id="more_rates" value="+" /></div></div>';
        } else {
            divtest.innerHTML += '<div class="col-md-2"><input type="button" class="btn btn-danger remove_fields" data-rowid = ' + room + ' id="remove_fields' + room + '" value="-" /></div></div>';
        }
        objTo.appendChild(divtest);

        var $select = $('#answer_quantity' + room).selectize({
            allowEmptyOption: true,
            create: false,
        });
    }

    function add_new_rates(room) {
        room++;
        var objTo = document.getElementById('answer_val_section')
        //var divtest = document.createElement("div");
        var divtest = document.createElement("div");
        divtest.className = "form-group row-" + room;
        divtest.innerHTML +=
                '<label class="col-md-3 control-label padng_rgtrmv">RATE ' + room + '<span class="text-maroon"> *</span></label>\n\
                 <div class="col-md-4"><input class="form-control ans-qunatity-tile required" name="rate[' + room + '][label]" id="answer_val" placeholder="Rate Title ' + room + '"></div>\n\
                 <div class="col-md-2"><input class="form-control ans-qunatity-rate required" type="number" name="rate[' + room + '][price]" id="answer_val" placeholder="Price(Rs.)"></div>';
        if (room == 1) {
            divtest.innerHTML += '<input type="button" class="btn btn-primary" id="more_rates" value="+" /></div>';
        } else {
            divtest.innerHTML += '<a href="javascript:void(0)" class="btn btn-success btn-new-rate-save fa fa-save" data-id="' + room + '" title="SAVE"></a> <a href="javascript:void(0)" class="btn btn-danger btn-new-rate-cancel fa fa-times" data-id="' + room + '" title="SAVE"></a>';
        }
        objTo.appendChild(divtest);
        $('#more_rates').attr('disabled', 'disabled');
        $('.btn-rate-edit').attr('disabled', 'disabled');
        $('.btn-rate-delete').attr('disabled', 'disabled');
    }

    function add_text_field() {
        var room = 1;
        var objTo = document.getElementById('text_section');
        var divtest = document.createElement("div");
        divtest.className = "form-group";
        divtest.innerHTML =
                '<label class="col-md-3 control-label padng_rgtrmv">ANSWER DETAILS <span class="text-maroon"> *</span></label>\n\
                 <div class="col-md-3"><input class="form-control" name="answers[' + room + '][label]" id="answer_val" placeholder="Label"></div>\n\
                 <div class="col-md-2"><input class="form-control" type="number" name="answers[' + room + '][quantity]" id="answer_quantity" placeholder="Quantity"></div>\n\
                 <div class="col-md-2 text-right"><input class="form-control number" type="text" name="answers[' + room + '][price]" id="answer_val" placeholder="Price(Rs.)"></div>';
        objTo.appendChild(divtest)
    }

    function add_rate() {
        var room = 1;
        var objTo = document.getElementById('text_section');
        var divtest = document.createElement("div");
        divtest.className = "form-group";
        divtest.innerHTML =
                '<label class="col-md-3 control-label padng_rgtrmv">RATE <span class="text-maroon"> *</span></label>\n\
                 <div class="col-md-6"><input class="form-control number required" type="text" name="price" id="answer_val" placeholder="Price(Rs.)"></div>';
        objTo.appendChild(divtest)
    }

    $('#more_fields').click(function () {
        var room = 1;
        room = $(this).attr('data-answer-count');
        add_field(room);
    });

    $('#more_rates').click(function () {
        room = $(this).attr('data-rate-count');
        add_new_rates(room);
    });

    function add_field(room) {
        room++;
        var objTo = document.getElementById('answer_val_section')
        //var divtest = document.createElement("div");
        var divtest = document.createElement("div");
        divtest.className = "form-group row-" + room;
        divtest.innerHTML +=
                '<label class="col-md-3 control-label padng_rgtrmv">ANSWER DETAILS ' + room + '<span class="text-maroon"> *</span></label>\n\
                 <div class="col-md-2"><div class="input text"><input class="form-control ans-label" name="label" id="answer_val' + room + '" placeholder="Label ' + room + '"></div><label id="question-title-error-' + room + '" class="error" style="display:none;" for="question-title">THIS FIELD IS REQUIRED.</label></div>\n\
                 <div class="col-sm-3">\n\
                 <div class="col-sm-2 pull-left"><input class="required imgpreview" name="answers[' + room + '][icon]" id="icons' + room +'" type="file" /></div>\n\
                 <div class="imageblock pull-right"><div class="form-group hover-element scanimgblock"><div class="col-sm-1"><img src="/hmen/img/upload_image.png" class="icon_upload" alt="Your image" id="icons'+room+'_upload_preview" height="30"></div></div></div>\n\
                 </div> \n\
                 <div class="col-md-1"><div class="input text"><select class="ans-qunat" name="quantity" id="answer_quantity' + room + '"><option value="YES">YES</option><option value="NO">NO</option><option value="ON_INSPECTION">ON INSPECTION</option></select></div></div>\n\
                 <div class="col-md-1"><div class="input text"><input class="form-control ans-price" id="answer_price' + room + '" type="number" name="price" placeholder="Price(Rs.)"></div><label id="question-price-error-' + room + '" class="error" style="display:none;" for="question-price">THIS FIELD IS REQUIRED.</label></div>\n\
                 <div class="col-md-2"><a href="javascript:void(0)" class="btn btn-success btn-new-que-save fa fa-save" data-id="' + room + '" title="SAVE"></a> <a href="javascript:void(0)" class="btn btn-danger btn-new-que-cancel fa fa-times" data-id="' + room + '" title="SAVE"></a></div>';
        objTo.appendChild(divtest);
        $('#more_fields').attr('disabled', 'disabled');
        $('.btn-que-edit').attr('disabled', 'disabled');
        $('.btn-que-delete').attr('disabled', 'disabled');
        var $select = $('#answer_quantity' + room).selectize({
            allowEmptyOption: true,
            create: false,
        });
        
        $("#icons"+room).change(function () {
            readURL(this);
        });
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    //icons1_upload_preview
                    $('#icons'+room+'_upload_preview').attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    }

    $(document).on('click', '#answer_val_section #more_fields', function () {
        add_fields();
    });

    $(document).on('click', '#answer_val_section #more_rates', function () {
        add_rates();
    });

    $(document).on('click', '#answer_val_section .remove_fields', function () {
        var val = $(this).attr('data-rowid');
        $('.row-' + val).fadeOut("medium", function () {
            $(this).css({display: "none"});
        });
    })

    // Code for Questions Edit
    $('.btn-que-edit').click(function () {
        var id = $(this).attr('data-id');
        $('.input-label-' + id).removeAttr('disabled');
        $('.input-price-' + id).removeAttr('disabled');
        $('.input-quantity-' + id)[0].selectize.enable();
        $('.div-edit-' + id).css('display', 'none');
        $('.div-save-' + id).css('display', 'block');
        $('.btn-que-edit').attr('disabled', 'disabled');
        $('.btn-que-delete').attr('disabled', 'disabled');
    });

    $('.btn-que-cancel').click(function () {
        var id = $(this).attr('data-id');
        $('.input-label-' + id).attr('disabled', 'disabled');
        $('.input-price-' + id).attr('disabled', 'disabled');
        $('.input-quantity-' + id)[0].selectize.disable();
        $('.div-edit-' + id).css('display', 'block');
        $('.div-save-' + id).css('display', 'none');
        $('.btn-que-edit').removeAttr('disabled')
        $('.btn-que-delete').removeAttr('disabled');
    });

    $('.btn-que-save').click(function () {
        var id = $(this).attr('data-id');
        var title = '';
        var quantity = '';
        var price = '';
        if (id == '') {
            return;
        }
        title = $('.input-label-' + id).val();
        price = $('.input-price-' + id).val();
        quantity = $('.input-quantity-' + id).val();
        if (title == '') {
            $('#question-title-error-' + id).css('display', 'block');
            $('.input-label-' + id).addClass('error');
            return false;
        }
        if (price == '') {
            $('#question-price-error-' + id).css('display', 'block');
            $('.input-price-' + id).addClass('error');
            return false;
        } else {
            if (quantity == 'YES') {
                if (price == 0) {
                    $('.input-price-' + id).addClass('error');
                    $('#question-price-error-' + id).html('PLEASE ENTER PRICE!');
                    $('#question-price-error-' + id).css('display', 'block');
                    return false;
                }
            }
        }
        var url = $('#updateanswer').val();
        $.ajax({
            type: "POST",
            url: url + '/' + id,
            data: {title: title, quantity: quantity, price: price},
            success: function (data) {
                console.log(data);
                location.reload();
            }
        });
    });

    // answer_val_section
    $(document).on('click', '#answer_val_section .btn-new-que-save', function () {
        //$('.btn-new-que-save').click(function () {
        var id = $(this).attr('data-id');
        var title = '';
        var quant = '';
        var price = '';
        var flag = 0;
        title = $('.ans-label').val();
        price = $('.ans-price').val();
        quant = $('.ans-qunat').val();
        if (title == '') {
            $('#question-title-error-' + id).css('display', 'block');
            $('#answer_val' + id).addClass('error');
            flag = 1;
        }
        if (price == '') {
            $('#question-price-error-' + id).css('display', 'block');
            $('.input-price-' + id).addClass('error');
            $('#answer_price' + id).addClass('error');
            flag = 1;
        } else {
            if (quant == 'YES') {
                if (price == 0) {
                    $('.input-price-' + id).addClass('error');
                    $('#question-price-error-' + id).html('PLEASE ENTER PRICE!');
                    $('#question-price-error-' + id).css('display', 'block');
                    flag = 1;
                }
            }
        }
        if (flag == 1) {
            return false;
        }
        var url = $('#addnewanswer').val();
        $.ajax({
            type: "POST",
            url: url,
            data: {title: title, quantity: quant, price: price},
            success: function (data) {
                console.log(data);
                location.reload();
            }
        });
    });

    $(document).on('click', '#answer_val_section .btn-new-que-cancel', function () {
        var val = $(this).attr('data-id');
        $('.row-' + val).fadeOut("medium", function () {
            $(this).css({display: "none"});
            $('#more_fields').removeAttr('disabled');
            $('.btn-que-edit').removeAttr('disabled');
            $('.btn-que-delete').removeAttr('disabled');
        });
    });

    $(document).on('click', '#answer_val_section .btn-new-rate-cancel', function () {
        var val = $(this).attr('data-id');
        $('.row-' + val).fadeOut("medium", function () {
            $(this).css({display: "none"});
            $('#more_rates').removeAttr('disabled');
            $('.btn-rate-edit').removeAttr('disabled');
            $('.btn-rate-delete').removeAttr('disabled');
        });
    });

    // Code for Questions Edit
    $('.btn-rate-edit').click(function () {
        var id = $(this).attr('data-id');
        $('.input-qunatity-title-' + id).removeAttr('disabled');
        $('.input-rate-' + id).removeAttr('disabled');
        $('.div-edit-' + id).css('display', 'none');
        $('.div-save-' + id).css('display', 'block');
        $('.btn-rate-edit').attr('disabled', 'disabled');
        $('.btn-rate-delete').attr('disabled', 'disabled');
        $('#more_rates').attr('disabled', 'disabled');
    });

    $('.btn-rate-cancel').click(function () {
        var id = $(this).attr('data-id');
        $('.input-qunatity-title-' + id).attr('disabled', 'disabled');
        $('.input-rate-' + id).attr('disabled', 'disabled');
        $('.div-edit-' + id).css('display', 'block');
        $('.div-save-' + id).css('display', 'none');
        $('.btn-rate-edit').removeAttr('disabled')
        $('.btn-rate-delete').removeAttr('disabled');
        $('#more_rates').removeAttr('disabled');
    });

    $('.btn-rate-save').click(function () {
        var id = $(this).attr('data-id');
        var title = '';
        var price = '';
        if (id == '') {
            return;
        }
        var flag = false;
        title = $('.input-qunatity-title-' + id).val();
        price = $('.input-rate-' + id).val();
        if (title == '') {
            $('#question-title-error-' + id).css('display', 'block');
            $('.input-qunatity-title-' + id).addClass('error');
            flag = true;
        }
        if (price == '') {
            $('#question-price-error-' + id).css('display', 'block');
            $('.input-rate-' + id).addClass('error');
            flag = true;
        } else {
            if (price == 0) {
                $('.input-price-' + id).addClass('error');
                $('#question-price-error-' + id).html('PLEASE ENTER PRICE!');
                $('#question-price-error-' + id).css('display', 'block');
                flag = true;
            }
        }
        if (flag == true) {
            return false;
        }
        var url = $('#updaterate').val();
        //console.log(url); return;
        $.ajax({
            type: "POST",
            url: url + '/' + id,
            data: {title: title, price: price},
            success: function (data) {
                console.log(data);
                location.reload();
            }
        });
    });

    $(document).on('click', '#answer_val_section .btn-new-rate-save', function () {
        var id = $(this).attr('data-id');
        var title = '';
        var price = '';
        var flag = false;
        title = $('.ans-qunatity-tile').val();
        price = $('.ans-qunatity-rate').val();
        if (title == '') {
            $('#question-title-error-' + id).css('display', 'block');
            $('.ans-qunatity-tile').addClass('error');
            flag = true;
        }
        if (price == '') {
            $('#question-price-error-' + id).css('display', 'block');
            $('.ans-qunatity-rate').addClass('error');
            flag = true;
        } else {

            if (price == 0) {
                $('.ans-qunatity-rate').addClass('error');
                $('#question-price-error-' + id).html('PLEASE ENTER PRICE!');
                $('#question-price-error-' + id).css('display', 'block');
                flag = true;
            }

        }
        if (flag == 1) {
            flag = true;
        }
        var url = $('#addnewrate').val();
        $.ajax({
            type: "POST",
            url: url,
            data: {title: title, price: price},
            success: function (data) {
                console.log(data);
                location.reload();
            }
        });
    });

});
// Questions Sections Code


$(document).ready(function () {
    $('.dateField').datepicker({
        dateFormat: 'dd-mm-yy',
        minDate: 0,
    });
    $('.todayDate').val($.datepicker.formatDate("dd-mm-yy", new Date()));
    var myDate = new Date();
    myDate.setDate(myDate.getDate() + 1);
    $('.tomorrowDate').val($.datepicker.formatDate("dd-mm-yy", myDate));
    //tomorrowDate

    tinymce.init({
        selector: 'textarea',
        height: 350,
        menubar: false,
        plugins: [
            'advlist autolink lists link image charmap print preview anchor textcolor',
            'searchreplace visualblocks code fullscreen',
            'insertdatetime media table contextmenu paste code help'
        ],
        toolbar: 'undo redo |  formatselect | bold italic backcolor  | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent',
        content_css: [
            '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
            '//www.tinymce.com/css/codepen.min.css']
    });

});