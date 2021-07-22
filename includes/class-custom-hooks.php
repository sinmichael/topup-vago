<?php
if (!defined('ABSPATH')) {
    die('Access Denied!');
}

class Custom_Hooks
{
    public function before_add_to_cart_form()
    {
        $product = wc_get_product();

        if ($product->is_virtual()) {
            echo '<small>' . $this->getSetting('before-add-to-cart-form-text') . '</small>';
        }
    }

    private function debug($message)
    {
        $logfile = plugin_dir_path(__FILE__) . "debug.log";

        if ($this->getSetting("enable-logging") == "yes") {
            file_put_contents($logfile, $message . "\n", FILE_APPEND);
        }
    }

    private function getSetting($field = '')
    {
        $prefix = 'topup_vago_';
        $data = get_option($prefix . $field);
        if (!isset($data)) return;
        return $data;
    }
}
