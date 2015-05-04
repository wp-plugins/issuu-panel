<?php

class IssuuPanelTinyMCEButton
{
	public function __construct()
	{
		add_action('init', array($this, 'init'));
		add_filter('tiny_mce_version', array($this, 'issuuPanelRefreshMCE'));
		add_action('wp_ajax_issuu_panel_tinymce_ajax', array($this, 'tinymceButtonPage'));
		issuu_panel_debug("TinyMCE Button");
	}

	public function init()
	{
		if (!current_user_can('edit_posts') && !current_user_can('edit_pages'))
			return;

		if (get_user_option('rich_editing') == 'true')
		{
			add_filter('mce_external_plugins', array($this, 'addIssuuPanelTinyMCEPlugin'));
			add_filter('mce_buttons', array($this, 'registerIssuuPanelButton'));
		}
	}

	public function addIssuuPanelTinyMCEPlugin($plugin_array)
	{
		$plugin_array['issuupanel'] = ISSUU_PAINEL_URL . 'js/tinymce-button.js';
		return $plugin_array;
	}

	public function registerIssuuPanelButton($buttons)
	{
		array_push($buttons, "|", "issuupanel");
		return $buttons;
	}

	public function issuuPanelRefreshMCE($ver)
	{
		$ver += 3;
		return $ver;
	}

	public function tinymceButtonPage()
	{
		global $issuu_panel_api_key, $issuu_panel_api_secret;

		try {
			$issuu_folder = new IssuuFolder($issuu_panel_api_key, $issuu_panel_api_secret);
			$result = $issuu_folder->issuuList();
			issuu_panel_debug("TinyMCE Modal URL folder - " . $issuu_folder->buildUrl());
		} catch (Exception $e) {
			issuu_panel_debug("TinyMCE Modal Exception - " . $e->getMessage());
			die();
		}
		
		?>

		<!DOCTYPE html>
		<html>
		<head>
			<title>Issuu Panel Shortcode</title>
			<meta charset="utf-8">
			<link rel="stylesheet" href="<?= ISSUU_PAINEL_URL; ?>css/issuu-painel-tinymce-popup.css">
			<?php
				wp_enqueue_script('tiny_mce_popup.js', includes_url('js/tinymce/tiny_mce_popup.js'));
				wp_print_scripts('jquery');
				wp_print_scripts('tiny_mce_popup.js');
			?>
		</head>
		<body>
			<form action="#" id="issuu-painel-form-popup">
				<div id="issuu-painel-table">
					<div class="issuu-painel-table-row">
						<div class="issuu-painel-table-cell"><?php the_issuu_message('Folder'); ?></div>
						<div class="issuu-painel-table-cell">
							<select name="folderId" id="folderId">
								<option value="none"><?php the_issuu_message('Select...'); ?></option>
								<?php if ($result['stat'] == 'ok' && (isset($result['folder']) && !empty($result['folder']))) : ?>
									<?php foreach ($result['folder'] as $folder) : ?>
										<option value="<?= $folder->folderId; ?>"><?= $folder->name; ?></option>
									<?php endforeach; ?>
								<?php endif; ?>
							</select>
						</div>
					</div>
					<div class="issuu-painel-table-row">
						<div class="issuu-painel-table-cell"><?php the_issuu_message('Documents per page'); ?></div>
						<div class="issuu-painel-table-cell">
							<input type="text" name="per_page" id="per_page" maxlength="2" size="1">
						</div>
					</div>
					<div class="issuu-painel-table-row">
						<div class="issuu-painel-table-cell"><?php the_issuu_message('Order'); ?></div>
						<div class="issuu-painel-table-cell">
							<select name="result_order" id="result_order">
								<option value="asc"><?php the_issuu_message('Ascending'); ?></option>
								<option value="desc"><?php the_issuu_message('Descending'); ?></option>
							</select>
						</div>
					</div>
					<div class="issuu-painel-table-row">
						<div class="issuu-painel-table-cell"><?php the_issuu_message('Order by'); ?></div>
						<div class="issuu-painel-table-cell">
							<select name="order_by" id="order_by">
								<option value="title"><?php the_issuu_message('Title'); ?></option>
								<option value="publishDate"><?php the_issuu_message('Publish date'); ?></option>
								<option value="description"><?php the_issuu_message('Description'); ?></option>
								<option value="documentId"><?php the_issuu_message('Document ID'); ?></option>
							</select>
						</div>
					</div>
					<div class="issuu-painel-table-row">
						<div class="issuu-painel-table-cell">
							<input type="submit" class="issuu-submit-button" value="<?php the_issuu_message('Insert'); ?>">
						</div>
						<div class="issuu-painel-table-cell">
							<input type="button" class="issuu-cancel-button" value="<?php the_issuu_message('Cancel'); ?>"
								onClick="tinyMCEPopup.close();">
						</div>
					</div>
				</div>
			</form>
			<script type="text/javascript">
				(function($){
					$('#per_page').keypress(function(e){
						if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
							return false;
						}
					});

					$('#issuu-painel-form-popup').submit(function(){
						var folder_id = $('#folderId').val();
						var per_page = $('#per_page').val();
						var result_order = $('#result_order').val();
						var order_by = $('#order_by').val();

						if (folder_id == 'none')
						{
							var shortcode = '[issuu-painel-document-list ';
						}
						else
						{
							var shortcode = '[issuu-painel-folder-list id="' + folder_id + '" ';
						}

						if (per_page.length > 0)
						{
							per_page = parseInt(per_page);

							if (per_page < 0 || per_page > 30)
							{
								per_page = 12;
							}

							shortcode += 'per_page="' + per_page + '" ';
						}

						shortcode += 'result_order="' + result_order + '" ';
						shortcode += 'order_by="' + order_by + '"]';

						tinyMCEPopup.execCommand('mceInsertContent', false, shortcode);
						tinyMCEPopup.close();
					});
				})(jQuery);
			</script>
		</body>
		</html>

		<?php
		die();
	}
}

new IssuuPanelTinyMCEButton();