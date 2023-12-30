<?php

namespace FeedbackBird\Services\Admin;

class SettingsPage
{
	private $feedbackbird_options;

	public function init()
	{
		add_action('admin_menu', array($this, 'registerMenu'));
		add_action('admin_init', array($this, 'registerFields'));
	}

	public function registerMenu()
	{
		add_options_page(
			'FeedbackBird', // page_title
			'FeedbackBird', // menu_title
			'manage_options', // capability
			'feedbackbird', // menu_slug
			array($this, 'renderSettingsPage') // function
		);
	}

	public function renderSettingsPage()
	{
		$this->feedbackbird_options = get_option('feedbackbird'); ?>

		<div class="wrap">
			<h2>FeedbackBird</h2>
			<p></p>
			<?php settings_errors(); ?>

			<form method="post" action="options.php">
				<?php
				settings_fields('feedbackbird_option_group');
				do_settings_sections('feedbackbird-admin');
				submit_button();
				?>
			</form>
		</div>
	<?php }

	public function registerFields()
	{
		register_setting(
			'feedbackbird_option_group', // option_group
			'feedbackbird', // option_name
			array($this, 'feedbackbird_sanitize') // sanitize_callback
		);

		add_settings_section(
			'feedbackbird_setting_section', // id
			'Settings', // title
			array($this, 'feedbackbird_section_info'), // callback
			'feedbackbird-admin' // page
		);

		add_settings_field(
			'widget_status', // id
			'Status', // title
			array($this, 'widget_status_callback'), // callback
			'feedbackbird-admin', // page
			'feedbackbird_setting_section' // section
		);

		add_settings_field(
			'uid', // id
			'Account UID', // title
			array($this, 'uid_callback'), // callback
			'feedbackbird-admin', // page
			'feedbackbird_setting_section' // section
		);

		add_settings_field(
			'widget_position', // id
			'Widget Position', // title
			array($this, 'widget_position_callback'), // callback
			'feedbackbird-admin', // page
			'feedbackbird_setting_section' // section
		);

		add_settings_field(
			'widget_color', // id
			'Widget Color', // title
			array($this, 'widget_color_callback'), // callback
			'feedbackbird-admin', // page
			'feedbackbird_setting_section' // section
		);

		add_settings_field(
			'button_label', // id
			'Button Label', // title
			array($this, 'button_label_callback'), // callback
			'feedbackbird-admin', // page
			'feedbackbird_setting_section' // section
		);

		add_settings_field(
			'widget_subtitle', // id
			'Widget Subtitle', // title
			array($this, 'widget_subtitle_callback'), // callback
			'feedbackbird-admin', // page
			'feedbackbird_setting_section' // section
		);
	}

	public function feedbackbird_sanitize($input)
	{
		$sanitary_values = array();
		if (isset($input['widget_status'])) {
			$sanitary_values['widget_status'] = $input['widget_status'];
		}

		if (isset($input['uid'])) {
			$sanitary_values['uid'] = sanitize_text_field($input['uid']);
		}

		if (isset($input['widget_position'])) {
			$sanitary_values['widget_position'] = $input['widget_position'];
		}

		if (isset($input['widget_color'])) {
			$sanitary_values['widget_color'] = sanitize_text_field($input['widget_color']);
		}

		if (isset($input['button_label'])) {
			$sanitary_values['button_label'] = sanitize_text_field($input['button_label']);
		}

		if (isset($input['widget_subtitle'])) {
			$sanitary_values['widget_subtitle'] = sanitize_text_field($input['widget_subtitle']);
		}

		return $sanitary_values;
	}

	public function feedbackbird_section_info()
	{

	}

	public function widget_status_callback()
	{
		printf(
			'<input type="checkbox" name="feedbackbird[widget_status]" id="widget_status" value="widget_status" %s> <label for="widget_status">Enable or disable the widget.</label>',
			(isset($this->feedbackbird_options['widget_status']) && $this->feedbackbird_options['widget_status'] === 'widget_status') ? 'checked' : ''
		);
	}

	public function uid_callback()
	{
		printf(
			'<input class="regular-text" type="text" name="feedbackbird[uid]" id="uid" value="%s"><p class="description">If you don’t have an Account UID, you should create a new account at <a href="https://feedbackbird.io/dashboard/settings" target="_blank">FeedbackBird</a> and get it in your Profile → Settings.</p>',
			isset($this->feedbackbird_options['uid']) ? esc_attr($this->feedbackbird_options['uid']) : ''
		);
	}

	public function widget_position_callback()
	{
		?> <select name="feedbackbird[widget_position]" id="widget_position">
		<?php $selected = (isset($this->feedbackbird_options['widget_position']) && $this->feedbackbird_options['widget_position'] === 'top-left') ? 'selected' : ''; ?>
		<option value="top-left" <?php echo esc_attr($selected); ?>>Top Left</option>
		<?php $selected = (isset($this->feedbackbird_options['widget_position']) && $this->feedbackbird_options['widget_position'] === 'top-right') ? 'selected' : ''; ?>
		<option value="top-right" <?php echo esc_attr($selected); ?>>Top Right</option>
		<?php $selected = (isset($this->feedbackbird_options['widget_position']) && $this->feedbackbird_options['widget_position'] === 'right') ? 'selected' : ''; ?>
		<option value="right" <?php echo esc_attr($selected); ?>>Right</option>
		<?php $selected = (isset($this->feedbackbird_options['widget_position']) && $this->feedbackbird_options['widget_position'] === 'left') ? 'selected' : ''; ?>
		<option value="left" <?php echo esc_attr($selected); ?>>Left</option>
		<?php $selected = (isset($this->feedbackbird_options['widget_position']) && $this->feedbackbird_options['widget_position'] === 'bottom-left') ? 'selected' : ''; ?>
		<option value="bottom-left" <?php echo esc_attr($selected); ?>>Bottom Left</option>
		<?php $selected = (isset($this->feedbackbird_options['widget_position']) && $this->feedbackbird_options['widget_position'] === 'bottom-right') ? 'selected' : ''; ?>
		<option value="bottom-right" <?php echo esc_attr($selected); ?>>Bottom Right</option>
	</select><p class="description">Select the position of the widget on your website.</p> <?php
	}

	public function widget_color_callback()
	{
		printf(
			'<input class="regular-text" type="text" name="feedbackbird[widget_color]" id="widget_color" value="%s"><p class="description">Choose a widget color by entering the HEX color code, for example <code>#76c436</code>. Find more colors at <a target="_blank" href="%s">Color Hex</a></p>',
			isset($this->feedbackbird_options['widget_color']) ? esc_attr($this->feedbackbird_options['widget_color']) : '',
			'https://www.color-hex.com/'
		);
	}

	public function button_label_callback()
	{
		printf(
			'<input class="regular-text" type="text" name="feedbackbird[button_label]" id="button_label" value="%s"><p class="description">Choose a label for the button of widget.</p>',
			isset($this->feedbackbird_options['button_label']) ? esc_attr($this->feedbackbird_options['button_label']) : ''
		);
	}

	public function widget_subtitle_callback()
	{
		printf(
			'<input class="regular-text" type="text" name="feedbackbird[widget_subtitle]" id="widget_subtitle" value="%s"><p class="description">Choose a subtitle for the widget.</p>',
			isset($this->feedbackbird_options['widget_subtitle']) ? esc_attr($this->feedbackbird_options['widget_subtitle']) : ''
		);
	}
}
