<?php

function issuu_painel_embed_documents_shortcode($atts)
{
	global $issuu_panel_api_key, $issuu_panel_api_secret, $issuu_shortcode_index;

	$issuu_shortcode_index++;
	$page_query_name = 'ip_shortcode' . $issuu_shortcode_index . '_page';

	$atts = shortcode_atts(
		array(
			'order_by' => 'publishDate',
			'result_order' => 'desc',
			'per_page' => '12'
		),
		$atts
	);

	$page = (isset($_GET[$page_query_name]) && is_numeric($_GET[$page_query_name]))?
		intval($_GET[$page_query_name]) : 1;

	$params = array(
		'pageSize' => $atts['per_page'],
		'startIndex' => ($atts['per_page'] * ($page - 1)),
		'resultOrder' => $atts['result_order'],
		'documentSortBy' => $atts['order_by']
	);

	$issuu_document = new IssuuDocument($issuu_panel_api_key, $issuu_panel_api_secret);
	$documents = $issuu_document->issuuList($params);

	if ($documents['stat'] == 'ok')
	{
		if (isset($documents['document']) && !empty($documents['document']))
		{
			$docs = array();
			$pagination = array(
				'pageSize' => $documents['pageSize'],
				'totalCount' => $documents['totalCount']
			);

			foreach ($documents['document'] as $doc) {
				$docs[] = array(
					'id' => $doc->documentId,
					'thumbnail' => 'http://image.issuu.com/' . $doc->documentId . '/jpg/page_1_thumb_large.jpg',
					'url' => 'http://issuu.com/' . $doc->username . '/docs/' . $doc->name,
					'title' => $doc->title,
					'date' => date_i18n('d/F/Y', strtotime($doc->publishDate)) 
				);
			}
			
			include(ISSUU_PAINEL_DIR . 'shortcode/generator.php');

			return $content;
		}
		else
		{
			return '<h3>' . get_issuu_message('No documents in list') . '</h3>';
		}
	}
	else
	{
		return '<h3>' . get_issuu_message($documents['message']) . '</h3>';
	}

}

add_shortcode('issuu-painel-document-list', 'issuu_painel_embed_documents_shortcode');