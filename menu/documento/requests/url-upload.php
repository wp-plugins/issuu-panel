<?php

$data = true;

foreach ($_POST['pub'] as $key => $value) {
	if ($value != '') $data = false;
}

if ($data)
{
	$_POST['publishDate'] = $datetime;	
}
else
{
	if ($_POST['pub']['day'] == '' || $_POST['pub']['month'] == '' || $_POST['pub']['year'] == '')
	{
		$_POST['publishDate'] = $date;
	}
	else
	{
		$_POST['publishDate'] = $_POST['pub']['year'] . '-' . $_POST['pub']['month'] . '-' . $_POST['pub']['day'] . 'T';
	}

	if ($_POST['pub']['hour'] == '' || $_POST['pub']['min'] == '')
	{
		$_POST['publishDate'] .= $time;
	}
	else
	{
		if ($_POST['pub']['sec'] == '')
		{
			$_POST['pub']['sec'] = '00';
		}
		else
		{
			if (strlen($_POST['pub']['sec']) == 1)
			{
				$_POST['pub']['sec'] = '0' . $_POST['pub']['sec'];
			}

			$_POST['pub']['sec'] = ':' . $_POST['pub']['sec'];

			if ($_POST['pub']['sec'] == ':00')
			{
				$_POST['pub']['sec'] = '';
			}

			$_POST['pub']['sec'] = $_POST['pub']['sec'];
		}

		if ($_POST['pub']['hour'] == '')
		{
			$_POST['pub']['hour'] = '00';
		}
		else
		{
			if (strlen($_POST['pub']['hour']) == 1)
			{
				$_POST['pub']['hour'] = '0' . $_POST['pub']['hour'];
			}
		}

		if ($_POST['pub']['hour'] == '')
		{
			$_POST['pub']['hour'] = '00';
		}
		else
		{
			if (strlen($_POST['pub']['min']) == 1)
			{
				$_POST['pub']['min'] = '0' . $_POST['pub']['min'];
			}
		}
		
		$_POST['publishDate'] .= $_POST['pub']['hour'] . ':' . $_POST['pub']['min'] . $_POST['pub']['sec'] . 'Z';
	}
}

unset($_POST['pub']);

if (isset($_POST['folder']) && !empty($_POST['folder']))
{
	$count = count($_POST['folder']);
	for ($i = 0; $i < $count; $i++) {
		if ($i == ($count - 1))
		{
			$_POST['folderIds'] .= $_POST['folder'][$i];
		}
		else
		{
			$_POST['folderIds'] .= $_POST['folder'][$i] . ',';
		}
	}
}

unset($_POST['folder']);

if (trim($_POST['name']) != '')
{
	$_POST['name'] = str_replace(" ", "", $_POST['name']);
}

if (!isset($_POST['commentsAllowed']) || trim($_POST['commentsAllowed']) != 'true')
{
	$_POST['commentsAllowed'] = 'false';
}

if (!isset($_POST['downloadable']) || trim($_POST['downloadable']) != 'true')
{
	$_POST['downloadable'] = 'false';
}

foreach ($_POST as $key => $value) {
	$_POST[$key] = trim($value);
}

$result = $issuu_document->urlUpload($_POST);

if ($result['stat'] == 'ok')
{
	echo '<div class="updated"><p>' . get_issuu_message('Document sent successfully') . '</p></div>';
}
else if ($result['stat'] == 'fail')
{
	echo '<div class="error"><p>' . $result['message'] . ': ' . $result['field'] . '</p></div>';
}