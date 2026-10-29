<?php
/**
 * AI crawler signals: robots.txt rules, alt tags and JSON-LD.
 *
 * @package UrfaYoresiAIVisibility
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Emits AI discovery signals to crawlers and search engines.
 */
class AIR_Crawlers {

	/**
	 * Known AI crawler user agents.
	 *
	 * @return array
	 */
	public static function ai_agents() {
		return array(
			'GPTBot',
			'OAI-SearchBot',
			'ChatGPT-User',
			'CCBot',
			'anthropic-ai',
			'ClaudeBot',
			'Claude-User',
			'Claude-SearchBot',
			'perplexity',
			'PerplexityBot',
			'Perplexity-User',
			'Google-Extended',
			'Applebot-Extended',
			'Bytespider',
			'Meta-ExternalAgent',
			'FacebookBot',
			'mistralai-User',
			'omgili',
			'omgilibot',
			'Cohere',
			'cohere-ai',
			'DuckAssistBot',
			'YouBot',
			'Baiduspider',
			'Amazonbot',
			'Applebot',
			'SemrushBot-OCOB',
			'AhrefsBot',
		);
	}

	/**
	 * Singleton instance.
	 *
	 * @var AIR_Crawlers|null
	 */
	private static $instance = null;

	/**
	 * Get instance.
	 *
	 * @return AIR_Crawlers
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
		add_filter( 'robots_txt', array( $this, 'robots_txt' ), 10, 2 );
		add_action( 'wp_head', array( $this, 'head_signals' ), 5 );
	}

	/**
	 * Append AI crawler rules to robots.txt.
	 *
	 * @param string $output Robots.txt content.
	 * @param mixed  $public Whether site is public.
	 * @return string
	 */
	public function robots_txt( $output, $public ) {
		if ( '1' !== (string) $public ) {
			return $output;
		}

		if ( ! AIR_LLMS::instance()->is_enabled( 'enable_robots' ) ) {
			return $output;
		}

		$host = wp_parse_url( home_url( '/' ), PHP_URL_HOST );

		$lines   = array();
		$lines[] = '';
		$lines[] = '# AI crawler signals - added by Urfa Yöresi AI Visibility';
		$lines[] = '# Full site access for AI crawlers and agents';

		foreach ( self::ai_agents() as $agent ) {
			$lines[] = 'User-agent: ' . $agent;
			$lines[] = 'Allow: /';
			$lines[] = '';
		}

		$lines[] = 'User-agent: *';
		$lines[] = 'Allow: /';
		$lines[] = '';
		$lines[] = 'Sitemap: https://' . $host . '/wp-sitemap.xml';
		$lines[] = 'Sitemap: https://' . $host . '/llms.txt';
		$lines[] = '';

		return $output . implode( "\n", $lines );
	}

	/**
	 * Add alternate links and JSON-LD in the head.
	 */
	public function head_signals() {
		if ( ! AIR_LLMS::instance()->is_enabled( 'enable_head_links' ) ) {
			return;
		}

		$host = wp_parse_url( home_url( '/' ), PHP_URL_HOST );

		printf(
			'<link rel="alternate" type="text/plain" href="https://%1$s/llms-full.txt" title="LLMs full text" />' . "\n",
			esc_html( $host )
		);

		$schema = array(
			'@context' => 'https://schema.org',
			'@type'    => 'WebSite',
			'name'     => get_bloginfo( 'name' ),
			'url'      => home_url( '/' ),
			'description' => get_bloginfo( 'description' ),
			'potentialAction' => array(
				'@type'       => 'SearchAction',
				'target'      => array(
					'@type'       => 'EntryPoint',
					'urlTemplate' => home_url( '/?s={search_term_string}' ),
				),
				'query-input' => 'required name=search_term_string',
			),
		);

		$logo = get_theme_mod( 'custom_logo' );
		if ( $logo ) {
			$image        = wp_get_attachment_image_src( $logo, 'full' );
			$schema['image'] = $image ? $image[0] : home_url( '/wp-content/uploads/logo.png' );
		}

		printf(
			'<script type="application/ld+json">%s</script>' . "\n",
			wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE )
		);
	}
}
