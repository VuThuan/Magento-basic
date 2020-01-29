define([
	"jquery",
    "mage/template",
    "mage/translate",
    "domReady!"
], function($, mageTemplate) {
	"use strict";
	
	return {
        loadNotifications : function(config, page=1, expand = 0){
			var AjaxNotificationLoadUrl = config.AjaxNotificationLoadUrl;
			$.ajax({
				url: AjaxNotificationLoadUrl,
				type: 'POST',
				data: {
					page: page,
					expand: expand
				}
			}).done(function(data){
				console.log(data);
				if(!data) return false;
				if(data=="end")
				{
					$('li.dropdown-item').last().remove();
					return;
				}
				$('#noti-count').html(data.unreadRecords);
				$('li.dropdown-item').last().remove();
				var template = mageTemplate('#blog-notification');
				//$('ul#notification-content').empty();
				
				var notis = data.items;
				notis.forEach(function(noti){
					var newField = template({
			            noti: noti
			        });

			        $('ul#notification-content').append(newField);
			    });
			    if(Math.ceil(data.totalRecords/5)>=page)
			    {
			    	var loadMore = '<li class="dropdown-item"><button align="center" id="load-more">Load More</button></li>';
			    	$('ul#notification-content').append(loadMore);
			    }
			    
			});
        }
    };
});