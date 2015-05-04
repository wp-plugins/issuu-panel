<?php

class IssuuPageDocuments extends IssuuPanelSubmenu
{
	protected $slug = 'issuu-document-admin';

	protected $page_title = 'Documents';

	protected $menu_title = 'Documents';

	protected $priority = 1;

	public function page()
	{
		global $issuu_panel_api_key, $issuu_panel_api_secret;
		issuu_panel_debug("Issuu Panel Page (Documents)");

		echo '<div class="wrap">';

		try {
			$issuu_document = new IssuuDocument($issuu_panel_api_key, $issuu_panel_api_secret);
		} catch (Exception $e) {
			issuu_panel_debug("Page Exception - " . $e->getMessage());
			return "";
		}

		if (isset($_GET['upload']) && !isset($_POST['delete']))
		{
			if ($_SERVER['REQUEST_METHOD'] == 'POST')
			{
				$date = date_i18n('Y-m-d') . 'T';
				$time = date_i18n('H:i:s') . 'Z';
				$datetime = $date . $time;
				
				try {
					require(ISSUU_PAINEL_DIR . 'menu/documento/requests/upload.php');
				} catch (Exception $e) {
					issuu_panel_debug("Document Upload Exception - " . $e->getMessage());
					return "";
				}
			}
			else
			{
				try {
					$issuu_folder = new IssuuFolder($issuu_panel_api_key, $issuu_panel_api_secret);
					$folders = $issuu_folder->issuuList();
				} catch (Exception $e) {
					issuu_panel_debug("Page Exception - " . $e->getMessage());
					return "";
				}

				$cnt_f = (isset($folders['folder']))? count($folders['folder']) : 0;
				include(ISSUU_PAINEL_DIR . 'menu/documento/forms/upload.php');
				$load = true;
			}
		}
		else if (isset($_GET['url_upload']) && !isset($_POST['delete']))
		{
			if ($_SERVER['REQUEST_METHOD'] == 'POST')
			{
				$date = date_i18n('Y-m-d') . 'T';
				$time = date_i18n('H:i:s') . 'Z';
				$datetime = $date . $time;

				try {
					require(ISSUU_PAINEL_DIR . 'menu/documento/requests/url-upload.php');
				} catch (Exception $e) {
					issuu_panel_debug("Document URL Upload Exception - " . $e->getMessage());
					return "";
				}
			}
			else
			{
				try {
					$issuu_folder = new IssuuFolder($issuu_panel_api_key, $issuu_panel_api_secret);
					$folders = $issuu_folder->issuuList();
				} catch (Exception $e) {
					issuu_panel_debug("Page Exception - " . $e->getMessage());
					return "";
				}

				$cnt_f = (isset($folders['folder']))? count($folders['folder']) : 0;
				include(ISSUU_PAINEL_DIR . 'menu/documento/forms/url-upload.php');
				$load = true;
			}
		}
		else if (isset($_GET['update']) && strlen($_GET['update']) > 0)
		{
			$load = true;

			if ($_SERVER['REQUEST_METHOD'] == 'POST')
			{
				$date = date_i18n('Y-m-d') . 'T';
				$time = date_i18n('H:i:s') . 'Z';
				$datetime = $date . $time;

				try {
					require(ISSUU_PAINEL_DIR . 'menu/documento/requests/update.php');
				} catch (Exception $e) {
					issuu_panel_debug("Document Update Exception - " . $e->getMessage());
					return "";
				}

				$doc = $result;
				$load = false;
			}
			else
			{
				$params['name'] = strtr($_GET['update'], array('%20' => '+', ' ' => '+'));

				try {
					$doc = $issuu_document->update($params);
				} catch (Exception $e) {
					issuu_panel_debug("Page Exception - " . $e->getMessage());
					return "";
				}
			}

			if ($doc['stat'] == 'ok' && !empty($doc['document']))
			{
				if ($load)
				{
					$doc = $doc['document'];
				}
				else
				{
					$doc = $doc['document'];
				}
			}
			else
			{
				echo '<div class="error"><p>' . get_issuu_message('No documents found') . '</p></div>';
				exit;
			}

			$tags = '';

			if ($doc->tags)
			{
				foreach ($doc->tags as $tag) {
					$tags .= $tag . ',';
				}
			}

			if (($length = strlen($tags)) > 0)
			{
				$tags = substr($tags, 0, $length - 1);
			}

			include(ISSUU_PAINEL_DIR . 'menu/documento/forms/update.php');
		}

		if (!isset($load))
		{
			if ($_SERVER['REQUEST_METHOD'] == 'POST' && (isset($_POST['delete']) && $_POST['delete'] == 'true'))
			{
				try {
					require(ISSUU_PAINEL_DIR . 'menu/documento/requests/delete.php');
				} catch (Exception $e) {
					issuu_panel_debug("Document Delete Exception - " . $e->getMessage());
					return "";
				}
			}

			$image = 'http://image.issuu.com/%s/jpg/page_1_thumb_large.jpg';
			$page = (isset($_GET['pn']))? $_GET['pn'] : 1;
			$per_page = 10;
			$params = array(
				'pageSize' => $per_page,
				'startIndex' => $per_page * ($page - 1)
			);

			try {
				$docs = $issuu_document->issuuList($params);
			} catch (Exception $e) {
				issuu_panel_debug("Page Exception - " . $e->getMessage());
				return "";
			}
			
			if (isset($docs['totalCount']) && $docs['totalCount'] > $docs['pageSize'])
			{
				$number_pages = ceil($docs['totalCount'] / $per_page);
			}

			require(ISSUU_PAINEL_DIR . 'menu/documento/document-list.php');
		}
	}
}

new IssuuPageDocuments();