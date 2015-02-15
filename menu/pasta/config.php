<?php

add_action(ISSUU_PAINEL_PREFIX . 'submenu_pages', 'issuu_panel_menu_folder', 2);

function issuu_panel_menu_folder()
{
	global $issuu_painel_capacity;
	
	add_submenu_page(
		ISSUU_PAINEL_MENU,
		get_issuu_message('Folders'),
		get_issuu_message('Folders'),
		$issuu_painel_capacity,
		'issuu-folder-admin',
		'issuu_panel_menu_folder_init'
	);
}

function issuu_panel_menu_folder_init()
{
	global $issuu_panel_api_key, $issuu_panel_api_secret;

	echo '<div class="wrap">';

	$issuu_folder = new IssuuFolder($issuu_panel_api_key, $issuu_panel_api_secret);
	$issuu_document = new IssuuDocument($issuu_panel_api_key, $issuu_panel_api_secret);

	if (isset($_GET['add']))
	{
		if ($_SERVER['REQUEST_METHOD'] == 'POST'  && !isset($_POST['delete']))
		{
			include(ISSUU_PAINEL_DIR . 'menu/pasta/requests/add.php');
		}
		else
		{
			$load = true;

			include(ISSUU_PAINEL_DIR . 'menu/pasta/forms/add.php');
		}
	}
	else if (isset($_GET['folder']) && strlen($_GET['folder']) > 1)
	{
		$fo = $issuu_folder->update(array('folderId' => $_GET['folder']));

		if ($fo['stat'] == 'ok')
		{
			$issuu_bookmark = new IssuuBookmark($issuu_panel_api_key, $issuu_panel_api_secret);
			$bookmarks = $issuu_bookmark->issuuList(array('folderId' => $_GET['folder']));

			$fo = $fo['folder'];
			$image = 'http://image.issuu.com/%s/jpg/page_1_thumb_large.jpg';
			$folders_documents = array();

			$folders_documents['name'] = $fo->name;
			$folders_documents['items'] = $fo->items;

			if ($bookmarks['stat'] == 'ok' && isset($bookmarks['bookmark']) && !empty($bookmarks['bookmark']))
			{
				$folders_documents['documentsId'] = $bookmarks['bookmark'];
			}
			else
			{
				$folders_documents['documentsId'] = array();
			}

			include(ISSUU_PAINEL_DIR . 'menu/pasta/forms/update.php');
		}
		else
		{
			echo '<div class="error"><p>' . get_issuu_message('The folder does not exist') . '</p></div>';
		}

		$load = true;
	}

	if (!isset($load))
	{
		if ($_SERVER['REQUEST_METHOD'] == 'POST' && (isset($_POST['delete']) && $_POST['delete'] == 'true'))
		{
			include(ISSUU_PAINEL_DIR . 'menu/pasta/requests/delete.php');
		}

		$image = 'http://image.issuu.com/%s/jpg/page_1_thumb_large.jpg';
		$page = (isset($_GET['pn']))? $_GET['pn'] : 1;
		$per_page = 10;
		$params = array(
			'pageSize' => $per_page,
			'folderSortBy' => 'created',
			'startIndex' => $per_page * ($page - 1)
		);
		$folders_documents = array();

		$folders = $issuu_folder->issuuList($params);

		if (isset($folders['totalCount']) && $folders['totalCount'] > $folders['pageSize'])
		{
			$number_pages = ceil($folders['totalCount'] / $per_page);
		}

		if (isset($folders['folder']) && !empty($folders['folder']))
		{
			$issuu_bookmark = new IssuuBookmark($issuu_panel_api_key, $issuu_panel_api_secret);
	
			foreach ($folders['folder'] as $f) {
				$fId = $f->folderId;
				$folders_documents[$fId] = array(
					'name' => $f->name,
					'items' => $f->items
				);

				$bookmarks = $issuu_bookmark->issuuList(array('pageSize' => 3, 'folderId' => $fId));

				if ($bookmarks['stat'] == 'ok' && (isset($bookmarks['bookmark']) && !empty($bookmarks['bookmark'])))
				{
					$folders_documents[$fId]['documentsId'] = $bookmarks['bookmark'];
				}
				else
				{
					$folders_documents[$fId]['documentsId'] = array();
				}
			}
		}

		include(ISSUU_PAINEL_DIR . 'menu/pasta/folder-list.php');
	}

	echo '</div><!-- FIM wrap -->';
}