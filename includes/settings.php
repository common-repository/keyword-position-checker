<div class="wrap kpc-wrap">
<?php
screen_icon( "admin" );
?>
<h2>SETTINGS</h2>
<?php 
$settings = get_option( 'kpc_settings' );
if( isset( $_POST['kpc-update-settings'] ) ) {
    $proxy = $_POST['kpc-proxy-address'];
    $check_range = $_POST['kpc-check-range'];
    $style = $_POST['kpc-custom-style'];
    if( !is_numeric( $check_range ) || empty( $check_range ) || $check_range < 100 ) {
        $check_range = 100;
    }
    $settings = array( 'proxy' => $proxy, 'check_range' => $check_range, 'style' => $style );
    update_option( 'kpc_settings', $settings );
?>
<div id="message" class="updated below-h2">
    <p>Saved done!</p>
</div>
<?php
}
?>
<form action="" method="post" name="form-settings" id="form-settings">
    <p>
        <label for="kpc-proxy-address"><?php _e( 'Proxy' ); ?></label>
        <input type="text" name="kpc-proxy-address" id="kpc-proxy-address" value="<?php echo $settings['proxy']; ?>" /><small>&nbsp;(Ex: 192.168.1.1:80 )</small>
    </p>
    <p>
        <label for="kpc-check-range"><?php _e( 'Position' ); ?></label>
        <input type="text" name="kpc-check-range" id="kpc-check-range" value="<?php echo $settings['check_range']; ?>" />
    </p>
    <p>
        <label for="kpc-custom-style"><?php _e( 'Custom CSS ' ); ?><small style="display: block;"><?php _e( '(Will be override default CSS)' ); ?></small></label>
        <textarea rows="10" name="kpc-custom-style" id="kpc-custom-style"><?php echo $settings['style']; ?></textarea>
    </p>
    <p>
        <input class="button-primary" type="submit" name="kpc-update-settings" id="kpc-update-settings" value="<?php _e( 'Update' ); ?>" />
    </p>
</form>
</div>