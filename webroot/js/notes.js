/**
 * File Name : notes.js
 *
 * For notes Module to add/datepicker/edit/view records   
 * 
 * $Author: Bhakti Thakkar
 */
$(document).ready(function () {

    $('.dateField').datepicker({
        dateFormat: 'dd-mm-yy',
        minDate: 0,
    });
    $('.dateField').val($.datepicker.formatDate("dd-mm-yy", new Date()));
});
//note add modal data
$("#addbutton").on("click", function () {
    $('#description').val('');
    $('#title').val('');
    $('.dateField').val($.datepicker.formatDate("dd-mm-yy", new Date()));

});
$("#addRecord").click(function () {
    $("#addnotes").validate().form();

    // get values
    var date = $("#date").val();
    var title = $("#title").val();
    var description = $("#description").val();
//    description = description.replace(/\r?\n/g, '<br />');
    var requesturl = $('#requesturl').val();
    //alert(requesturl + " " + description + " "+ title);
    // Add record

    if (title == "") {
        return;
    }

    $.ajax({
        type: "POST",
        url: requesturl,
        data: {
            date: date,
            title: title,
            description: description,
        },
        dataType: "json",
        success: function (data) {
            if (data.sucess) {
                location.reload();
                // $("#NotesAddModal").modal("hide");

            } else {
            }
        },
        error: function () {
        }
    });
});
//note get data to show in edit
$(".edit").on("click", function () {
    var url = $(this).attr('url');
    var id = $(this).attr('data-id');

    $.ajax({
        type: "POST",
        url: url,
        data: {id: id},
        dataType: "json",
        success: function (data) {
            if (data.sucess) {
                //alert(data.result.title);
                console.log(data.result);
                $('#hiddenid').val(data.result.id);
                $('#date1').val(data.result.date);
                $('#title1').val(data.result.title);
                $('#des').val(data.result.description);

            } else {

            }
        },
        error: function () {

        }
    });
});
//note view modal data
$(".view").on("click", function () {
    var url = $(this).attr('url');
    var id = $(this).attr('data-id');

    $.ajax({
        type: "POST",
        url: url,
        data: {id: id},
        dataType: "json",
        success: function (data) {
            if (data.sucess) {
                $("#NotesViewModal").modal("show");
                console.log(data.result);
                $('#hiddenviewid').val(data.result.id);
                $('#viewdate').html(data.result.date);
                $('#viewtitle').html(data.result.title);
                $('#viewdescription').html(data.result.description);
            } else {

            }
        },
        error: function () {

        }
    });

});

//note edit modal data
$("#editsave").on("click", function () {
    
    // get values
    var id = $("#hiddenid").val();requeseditturl
    var editurl = $('#requeseditturl').val() + '/' + id;
    var description = $("#des").val();
    description = description.replace(/\r?\n/g, '<br />');
    $("#des").val(description);
//    alert(editurl);
    // Update the details by requesting to the server using ajax
    $("#editnote").validate().form();
    $.ajax({
        type: "POST",
        url: editurl,
        data: $('form#editnote').serialize(),
        dataType: "json",
        success: function (data) {
            if (data.sucess) {
             location.reload();
//              $("#NotesEditModal").modal("hide");

            } else {

            }
        },
        error: function () {

        }
    });
});
//note cancle add modal data
$('#closeaddform').click(function () {
    $('#description').val('');
    $('#title').val('');
    $('.dateField').val($.datepicker.formatDate("dd-mm-yy", new Date()));
    $('#NotesAddModal').modal('hide');


});
$('#closeform').click(function () {
    $('#description').val('');
    $('#title').val('');
    $('.dateField').val($.datepicker.formatDate("dd-mm-yy", new Date()));
    $('#NotesAddModal').modal('hide');


});




//  $('#addnotes').on('submit', function(e) {
//    var title = $('#title');
//
//    // Check if there is an entered value
//    if(!title.val()) {
//      // Add errors highlight
//      title.closest('.form-group').removeClass('has-success').addClass('has-error');
//
//      // Stop submission of the form
//      e.preventDefault();
//    } else {
//      // Remove the errors highlight
//      title.closest('.form-group').removeClass('has-error').addClass('has-success');
//    }
//  });
