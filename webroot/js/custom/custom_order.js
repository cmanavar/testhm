$(document).ready(function () {

    $("#serviceCategory").change(function () {
        var values = $(this).val();
        var requesturl = $('#ajaxUrlforGetServices').val();
        $.ajax({
            type: "GET",
            url: requesturl + '/' + values,
            data: '',
            dataType: "json",
            success: function (rslt) {
                if (rslt.status == "success") {
                    var selectize = $('#serviceList')[0].selectize;
                    rslt.data.forEach(function (element) {
                        selectize.addOption({value: element.key, text: element.val});
                    });
                    selectize.refreshOptions();
                } else {
                    alert(rslt.msg);
                }
            },
            error: function () {

            }

        });
    });

});
