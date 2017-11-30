jQuery(document).ready(function () {
       var date = new Date(), y = date.getFullYear(), m = date.getMonth();
    var firstDay = new Date(y, m, 1);
    $('#dfromdate').datepicker({
        dateFormat: 'dd-mm-yy',
        // minDate: 0 ,
    }).datepicker('setDate', firstDay);
    ;
    $('#dtodate').datepicker({
        dateFormat: 'dd-mm-yy',
        // minDate: 0 ,
    }).datepicker('setDate', 'today');
});