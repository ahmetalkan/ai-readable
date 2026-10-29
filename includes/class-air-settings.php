<?php
/**
 * Admin settings page.
 *
 * @package UrfaYoresiAIVisibility
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registers the settings screen under Settings.
 */
class AIR_Settings {

	/**
	 * Singleton instance.
	 *
	 * @var AIR_Settings|null
	 */
	private static $instance = null;

	/**
	 * Get instance.
	 *
	 * @return AIR_Settings
	 */
	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Default option values.
	 *
	 * @return array
	 */
	public static function defaults() {
		return array(
			'enable_robots'      => true,
			'enable_head_links'  => true,
			'enable_footer_link' => false,
			'allow_training'     => true,
			'footer_label'       => 'AI Readable',
			'footer_url'         => 'https://github.com/ahmetalkan/ai-readable',
		);
	}

	/**
	 * Hook up.
	 */
	private function __construct() {
		add_action( 'admin_menu', array( $this, 'menu' ) );
		add_action( 'admin_init', array( $this, 'register' ) );
	}

	/**
	 * Add settings page.
	 */
	public function menu() {
		add_options_page(
			__( 'AI Visibility', 'ai-readable' ),
			__( 'AI Visibility', 'ai-readable' ),
			'manage_options',
			'air-settings',
			array( $this, 'render_page' )
		);
	}

	/**
	 * Register settings and fields.
	 */
	public function register() {
		$defaults = self::defaults();

		register_setting(
			'air_settings',
			'air_enable_robots',
			array(
				'type'              => 'boolean',
				'default'           => $defaults['enable_robots'],
				'sanitize_callback' => 'rest_sanitize_boolean',
			)
		);
		register_setting(
			'air_settings',
			'air_enable_head_links',
			array(
				'type'              => 'boolean',
				'default'           => $defaults['enable_head_links'],
				'sanitize_callback' => 'rest_sanitize_boolean',
			)
		);
		register_setting(
			'air_settings',
			'air_enable_footer_link',
			array(
				'type'              => 'boolean',
				'default'           => $defaults['enable_footer_link'],
				'sanitize_callback' => 'rest_sanitize_boolean',
			)
		);
		register_setting(
			'air_settings',
			'air_allow_training',
			array(
				'type'              => 'boolean',
				'default'           => $defaults['allow_training'],
				'sanitize_callback' => 'rest_sanitize_boolean',
			)
		);
		register_setting(
			'air_settings',
			'air_footer_label',
			array(
				'type'              => 'string',
				'default'           => $defaults['footer_label'],
				'sanitize_callback' => 'sanitize_text_field',
			)
		);
		register_setting(
			'air_settings',
			'air_footer_url',
			array(
				'type'              => 'string',
				'default'           => $defaults['footer_url'],
				'sanitize_callback' => 'esc_url_raw',
			)
		);
	}

	/**
	 * Render settings page.
	 */
	public function render_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'AI Readable', 'ai-readable' ); ?></h1>
			<p><?php esc_html_e( 'Publishes llms.txt, llms-full.txt and ai.txt for AI engines, and adds crawler signals to robots.txt and the document head.', 'ai-readable' ); ?></p>
			<form method="post" action="options.php">
				<?php settings_fields( 'air_settings' ); ?>
				<table class="form-table" role="presentation">
					<tr>
						<th scope="row"><?php esc_html_e( 'robots.txt AI crawler rules', 'ai-readable' ); ?></th>
						<td><input type="checkbox" name="air_enable_robots" value="1" <?php checked( (bool) get_option( 'air_enable_robots', true ) ); ?> /></td>
					</tr>
					<tr>
						<th scope="row"><?php esc_html_e( 'Alternate link tags in head', 'ai-readable' ); ?></th>
						<td><input type="checkbox" name="air_enable_head_links" value="1" <?php checked( (bool) get_option( 'air_enable_head_links', true ) ); ?> /></td>
					</tr>
					<tr>
						<th scope="row"><?php esc_html_e( 'Allow AI training (ai.txt)', 'ai-readable' ); ?></th>
						<td><input type="checkbox" name="air_allow_training" value="1" <?php checked( (bool) get_option( 'air_allow_training', true ) ); ?> /></td>
					</tr>
					<tr>
						<th scope="row"><?php esc_html_e( 'Footer credit link', 'ai-readable' ); ?></th>
						<td>
							<input type="checkbox" name="air_enable_footer_link" value="1" <?php checked( (bool) get_option( 'air_enable_footer_link', false ) ); ?> />
							<p class="description"><?php esc_html_e( 'Optional. Off by default. Recommended for sites that want to give back to the project.', 'ai-readable' ); ?></p>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="air_footer_label"><?php esc_html_e( 'Footer label', 'ai-readable' ); ?></label></th>
						<td><input type="text" class="regular-text" id="air_footer_label" name="air_footer_label" value="<?php echo esc_attr( get_option( 'air_footer_label', self::defaults()['footer_label'] ) ); ?>" /></td>
					</tr>
					<tr>
						<th scope="row"><label for="air_footer_url"><?php esc_html_e( 'Footer URL', 'ai-readable' ); ?></label></th>
						<td><input type="url" class="regular-text" id="air_footer_url" name="air_footer_url" value="<?php echo esc_attr( get_option( 'air_footer_url', self::defaults()['footer_url'] ) ); ?>" /></td>
					</tr>
				</table>
				<?php submit_button(); ?>
			</form>
		</div>
		<?php
	}
}
