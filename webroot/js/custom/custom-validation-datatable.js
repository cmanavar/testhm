$(document).ready(function () {    
    //  responsive datatable  
    $('#dataTables-responsive').DataTable({
        //rowReorder: true,
        "iDisplayLength": 10,
        "bSort": false,
        responsive: true,
    });    
});
