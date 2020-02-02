define([
	"jquery",
    "mage/template",
    "mage/translate",
    "domReady!"
], function($, mageTemplate) {
	"use strict";
	
	return {
        loadNotifications : function(config, page=1){
			var AjaxNotificationLoadUrl = config.AjaxNotificationLoadUrl;
			$.ajax({
				url: AjaxNotificationLoadUrl,
				type: 'POST',
				data: {
					page: page
				}
			}).done(function(data){
				console.log(data);
				if(!data) return false;
				
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