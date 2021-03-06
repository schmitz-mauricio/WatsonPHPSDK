var jstree = require('./jstree/dist/jstree.min');


var WatsonPanel = {
    cloneObject() {

        $('.copybutton').each(function (i, el) {

            $(el).on('click', function (event) {
                event.preventDefault();
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

                WatsonPanel.removeObject();
                WatsonPanel.callClick();
            })
        });
    },

    removeObject () {
        $('.trash-example').each(function(i, el) {
            $(el).on('click', function(event){
                event.preventDefault();
                $(this).parent().remove();
            });

        });
        return false;
    },
    showDivAndHideAnother () {
        $('.showHide').each(function(i, el){
            $(el).on('click', function(event){
                event.preventDefault();
                var hide = $(el).data('hide');
                var show = $(el).data('show');
                var callback = $(el).data('callback');

                $(hide).addClass('hidden');
                $(show).removeClass('hidden');

                var x = eval(callback)
                if (typeof x == 'function') {
                    setTimeout(function(){x()},1000);
                }
            });
        });
    },
    enableDisableButton () {
        $('.enableDisableButton').each(function(i, el) {

            $(el).on('click', function(event){
                event.preventDefault();
                var enable = $(this).data('enable-target');
                var disable = $(this).data('disable-target');
                $(enable).removeAttr('disabled');
                $(disable).attr('disbaled', 'disabled');
            });

        });
    },
    summernote(){
        $('.summernote').summernote({
            lang: 'pt-BR',
            height: 200,
            placeholder: '',
            // tooltip: false,
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
    },
    callClick (){
        $('.callClick').each(function(i, el){
            var target = $(el).data('callclick-target');
            $(el).unbind('keypress')
            $(el).on('keypress', function(e){
                if(e.which == 13) {
                    $(target).click();
                }
            });
        });
    },
    tree (){
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

    },
    init (){
        this.cloneObject();
        this.removeObject();
        this.showDivAndHideAnother();
        this.enableDisableButton();
        this.summernote();
        this.callClick();
        this.tree();

        $('[data-toggle="popover"][data-timeout]').on('shown.bs.popover', function() {
            this_popover = $(this);
            setTimeout(function () {
                this_popover.popover('hide');
            }, $(this).data("timeout"));
        });


    }
};

module.exports = WatsonPanel;