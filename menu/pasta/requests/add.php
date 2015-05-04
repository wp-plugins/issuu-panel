<?php

foreach ($_POST as $key => $value) {
	if (($_POST[$key] = trim($value)) == "")
	{
		unset($_POST[$key]);
	}
}

$response = $issuu_folder->add($_POST);

if ($response['stat'] == 'ok')
{
	echo '<div class="updated"><p>' . get_issuu_message('Folder created successfully') . '</p></div>';
}
else
{
	echo '<div class="error"><p>' . get_issuu_message('Error while creating the folder') . ' - ' .
		$response['message'] . (($response['field'] != '')? ' :' . $response['field'] : '') . '</p></div>';
}