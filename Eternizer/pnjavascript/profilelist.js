Event.observe(window, 'load', eternizer_profile_sortinit, false);

function eternizer_profile_sortinit() {
    Sortable.create("profiletable",
        {
            dropOnEmpty: true,
            only: 'z-sortable',
            constraint: false,
            onUpdate: eternizer_profile_updatesort
    });
    eternizer_profile_updatesort();
}

function eternizer_profile_updatesort() {
    var i = 0;
    var debug = ''
    $A(document.getElementsByClassName('z-sortable', 'profiletable')).each(
        function(node) {
            $(node.id+'_pos').value = i;
            i++;
        });
}

function eternizer_profile_delete(id) {
    var check = confirm(eternizer_deleteconfirm);
    if (check == true) {
        $('profile_' + id).remove();
    }
    return;
}