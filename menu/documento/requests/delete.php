<?php

$params['names'] = '';
$count = count($_POST['name']);

if ($count > 0)
{
	for ($i = 0; $i < $count; $i++) {
		if ($i == ($count - 1))
		{
			$params['names'] .= $_POST['name'][$i];
		}
		else
		{
			$params['names'] .= $_POST['name'][$i] . ',';
		}
	}

	$result = $issuu_document->delete($params);

	if ($result['stat'] == 'ok')
	{
		if ($count > 1)
		{
			echo '<div class="updated"><p>' . get_issuu_message('Documents deleted successfully') . '</p></div>';
		}
		else
		{
			echo '<div class="updated"><p>' . get_issuu_message('Document deleted successfully') . '</p></div>';
		}
	}
	else if ($result['stat'] == 'fail')
	{
		echo '<div class="error"><p>' . get_issuu_message($result['message'])
			. (($result['field'] != '')? ': ' . $result['field'] : '') .'</p></div>';
	}
}
else
{
	echo '<div class="update-nag">' . get_issuu_message('Nothing was excluded') . '</div>';
}