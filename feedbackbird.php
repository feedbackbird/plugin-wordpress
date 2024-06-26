<?php
/**
 * Plugin Name: FeedbackBird
 * Plugin URI: https://feedbackbird.io
 * Description: FeedbackBird helps you easily gather feedback through your WordPress website, letting you understand and meet your audience's needs better.
 * Author: VeronaLabs
 * Author URI: https://veronalabs.com
 * Text Domain: feedbackbird
 * Version: 1.0.4
 * License: GPL-2.0+
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Requires at least: 4.0
 * Requires PHP: 5.6
 *
 * @package FeedbackBird
 */

if (!defined('ABSPATH')) exit;

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
        // Set Plugin path and url defines.
        define('FEEDBACKBIRD_URL', plugin_dir_url(__FILE__));
        define('FEEDBACKBIRD_DIR', plugin_dir_path(__FILE__));

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
