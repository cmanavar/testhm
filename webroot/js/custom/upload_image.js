/**********************************************/
// * FUNCTIONS : upload_image.js
// * DETAILS   : FOR UPLOADING THE IMAGE
// * AUTHOR    : CHIRAG MANAVAR
// * Date      : 24-OCTOBER-2017
/*********************************************/


/************* FOR ICON UPLOAD (STARTS) **********************/
$("#iconlogo").change(function () {
    readURL(this);
});
function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#iconlogo_upload_preview').attr('src', e.target.result);
        }
        reader.readAsDataURL(input.files[0]);
    }
}

/************* FOR ICON UPLOAD (ENDS) **********************/


/************* FOR SQUARE BANNER UPLOAD (STARTS) **********************/
$("#squarebanner").change(function () {
    readURL1(this);
});
function readURL1(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#squarebanner_upload_preview').attr('src', e.target.result);
        }
        reader.readAsDataURL(input.files[0]);
    }
}

/************* FOR SQUARE BANNER UPLOAD (ENDS) **********************/


/************* FOR BANNER UPLOAD (STARTS) **********************/
$("#banner").change(function () {
    readURL2(this);
});
function readURL2(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#banner_upload_preview').attr('src', e.target.result);
        }
        reader.readAsDataURL(input.files[0]);
    }
}

/************* FOR BANNER UPLOAD (ENDS) **********************/