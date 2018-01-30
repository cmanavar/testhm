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

/************* FOR ICON 1 UPLOAD (STARTS) **********************/
$("#icon_1").change(function () {
    readURLI1(this);
});
function readURLI1(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            $('#icon_1_upload_preview').attr('src', e.target.result);
        }
        reader.readAsDataURL(input.files[0]);
    }
}

/************* FOR ICON 1 UPLOAD (ENDS) **********************/

/************* FOR ICON 2 UPLOAD (STARTS) **********************/
$("#icon_2").change(function () {
    readURLI2(this);
});
function readURLI2(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            $('#icon_2_upload_preview').attr('src', e.target.result);
        }
        reader.readAsDataURL(input.files[0]);
    }
}

/************* FOR ICON 2 UPLOAD (ENDS) **********************/

/************* FOR ICON 3 UPLOAD (STARTS) **********************/
$("#icon_3").change(function () {
    readURLI3(this);
});
function readURLI3(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            $('#icon_3_upload_preview').attr('src', e.target.result);
        }
        reader.readAsDataURL(input.files[0]);
    }
}

/************* FOR ICON 3 UPLOAD (ENDS) **********************/

/************* FOR ICON 4 UPLOAD (STARTS) **********************/
$("#icon_4").change(function () {
    readURLI4(this);
});
function readURLI4(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            $('#icon_4_upload_preview').attr('src', e.target.result);
        }
        reader.readAsDataURL(input.files[0]);
    }
}

/************* FOR ICON 4 UPLOAD (ENDS) **********************/

/************* FOR ICON 5 UPLOAD (STARTS) **********************/
$("#icon_5").change(function () {
    readURLI5(this);
});
function readURLI5(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            $('#icon_5_upload_preview').attr('src', e.target.result);
        }
        reader.readAsDataURL(input.files[0]);
    }
}

/************* FOR ICON 5 UPLOAD (ENDS) **********************/

/************* FOR ICON 6 UPLOAD (STARTS) **********************/
$("#icon_6").change(function () {
    readURLI6(this);
});
function readURLI6(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            $('#icon_6_upload_preview').attr('src', e.target.result);
        }
        reader.readAsDataURL(input.files[0]);
    }
}

/************* FOR ICON 6 UPLOAD (ENDS) **********************/