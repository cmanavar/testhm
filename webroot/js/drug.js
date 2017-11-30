

var $select = $('#select-freq').selectize({
    delimiter: ',',
    persist: false,
    create: false,
    openOnFocus: true,
});

var $select1 = $('#select-timing').selectize({
    delimiter: ',',
    persist: false,
    create: false,
    openOnFocus: true,
});



var $select = $('#select_drugform').selectize({
    delimiter: ',',
    persist: false,
    create: false,
    openOnFocus: true,
});
$(document).ready(function () {
    $('#timing').multiselect({
        enableFiltering: true,
        includeSelectAllOption: true,
        enableCaseInsensitiveFiltering: true,
        maxHeight: 400,
        dropUp: true,

    });
});