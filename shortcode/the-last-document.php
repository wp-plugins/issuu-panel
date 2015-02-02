<?php

function issuu_panel_the_last_document($atts)
{
	global $api_key, $api_secret;

	$doc = array();

	$atts = shortcode_atts(
		array(
			'id' => '',
			'link' => '',
			'order_by' => 'publishDate',
			'result_order' => 'desc',
			'per_page' => '12'
		),
		$atts
	);

	if (trim($atts['id']) != '')
	{
		$issuu_bookmark = new IssuuBookmark($api_key, $api_secret);
		include(ISSUU_PAINEL_DIR . 'shortcode/the-last-document-folder.php');
	}
	else
	{
		$issuu_document = new IssuuDocument($api_key, $api_secret);
		$params = array(
			'resultOrder' => 'desc',
			'startIndex' => '0',
			'documentSortBy' => $atts['order_by'],
			'pageSize' => '1'
		);
		$docs = $issuu_document->issuuList($params);
		$docs = $docs['document'];
		$doc = array(
			'thumbnail' => 'http://image.issuu.com/' . $docs[0]->documentId . '/jpg/page_1_thumb_large.jpg'
		);
	}

	$content = '';

	if (!empty($doc))
	{
		if ($atts['link'] != '')
		{
			$content .= '<a href="' . $atts['link'] . '">';
		}

		$content .= '<img src="' . $doc['thumbnail'] . '">';

		if ($atts['link'] != '')
		{
			$content .= '</a>';
		}
	}
	else
	{
		$content = '<p>';
		$content .= get_issuu_message('No documents');
		$content .= '</p>';
	}

	return $content;
}

add_shortcode('issuu-panel-last-document', 'issuu_panel_the_last_document');