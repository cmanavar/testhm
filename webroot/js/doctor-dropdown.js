$(function () {
    var $wrapper = $('#wrapper');

    // theme switcher
    //var theme_match = String(window.location).match(/[?&]theme=([a-z0-9]+)/);
    //var theme = (theme_match && theme_match[1]) || 'default';
    //var themes = ['default','legacy','bootstrap2','bootstrap3'];
    //$('head').append('<link rel="stylesheet" href="../dist/css/selectize.' + theme + '.css">');

    //var $themes = $('<div>').addClass('theme-selector').insertAfter('h1');
    //for (var i = 0; i < themes.length; i++) {
    //	$themes.append('<a href="?theme=' + themes[i] + '"' + (themes[i] === theme ? ' class="active"' : '') + '>' + themes[i] + '</a>');
    //}

    // display scripts on the page
    $('script', $wrapper).each(function () {
        var code = this.text;
        if (code && code.length) {
            var lines = code.split('\n');
            var indent = null;

            for (var i = 0; i < lines.length; i++) {
                if (/^[	 ]*$/.test(lines[i]))
                    continue;
                if (!indent) {
                    var lineindent = lines[i].match(/^([ 	]+)/);
                    if (!lineindent)
                        break;
                    indent = lineindent[1];
                }
                lines[i] = lines[i].replace(new RegExp('^' + indent), '');
            }

            var code = $.trim(lines.join('\n')).replace(/	/g, '    ');
            var $pre = $('<pre>').addClass('js').text(code);
            $pre.insertAfter(this);
        }
    });

    // show current input values
    $('select.selectized,input.selectized', $wrapper).each(function () {
        var $container = $('<div>').addClass('value').html('Current Value: ');
        var $value = $('<span>').appendTo($container);
        var $input = $(this);
        var update = function (e) {
            $value.text(JSON.stringify(data));
        }

        $(this).on('change', update);
        update();

        $container.insertAfter($input);
    });

    /*Added by haresh - 07102015*/


    /*$('#input-tags6').selectize({
     plugins: ['restore_on_backspace'],
     persist: false,
     create: true
     });*/




    var $select = $('#select-beast').selectize({
        allowEmptyOption: true,
        create: true,
        onOptionAdd: function (value, data) {
            //var postid = $('.hourseid').val();
            $.ajax({
                url: $('.adddoctorajax').val(),
                data: {
                    //id: postid,
                    degree: value,
                },
                error: function () {
                    $('#info').html('<p>An error has occurred</p>');
                },
                dataType: 'json',
                success: function (data) {
                    //  $('#hdn_damID').val(data.id);
                    return data;
                },
                type: 'POST'
            });
        },
        onItemAdd: function (value, $item) {


            // $('#hdn_damID').val(value);
        },
        onDelete: function (values) {
            // if(confirm(values.length > 1 ? 'Are you sure you want to remove these ' + values.length + ' items?' : 'Are you sure you want to remove "' + values[0] + '"?')){

            return true;
            //}

        }
    });


    //var control = $select[0].selectize;
    //control.setValue(jQuery.parseJSON($('.selecteddam').val()));

    var $select = $('#select-doctortype').selectize({
        delimiter: ',',
        persist: false,
        create: false,
        openOnFocus: true,
    });



});

       