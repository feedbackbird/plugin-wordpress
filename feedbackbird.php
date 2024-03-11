<?php
/**
 * Plugin Name:     FeedbackBird
 * Plugin URI:      https://feedbackbird.io
 * Description:     Display the FeedbackBird widget in WordPress, Collect valuable feedback effortlessly and enhance user engagement.
 * Author:          VeronaLabs
 * Author URI:      https://veronalabs.com
 * Text Domain:     feedbackbird
 * Domain Path:     /languages
 * Version:         1.0.0
 *
 * @package         FeedbackBird
 */

use FeedbackBird\Assets\EnqueueScripts;
use FeedbackBird\Services\Admin\SettingsPage;

class FeedbackBird
{
    protected static $instance = null;

    public function __construct()
    {
        add_action('plugins_loaded', array($this, 'init'));
    }

    public static function getInstance()
    {
        null === self::$instance and self::$instance = new self;

        return self::$instance;
    }

    public function init()
    {
        $this->includeFiles();
        $this->initSettingsPage();
        $this->initScripts();
    }

    private function includeFiles()
    {
        include_once(plugin_dir_path(__FILE__) . 'src/Utils/Option.php');
        include_once(plugin_dir_path(__FILE__) . 'src/Services/Admin/SettingsPage.php');
        include_once(plugin_dir_path(__FILE__) . 'src/Services/Assets/EnqueueScripts.php');
    }

    private function initSettingsPage()
    {
        $instance = new SettingsPage();
        $instance->init();
    }

    private function initScripts()
    {
        $instance = new EnqueueScripts();
        $instance->init();
    }
}

function feedbackBird()
{
    return FeedbackBird::getInstance();
}

feedbackBird();
