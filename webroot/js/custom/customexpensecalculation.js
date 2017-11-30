//expense calculation for selected dates and month
$(document).ready(function () {
    $('[data-toggle="tooltip"]').tooltip();
    $('.arrivaldate').datepicker({
        dateFormat: 'dd-mm-yy',
    });
    $(".arrivaldate").mask("99-99-9999", {placeholder: "DD-MM-YYYY"});

    $('.alarmdate').datepicker({
        dateFormat: 'dd-mm-yy',
        minDate: 0,
    });
    $(".arrivaldate").mask("99-99-9999", {placeholder: "DD-MM-YYYY"});
    $('.patientdate').datepicker({
        dateFormat: 'dd-mm-yy',
    });

    $('#expense-month').on('change', function () {
        var value = $('select#expense-month option:selected').val();
        if (value == '') {
            $('expensedFromDate').val(""); // or $(this).val()
            $('.expensedToDate').val("");
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
            $('.expensedFromDate').val(startday + '-' + startmonth + '-' + startyr); // or $(this).val()
            $('.expensedToDate').val(endday + '-' + endmonth + '-' + endyr);
        }

    });

    //FOR MONTH DROPDOWN OF INCOME EXPENSE CALCULATOR (AUTHOR : NAMRATA DUBEY)
    var $select = $('#IncomeExpensesMonth').selectize({
        delimiter: ',',
        persist: false,
        create: true,
        //******************** GET START AND END DATES FROM MONTH (AUTHOR : BHAKTI THAKKAR)  : STRAT ********************************//
        onChange: function () {
            var value = $('#IncomeExpensesMonth option:selected').val();
            if (value == '') {
                $('.expensedFromDate').val(""); // or $(this).val()
                $('.expensedToDate').val("");
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
                $('.expensedFromDate').val(startday + '-' + startmonth + '-' + startyr); // or $(this).val()
                $('.expensedToDate').val(endday + '-' + endmonth + '-' + endyr);

            }
            //******************** GET START AND END DATES FROM MONTH (AUTHOR : BHAKTI THAKKAR)  : END ********************************//

        }

    });

    //MONTH DROPDOWN OF LIST CENTER EXPENSES (AUTHOR : NAMRATA DUBEY)
    var $select = $('#SearchexpensebyMonth').selectize({
        delimiter: ',',
        persist: false,
        create: true,
        //******************** GET START AND END DATES FROM MONTH (AUTHOR : BHAKTI THAKKAR)  : STRAT ********************************//
        onChange: function () {

            var value = $('#SearchexpensebyMonth option:selected').val();
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

        //******************** GET START AND END DATES FROM MONTH (AUTHOR : BHAKTI THAKKAR)  : END ********************************//
    });


});