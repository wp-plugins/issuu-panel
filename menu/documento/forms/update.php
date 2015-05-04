<h1><?php the_issuu_message('Document'); ?></h1>
<form action="" method="post" id="document-upload">
	<input type="hidden" name="name" value="<?= $doc->name; ?>">
	<table class="form-table">
		<tbody>
			<tr>
				<th><label for="title"><?php the_issuu_message('Title'); ?></label></th>
				<td><input type="text" name="title" id="title" class="regular-text code" value="<?= $doc->title; ?>"></td>
			</tr>
			<tr>
				<th><label for="description"><?php the_issuu_message('Description'); ?></label></th>
				<td>
					<textarea name="description" id="description" cols="45" rows="6"><?= $doc->description; ?></textarea>
				</td>
			</tr>
			<tr>
				<th><label for="tags">Tags</label></th>
				<td>
					<textarea name="tags" id="tags" cols="45" rows="6"><?= $tags; ?></textarea>
					<p class="description">
						<?php the_issuu_message('Use commas to separate tags. Do not use spaces.'); ?>
					</p>
				</td>
			</tr>
			<tr>
				<th><label><?php the_issuu_message('Publish date'); ?></label></th>
				<td>
					<input type="text" name="pub[day]" id="dia" placeholder="<?php the_issuu_message('Day'); ?>" class="small-text"
						maxlength="2" value="<?= date('d', strtotime($doc->publishDate)); ?>"> /
					<input type="text" name="pub[month]" id="mes" placeholder="<?php the_issuu_message('Month'); ?>" class="small-text"
						maxlength="2" value="<?= date('m', strtotime($doc->publishDate)); ?>"> /
					<input type="text" name="pub[year]" id="ano" placeholder="<?php the_issuu_message('Year'); ?>" class="small-text"
						maxlength="4" value="<?= date('Y', strtotime($doc->publishDate)); ?>">
					<p class="description">
						<?php the_issuu_message('Date of publication of the document.<br><strong>NOTE:</strong> If you do not enter a value, the current date will be used'); ?>
					</p>
				</td>
			</tr>
			<tr>
				<th><label for="commentsAllowed"><?php the_issuu_message('Allow comments'); ?></label></th>
				<td>
					<input type="checkbox" name="commentsAllowed" id="commentsAllowed" value="true"
						<?= ($doc->commentsAllowed == true)? 'checked' : ''; ?>>
				</td>
			</tr>
			<tr>
				<th><label for="downloadable"><?php the_issuu_message('Allow file download'); ?></label></th>
				<td>
					<input type="checkbox" name="downloadable" id="downloadable" value="true"
						<?= ($doc->downloadable == true)? 'checked' : ''; ?>>
				</td>
			</tr>
			<tr>
				<th><label><?php the_issuu_message('Access'); ?></label></th>
				<td>
					<?php if ($doc->access == 'private') : ?>
						<p><strong><?php the_issuu_message('Private'); ?></strong></p>
						<p class="description">
							<?php the_issuu_message('To publish this document <a href="http://issuu.com/home/publications" target="_blank">click here</a>'); ?>
						</p>
					<?php else: ?>
						<p><strong><?php the_issuu_message('Public'); ?></strong></p>
					<?php endif; ?>
				</td>
			</tr>
			<tr>
				<th>
					<input type="submit" class="button-primary" value="<?php the_issuu_message('Update'); ?>">
					<h3>
						<a href="admin.php?page=issuu-document-admin" style="text-decoration: none;">
							<?php the_issuu_message('Back'); ?>
						</a>
					</h3>
				</th>
			</tr>
		</tbody>
	</table>
</form>