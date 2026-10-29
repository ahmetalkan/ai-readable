<?php
/**
 * Uninstall routine: removes all plugin options.
 *
 * @package AIReadable
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

$air_options = array(
	'air_enable_robots',
	'air_enable_head_links',
	'air_enable_footer_link',
	'air_allow_training',
	'air_footer_label',
	'air_footer_url',
);

foreach ( $air_options as $air_option ) {
	delete_option( $air_option );
}

delete_option( 'air_settings' );
