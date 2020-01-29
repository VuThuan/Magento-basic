define([
	"jquery",
	"jquery/ui",
	"loadnotification"
], function($, ui, notification) {
	"use strict";

	function mains(config, element) {
		var $element = $(element);
		var page = 1;
		$(document).on('click', '#load-more',function(){
			event.preventDefault();
			page++;
			notification.loadNotifications(config, page);
		});
	};
	return mains;
});