define([
    'jquery',
    'mage/template',
    'mage/translate',
    'domReady'
], function($, mageTemplate) {
    'use strict';
    
    return {
        loadComments: function(config){
            var AjaxCommentLoadUrl = config.AjaxCommentLoadUrl;
            var AjaxPostId         = config.AjaxPostId;
            $.ajax({
                url: AjaxCommentLoadUrl,
                type: 'POST',
                data: {
                    post_id: AjaxPostId
                }
            }).done(function(data){
                var template = mageTemplate('#blog-comment');
                $('ul#data').empty();
                if(!data) return false;
                var comments = data.items;
                comments.forEach(function(cmt){
                    var newField = template({
                        cmt: cmt
                    });
                    $('ul#data').append(newField);
                });
            });
        }
    };
});