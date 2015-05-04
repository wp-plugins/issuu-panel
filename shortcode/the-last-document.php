<?php

function issuu_panel_the_last_document($atts)
{
	global $issuu_panel_api_key, $issuu_panel_api_secret;
	issuu_panel_debug("Shortcode [issuu-panel-last-document]: Init");

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
		try {
			$issuu_bookmark = new IssuuBookmark($issuu_panel_api_key, $issuu_panel_api_secret);
		} catch (Exception $e) {
			issuu_panel_debug("Shortcode [issuu-panel-last-document]: Exception - " . $e->getMessage());
		}
		include(ISSUU_PAINEL_DIR . 'shortcode/the-last-document-folder.php');
	}
	else
	{
		try {
			$issuu_document = new IssuuDocument($issuu_panel_api_key, $issuu_panel_api_secret);
			$params = array(
				'resultOrder' => 'desc',
				'startIndex' => '0',
				'documentSortBy' => $atts['order_by'],
				'pageSize' => '1'
			);
			$docs = $issuu_document->issuuList($params);
			$docs = isset($docs['document'])? $docs['document'] : array();

			if (!empty($docs))
			{
				$doc = array(
					'thumbnail' => 'http://image.issuu.com/' . $docs[0]->documentId . '/jpg/page_1_thumb_large.jpg',
					'title' => $docs[0]->title
				);
			}
			else
			{
				$doc = array();
			}
		} catch (Exception $e) {
			issuu_panel_debug("Shortcode [issuu-panel-last-document]: Exception - " . $e->getMessage());
		}
	}

	$content = '';

	if (!empty($doc))
	{
		if ($atts['link'] != '')
		{
			$content .= '<a href="' . $atts['link'] . '">';
		}

		$content .= '<img id="issuu-panel-last-document" src="' . $doc['thumbnail'] . '" alt="' . $doc['title'] . '"">';

		if ($atts['link'] != '')
		{
			$content .= '</a>';
		}
		issuu_panel_debug("Shortcode [issuu-panel-last-document]: Document displayed");
	}
	else
	{
		$content = '<p>';
		$content .= get_issuu_message('No documents');
		$content .= '</p>';
		issuu_panel_debug("Shortcode [issuu-panel-last-document]: No documents");
	}

	$content .= '<!-- Issuu Panel | Developed by Pedro Marcelo de Sá Alves -->';

	return $content;
}

add_shortcode('issuu-panel-last-document', 'issuu_panel_the_last_document');