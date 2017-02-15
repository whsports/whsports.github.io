<?php
/*
Plugin Name: JWDrivePlay
Plugin URI: 
Description: use short code like this [jwdrivet]link your video@link_your_subtitle[/jwdrive]
Version: 4.2
Author: -KD-
Author URI: http://play.dulbacloud.com
*/

ini_set('display_errors', '1');

function getvideo($url)
{
    $plain_txt = base64_encode($url.'@'.$_SERVER['HTTP_HOST']);
    $string = $plain_txt;
    $encrypt_method = "AES-256-CBC";
    $secret_key = 'This is my secret key';
    $secret_iv = 'This is my secret iv';
    // hash
    $key = hash('sha256', $secret_key); 
    // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
    $iv = substr(hash('sha256', $secret_iv), 0, 16);
    $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
    $output = base64_encode($output);
    $encrypted_txt = $output;
    $urlen = $encrypted_txt;
    return '<iframe class=\'embed-responsive-item\' style="border:0px #FFFFFF none;" scrolling="no" frameborder="0" marginheight="0px" marginwidth="0px" height="360px" width="640px" src="//play.dulbacloud.com/e/wp-embed.php?url='.$urlen.'" allowfullscreen></iframe>';
}

function pl_content() {
            $c = get_the_content();
			if (strpos($c, '[jwdrive]') !== false) {
				$c = explode('[jwdrive]', $c);
				$c2 = $c[0];
				$c = explode('[/jwdrive]', $c[1]);
				$c = getvideo($c[0]);
			}else{
				$c2 = '';
			}
			echo $c2.$c;
}
add_filter('the_content', 'pl_content');
add_action('admin_menu','pl_modifymenu');
function pl_modifymenu() {

	add_menu_page('JWPLUGINS',
		'JWDrivePlay',
		'manage_options',
		'pl_help',
		 pl_help
		);
}
define('ROOTDIR', plugin_dir_path(__FILE__));
require_once(ROOTDIR . 'pl-help.php');