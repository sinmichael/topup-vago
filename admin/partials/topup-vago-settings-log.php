<?php
/**
 * Displays a table in the WC Settings page
 *
 * @link        https://paulmiller3000.com
 * @since       1.0.0
 *
 * @package     P3k_Galactica
 * @subpackage  P3k_Galactica/admin
 *
 */

$GLOBALS['hide_save_button'] = true;

?>
<br />
Log file: <?php echo plugin_dir_path(__FILE__) . "../../includes/debug.log"; ?> <br/>
<br />
<textarea id="topup-vago-log" name="topup-vago-log" rows="20" cols="150" readonly>
<?php
echo file_get_contents(plugin_dir_path(__FILE__) . "../../includes/debug.log");
?>
</textarea>