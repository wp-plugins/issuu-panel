<h1><?php the_issuu_message('Documents list'); ?></h1>
<form action="" method="post">
	<input type="hidden" name="delete" value="true">
	<a href="admin.php?page=issuu-document-admin&upload" class="buttons-top issuu-other-button" title="">
		<?php the_issuu_message('Upload file'); ?>
	</a>
	<a href="admin.php?page=issuu-document-admin&url_upload" class="buttons-top issuu-other-button" title="">
		<?php the_issuu_message('Upload file by URL'); ?>
	</a>
	<input type="submit" class="buttons-top issuu-submit-button" value="<?php the_issuu_message('Delete'); ?>">
	<?php if (isset($docs['totalCount']) && $docs['totalCount'] > $docs['pageSize']) : ?>
		<div id="issuu-painel-pagination">
			<?php for ($i = 1; $i <= $number_pages; $i++) : ?>
				<?php if ($page == $i) : ?>
					<span class="issuu-painel-number-page"><?= $i; ?></span>
				<?php else : ?>
					<a class="issuu-painel-number-page" href="?page=issuu-document-admin&pn=<?= $i; ?>"><?= $i; ?></a>
				<?php endif; ?>
			<?php endfor; ?>
		</div>
	<?php endif; ?>
	<div id="document-list">
		<?php if (isset($docs['document']) && !empty($docs['document'])) : ?>
			<?php foreach ($docs['document'] as $doc) : ?>
				<?php if (empty($doc->coverWidth) && empty($doc->coverHeight)) : ?>
					<div id="<?= $doc->orgDocName; ?>" class="document converting">
						<input type="checkbox" name="name[]" class="issuu-checkbox" value="<?= $doc->name; ?>">
						<div class="document-box">
							<div class="loading-issuu"></div>
				<?php else: ?>
					<div class="document complete">
						<input type="checkbox" name="name[]" class="issuu-checkbox" value="<?= $doc->name; ?>">
						<div class="document-box">
							<img src="<?= sprintf($image, $doc->documentId) ?>" alt="">
							<div class="update-document">
								<a href="admin.php?page=issuu-document-admin&update=<?= $doc->name; ?>">
									<?php the_issuu_message('Edit'); ?>
								</a>
							</div>
				<?php endif; ?>
					</div>
					<p class="description"><?= $doc->title ?></p>
				</div>
			<?php endforeach; ?>
		<?php endif; ?>
	</div>
</form>
<?php if (isset($docs['document']) && !empty($docs['document'])) : ?>
	<h3>Shortcode</h3>
	<input type="text" value="[issuu-painel-document-list]" disabled size="28" class="code shortcode">
<?php endif; ?>
<script type="text/javascript">
	(function($){
		$(document).ready(function(){
			var ua = navigator.userAgent.toLowerCase();
			if (ua.indexOf('chrome') <= -1) {
				$('.update-document a').each(function(){
					var p = $(this).parent();
					var width = (p.width() / 2) - 26;
					var height = (p.height() / 2) - 17;

					$(this).css({
						top: height + 'px',
						left: width + 'px'
					});
				});
			}
		});

		var idInt = window.setInterval(atualizaDocs, 5000);

		function atualizaDocs()
		{
			var $con = $('.converting');
			var url = '<?= ISSUU_PAINEL_URL; ?>menu/documento/requests/ajax-docs.php';
			var abspath = '<?= str_replace("\\", "/", ABSPATH); ?>';

			if ($con.length)
			{
				$.ajax(
					url,
					{
						type: 'GET',
						data: {name: $con.attr('id'), abspath: abspath}
					}
				).done(function(data){
					if (data != "stat-fail")
					{
						$con.html(data);
						$con.removeAttr('id');
						$con.addClass('complete').removeClass('converting');
					}
					else
					{
						console.log(data);
					}
				});
			}
			else
			{
				window.clearInterval(idInt);
			}
		}

	})(jQuery);
</script>