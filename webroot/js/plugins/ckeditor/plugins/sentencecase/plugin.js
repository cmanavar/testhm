CKEDITOR.plugins.add('sentencecase', {
    icons: 'sentencecase',
    init: function (editor) {
        editor.ui.addButton('Sentence Case', {
            label: 'Sentence Case',
            command: 'convertToSentenceCase',
            toolbar: 'insert',
            icon: this.path + '/icons/sentencecase.png'
        });

        editor.addCommand('convertToSentenceCase',
                {
                    exec: function ()
                    {
                        var selection = editor.getSelection();
                        if (selection.getSelectedText().length > 0) {
                            var ranges = selection.getRanges(), walker = new CKEDITOR.dom.walker(ranges[0]), node;
                            while ((node = walker.next()))
                                if (node.type == CKEDITOR.NODE_TEXT && node.getText())
                                    node.$.textContent = node.$.textContent.replace(/([\w\s]+[\n|.?!])/gm,
                                        function (txt) {
                                            txt = txt.trim();
                                            console.log(txt);
                                            if (editor.langCode == "tr") {
                                                return  txt.charAt(0).trToUpperCase() +
                                                        txt.substr(1).trToLowerCase();
                                            } else {
                                                return  txt.charAt(0).toLocaleUpperCase() +
                                                        txt.substr(1).toLocaleLowerCase();
                                            } 
                                        }
                                    );
                        }//if
                    }
                });
    }
});