<?php

if (trim($atts['order_by']) == 'publishDate')
{
	$params = array(
		'folderId' => $atts['id'],
		'pageSize' => 30,
		'startIndex' => 0
	);

	$bookmarks = $issuu_bookmark->issuuList($params);

	if ($bookmarks['stat'] == 'ok')
	{
		if (isset($bookmarks['bookmark']) && !empty($bookmarks['bookmark']))
		{
			$docs = array();
			$issuu_document = new IssuuDocument($api_key, $api_secret);

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

			$docs = issuu_panel_quick_sort($docs, 'desc');
			$doc = $docs[0];
		}
	}
}
else
{
	$params = array(
		'folderId' => $atts['id'],
		'pageSize' => 1,
		'startIndex' => 0,
		'resultOrder' => $atts['result_order'],
		'bookmarkSortBy' => 'desc'
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
		}
	
		$doc = $docs[0];
	}
}