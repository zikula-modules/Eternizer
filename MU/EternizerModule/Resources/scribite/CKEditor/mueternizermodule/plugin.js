CKEDITOR.plugins.add('mueternizermodule', {
    requires: 'popup',
    lang: 'en,nl,de',
    init: function (editor) {
        editor.addCommand('insertMUEternizerModule', {
            exec: function (editor) {
                var url = Routing.generate('mueternizermodule_external_finder', { objectType: 'entry', editor: 'ckeditor' });
                // call method in MUEternizerModule.Finder.js and provide current editor
                MUEternizerModuleFinderCKEditor(editor, url);
            }
        });
        editor.ui.addButton('mueternizermodule', {
            label: editor.lang.mueternizermodule.title,
            command: 'insertMUEternizerModule',
            icon: this.path.replace('scribite/CKEditor/mueternizermodule', 'public/images') + 'admin.png'
        });
    }
});
