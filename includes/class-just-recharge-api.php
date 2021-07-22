<?php
if (!defined('ABSPATH')) {
    die('Access Denied!');
}

class Just_Recharge_Api
{
    public function payment_complete($order_id)
    {
        $order = wc_get_order($order_id);
        $user = $order->get_user();
        $contains_physical_product = false;
        $has_error = false;
        $note = '';

        $arrayData = [];

        $j = 1;
        foreach ($order->get_items() as $order_item) {
            $item = wc_get_product($order_item->get_product_id());
            if ($item->is_virtual()) {

                $item_quantity = $order_item->get_quantity();

                for ($i = 1; $i <= $item_quantity; $i++) {

                    $item_name = $item->get_title();
                    $this->debug("[" . $order_id . "]" . " Order contains virtual product: " . $item_name);
                    $evcode = $item->get_attribute('evcode');
                    if (!empty($evcode)) {
                        $this->debug("[" . $order_id . "]" . " " . $item_name . " contains evcode: " . $evcode);
                        $pad = str_pad((int)$j . (int)$i, 6, '0', STR_PAD_LEFT);
                        $txnReference = $order_id . $pad;
                        $data = $this->getEvoucher($evcode, "1", $txnReference);

                        if (!$data) {
                            $log = "Topup Vago failed!";
                            $this->debug("[" . $order_id . "]" . " " . $log);
                            $this->debug("---------------------------------------------------------------------------");
                            $note = $note . "\n" . $log;
                            $order->add_order_note($note);
                            return;
                        }

                        $currency = $data->currency;
                        $instructions = $data->instructions;
                        $message = $data->message;
                        $price = $data->price;
                        $receipt = $data->receipt;
                        $responseID = $data->responseID;
                        $result = $data->result;
                        $total_price = $data->total_price;
                        $txnReference = $data->txnReference;
                        $vat = $data->vat;
                        $voucherExpiry = $data->voucherExpiry;
                        $voucherPIN = $data->voucherPIN;
                        $voucherSNO = $data->voucherSNO;
                        $walletBalance = $data->walletBalance;
                        $data->itemName = $item_name;

                        // if topupvago failed
                        if ($result != "0") {
                            $log = "(" . $item_name . ") Topup Vago failed! " . $message . "; Error result: " . $result;
                            $this->debug("[" . $order_id . "]" . " " . $log);
                            $this->debug("[" . $order_id . "]" . " responseID: " . $responseID);
                            $note = $note . "\n" . $log;
                            $order->add_order_note($note);
                            $has_error = true;
                            if ($result != "129") {
                                $this->debug("---------------------------------------------------------------------------");
                                return;
                            }
                        }

                        $note = "[" . $item_name . " - " . "Expiry: " . $voucherExpiry . "; PIN: " . $voucherPIN . "; SNO: " . $voucherSNO . "; " . " Transaction Reference: " . $txnReference . "; " . " Response ID: " . $responseID . "]" . "\n";
                        $order->add_order_note($note);
                        $this->debug("[" . $order_id . "]" . " " . "Note set");
                        $this->debug("[" . $order_id . "]" . " message: " . $message);
                        $this->debug("[" . $order_id . "]" . " instructions: " . $instructions);
                        $this->debug("[" . $order_id . "]" . " receipt: " . $receipt);
                        $this->debug("[" . $order_id . "]" . " responseID: " . $responseID);
                        $this->debug("[" . $order_id . "]" . " result: " . $result);
                        $this->debug("[" . $order_id . "]" . " currency: " . $currency);
                        $this->debug("[" . $order_id . "]" . " price: " . $price);
                        $this->debug("[" . $order_id . "]" . " vat: " . $vat);
                        $this->debug("[" . $order_id . "]" . " total_price: " . $total_price);
                        $this->debug("[" . $order_id . "]" . " txnReference: " . $txnReference);
                        $this->debug("[" . $order_id . "]" . " voucherExpiry: " . $voucherExpiry);
                        $this->debug("[" . $order_id . "]" . " voucherPIN: " . $voucherPIN);
                        $this->debug("[" . $order_id . "]" . " voucherSNO: " . $voucherSNO);
                        $this->debug("[" . $order_id . "]" . " walletBalance: " . $walletBalance);
                        $this->debug("---------------------------------------------------------------------------");

                        array_push($arrayData, $data);
                    } else {
                        $this->debug("[" . $order_id . "]" . " evcode for " . $item_name . " not found!");
                        $this->debug("---------------------------------------------------------------------------");
                        return;
                    }

                    $j = $j + 1;
                }
            } else {
                $contains_physical_product = true;
            }
        }

        if (!$has_error) {
            $mailer = WC()->mailer();
            $recipient = $order->get_billing_email();
            $subject = __("[" . $order_id . "]" . " " . "Here's your gift card!", 'topup-vago');
            $content = $this->get_custom_email_html($order, $subject, $mailer, $arrayData);
            $headers = "Content-Type: text/html\r\n";

            $mailer->send($recipient, $subject, $content, $headers);
        }

        if (!$contains_physical_product && !$has_error) {
            $order->update_status('for-completion', '', true);
        }

        return;
    }

    private function getEvoucher($evCode, $terminalID, $txnReference)
    {
        try {
            $url = 'https://www.connectvago.com/v2/getEvouchers.svc?WSDL';

            $options = array(
                "soap_version" => SOAP_1_1,
                "cache_wsdl" => WSDL_CACHE_NONE,
                "exceptions" => false,
                "trace" => 1,
            );

            $client = new SoapClient($url, $options);

            $parameters = new stdClass();
            $parameters->client_passWrd = $this->getSetting('password');
            $parameters->client_userName = $this->getSetting('username');
            $parameters->evCode = $evCode;
            $parameters->terminalID = $terminalID;
            $parameters->txnReference = $txnReference;

            $this->debug("[" . $txnReference . "]" . " SoapClient initialized.");

            return $client->getEvoucher(array("request" => $parameters))->getEvoucherResult;
        } catch (Exception $exception) {
            return;
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

    private function get_custom_email_html($order, $heading = false, $mailer, $topupvago)
    {

        $template = 'topup-vago-email-template.php';
        $template_path = '';
        $default_path = untrailingslashit(plugin_dir_path(__FILE__)) . "/";

        return wc_get_template_html($template, array(
            'order'         => $order,
            'email_heading' => $heading,
            'sent_to_admin' => false,
            'plain_text'    => false,
            'email'         => $mailer,
            'topup_vago'    => $topupvago
        ), $template_path, $default_path);
    }
}
