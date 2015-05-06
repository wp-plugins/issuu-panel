var issuuPanel = {
	params : {
	    allowfullscreen: 'true',
	    menu: 'false',
	    wmode: 'transparent'
	},
	flashvars : {
	    jsAPIClientDomain: window.location.hostname,
	    mode: 'mini'
	}
};
(function($){
	$('[data-issuu-viewer]').each(function(){
		issuuPanel.flashvars.documentId = $(this).data('document-id');
		var id = $(this).data("issuu-viewer");
		swfobject.embedSWF(
			"http://static.issuu.com/webembed/viewers/style1/v2/IssuuReader.swf",
			id,
			"100%",
			"323",
			"9.0.0",
			"http://static.issuu.com/webembed/viewers/style1/v2/IssuuReader.swf",
			issuuPanel.flashvars,
			issuuPanel.params,
			{id : id}
		);
	});
	$('.link-issuu-document').click(function(e){
		e.preventDefault();
		issuuPanel.flashvars.documentId = $(this).attr('href');
		var id = $(this).data('target');

		swfobject.embedSWF(
	    	"http://static.issuu.com/webembed/viewers/style1/v2/IssuuReader.swf",
	    	id,
	    	"100%",
	    	"323",
	    	"9.0.0",
	    	"http://static.issuu.com/webembed/viewers/style1/v2/IssuuReader.swf",
	    	issuuPanel.flashvars,
	    	issuuPanel.params,
	    	{id : id}
	    );

		var top = $('#' + id).offset().top - 50;
		$('html, body').animate({scrollTop : top}, 'slow');
	});

	$('.issuu-painel-paginate').each(function(){
		var paginate_html = $(this).html();
		var regex = /(\<p\>|\<\/p\>)/;

		paginate_html = paginate_html.replace(regex, '');
		$(this).html(paginate_html);
	});
})(jQuery);