$(function () {
    var $select = $('#select-phone').selectize({
        allowEmptyOption: true,
        create: false,
    });
});


$(function () {
    $('#appoinment-time').datetimepicker({
        format: 'HH:mm:ss'
    });
});