/**********************************************/
// * FUNCTIONS : inventory.js
// * DETAILS   : FOR INVENTORY MODULE 
// * AUTHOR    : NAMRATA DUBEY
// * Date      : 10-March-2017
/*********************************************/
$(document).ready(function () {

    $('.dateField').datepicker({
        dateFormat: 'dd-mm-yy',
        minDate: 0,
    });
    $('.dateField').val($.datepicker.formatDate("dd-mm-yy", new Date()));
	
	var $select = $('#InventoryStatus').selectize({
        delimiter: ',',
        persist: false,
        create: true
    });
    
    var $select = $('#InventoryStatus1').selectize({
        delimiter: ',',
        persist: false,
        create: true
    });


//*inventory add modal *//
$("#addRecord").on("click", function () {
    // get values
    var date = $("#date").val();
    var quantity = $("#quantity").val();
    var to_order = $("#to_order").val();
    var details = $("#details").val();
	var inventorystatus = $('#InventoryStatus').val();
    var requesturl = $('#requesturl').val();
    //alert(requesturl + " " + description + " "+ title);
    // Add record
    $.ajax({
        type: "POST",
        url: requesturl,
        data: {
            date: date,
            quantity: quantity,
            to_order: to_order,
            details: details,
			inventory_status : inventorystatus
        },
        dataType: "json",
        success: function (data) {
            if (data.sucess) {
                //location.reload();
                //$("#InventoryAddModal").modal("hide");
            } else {
            }
        },
        error: function () {
        }
    });
});

//CODE FOR GETTING DATA IN INVENTORY EDIT

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
                $('#quantity1').val(data.result.quantity);
                $('#to_order1').val(data.result.to_order);
                $('#details1').val(data.result.details);
				$("#InventoryStatus1")[0].selectize.setValue(data.result.inventory_status, true);
            } else {

            }
        },
        error: function () {

        }
    });
});

//INVENTORY EDIT 

$('.editsave').on("click", function () {
    // get values
    var date = $("#date1").val();
    var quantity = $("#quantity1").val();
    var to_order = $("#to_order1").val();
    var details = $("#details1").val();
	var inventorystatus = $('#InventoryStatus1').val();
    var editurl = $(this).attr('url');
	

    var id = $("#hiddenid").val();

    // Update the details by requesting to the server using ajax
    $.ajax({
        type: "POST",
        url: editurl,
        data: {
            id: id,
            date: date,
            quantity: quantity,
            to_order: to_order,
            details: details,
			inventory_status : inventorystatus,
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

});