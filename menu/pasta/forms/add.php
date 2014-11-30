<h1><?php the_issuu_message('Create new folder'); ?></h1>
<form action="" id="add-folder" method="post" accept-charset="utf-8">
	<table class="form-table">
		<tbody>
			<tr>
				<th><label for="folderName"><?php the_issuu_message("Folder's name"); ?></label></th>
				<td><input type="text" name="folderName" id="folderName" class="regular-text code"></td>
			</tr>
			<tr>
				<th><label for="folderDescription"><?php the_issuu_message('Description'); ?></label></th>
				<td><textarea name="folderDescription" id="folderDescription" cols="45" rows="6"></textarea></td>
			</tr>
			<tr>
				<th>
					<input type="submit" value="<?php the_issuu_message('Save'); ?>" class="button-primary">
					<h3>
						<a href="admin.php?page=issuu-folder-admin" style="text-decoration: none;">
							<?php the_issuu_message('Back'); ?>
						</a>
					</h3>
				</th>
			</tr>
		</tbody>
	</table>
</form>
<script type="text/javascript" charset="utf-8">
	(function($){
		$('#add-folder').submit(function(){
			if ($('#folderName').val().trim() == "")
			{
				alert('<?php the_issuu_message("Insert folder\'s name"); ?>');
				return false;
			}
		});
	})(jQuery);
</script>