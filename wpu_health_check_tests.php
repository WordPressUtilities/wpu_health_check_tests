<?php
/*
Plugin Name: WPU Health Check Tests
Plugin URI: https://github.com/WordPressUtilities/wpu_health_check_tests
Description: Add More Health Checks Tests
Version: 0.2.3
Author: Darklg
Author URI: http://darklg.me/
License: MIT License
License URI: http://opensource.org/licenses/MIT
*/

add_action('plugins_loaded', 'wpu_health_check_tests_plugins_loaded', 10);
function wpu_health_check_tests_plugins_loaded() {
    if (!is_admin()) {
        return;
    }

    load_muplugin_textdomain('wpu_health_check_tests', dirname(plugin_basename(__FILE__)) . '/lang/');

    include dirname(__FILE__) . '/tests/autoloaded_options_weight.php';
    include dirname(__FILE__) . '/tests/correct_tables_charset.php';
}
