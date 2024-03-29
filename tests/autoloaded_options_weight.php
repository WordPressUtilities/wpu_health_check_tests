<?php

class wpu_health_check_tests__autoloaded_options_weight {
    public function __construct() {
        add_filter('site_status_tests', array(&$this, 'add_tests'), 10, 1);
    }

    public function add_tests($tests) {
        $tests['direct']['wpu_health_check_tests__autoloaded_options_weight'] = array(
            'label' => __('Autoloaded options weight', 'wpu_health_check_tests'),
            'test' => array(&$this, 'run_test')
        );
        return $tests;
    }

    public function run_test() {
        $result = array(
            'test' => 'wpu_health_check_tests__correct_table_format',
            'label' => __('Autoloaded options weight is correct', 'wpu_health_check_tests'),
            'description' => '<p>' . __('Autoloaded options should not exceed a certain weight. Your website seems ok.', 'wpu_health_check_tests') . '</p>',
            'badge' => array(
                'label' => __('Performance', 'wpu_health_check_tests'),
                'color' => 'blue'
            )
        );

        $result['status'] = $this->get_status();

        $weight = 0;
        if (is_numeric($result['status'])) {
            $weight = $result['status'];
            if ($weight < 250 * 1024) {
                $result['status'] = 'good';
            } else if ($weight < 500 * 1024) {
                $result['status'] = 'recommended';
            } else {
                $result['status'] = 'critical';
            }
        }

        if ($result['status'] == 'recommended') {
            $result['label'] = __('Autoloaded options weight should be examined', 'wpu_health_check_tests');
            $result['description'] = '<p>' . __('The autoloaded options may be too heavy. It could be slowing down your website', 'wpu_health_check_tests') . '</p>';
        }

        if ($result['status'] == 'critical') {
            $result['label'] = __('Autoloaded options weight is too heavy', 'wpu_health_check_tests');
            $result['description'] = '<p>' . __('The autoloaded options are too heavy. It is slowing down your website', 'wpu_health_check_tests') . '</p>';
        }

        if ($weight) {
            $result['description'] .= '<p>' . sprintf(__('Current weight: %sko', 'wpu_health_check_tests'), round($weight / 1024)) . '</p>';
        }

        return $result;
    }

    public function get_status() {
        global $wpdb;
        return intval($wpdb->get_var("SELECT SUM(LENGTH(option_value)) as autoload_weight FROM $wpdb->options WHERE autoload='yes';"));
    }
}

$wpu_health_check_tests__autoloaded_options_weight = new wpu_health_check_tests__autoloaded_options_weight();
