
$(document).ready(function(){              

    $('#exa-donetemp').change(function(){
        var value = $('select#exa-donetemp option:selected').text();
     
        $.ajax({
                type: "POST",
                url: $('.getexaminationURL').val(),
                dataType: 'JSON',
                data: { heading: value},
                success: function(data){ 
                      //  console.log(data);
                             CKEDITOR.instances.editor3.setData(data.description);
                }
        });
    });
    $('#evaluationtemp').change(function(){
        var value = $('select#evaluationtemp option:selected').text();
     
        $.ajax({
                type: "POST",
                url: $('.getexaminationURL').val(),
                dataType: 'JSON',
                data: { heading: value},
                success: function(data){ 
                      //  console.log(data);
                           CKEDITOR.instances.editor1.setData(data.description);
                }
        });
    });
    $('#remarktemp').change(function(){
        var value = $('select#remarktemp option:selected').text();
     
        $.ajax({
                type: "POST",
                url: $('.getexaminationURL').val(),
                dataType: 'JSON',
                data: { heading: value},
                success: function(data){ 
                      //console.log(data.description);
                           CKEDITOR.instances.editor4.setData(data.description);
                }
        });
    });
    $('#radiographicimpression-temp').change(function(){
        var value = $('select#radiographicimpression-temp option:selected').text();
     
        $.ajax({
                type: "POST",
                url: $('.getexaminationURL').val(),
                dataType: 'JSON',
                data: { heading: value},
                success: function(data){ 
                      //console.log(data.description);
                           CKEDITOR.instances.editor2.setData(data.description);
                }
        });
    });
    
 });
