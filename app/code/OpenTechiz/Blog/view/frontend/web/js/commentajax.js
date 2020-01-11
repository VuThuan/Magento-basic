define([
    "jquery",
    "jquery/ui",
    "loadcomments"
], function($) {
    "use strict";

    function main(config, element) {
        var $element = $(element);
        loadcomments.loadComments(config);

        var AjaxCommentPostUrl = config.AjaxCommentPostUrl;

        var dataForm = $('#comment-form');
        dataForm.mage('validation', {});

        $(document).on('click', '.submit', function() {
            if(dataForm.valid()) {
                event.preventDefault();
                var param = dataForm.serialize();

                $.ajax({
                    showLoader: true,
                    url: AjaxCommentPostUrl,
                    data: param,
                    type: "POST"
                }).done(function (data) {
                    if (data.result == 'error') {
                        $('.note').html(data.message);
                        $('.note').css('color','red');
                    } 
                    document.getElementById('comment-form').reset();
                    $('.note').html(data.message);
                    $('.note').css('color','green');
                    loadcomments.loadComments(config);
                });
            }
        });
    };
    return main;
})