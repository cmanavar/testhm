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
$("#addRecord").on("click", function () {
    // get values
    var dateValue = $("#date").val();
    var quantityValue = $("#quantity").val();
    var to_orderValue = $("#to-order").val();
    var detailValue = $("#detail").val();
    var requesturl = $('#requesturlNew').val();
//    alert(requesturl+" "+dateValue + " " + qauantityValue + " "+ to_orderValue+" "+detailValue);
    // Add record
    //var props = [{ "Name": "date", "Value": dateValue }, {"Name": "qauantity", "Value": qauantityValue}, {"Name": "to_order", "Value": to_orderValue}, {"Name": "detail", "Value": detailValue}];
    $.ajax({
        type: "POST",
        url: requesturl,
        data: {
            date: dateValue,
            quantity: quantityValue,
            to_order: to_orderValue,
            detail: detailValue,
        },
        dataType: "json",
        success: function (data) {
            if (data.sucess) {
                location.reload();
                $("#InventoryCasesAddModal").modal("hide");
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
               // alert(data.result.id+" "+data.result.date+" "+data.result.quantity+" "+data.result.to_order+" "+data.result.detail);
                console.log(data.result);
                $('#hiddenid').val(data.result.id);
                $('#date1').val(data.result.date);
                $('#quantity1').val(data.result.quantity);
                $('#to-order1').val(data.result.to_order);
                $('#detail1').val(data.result.detail);

            } else {

            }
        },
        error: function () {
            alert("error");
        }
    });
});
//note edit modal data
$('.editInventory').on("click", function () {
    // get values
    var dateValue = $("#date1").val();
    var quantityValue = $("#quantity1").val();
    var to_orderValue = $("#to-order1").val();
    var detailValue = $("#detail1").val();
    var editurl = $(this).attr('url');
    var id = $("#hiddenid").val();
    // Update the details by requesting to the server using ajax
    $.ajax({
        type: "POST",
        url: editurl,

        data: {
           date: dateValue,
            quantity: quantityValue,
            to_order: to_orderValue,
            detail: detailValue,
        },
        dataType: "json",
        success: function (data) {
            if (data.sucess) {
                location.reload();
                $("#InventoryEditModal").modal("hide");

            } else {

            }
        },
        error: function () {

        }
    });
});


//note cancle modal data
$('#closeaddform').click(function () {
    location.reload();
//        $('#NotesAddModal').modal('hide');
        
        
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
