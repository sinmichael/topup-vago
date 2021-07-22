<?php

/**
 * Estimated Ship Date email
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/customer-note.php.
 *
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		Bluehive Interactive
 * @package 	WooCommerce/Templates/Emails
 * @version     2.5.0
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * @hooked WC_Emails::email_header() Output the email header
 */
do_action('woocommerce_email_header', $email_heading, $email); ?>

<?php
foreach ($topup_vago as $data) {
    $itemName = $data->itemName;
    $instructions = $data->instructions;
    $voucherExpiry = $data->voucherExpiry;
    $voucherPIN = $data->voucherPIN;
    $voucherSNO = $data->voucherSNO;

    echo '<strong><h3>' . $itemName . '</h3></strong>' . '<br />';
    echo 'Instructions:' . '<br />';
    echo $instructions . '<br />';
    echo '<br />';
    echo '<br />';
    echo 'Voucher Expiry: <strong>' . $voucherExpiry . '</strong><br />';
    echo 'Voucher PIN: <strong>' . $voucherPIN . '</strong><br />';
    echo 'Voucher SNO: <strong>' . $voucherSNO . '</strong><br />';
    echo '<br />';
    echo '<br />';
    echo '<hr />';
    echo '<br />';
    echo '<br />';
}

echo '<small>The gift cards are non-refundable or redeemable for cash or credit after the code is generated.</small>';
echo '<br />';
echo '<br />';
echo '<hr />';
echo '<br />';
echo '<br />';
?>

<?php

/**
 * @hooked WC_Emails::customer_details() Shows customer details
 * @hooked WC_Emails::email_address() Shows email address
 */
do_action('woocommerce_email_customer_details', $order, $sent_to_admin, $plain_text, $email);

/**
 * @hooked WC_Emails::email_footer() Output the email footer
 */
do_action('woocommerce_email_footer', $email);
