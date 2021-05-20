<?php

class wpu_health_check_tests__correct_tables_charset {
    public function __construct() {
        add_filter('site_status_tests', array(&$this, 'add_tests'), 10, 1);
    }

    public function add_tests($tests) {
        $tests['direct']['wpu_health_check_tests__correct_tables_charset'] = array(
            'label' => __('Table charset', 'wpu_health_check_tests'),
            'test' => array(&$this, 'run_test')
        );
        return $tests;
    }

    public function run_test() {
        $result = array(
            'test' => 'wpu_health_check_tests__correct_tables_charset',
            'label' => __('Table charset is correct', 'wpu_health_check_tests'),
            'description' => '<p>' . __('No table have an old charset. Your website seems ok.', 'wpu_health_check_tests') . '</p>',
            'badge' => array(
                'label' => __('Performance', 'wpu_health_check_tests'),
                'color' => 'blue'
            )
        );

        $result['status'] = $this->get_status();

        if ($result['status'] == 'recommended') {
            $result['label'] = __('Table charset should be examined', 'wpu_health_check_tests');
            $result['description'] = '<p>' . __('At least one table is using an outdated charset. It can cause problems with emojis.', 'wpu_health_check_tests') . '</p>';
        }
        if ($result['status'] == 'critical') {
            $result['label'] = __('Table charset should be fixed', 'wpu_health_check_tests');
            $result['description'] = '<p>' . __('Multiple tables are using an outdated charset. It can cause problems with emojis.', 'wpu_health_check_tests') . '</p>';
        }

        return $result;
    }

    public function get_status() {
        global $wpdb;
        $tables = $wpdb->get_results($wpdb->prepare("SELECT TABLE_NAME,TABLE_COLLATION FROM information_schema.tables WHERE table_schema=%s", DB_NAME));

        $errors = 0;
        foreach ($tables as $table) {
            if ($table->TABLE_NAME == $wpdb->postmeta || $table->TABLE_NAME == $wpdb->usermeta || $table->TABLE_NAME == $wpdb->posts || $table->TABLE_NAME == $wpdb->termmeta) {
                if ($table->TABLE_COLLATION == 'utf8_general_ci') {
                    $errors++;
                }
            }
        }

        if ($errors < 1) {
            return 'good';
        }
        if ($errors < 2) {
            return 'recommended';
        }
        return 'critical';

    }
}

$wpu_health_check_tests__correct_tables_charset = new wpu_health_check_tests__correct_tables_charset();
