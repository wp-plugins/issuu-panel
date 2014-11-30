<?php

foreach ($_POST as $key => $value) {
	$_POST[$key] = trim($value);
}

$response = $issuu_folder->update($_POST);

if ($response['stat'] == 'ok')
{
	echo '<div class="updated"><p>' . get_issuu_message('Folder updated successfully') . '</p></div>';
}
else
{
	echo '<div class="error"><p>' . get_issuu_message('Error while updating the folder') . ' - ' .
		$response['message'] . '</p></div>';
}