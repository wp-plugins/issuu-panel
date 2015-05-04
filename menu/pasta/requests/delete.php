<?php


$params['folderIds'] = '';
$count = count($_POST['folderId']);

if ($count > 0)
{
	for ($i = 0; $i < $count; $i++) {
		if ($i == ($count - 1))
		{
			$params['folderIds'] .= $_POST['folderId'][$i];
		}
		else
		{
			$params['folderIds'] .= $_POST['folderId'][$i] . ',';
		}
	}

	$result = $issuu_folder->delete($params);

	if ($result['stat'] == 'ok')
	{
		if ($count > 1)
		{
			echo '<div class="updated"><p>' . get_issuu_message('Folders deleted successfully') . '</p></div>';
		}
		else
		{
			echo '<div class="updated"><p>' . get_issuu_message('Folder deleted successfully') . '</p></div>';
		}
	}
	else if ($result['stat'] == 'fail')
	{
		echo '<div class="error"><p>' . $result['message'] . '</p></div>';
	}
}
else
{
	echo '<div class="update-nag">' . get_issuu_message('Nothing was excluded') . '</div>';
}