<?php
/**
 * @package express
 */
/*
Plugin Name: PayPal Express Checkout
Plugin URI: https://example.com
Description: PayPal Express Checkout
Version: 0.0.0
Author: echizenya
Author URI: https://e-yota.com
License: GPLv2 or later
Text Domain: paypal_expresscheckout
*/

// checkout.jsの読み込み
function paypal_scripts() {
  wp_enqueue_script( 'paypal-checkout', 'https://www.paypalobjects.com/api/checkout.js' );
}
add_action( 'wp_enqueue_scripts', 'paypal_scripts' );

// ショートコードとオプションによるPayPalボタンの表示
function paypaldiv_func( $atts ){

  $config = shortcode_atts( array(
    'id' => '',
    'total' => '0',
		'currency' => '',
    'env' => 'sandbox',
    'color' => '',
		'size' => '',
	), $atts );

  // id、価格、通貨のいずれかがない場合は実行終了
  if ( !$config['id'] || $config['total'] === '0' || !$config['currency'] ) return;

  // 実行環境によって使用するトークンを変更する
  if ( $config['env'] === 'sandbox' ) {
    $token = "sandbox: 'AZDxjDScFpQtjWTOUtWKbyN_bDt4OgqaF4eYXlewfBP4-8aqX3PiV8e1GWU6liB2CUXlkA59kJXE7M6R'";
  } elseif ( $config['env'] === 'production' ) {
    $token = "production: 'input your production token'";
  }

  $paypaldiv = '<div id="' . $config['id'] . '"></div>';
  $paypaldiv .= "<script>
		paypal.Button.render({
			env: 'sandbox',
			client: {
				{$token},
			},
			style: {
				color: '$config[color]',
				size: '$config[size]',
			},
			commit: true,
			payment: function(data, actions) {
				return actions.payment.create({
					payment: {
						transactions: [{
							amount: { total: '$config[total]', currency: '$config[currency]' }
						}]
					}
				});
			},
			onAuthorize: function(data, actions) {
				return actions.payment.execute().then(function() {
					window.alert('Payment Complete!');
				});
			}
		}, '#$config[id]');
	</script>";

  // スクリプトの記述が表示される
  return $paypaldiv;
}
add_shortcode( 'paypaldiv', 'paypaldiv_func' );

function paypalexpresscheckout_add_admin_menu(){
    add_submenu_page('plugins.php','PayPal Express Checkoutの設定','PayPal Express Checkoutの設定', 'administrator', __FILE__, 'paypalexpresscheckout_admin_menu');
    add_action( 'admin_init', 'register_paypalsettings' );
}
add_action('admin_menu', 'paypalexpresscheckout_add_admin_menu');

function register_paypalsettings() {
	register_setting( 'paypal-settings-group', 'env' );
  register_setting( 'paypal-settings-group', 'client' );
}


require_once(__DIR__ . '/express_admin.php');
