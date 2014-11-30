<h1><?php the_issuu_message('Folders list'); ?></h1>
<form action="" method="post" accept-charset="utf-8">
	<input type="hidden" name="delete" value="true">
	<input type="submit" class="issuu-submit-button" value="<?php the_issuu_message('Delete'); ?>">
	<?php if (isset($folders['totalCount']) && $folders['totalCount'] > $folders['pageSize']) : ?>
		<div id="issuu-painel-pagination">
			<?php for ($i = 1; $i <= $number_pages; $i++) : ?>
				<?php if ($page == $i) : ?>
					<span class="issuu-painel-number-page"><?= $i; ?></span>
				<?php else : ?>
					<a class="issuu-painel-number-page" href="?page=issuu-folder-admin&pn=<?= $i; ?>"><?= $i; ?></a>
				<?php endif; ?>
			<?php endfor; ?>
		</div>
	<?php endif; ?>
	<div class="issuu-folder-content">
		<?php foreach ($folders_documents as $key => $value) : ?>
			<div class="issuu-folder">
				<input type="checkbox" name="folderId[]" class="issuu-checkbox" value="<?= $key; ?>">
				<a href="admin.php?page=issuu-folder-admin&folder=<?= $key; ?>">
					<?php for ($i = 0; $i < 3; $i++) : ?>
						<?php if (isset($value['documentsId'][$i])) : ?>
							<div class="folder-item folder-item-doc">
								<img src="<?= sprintf($image, $value['documentsId'][$i]->documentId); ?>">
							</div><!-- FIM folder-item -->
						<?php else: ?>
							<div class="folder-item"></div><!-- FIM folder-item -->
						<?php endif; ?>
					<?php endfor; ?>
					<div>
						<p>
							<span><?= $value['name']; ?></span>
						</p>
					</div>
				</a>
			</div><!-- FIM issuu-folder -->
		<?php endforeach; ?>
		<div class="issuu-folder">
			<a href="admin.php?page=issuu-folder-admin&add">
				<div class="folder-item"></div><!-- FIM folder-item -->
				<div class="folder-item"></div><!-- FIM folder-item -->
				<div class="folder-item"></div><!-- FIM folder-item -->
				<div>
					<p>
						<span class="add-stack" title="<?php the_issuu_message('Create new folder'); ?>">
						</span><!-- FIM add-stack -->
					</p>
				</div>
			</a>
		</div><!-- FIM issuu-folder -->
	</div><!-- FIM issuu-folder-content -->
</form>