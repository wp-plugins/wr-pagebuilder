<?php
/**
 * @version    $Id$
 * @package    WR PageBuilder
 * @author     WooRockets Team <support@www.woorockets.com>
 * @copyright  Copyright (C) 2012 www.woorockets.com. All Rights Reserved.
 * @license    GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.www.woorockets.com
 * Technical Support:  Feedback - http://www.www.woorockets.com
 */

/**
 * @todo : WR PageBuilder Settings page
 */
?>
<div class="wrap">

	<h2>
	<?php esc_html_e( 'WR PageBuilder Settings', WR_PBL ); ?>
	</h2>

	<?php
	// Show message when save
	$saved = ( isset ( $_GET ) && $_GET['settings-updated'] == 'true' ) ? __( 'Settings saved.', WR_PBL ) : __( 'Settings saved.', WR_PBL );

	$msg = $type = '';
	if ( isset ( $_GET['settings-updated'] ) && $_GET['settings-updated'] == 'true' ) {
		$msg  = __( 'Settings saved.', WR_PBL );
		$type = 'updated';
	} else {
		if ( $_GET['settings-updated'] != 'true' ) {
			$msg  = __( 'Settings is not saved.', WR_PBL );
			$type = 'error';
		}
	}

	if ( isset ( $_GET['settings-updated'] ) ) {
		?>
	<div id="setting-error-settings_updated"
		class="<?php echo esc_attr( $type ); ?> settings-error">
		<p>
			<strong><?php echo esc_html( $msg ); ?> </strong>
		</p>
	</div>
	<?php
	}


	$options = array( 'wr_pb_settings_cache', 'wr_pb_settings_boostrap_js', 'wr_pb_settings_boostrap_css' );
	// submit handle
	if ( ! empty ( $_POST ) ) {
		foreach ( $options as $key ) {
			$value = ! empty( $_POST[$key] ) ? 'enable' : 'disable';
			update_option( $key, $value );
		}

		unset( $_POST );
		WR_Pb_Helper_Functions::alert_msg( array( 'success', __( 'Your settings are saved successfully', WR_PBL ) ) );
	}
	// get saved options value
	foreach ( $options as $key ) {
		$$key = get_option( $key, 'enable' );
	}

	// show options form
	?>
	<form method="POST" action="options.php">
	<?php
	$page = 'wr-pb-settings';
	settings_fields( $page );
	do_settings_sections( $page );
	submit_button();
	?>
	</form>
</div>

	<?php
	// Load inline script initialization
	$script = '
		new WR_Pb_Settings({
			ajaxurl: "' . admin_url( 'admin-ajax.php' ) . '",
			_nonce: "' . wp_create_nonce( WR_NONCE ) . '",
			button: "wr-pb-clear-cache",
			button: "wr-pb-clear-cache",
			loading: "#wr-pb-clear-cache .layout-loading",
			message: $("#wr-pb-clear-cache").parent().find(".layout-message"),
		});
        ';

WR_Pb_Init_Assets::inline( 'js', $script );

	// Load inlide style
	$loading_img = WR_PB_URI . '/assets/woorockets/images/icons-16/icon-16-loading-circle.gif';
	$style = '
		.jsn-bootstrap3 { margin-top: 30px; }
        .jsn-bootstrap3 .checkbox { background:#fff; }
        #wr-pb-clear-cache, .layout-message { margin-left: 6px; }
        .jsn-icon-loading { background: url("' . $loading_img . '") no-repeat scroll left center; content: " "; display: none; height: 16px; width: 16px; float: right; margin-left: 20px; margin-top: -26px; padding-top: 10px; }
        ';
WR_Pb_Init_Assets::inline( 'css', $style );
