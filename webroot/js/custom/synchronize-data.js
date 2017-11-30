$(document).ready(function () {
    $(".loading").hide();
    $(".synchronize").on("click", function () {
        var val = $(this).attr('data-url');
        var url = $(this).attr('data-url');
        $('.loading').show();
        $.ajax({
            type: "POST",
            url: url,
            data: {value: val},
            success: function (data) {
                
                var json = JSON.parse(data);
                if (json.Status == 'Success') {
                    $(".message").html('IMPORT SUCCESSFULLY');
                    $(".addpanel").addClass("panel-green");
                    $(".addpanel").removeClass("panel-red");
                    $(".adddate").html('<i>SYNCHRONIZE DATABASE</i><p style="font-size:16px;">LAST SYNCHRONIZE : ' + json.date + '</p>');

                } else {
                    $(".message").html('SOMETHING ARE MISSING!');
                    $(".addpanel").addClass("panel-red");
                    $(".addpanel").removeClass("panel-green");
                }
            },
            complete: function () {
                $('.loading').hide();
            }

        });
    });


});