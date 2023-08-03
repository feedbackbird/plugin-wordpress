<?php

namespace FeedbackBird\Assets;

use FeedbackBird\Utils\Option;

class EnqueueScripts
{
	public function init()
	{
		add_action('wp_enqueue_scripts', [$this, 'registerWidgetScripts']);
	}

	public function registerWidgetScripts()
	{
		if (Option::get('widget_status')) {
			wp_enqueue_script('feedbackbird-widget', sprintf('https://cdn.jsdelivr.net/gh/feedbackbird/assets@master/wp/app.js?uid=%s', Option::get('uid')), array(), '1.0.0', true);
			wp_add_inline_script('feedbackbird-widget', sprintf('var feedbackBirdObject = %s;', json_encode($this->generateObjects())));

			add_filter('script_loader_tag', function ($tag, $handle, $src) {
				if ('feedbackbird-widget' === $handle) {
					return preg_replace('/^<script /i', '<script type="module" crossorigin="crossorigin" ', $tag);
				}
				return $tag;
			}, 10, 3);
		}
	}

	private function generateObjects()
	{
		return [
			'user_email' => function_exists('wp_get_current_user') ? wp_get_current_user()->user_email : '',
			'customization' => [
				"position" => Option::get('widget_position'),
				"color" => Option::get('widget_color'),
				"button" => Option::get('button_label'),
				"subtitle" => Option::get('widget_subtitle'),
			],
			'meta' => [],
		];
	}
}
