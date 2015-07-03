<?php

class IssuuPanelWidget extends WP_Widget
{
	public function __construct()
	{
		parent::WP_Widget(
			false,
			'Issuu Panel',
			array('description' => get_issuu_message('Get and display the last document'))
		);
	}

	public function widget($args, $instance)
	{
		$id = ($instance['issuu_panel_folder'] != '0')? ' id="' . $instance['issuu_panel_folder'] . '"' : '';
		$link = (isset($instance['issuu_panel_url_page']) && trim($instance['issuu_panel_url_page']) != '')?
			' link="' . $instance['issuu_panel_url_page'] . '"' : '';
		$order_by = ' order_by="' . $instance['issuu_panel_order_by'] . '"';

		echo $args['before_widget'];

		if (!empty($instance['issuu_panel_title']))
		{
			echo $args['before_title'];
			echo $instance['issuu_panel_title'];
			echo $args['after_title'];
		}

		echo do_shortcode("[issuu-panel-last-document{$id}{$link}{$order_by}]");
		echo $args['after_widget'];
	}

	public function form($instance)
	{
		$ipanel_folder = $instance['issuu_panel_folder'];
		$ipanel_url_page = $instance['issuu_panel_url_page'];
		$ipanel_order_by = $instance['issuu_panel_order_by'];
		$ipanel_title = $instance['issuu_panel_title'];
		$issuu_panel_api_key = IssuuPanelConfig::getVariable('issuu_panel_api_key');
		$issuu_panel_api_secret = IssuuPanelConfig::getVariable('issuu_panel_api_secret');

		$issuu_folder = new IssuuFolder($issuu_panel_api_key, $issuu_panel_api_secret);
		$result = $issuu_folder->issuuList();
	?>
		<p>
			<label for="<?= $this->get_field_id('issuu_panel_title'); ?>">
				<strong><?php _e('Title'); ?></strong><br>
				<input type="text" id="<?= $this->get_field_id('issuu_panel_title'); ?>" class="widefat"
					name="<?= $this->get_field_name('issuu_panel_title'); ?>" value="<?= $ipanel_title ?>">
			</label>
		</p>
		<p>
			<label for="<?= $this->get_field_id('issuu_panel_folder'); ?>">
				<strong><?php the_issuu_message('Folder') ?></strong><br>
				<select id="<?= $this->get_field_id('issuu_panel_folder'); ?>"
					name="<?= $this->get_field_name('issuu_panel_folder'); ?>">
					<option value="0"><?php the_issuu_message('Select...'); ?></option>
					<?php if ($result['stat'] == 'ok' && (isset($result['folder']) && !empty($result['folder']))) : ?>
						<?php foreach ($result['folder'] as $folder) : ?>
							<option <?= ($ipanel_folder == $folder->folderId)? 'selected' : ''?>
								value="<?= $folder->folderId; ?>">
								<?= $folder->name; ?>
							</option>
						<?php endforeach; ?>
					<?php endif; ?>
				</select>
			</label>
		</p>
		<p>
			<label for="<?= $this->get_field_id('issuu_panel_url_page'); ?>">
				<strong><?php the_issuu_message('URL page') ?></strong><br>
				<input type="text" id="<?= $this->get_field_id('issuu_panel_url_page'); ?>" class="widefat"
					name="<?= $this->get_field_name('issuu_panel_url_page'); ?>" value="<?= $ipanel_url_page ?>">
			</label>
		</p>
		<p>
			<label for="<?= $this->get_field_id('issuu_panel_order_by'); ?>">
				<strong><?php the_issuu_message('Order by'); ?></strong><br>
				<select name="<?= $this->get_field_name('issuu_panel_order_by'); ?>"
					id="<?= $this->get_field_id('issuu_panel_order_by'); ?>">
					<option <?= ($ipanel_order_by == 'title')? 'selected' : ''?> value="title">
						<?php the_issuu_message('Title'); ?>
					</option>
					<option <?= ($ipanel_order_by == 'publishDate')? 'selected' : ''?> value="publishDate">
						<?php the_issuu_message('Publish date'); ?>
					</option>
					<option <?= ($ipanel_order_by == 'description')? 'selected' : ''?> value="description">
						<?php the_issuu_message('Description'); ?>
					</option>
					<option <?= ($ipanel_order_by == 'documentId')? 'selected' : ''?> value="documentId">
						<?php the_issuu_message('Document ID'); ?>
					</option>
				</select>
			</label>
		</p>
	<?php
	}

	public function update($new_instance, $old_instance)
	{
		return array_merge($old_instance, $new_instance);
	}
}

add_action('widgets_init', create_function('', 'return register_widget("IssuuPanelWidget");'));