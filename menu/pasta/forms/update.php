<h1><?php the_issuu_message("Update folder"); ?></h1>
<form action="" id="update-folder" method="post" accept-charset="utf-8">
	<input type="hidden" name="folderId" value="<?= $fo->folderId; ?>">
	<table class="form-table">
		<tbody>
			<tr>
				<th><label for="folderName"><?php the_issuu_message("Folder's name"); ?></label></th>
				<td>
					<input type="text" name="folderName" id="folderName" class="regular-text code"
						value="<?= $fo->name; ?>">
				</td>
			</tr>
			<tr>
				<th><label for="folderDescription"><?php the_issuu_message('Description'); ?></label></th>
				<td>
					<textarea name="folderDescription" id="folderDescription"
						cols="45" rows="6"><?= $fo->description; ?></textarea>
				</td>
			</tr>
			<tr>
				<th>
					<input type="submit" value="<?php the_issuu_message('Update'); ?>" class="button-primary">
					<h3>
						<a href="admin.php?page=issuu-folder-admin" style="text-decoration: none;">
							<?php the_issuu_message('Back'); ?>
						</a>
					</h3>
				</th>
			</tr>
		</tbody>
	</table>
	<?php if (isset($folders_documents['documentsId']) && !empty($folders_documents['documentsId'])) : ?>
		<h3>Shortcode</h3>
		<input type="text" class="code shortcode" disabled size="70"
			value='[issuu-painel-folder-list id="<?= $fo->folderId; ?>"]'>
	<?php endif; ?>
	<div id="document-list">
		<?php if (isset($folders_documents['documentsId']) && !empty($folders_documents['documentsId'])) : ?>
		<h3><?php the_issuu_message("Folder's documents"); ?></h3>
			<?php foreach ($folders_documents['documentsId'] as $doc) : ?>
				<div class="document complete">
					<div class="document-box">
						<img src="<?= sprintf($image, $doc->documentId) ?>" alt="">
					</div>
					<p class="description"><?= $doc->title ?></p>
				</div>
			<?php endforeach; ?>
		<?php endif; ?>
	</div>
</form>
<script type="text/javascript" charset="utf-8">
	(function($){
		$('#update-folder').submit(function(){
			if ($('#folderName').val().trim() == "")
			{
				alert('<?php the_issuu_message("Insert folder\'s name"); ?>');
				return false;
			}
		});
	})(jQuery);
</script>