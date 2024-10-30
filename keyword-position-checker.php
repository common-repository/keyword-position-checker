<?php 
/*
Plugin Name: Keyword Position Checker
Version: 1.1.3
Description: Check keyword position on Google.You can use shortcode <strong>[kwposchecker]</strong> or use <strong>widget</strong> integrated in this plugin
Author: doanhienitpro
Author URI: http://weblife24h.com
Plugin URI: http://weblife24h.com
*/

error_reporting( 0 );
$kpc_plugin_url =  trailingslashit( WP_PLUGIN_URL.'/'. dirname( plugin_basename(__FILE__) ) );
require_once "includes/widget.php";
global $wp_version;
$exit_msg='Keyword Position Checker requires WordPress 2.5 or newer. <a href="http://codex.wordpress.org/Upgrading_WordPress">Please update!</a>';

if (version_compare($wp_version,"3.3.1","<"))
{
	exit ($exit_msg);
}

function kpc_init() {
    $settings = array( 
        'proxy' => "",
        'check_range' => "100",
        'style' => ''
    );
    $check_empty_options = get_option( "kpc_settings" );
        if( empty( $check_empty_options ) ) {
        update_option("kpc_settings", $settings);
    }
    global $wpdb;
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	dbDelta("CREATE TABLE IF NOT EXISTS `kpc_place` (
                  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                  `google_place` varchar(20) NOT NULL,
                  `language` varchar(20) NOT NULL,
                  `country_name` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                  PRIMARY KEY (`id`)
                ) ENGINE=MyISAM  DEFAULT CHARSET=latin1;");
    dbDelta("INSERT INTO `kpc_place` (`id`, `google_place`, `language`, `country_name`) VALUES
                (1, 'com.vn', 'vi', 'Việt Nam'),
                (2, 'com', 'en', 'International');");
}

register_activation_hook( __FILE__, "kpc_init" );

function kpc_uninstall() {
    global $wpdb;
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    $table = array( "kpc_place", "kpc_proxy" );
    foreach( $table as $value ) {
        $sql = "DROP TABLE {$value}";
        $wpdb->query( $sql );
    }
}

register_deactivation_hook( __FILE__, "kpc_uninstall" );

add_action('admin_menu', 'kpc_menu_pages');
function kpc_menu_pages(){
		add_menu_page('Keyword Position Checker', 'Keyword Position Checker', 8,'kpc_management',  'kpc_management', "", 3);
			add_submenu_page('kpc_management', 'Countries', 'Countries', 8, 'countries', 'countries');
            add_submenu_page('kpc_management', 'Settings', 'Settings', 8,'settings',  'settings');		
}

function kpc_management() {
    screen_icon( "admin" );
    echo "<h2>Keyword Position Checker</h2>";
    echo keyword_position_checker();
?>
<form style="float: right;" action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
<small>Some feature I want to develop but I need your Donate.I hope!.Thank you so much.</small>
<input type="hidden" name="cmd" value="_donations">
<input type="hidden" name="business" value="trandoanhien@gmail.com">
<input type="hidden" name="lc" value="US">
<input type="hidden" name="item_name" value="keyword position checker">
<input type="hidden" name="item_number" value="772889">
<input type="hidden" name="no_note" value="0">
<input type="hidden" name="currency_code" value="USD">
<input type="hidden" name="bn" value="PP-DonationsBF:btn_donate_SM.gif:NonHostedGuest">
<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_SM.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
</form>
<?php
}

function countries() {
    include_once "includes/countries.php";
}

function settings() {
    include_once "includes/settings.php";
}

function keyword_position_checker() {
    global $kpc_plugin_url;
    $form = '<div id="kw-pos-ck-wrapper">
        <form id="keyword_form_check" name="keyword_form_check" method="post">'
            .'<p>
                <label for="kw">Keyword <small>( Separate by break line )</small></label>
                <textarea cols="32" rows="5" id="kw" name="kw"></textarea>
            </p>
            <p>
                <label>Website <small>( Ex: http://www.yourdomain.com ........ )</small></label>
                <input type="text" id="website" name="website" />
            </p>
            
            <p>
                <label for="kpc-place">Country</label>
                '.kpc_country_dropdown().'
            </p>
            <p>
                <label for="kpc-range">Check range</label>
                '.kpc_range_dropdown().'
            </p>
            <p id="butsend">
                <input class="button" type="submit" id="send" name="send" value="Check" />
                <div id="checking" style="display: none;">
                     <strong>Checking.......</strong> <img style="float: left;" src="'.$kpc_plugin_url.'images/loading.gif" />
                </div>
            </p>
            <p style="display: none;" id="pos">
            </p>
        </form>
    </div>';
    return $form;
}

function kpc_get_country() {
    global $wpdb;
    $sql = "SELECT * FROM kpc_place";
    return $wpdb->get_results( $sql );
}

function kpc_get_country_byid( $id ) {
    global $wpdb;
    $sql = "SELECT * FROM kpc_place WHERE id = {$id}";
    return $wpdb->get_row( $sql );
}

function kpc_country_dropdown() {
    $args = kpc_get_country();
    $str = "";
    $str .= "<select name='kpc_place' id='kpc-place'>";
    foreach( $args as $val ) {
        $str .= "<option value={$val->id}>$val->country_name</option>";
    }
    $str .= "</select>";
    return $str;
}

function kpc_range_dropdown() {
    $settings = get_option( 'kpc_settings' );
    $range = $settings['check_range'];
    $count = ceil( $range/100 );
    $str = "";
    $str .= "<select name='kpc_range' id='kpc-range'>";
    for( $i = 1; $i <= $count; $i++ ) {
        $str .= "<option value='".($i*100)."'>1 - ".($i*100)."</option>";
    }
    $str .= "</select>";
    return $str;
}

function kpc_filter_number( $d ) {
    if( is_numeric( $d ) ) {
        return true;
    }
    return false;
}

function trim_strs($serp) {
	$serp = str_replace("<b>", "", $serp);
	$serp = str_replace("</b>", "", $serp);
	$serp = str_replace("\"", "", $serp);
	$serp = str_replace(" ", "", $serp);
	return $serp;
}

function kpc_pos( $website, $data, $page_start ) {
    $search=explode('<liclass=g>',$data);
    $pos = null;
    for($i=1;$i<=count( $search );$i++){
        if(substr_count($search[$i],$website)>0){
            $pos=$i;
            break;
        }
              
    }
    return ( $page_start + $pos );
}

function kpc_check($keyword, $website, $google_place, $language, $range = 100, $proxy = null) {	
    $rs = array();
    $key_args = explode( "\n", $keyword );
    $key_args = array_filter($key_args);
    $data = "";
    $not_accept = array( 503 );
    foreach( $key_args as $key => $val ) {
        $page_start=null;
        if( !isset( $proxy ) ) { 
            for($i=0;$i<$range;$i=$i+10){                
                $link="http://www.google.".$google_place."/search?hl=".$language."&start=".$i."&q=".$val;
                $ch = curl_init();
                curl_setopt ($ch, CURLOPT_URL, $link);
                curl_setopt ($ch, CURLOPT_RETURNTRANSFER, TRUE);
                if( !ini_get( "safe_mode" ) ) {
                    curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, true);
                }
                curl_setopt ($ch, CURLOPT_TIMEOUT, 30);
                curl_setopt($ch, CURLOPT_HEADER, 0);
                $code = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
                $data = curl_exec($ch);
                $data = trim_strs( $data );
                curl_close($ch);
                if( empty( $data ) || in_array( $code, $not_accept ) ) {
                    $rs["error"] = "Many request was sended!.Please try later!.";
                    break;
                }
                else if(substr_count($data,$website)>0){
                	$page_start=$i;
                	break;
                }
            }
        }
        else {
            for($i=0;$i<$range;$i=$i+10){
                $link="http://www.google.".$google_place."/search?hl=".$language."&start=".$i."&q=".$val;
                $ch = curl_init();
                curl_setopt ($ch, CURLOPT_URL, $link);
                curl_setopt ($ch, CURLOPT_RETURNTRANSFER, TRUE);
                if( !ini_get( "safe_mode" ) ) {
                    curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, true);
                }
                curl_setopt ($ch, CURLOPT_PROXY, $proxy);
                curl_setopt ($ch, CURLOPT_TIMEOUT, 10);
                curl_setopt($ch, CURLOPT_HEADER, 0);
                $code = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
                $data = curl_exec($ch);
                $data = trim_strs( $data );
                curl_close($ch);
                if( empty( $data ) || in_array( $code, $not_accept ) > 0 || substr_count($data,"sorry")>0 ) {
                    $rs["error"] = "Proxy not working!.Please try later!.";
                    break;
                }
                else if(substr_count($data,$website)>0){
                	$page_start=$i;
                	break;
                }
            }
        }
        if($page_start>=0 && isset( $page_start )){	 
            $rs[$val] = kpc_pos($website,$data,$page_start);
        }
        else if( !isset( $rs["error"] ) ){
            $rs[$val] = 'NOT TOP '.$range;
        }
    }
    $print = null;
    if( !isset( $rs['error'] ) ) {
        $print .= "<table id='kwposchecker'>";
        $print .= "<tr><th>Keyword</th><th>Position</th></tr>";
        foreach( $rs as $key => $val ) {
            $key = str_replace('+',' ',trim( strip_tags( $key ) ));
            $print .= "<tr><td class='kw'>".$key."</td><td class='pos'>".$val."</td></tr>";
        }
        $print .= "</table>";
        echo $print;
    }
    else {
        echo "<span class='error'>".$rs["error"]."</span>";
    }
}

add_action('wp_ajax_nopriv_kpc', 'kpc_ajax');
add_action('wp_ajax_kpc', 'kpc_ajax');
function kpc_ajax() {
    global $wpdb;
    $id_country = trim( strip_tags( $_POST['kpc_place'] ) );
    $country_arg = kpc_get_country_byid( $id_country );
    $settings = get_option( 'kpc_settings' );
    $proxy = $settings['proxy'];
    if( empty( $proxy ) ) {
        $proxy = null;
    }
    $range = (int)trim( strip_tags( $_POST['kpc_range'] ) );
    $keyword=str_replace(' ','+',trim( strip_tags( $_POST['kw'] ) ));
    $website=trim( strip_tags( $_POST['website'] ) );
    if( !empty( $website ) && !empty( $keyword ) && !empty( $country_arg ) ) {
        $g=trim( $country_arg->google_place );
        $l=trim( $country_arg->language );
        kpc_check( $keyword, $website, $g, $l, $range, $proxy );
    }
    die();
}

function kpc_scripts_action()
{
      global $kpc_plugin_url;
      wp_register_script( "kpc_ajax", $kpc_plugin_url . 'js/kpc_ajax.js', array('jquery') );
      wp_localize_script( 'kpc_ajax', 'kpcAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' )));
      wp_enqueue_script('jquery');
      wp_enqueue_script('kpc-validate', $kpc_plugin_url . 'js/jquery.validate.min.js', array('jquery'));
      wp_enqueue_script('kpc_ajax');
}

function kpc_head_action()
{
    global $kpc_plugin_url;
    echo '<link rel="stylesheet" href="' . $kpc_plugin_url . 'css/style.css" type="text/css" />';
    echo '<link rel="stylesheet" href="' . $kpc_plugin_url . 'css/admin.css" type="text/css" />';
}
  
add_action('wp_print_scripts', 'kpc_scripts_action');

add_action('wp_print_scripts', 'kpc_head_action');

add_shortcode( "kwposchecker", "keyword_position_checker" );

add_action( 'wp_head', 'custom_style' );
function custom_style() {
    $settings = get_option( 'kpc_settings' );
    if( !empty( $settings['style'] ) ) {
?>
<style type="text/css">
    <?php echo $settings['style']; ?>
</style>
<?php
    }
}
?>