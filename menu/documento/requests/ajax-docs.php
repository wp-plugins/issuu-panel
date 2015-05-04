<?php

require($_GET['abspath'] . '/wp-load.php');

$document = new IssuuDocument(
	get_option(ISSUU_PAINEL_PREFIX . 'api_key'),
	get_option(ISSUU_PAINEL_PREFIX . 'api_secret')
);

$params['orgDocName'] = $_GET['name'];

$doc = $document->issuuList($params);

if ($doc['stat'] == 'ok')
{
	$doc = $doc['document'][0];

	if (intval($doc->coverWidth) != 0 && intval($doc->coverHeight) != 0)
	{
		$image = 'http://image.issuu.com/%s/jpg/page_1_thumb_large.jpg';

		$echo = '<input type="checkbox" name="name[]" class="issuu-checkbox" value="' . $doc->name . '">
				<div class="document-box">
					<img src="' . sprintf($image, $doc->documentId) . '">
					<div class="update-document">
						<a href="admin.php?page=issuu-document-admin&update=' . $doc->orgDocName . '">Editar</a>
					</div>
				</div>
				<p class="description">' . $doc->title . '</p>';

		echo $echo;
	}
	else
	{
		echo 'stat-fail';
	}
}