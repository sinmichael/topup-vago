<?php

/**
 * Extends the WC_Settings_Page class
 *
 * @link       https://github.com/sinmichael
 * @since      1.0.0
 *
 * @package    Topup_Vago
 * @subpackage Topup_Vago/admin
 *
 */

if (!defined('ABSPATH')) exit; // Exit if accessed directly

if (!class_exists('Topup_Vago_WC_Settings')) {

    /**
     * Settings class
     *
     * @since 1.0.0
     */
    class Topup_Vago_WC_Settings extends WC_Settings_Page
    {

        /**
         * Constructor
         * @since  1.0
         */
        public function __construct()
        {

            $this->id    = 'topup-vago';
            $this->label = __('Topup Vago', 'topup-vago');

            // Define all hooks instead of inheriting from parent                    
            add_filter('woocommerce_settings_tabs_array', array($this, 'add_settings_page'), 20);
            add_action('woocommerce_sections_' . $this->id, array($this, 'output_sections'));
            add_action('woocommerce_settings_' . $this->id, array($this, 'output'));
            add_action('woocommerce_settings_save_' . $this->id, array($this, 'save'));
        }


        /**
         * Get sections.
         *
         * @return array
         */
        public function get_sections()
        {
            $sections = array(
                '' => __('Settings', 'topup-vago'),
                'log' => __('Log', 'topup-vago')
            );

            return apply_filters('woocommerce_get_sections_' . $this->id, $sections);
        }


        /**
         * Get settings array
         *
         * @return array
         */
        public function get_settings()
        {

            global $current_section;
            $prefix = 'topup_vago_';
            $settings = array();

            switch ($current_section) {
                case 'log':
                    include 'partials/topup-vago-settings-log.php';   
                    break;
                default:
                    include 'partials/topup-vago-settings-main.php';   
            }

            return apply_filters('woocommerce_get_settings_' . $this->id, $settings, $current_section);
        }

        /**
         * Output the settings
         */
        public function output()
        {
            $settings = $this->get_settings();

            WC_Admin_Settings::output_fields($settings);
        }

        /**
         * Save settings
         *
         * @since 1.0
         */
        public function save()
        {
            $settings = $this->get_settings();

            WC_Admin_Settings::save_fields($settings);
        }
    }
}


return new Topup_Vago_WC_Settings();
