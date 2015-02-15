<?php

function issuu_panel_quick_sort($array, $order = 'asc')
{
	$length = count($array);
	
	if ($length <= 1)
	{
		return $array;
	}
	else
	{
		$pivot = $array[0];
		$left = $right = array();
		$c = count($array);
		for ($i = 1; $i < $c; $i++) {
			if ($order == 'asc')
			{
				if ($array[$i]['pubTime'] < $pivot['pubTime'])
				{
					$left[] = $array[$i];
				}
				else
				{
					$right[] = $array[$i];
				}
			}
			else
			{
				if ($array[$i]['pubTime'] > $pivot['pubTime'])
				{
					$left[] = $array[$i];
				}
				else
				{
					$right[] = $array[$i];
				}
			}
		}
		
		return array_merge(issuu_panel_quick_sort($left, $order), array($pivot), issuu_panel_quick_sort($right, $order));
	}
}

function get_issuu_message($text)
{
	return __($text, ISSUU_PAINEL_DOMAIN_LANG);
}

function the_issuu_message($text)
{
	_e($text, ISSUU_PAINEL_DOMAIN_LANG);
}

function issuu_panel_link_page($page, $permalink, $page_name)
{
	$QUERY_STRING = $_SERVER['QUERY_STRING'];

	if (strpos($permalink, '?') === false)
	{
		if ($QUERY_STRING == "")
		{
			$link = $permalink . '?' . $page_name . '=' . $page;
		}
		else
		{
			if (strpos($QUERY_STRING, $page_name) === false)
			{
				$link = $permalink . '?' . $QUERY_STRING . '&' . $page_name . '=' . $page;
			}
			else
			{

				$QUERY_STRING = preg_replace("/($page_name=\d)/", $page_name . '=' . $page, $QUERY_STRING);
				$link = $permalink . '?' . $QUERY_STRING;
			}
		}
	}
	else
	{
		$pos = strpos($permalink, '?') + 1;
		$substr = substr($permalink, $pos);
		$arr = array($substr => '');
		$QUERY_STRING = strtr($QUERY_STRING, $arr);
		$QUERY_STRING = preg_replace('/\&' . $page_name . '\=\d/', '', $QUERY_STRING);

		if ($QUERY_STRING == "")
		{
			$link = $permalink . '&' . $page_name . '=' . $page;
		}
		else
		{
			if (strpos($QUERY_STRING, $page_name) === false)
			{
				$link = $permalink . '&' . $QUERY_STRING . '&' . $page_name . '=' . $page;
			}
			else
			{
				$QUERY_STRING = preg_replace("/($page_name=\d)/", $page_name . '=' . $page, $QUERY_STRING);
				$link = $permalink . '&' . $QUERY_STRING;
			}
		}
	}

	$link = preg_replace('/\&{2,}/', '&', $link);

	return $link;
}

function add_issuu_panel_button()
{
	if (!current_user_can('edit_posts') && !current_user_can('edit_pages'))
		return;
	if (get_user_option('rich_editing') == 'true')
	{
		add_filter('mce_external_plugins', 'add_issuu_panel_tinymce_plugin');
		add_filter('mce_buttons', 'register_issuu_panel_button');
	}
}

add_action('init', 'add_issuu_panel_button');

function register_issuu_panel_button($buttons)
{
	array_push($buttons, "|", "issuupanel");
	return $buttons;
}

function add_issuu_panel_tinymce_plugin($plugin_array)
{
	$plugin_array['issuupanel'] = ISSUU_PAINEL_URL . 'js/tinymce-button.js';
	return $plugin_array;
}

function issuu_panel_refresh_mce($ver)
{
	$ver += 3;
	return $ver;
}

add_filter('tiny_mce_version', 'issuu_panel_refresh_mce');

function issuu_panel_activation_hook()
{
	add_option(ISSUU_PAINEL_PREFIX . 'api_key', '');
	add_option(ISSUU_PAINEL_PREFIX . 'api_secret', '');
	add_option(ISSUU_PAINEL_PREFIX . 'enabled_user', 'Administrator');
}

function issuu_panel_uninstall_hook()
{
	delete_option(ISSUU_PAINEL_PREFIX . 'api_key');
	delete_option(ISSUU_PAINEL_PREFIX . 'api_secret');
	delete_option(ISSUU_PAINEL_PREFIX . 'enabled_user');
}

function issuu_panel_tinymce_ajax()
{
	global $issuu_panel_api_key, $issuu_panel_api_secret;

	$issuu_folder = new IssuuFolder($issuu_panel_api_key, $issuu_panel_api_secret);
	$result = $issuu_folder->issuuList();
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

add_action('wp_ajax_issuu_panel_tinymce_ajax', 'issuu_panel_tinymce_ajax');

function issuu_panel_wp_enqueue_scripts()
{
	wp_enqueue_style('issuu-painel-documents', ISSUU_PAINEL_URL . 'css/issuu-painel-documents.css');
	wp_enqueue_script(
		'issuu-iframe-link',
		ISSUU_PAINEL_URL . 'js/issuu-iframe-link.min.js',
		array('jquery'),
		null,
		true
	);
	wp_enqueue_script(
		'issuu-replace-tags',
		ISSUU_PAINEL_URL . 'js/replace-tags-p.min.js',
		array('jquery'),
		null,
		true
	);
}

add_action('wp_enqueue_scripts', 'issuu_panel_wp_enqueue_scripts');

function issuu_panel_admin_enqueue_scripts()
{
	wp_enqueue_style(
		'issuu-painel-pagination',
		ISSUU_PAINEL_URL . 'css/issuu-painel-pagination.css',
		array(),
		null,
		'screen, print'
	);
	wp_enqueue_style('document-list', ISSUU_PAINEL_URL . 'css/document-list.css', array(), null, 'screen, print');
	wp_enqueue_style('folder-list', ISSUU_PAINEL_URL . 'css/folder-list.css', array('dashicons'), null, 'screen, print');
	wp_enqueue_script('json2');
	wp_enqueue_script('jquery');

	if (isset($_GET['page']) && $_GET['page'] == 'issuu-document-admin')
	{
		if (isset($_GET['upload']))
		{
			wp_enqueue_script(
				'issuu-painel-document-upload-js',
				ISSUU_PAINEL_URL . 'js/document-upload.min.js',
				array('jquery'),
				null,
				true
			);
		}
		else if (isset($_GET['update']))
		{
			wp_enqueue_script(
				'issuu-painel-document-update-js',
				ISSUU_PAINEL_URL . 'js/document-update.min.js',
				array('jquery'),
				null,
				true
			);
		}
		else if (isset($_GET['url_upload']))
		{
			wp_enqueue_script(
				'issuu-painel-document-url-upload-js',
				ISSUU_PAINEL_URL . 'js/document-url-upload.min.js',
				array('jquery'),
				null,
				true
			);
		}
	}
}

add_action('admin_enqueue_scripts', 'issuu_panel_admin_enqueue_scripts');

function issuu_panel_init_hook()
{
	global $issuu_panel_api_key, $issuu_panel_api_secret, $issuu_painel_capabilities, $issuu_painel_capacity;

	if ($_SERVER['REQUEST_METHOD'] == 'POST' && (isset($_GET['page']) && $_GET['page'] == ISSUU_PAINEL_MENU))
	{
		update_option(ISSUU_PAINEL_PREFIX . 'api_key', trim($_POST['api_key']));
		update_option(ISSUU_PAINEL_PREFIX . 'api_secret', trim($_POST['api_secret']));

		if (in_array($_POST['enabled_user'], array('Administrator', 'Editor', 'Author')))
		{
			update_option(ISSUU_PAINEL_PREFIX . 'enabled_user', $_POST['enabled_user']);
		}
		else
		{
			$_POST['enabled_user'] = 'Administrator';
			update_option(ISSUU_PAINEL_PREFIX . 'enabled_user', 'Administrator');
		}

		$issuu_panel_api_key = trim($_POST['api_key']);
		$issuu_panel_api_secret = trim($_POST['api_secret']);
		$issuu_painel_capacity = $issuu_painel_capabilities[$_POST['enabled_user']];
	}
	else
	{
		$issuu_panel_api_key = get_option(ISSUU_PAINEL_PREFIX . 'api_key');
		$issuu_panel_api_secret = get_option(ISSUU_PAINEL_PREFIX . 'api_secret');
		$issuu_panel_enabled_user = get_option(ISSUU_PAINEL_PREFIX . 'enabled_user');
		$issuu_painel_capacity = $issuu_painel_capabilities[$issuu_panel_enabled_user];
	}
}

add_action('init', 'issuu_panel_init_hook');

function ip_menu_admin()
{
	global $issuu_panel_api_key, $issuu_panel_api_secret;

	do_action(ISSUU_PAINEL_PREFIX . 'menu_page');

	if ((!is_null($issuu_panel_api_key) && strlen($issuu_panel_api_key) > 0) && (!is_null($issuu_panel_api_secret) && strlen($issuu_panel_api_secret) > 0))
	{
		do_action(ISSUU_PAINEL_PREFIX . 'submenu_pages');
	}
}

add_action('admin_menu', 'ip_menu_admin');