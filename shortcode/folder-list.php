<?php

function issuu_painel_embed_folder_shortcode($atts)
{
	global $api_key, $api_secret, $issuu_shortcode_index;

	$issuu_shortcode_index++;
	$page_query_name = 'ip_shortcode' . $issuu_shortcode_index . '_page';

	$atts = shortcode_atts(
		array(
			'id' => '',
			'order_by' => 'publishDate',
			'result_order' => 'desc',
			'per_page' => '12'
		),
		$atts
	);

	$page = (isset($_GET[$page_query_name]) && is_numeric($_GET[$page_query_name]))?
		intval($_GET[$page_query_name]) : 1;

	if (is_string($atts['id']) && strlen(trim($atts['id'])) > 0)
	{
		$issuu_bookmark = new IssuuBookmark($api_key, $api_secret);

		if (trim($atts['order_by']) == 'publishDate')
		{
			$params = array(
				'folderId' => $atts['id'],
				'pageSize' => $atts['per_page'],
				'startIndex' => ($atts['per_page'] * ($page - 1))
			);

			$bookmarks = $issuu_bookmark->issuuList($params);

			if ($bookmarks['stat'] == 'ok')
			{
				if (isset($bookmarks['bookmark']) && !empty($bookmarks['bookmark']))
				{
					$docs = array();
					$issuu_document = new IssuuDocument($api_key, $api_secret);

					$pagination = array(
						'pageSize' => $bookmarks['pageSize'],
						'totalCount' => $bookmarks['totalCount']
					);

					foreach ($bookmarks['bookmark'] as $book) {
						$document = $issuu_document->update(array('name' => $book->name));

						$docs[] = array(
							'id' => $book->documentId,
							'thumbnail' => 'http://image.issuu.com/' . $book->documentId . '/jpg/page_1_thumb_large.jpg',
							'url' => 'http://issuu.com/' . $book->username . '/docs/' . $book->name,
							'title' => $book->title,
							'pubTime' => strtotime($document['document']->publishDate)
						);
					}

					$docs = issuu_painel_quick_sort($docs, $atts['result_order']);

					include(ISSUU_PAINEL_DIR . 'shortcode/generator.php');

					return $content;
				}
				else
				{
					return '<h3>No documents in list</h3>';
				}
			}
			else
			{
				return '<h3>' . $bookmarks['message'] . '</h3>';
			}
		}
		else
		{
			$params = array(
				'folderId' => $atts['id'],
				'pageSize' => $atts['per_page'],
				'startIndex' => ($atts['per_page'] * ($page - 1)),
				'resultOrder' => $atts['result_order'],
				'bookmarkSortBy' => $atts['order_by']
			);

			$bookmarks = $issuu_bookmark->issuuList($params);

			if ($bookmarks['stat'] == 'ok')
			{
				if (isset($bookmarks['bookmark']) && !empty($bookmarks['bookmark']))
				{
					$docs = array();

					foreach ($bookmarks['bookmark'] as $book) {
						$docs[] = array(
							'id' => $book->documentId,
							'thumbnail' => 'http://image.issuu.com/' . $book->documentId . '/jpg/page_1_thumb_large.jpg',
							'url' => 'http://issuu.com/' . $book->username . '/docs/' . $book->name,
							'title' => $book->title
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
				return '<h3>' . get_issuu_message($bookmarks['message'])
					. ((trim($bookmarks['field']) != '')? ': ' . $bookmarks['field'] : '') . '</h3>';
			}
		}
	}
	else
	{
		return '<h3>' . get_issuu_message('Insert folder ID') . '</h3>';
	}
}

add_shortcode('issuu-painel-folder-list', 'issuu_painel_embed_folder_shortcode');