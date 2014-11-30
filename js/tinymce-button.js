(function(){
	tinymce.create('tinymce.plugins.IssuuPainel', {
		init : function(ed, url) {
			var popupIssuu = url + '/tinymce/ola-mundo.php';

			ed.addCommand('IssuuPainelCommand', function(){
				ed.windowManager.open({
					file: ajaxurl + '?action=issuu_painel_tinymce_ajax',
					width: 420,
					height: 235,
					inline: 1
				});
			});

			ed.addButton('issuupainel', {
				title : 'Issuu Painel Shortcode',
				image : url + '/../images/issuu-painel-tinymce-button.png',
				cmd: 'IssuuPainelCommand'
			});
		},
		getInfo : function(){
			return {
				longname : "Issuu Painel",
				author : 'Pedro Marcelo',
				authorurl : 'https://github.com/pedromarcelojava/',
				infourl : 'https://github.com/pedromarcelojava/Issuu-Painel/',
				version : "1.0"
			};
		}
	});
	tinymce.PluginManager.add('issuupainel', tinymce.plugins.IssuuPainel);
})();