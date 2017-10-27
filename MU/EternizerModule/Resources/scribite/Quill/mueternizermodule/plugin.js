var mueternizermodule = function(quill, options) {
    setTimeout(function() {
        var button;

        button = jQuery('button[value=mueternizermodule]');

        button
            .css('background', 'url(' + Zikula.Config.baseURL + Zikula.Config.baseURI + '/web/modules/mueternizer/images/admin.png) no-repeat center center transparent')
            .css('background-size', '16px 16px')
            .attr('title', 'Eternizer')
        ;

        button.click(function() {
            MUEternizerModuleFinderOpenPopup(quill, 'quill');
        });
    }, 1000);
};
