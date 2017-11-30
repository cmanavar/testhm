//date picker
$(function () {
    $('[data-toggle="tooltip"]').tooltip();
    $('.arrivaldate').datepicker({
        dateFormat: 'dd-mm-yy',
        // minDate: 0 ,
    });
    $(".arrivaldate").mask("99-99-9999", {placeholder: "DD-MM-YYYY"});

    $('#reminderdate').datepicker({
        dateFormat: 'dd-mm-yy',
        
        
      
    });
    $('.alarmdate').datepicker({
        dateFormat: 'dd-mm-yy',
      
    });
    $(".arrivaldate").mask("99-99-9999", {placeholder: "DD-MM-YYYY"});
    $('.patientdate').datepicker({
        dateFormat: 'dd-mm-yy',
    });
    
    $("#receiptdate").datepicker({
        changeMonth: true,
        changeYear: true,
        yearRange: "1:c+10" // 1AD to 2013AD + 10
    });

    $('#month').on('change', function () {

        var value = $('select#month option:selected').val();
        if (value == '') {
            $('#dfromdate').val(""); // or $(this).val()
            $('#dtodate').val("");
        } else {
            var date = new Date(), y = date.getFullYear(), m = $(this).val();
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

    });

});