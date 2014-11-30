(function($){
	$('.link-issuu-document').click(function(e){
		var docId = $(this).attr('href');
		var $issuupainel = $(this).parent().parent().parent().parent();

		var script = '<div data-doc-id="' + docId + '" style="width: 100%; height: 323px;" class="issuuembed issuu-isrendered">';
		script += '<div style="width:100%; height:100%;">';
		script += '<div style="height:-moz-calc(100% - 18px); height:-webkit-calc(100% - 18px); height:-o-calc(100% - 18px); height:calc(100% - 18px);">';
		script += '<object id="issuu_18089929316192865" style="width:100%;height:100%" type="application/x-shockwave-flash" data="http://static.issuu.com/webembed/viewers/style1/v2/IssuuReader.swf">';
		script += '<param name="movie" value="http://static.issuu.com/webembed/viewers/style1/v2/IssuuReader.swf">';
		script += '<param name="flashvars" value="mode=mini&amp;pageNumber=issuu.com&amp;documentId=' + docId + '&amp;embedId=1">';
		script += '<param name="allowfullscreen" value="true">';
		script += '<param name="allowscriptaccess" value="always">';
		script += '<param name="menu" value="false">';
		script += '<param name="wmode" value="transparent">';
		script += '</object>';
		script += '</div>';
		script += '</div>';
		script += '</div>';

		$issuupainel.find('.issuu-iframe').html(script);
		e.preventDefault();
	});
})(jQuery);