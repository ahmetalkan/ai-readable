<?php
/**
 * Optional footer link (opt-in, off by default).
 *
 * @package UrfaYoresiAIVisibility
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Adds a configurable credit link to the site footer.
 */
class AIR_Footer {

	/**
	 * Singleton instance.
	 *
	 * @var AIR_Footer|null
	 */
	private static $instance = null;

	/**
	 * Get instance.
	 *
	 * @return AIR_Footer
	 */
	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Hook up.
	 */
	private function __construct() {
		add_action( 'wp_footer', array( $this, 'render' ), 20 );
	}

	/**
	 * Render the footer credit line.
	 */
	public function render() {
		if ( ! AIR_LLMS::instance()->is_enabled( 'enable_footer_link' ) ) {
			return;
		}

		$label = get_option( 'air_footer_label', AIR_Settings::defaults()['footer_label'] );
		$url   = get_option( 'air_footer_url', AIR_Settings::defaults()['footer_url'] );

		if ( ! $label || ! $url ) {
			return;
		}

		printf(
			'<div class="air-footer-credit" style="margin:12px 0;font-size:13px;opacity:.85;">%s</div>' . "\n",
			wp_kses_post(
				sprintf(
					'<a href="%1$s" target="_blank" rel="noopener nofollow">%2$s</a>',
					esc_url( $url ),
					esc_html( $label )
				)
			)
		);
	}
}
