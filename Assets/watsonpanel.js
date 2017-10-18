var Watson = [];

Watson.cloneObject = function(){

    $('.copybutton').each(function(i, el){

        $(el).on('click', function(){

            var target = $(el).data('copy-target');
            var to = $(el).data('copy-to');
            var ignore = $(el).data('copy-ignore');
            var callback = $(el).data('copy-callback');

            $(target).clone()
                .find('input:text, textarea').val('').end().removeClass('toClone')
                .appendTo(to).find(ignore).remove();

            var x = eval(callback)
            if (typeof x == 'function') {
                x();
            }

            Watson.removeObject();
            Watson.callClick();
        })
    });
};

Watson.removeObject = function () {
    $('.trash-example').each(function(i, el) {
        $(el).on('click', function(){
            $(this).parent().remove();
        });

    });
};

Watson.showDivAndHideAnother = function () {
    $('.showHide').each(function(i, el){
        $(el).on('click', function(){
            var hide = $(el).data('hide');
            var show = $(el).data('show');

            $(hide).addClass('hidden');
            $(show).removeClass('hidden');
        });
    });
};


Watson.enableDisableButton = function () {
    $('.enableDisableButton').each(function(i, el) {

        $(el).on('click', function(){
            var enable = $(this).data('enable-target');
            var disable = $(this).data('disable-target');
            $(enable).removeAttr('disabled');
            $(disable).attr('disbaled', 'disabled');
        });

    });
};

Watson.summernote = function(){
    $('.summernote').summernote({
        lang: 'pt-BR',
        height: 200,
        placeholder: '',
        hint: {
            mentions: [],
            match: /\B%(\w*)$/,
            search: function (keyword, callback) {
                callback($.grep(this.mentions, function (item) {
                    return item.indexOf(keyword) == 0;
                }));
            },
            content: function (item) {
                return '%' + item + '%';
            }
        }
    });
};

Watson.callClick = function(){
    $('.callClick').each(function(i, el){
        var target = $(el).data('callclick-target');
        $(el).unbind('keypress')
        $(el).on('keypress', function(e){
            if(e.which == 13) {
                $(target).click();
            }
        });
    });
};

Watson.tree = function(){
    $('#treeview').jstree({
        'core' : {
            'themes': {
                'icons' : false,
                'responsive' : true
            },
            'worker' : false
        }
    });

    $('#treeview').on("select_node.jstree", function (e, data) {
        window.location = data.node.data.jstree.href;
    });

}

$(document).ready(function () {
    Watson.cloneObject();
    Watson.removeObject();
    Watson.showDivAndHideAnother();
    Watson.enableDisableButton();
    Watson.summernote();
    Watson.callClick();
});


$(document).ready(function() {

});