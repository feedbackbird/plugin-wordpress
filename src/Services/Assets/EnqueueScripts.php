<?php

namespace FeedbackBird\Assets;

use FeedbackBird\Utils\Option;

if (!defined('ABSPATH')) exit;

class EnqueueScripts
{
    public function init()
    {
        add_action('wp_enqueue_scripts', [$this, 'registerWidgetScripts']);
    }

    public function registerWidgetScripts()
    {
        if (Option::get('widget_status')) {
            wp_enqueue_script('feedbackbird-widget', sprintf('%s/assets/js/app.js?uid=%s', esc_url(FEEDBACKBIRD_URL), Option::get('uid')), array(), '1.1.9', true);
            wp_add_inline_script('feedbackbird-widget', sprintf('var feedbackBirdObject = %s;', wp_json_encode($this->generateObjects())));

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
        $objects = [
            'user_email' => function_exists('wp_get_current_user') ? wp_get_current_user()->user_email : '',
            'config'     => [],
            'meta'       => [],
        ];

        $widget_position = Option::get('widget_position');
        if ($widget_position !== null && $widget_position !== '') {
            $objects['config']['position'] = $widget_position;
        }

        $widget_opening_style = Option::get('widget_opening_style');
        if ($widget_opening_style !== null && $widget_opening_style !== '') {
            $objects['config']['opening_style'] = $widget_opening_style;
        }

        $widget_color = Option::get('widget_color');
        if ($widget_color !== null && $widget_color !== '') {
            $objects['config']['color'] = $widget_color;
        }

        $button_label = Option::get('button_label');
        if ($button_label !== null && $button_label !== '') {
            $objects['config']['button'] = $button_label;
        }

        $widget_subtitle = Option::get('widget_subtitle');
        if ($widget_subtitle !== null && $widget_subtitle !== '') {
            $objects['config']['subtitle'] = $widget_subtitle;
        }

        return $objects;
    }
}
