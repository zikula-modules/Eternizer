CKEDITOR.plugins.add('mueternizermodule', {
    requires: 'popup',
    init: function (editor) {
        editor.addCommand('insertMUEternizerModule', {
            exec: function (editor) {
                MUEternizerModuleFinderOpenPopup(editor, 'ckeditor');
            }
        });
        editor.ui.addButton('mueternizermodule', {
            label: 'Eternizer',
            command: 'insertMUEternizerModule',
            icon: this.path.replace('scribite/CKEditor/mueternizermodule', 'images') + 'admin.png'
        });
    }
});
