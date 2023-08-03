<?php

namespace FeedbackBird\Utils;

class Option
{
    private static $optionName = 'feedbackbird';
    private static $options;

    public static function get($name)
    {
        // If options are not yet fetched, get them once and store in the static variable
        if (empty(self::$options)) {
            self::$options = get_option(self::$optionName);
        }

        return isset(self::$options[$name]) ? self::$options[$name] : null;
    }

    public static function set($name, $value)
    {
        // If options are not yet fetched, get them once and store in the static variable
        if (empty(self::$options)) {
            self::$options = get_option(self::$optionName);
        }

        self::$options[$name] = $value;
        update_option(self::$optionName, self::$options);
    }

    public static function delete($name)
    {
        // If options are not yet fetched, get them once and store in the static variable
        if (empty(self::$options)) {
            self::$options = get_option(self::$optionName);
        }

        if (isset(self::$options[$name])) {
            unset(self::$options[$name]);
            update_option(self::$optionName, self::$options);
        }
    }
}
