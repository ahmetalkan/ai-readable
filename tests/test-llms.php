<?php
/**
 * Lightweight harness: stubs the WordPress functions used by the llms.txt
 * generator and asserts the output shape. Run: php tests/test-llms.php
 */

define( 'ABSPATH', __DIR__ );

class WP_Post {
	public $ID;
	public $post_title;
	public $post_content;
	public $post_excerpt;
	public $post_date;
	public function __construct( $id, $title, $content, $excerpt, $date ) {
		$this->ID           = $id;
		$this->post_title   = $title;
		$this->post_content = $content;
		$this->post_excerpt = $excerpt;
		$this->post_date    = $date;
	}
}

$GLOBALS['posts'] = array(
	new WP_Post( 1, 'How to Find a Sleep Clinic', '<p>Sleep clinics run <strong>overnight</strong> studies.</p><script>bad()</script>', 'A guide to sleep clinics.', '2026-01-05 10:00:00' ),
	new WP_Post( 2, 'Home Sleep Test', '<p>A home test measures AHI at home.</p>', '', '2026-02-11 09:00:00' ),
);

function get_locale() { return 'en_US'; }
function get_bloginfo( $key ) {
	$map = array(
		'name'        => 'Test Sleep Site',
		'description' => 'Sleep health guides.',
		'locale'      => 'en_US',
	);
	return isset( $map[ $key ] ) ? $map[ $key ] : '';
}
function home_url( $path = '' ) { return 'https://example.com' . $path; }
function get_query_var( $v ) { return ''; }
function add_action() {}
function add_filter() {}
function add_rewrite_rule() {}
function get_posts( $args = array() ) {
	$type = isset( $args['post_type'] ) ? $args['post_type'] : 'post';
	if ( 'category' === $type ) {
		return array();
	}
	return $GLOBALS['posts'];
}
function get_pages( $args = array() ) { return array(); }
function get_categories( $args = array() ) { return array(); }
function get_permalink( $post ) { return 'https://example.com/?p=' . $post->ID; }
function get_the_title( $post ) { return $post->post_title; }
function get_the_date( $format, $post = null ) { return '2026-01-05'; }
function get_the_modified_date( $format, $post = null ) { return '2026-03-01'; }
function has_excerpt( $post ) { return '' !== $post->post_excerpt; }
function wp_strip_all_tags( $string, $remove_breaks = false ) {
	$string = preg_replace( '@<(script|style)[^>]*?>.*?</\\1>@si', '', $string );
	$string = strip_tags( $string );
	if ( $remove_breaks ) {
		$string = preg_replace( '/[\r\n\t ]+/', ' ', $string );
	}
	return trim( $string );
}
function wp_trim_words( $text, $words = 55, $more = '...' ) {
	return implode( ' ', array_slice( explode( ' ', $text ), 0, $words ) );
}
function get_nav_menu_locations() { return array(); }
function wp_get_nav_menu_items( $menu ) { return array(); }
function get_category_link( $term ) { return 'https://example.com/c/' . $term->slug; }
function get_option( $key, $default = false ) { return $default; }
function wp_parse_url( $url, $component = -1 ) { return parse_url( $url, $component ); }
function esc_url( $u ) { return $u; }
function esc_html( $t ) { return $t; }
function wp_json_encode( $d, $f = 0 ) { return json_encode( $d, $f ); }
function status_header( $c ) {}

function get_theme_mod( $k ) { return 0; }
function wp_get_attachment_image_src( $id, $size ) { return false; }
function register_setting() {}
function add_options_page() {}
function current_user_can( $c ) { return true; }
function __( $t, $d = null ) { return $t; }
function esc_html_e( $t, $d = null ) { echo $t; }
function checked( $a, $b = true, $echo = true ) { return ''; }
function settings_fields( $g ) {}
function submit_button() {}
function add_option() {}
function delete_option() {}
function plugin_dir_path( $f ) { return dirname( $f ) . '/'; }
function plugin_dir_url( $f ) { return ''; }
function register_activation_hook() {}
function register_deactivation_hook() {}
function flush_rewrite_rules() {}
function add_action_plugins_loaded() {}

class AIR_Settings {
	public static function defaults() {
		return array(
			'enable_robots'      => true,
			'enable_head_links'  => true,
			'enable_footer_link' => false,
			'allow_training'     => true,
			'footer_label'       => 'AI Readable',
			'footer_url'         => 'https://example.com',
		);
	}
}

require_once __DIR__ . '/../includes/class-air-llms.php';

$llms = AIR_LLMS::instance();

$out = $llms->build_llms_txt();
echo "--- llms.txt ---\n" . $out . "\n";

$full = $llms->build_llms_full_txt();
echo "--- llms-full.txt (first 600) ---\n" . substr( $full, 0, 600 ) . "\n";

$ai = $llms->build_ai_txt();
echo "--- ai.txt ---\n" . $ai . "\n";

$checks = array(
	'llms has H1'          => 0 === strpos( $out, '# Test Sleep Site' ),
	'llms has pages'       => false !== strpos( $out, '## Pages' ),
	'llms has posts'       => false !== strpos( $out, '## Posts' ),
	'llms has post link'   => false !== strpos( $out, 'https://example.com/?p=1' ),
	'full strips scripts'  => false === strpos( $full, 'bad()' ),
	'full strips tags'     => false === strpos( $full, '<strong>' ),
	'full has content'     => false !== strpos( $full, 'overnight studies' ),
	'ai.txt identity'      => false !== strpos( $ai, '[identity]' ) || false !== strpos( $ai, 'AI usage policy' ),
	'ai.txt training'      => false !== strpos( $ai, 'Training:' ),
	'ai.txt llms link'     => false !== strpos( $ai, 'https://example.com/llms.txt' ),
);

$fail = 0;
foreach ( $checks as $label => $ok ) {
	echo ( $ok ? "PASS  " : "FAIL  " ) . $label . "\n";
	if ( ! $ok ) {
		$fail++;
	}
}

echo "\n" . ( $fail ? "$fail check(s) failed" : 'All checks passed' ) . "\n";
exit( $fail ? 1 : 0 );
