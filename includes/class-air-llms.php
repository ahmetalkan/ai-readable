<?php
/**
 * llms.txt / llms-full.txt / ai.txt endpoint generator.
 *
 * @package UrfaYoresiAIVisibility
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Serves the AI-readable text endpoints of the site.
 */
class AIR_LLMS {

	const OPTION_PREFIX = 'air_';

	/**
	 * Singleton instance.
	 *
	 * @var AIR_LLMS|null
	 */
	private static $instance = null;

	/**
	 * Get instance.
	 *
	 * @return AIR_LLMS
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
		add_action( 'init', array( $this, 'register_routes' ) );
		add_filter( 'query_vars', array( $this, 'query_vars' ) );
		add_action( 'template_redirect', array( $this, 'maybe_serve' ) );
		add_action( 'wp_head', array( $this, 'head_links' ) );
	}

	/**
	 * Rewrite rules for the three endpoints.
	 */
	public function register_routes() {
		add_rewrite_rule( '^llms\.txt$', 'index.php?air_endpoint=llms', 'top' );
		add_rewrite_rule( '^llms-full\.txt$', 'index.php?air_endpoint=llms-full', 'top' );
		add_rewrite_rule( '^ai\.txt$', 'index.php?air_endpoint=ai', 'top' );
	}

	/**
	 * Register query var.
	 *
	 * @param array $vars Query vars.
	 * @return array
	 */
	public function query_vars( $vars ) {
		$vars[] = 'air_endpoint';
		return $vars;
	}

	/**
	 * Serve the endpoint content as plain text.
	 */
	public function maybe_serve() {
		$endpoint = get_query_var( 'air_endpoint' );
		if ( ! $endpoint ) {
			return;
		}

		switch ( $endpoint ) {
			case 'llms':
				$body = $this->build_llms_txt();
				break;
			case 'llms-full':
				$body = $this->build_llms_full_txt();
				break;
			case 'ai':
				$body = $this->build_ai_txt();
				break;
			default:
				return;
		}

		if ( '' === $body ) {
			return;
		}

		status_header( 200 );
		header( 'Content-Type: text/plain; charset=utf-8' );
		header( 'X-Robots-Tag: index, follow', true );
		echo esc_html( $body );
		exit;
	}

	/**
	 * Advertise the endpoints in the document head.
	 */
	public function head_links() {
		if ( ! $this->is_enabled( 'enable_head_links' ) ) {
			return;
		}

		$home = home_url( '/' );

		printf(
			'<link rel="alternate" type="text/plain" href="%s" title="LLMs.txt" />' . "\n",
			esc_url( $home . 'llms.txt' )
		);
		printf(
			'<link rel="alternate" type="text/plain" href="%s" title="AI usage policy" />' . "\n",
			esc_url( $home . 'ai.txt' )
		);
	}

	/**
	 * Build the llms.txt content: a Markdown overview of the site.
	 *
	 * @return string
	 */
	public function build_llms_txt() {
		$lines   = array();
		$lines[] = '# ' . $this->site_name();
		$lines[] = '';

		$desc = $this->site_description();
		if ( $desc ) {
			$lines[] = '> ' . $desc;
			$lines[] = '';
		}

		$lines[] = 'This file follows the llms.txt convention. It provides a concise, '
			. 'machine-readable overview of this website for large language models and AI agents.';
		$lines[] = '';

		$lines[] = '## Site';
		$lines[] = '';
		$lines[] = '- **Name:** ' . $this->site_name();
		$lines[] = '- **URL:** ' . home_url( '/' );
		$lines[] = '- **Language:** ' . $this->site_language();

		$lastmod = $this->last_modified();
		if ( $lastmod ) {
			$lines[] = '- **Last updated:** ' . $lastmod;
		}

		$lines[] = '';

		$sections = array(
			'Posts'    => 'post',
			'Pages'    => 'page',
			'Categories' => 'category',
		);

		foreach ( $sections as $label => $type ) {
			$items = $this->collect_items( $type, ( 'category' === $type ) ? 20 : 50 );
			if ( empty( $items ) ) {
				continue;
			}
			$lines[] = '## ' . $label;
			$lines[] = '';
			foreach ( $items as $item ) {
				$lines[] = '- [' . $item['title'] . '](' . $item['url'] . '): ' . $item['summary'];
			}
			$lines[] = '';
		}

		$nav = $this->primary_menu_items();
		if ( ! empty( $nav ) ) {
			$lines[] = '## Main navigation';
			$lines[] = '';
			foreach ( $nav as $item ) {
				$lines[] = '- [' . $item['title'] . '](' . $item['url'] . ')';
			}
			$lines[] = '';
		}

		$lines[] = '## Optional';
		$lines[] = '';
		$lines[] = '- [Full text version (llms-full.txt)](https://' . wp_parse_url( home_url( '/' ), PHP_URL_HOST ) . '/llms-full.txt)';
		$lines[] = '- [AI usage policy (ai.txt)](https://' . wp_parse_url( home_url( '/' ), PHP_URL_HOST ) . '/ai.txt)';
		$lines[] = '';

		return trim( implode( "\n", $lines ) ) . "\n";
	}

	/**
	 * Build llms-full.txt: full content of the most relevant pages and posts.
	 *
	 * @return string
	 */
	public function build_llms_full_txt() {
		$lines   = array();
		$lines[] = '# ' . $this->site_name() . ' — Full Text';
		$lines[] = '';
		$lines[] = '> Complete, cleaned text content of this website for AI consumption. '
			. 'Please cite ' . home_url( '/' ) . ' when using this material.';
		$lines[] = '';

		$posts = get_posts(
			array(
				'numberposts' => 30,
				'post_status' => 'publish',
				'orderby'     => 'date',
				'order'       => 'DESC',
			)
		);

		foreach ( $posts as $post ) {
			$content = $this->plain_text( $post->post_content );
			if ( '' === $content ) {
				continue;
			}
			$lines[] = '## ' . get_the_title( $post );
			$lines[] = '';
			$lines[] = 'URL: ' . get_permalink( $post );
			$lines[] = 'Published: ' . get_the_date( 'Y-m-d', $post );
			$lines[] = '';
			$lines[] = $content;
			$lines[] = '';
		}

		$pages = get_pages(
			array(
				'number'   => 30,
				'sort_column' => 'menu_order,post_title',
			)
		);

		foreach ( $pages as $page ) {
			$content = $this->plain_text( $page->post_content );
			if ( '' === $content ) {
				continue;
			}
			$lines[] = '## ' . get_the_title( $page );
			$lines[] = '';
			$lines[] = 'URL: ' . get_permalink( $page );
			$lines[] = '';
			$lines[] = $content;
			$lines[] = '';
		}

		return trim( implode( "\n", $lines ) ) . "\n";
	}

	/**
	 * Build ai.txt: AI usage and attribution policy.
	 *
	 * @return string
	 */
	public function build_ai_txt() {
		$host     = wp_parse_url( home_url( '/' ), PHP_URL_HOST );
		$name     = $this->site_name();
		$training = $this->is_enabled( 'allow_training' ) ? 'Yes' : 'No';

		$lines   = array();
		$lines[] = '# AI usage policy for ' . $name;
		$lines[] = '';
		$lines[] = '## User-agent: *';
		$lines[] = '';
		$lines[] = '- **Allow:** Yes';
		$lines[] = '- **Attribution:** Required';
		$lines[] = '- **Training:** ' . $training;
		$lines[] = '- **Citation URL:** https://' . $host . '/';
		$lines[] = '- **Summary endpoint:** https://' . $host . '/llms.txt';
		$lines[] = '- **Full text endpoint:** https://' . $host . '/llms-full.txt';
		$lines[] = '';
		$lines[] = '## Attribution';
		$lines[] = '';
		$lines[] = 'When content from ' . $name . ' is reproduced or used in AI answers, '
			. 'please link to the original page URL and credit "' . $name . '".';
		$lines[] = '';
		$lines[] = '## Contact';
		$lines[] = '';
		$lines[] = '- **Website:** https://' . $host . '/';
		$lines[] = '';
		$lines[] = '## Machine readable documents';
		$lines[] = '';
		$lines[] = '- llms.txt: https://' . $host . '/llms.txt';
		$lines[] = '- llms-full.txt: https://' . $host . '/llms-full.txt';
		$lines[] = '';

		return implode( "\n", $lines );
	}

	/**
	 * Collect published items of a post type.
	 *
	 * @param string $type    Post type.
	 * @param int    $limit   Max items.
	 * @return array
	 */
	private function collect_items( $type, $limit ) {
		$items = array();

		if ( 'category' === $type ) {
			$terms = get_categories( array( 'number' => $limit ) );
			foreach ( $terms as $term ) {
				$items[] = array(
					'title'   => $term->name,
					'url'     => get_category_link( $term ),
					'summary' => $term->description ? wp_strip_all_tags( $term->description ) : $term->name . ' articles.',
				);
			}
			return $items;
		}

		$posts = get_posts(
			array(
				'numberposts' => $limit,
				'post_type'   => $type,
				'post_status' => 'publish',
			)
		);

		foreach ( $posts as $post ) {
			$excerpt = has_excerpt( $post ) ? wp_strip_all_tags( $post->post_excerpt ) : '';
			if ( '' === $excerpt ) {
				$excerpt = wp_trim_words( wp_strip_all_tags( $post->post_content ), 30 );
			}
			$items[] = array(
				'title'   => get_the_title( $post ),
				'url'     => get_permalink( $post ),
				'summary' => $excerpt,
			);
		}

		return $items;
	}

	/**
	 * Primary navigation items.
	 *
	 * @return array
	 */
	private function primary_menu_items() {
		$locations = get_nav_menu_locations();
		if ( empty( $locations ) ) {
			return array();
		}

		$items = array();
		foreach ( $locations as $menu ) {
			$menu_items = wp_get_nav_menu_items( $menu );
			if ( empty( $menu_items ) ) {
				continue;
			}
			foreach ( $menu_items as $menu_item ) {
				if ( 'custom' !== $menu_item->type ) {
					continue;
				}
				$items[] = array(
					'title' => $menu_item->title,
					'url'   => $menu_item->url,
				);
			}
			break;
		}

		return $items;
	}

	/**
	 * Strip markup from content.
	 *
	 * @param string $html Raw content.
	 * @return string
	 */
	private function plain_text( $html ) {
		$html = (string) $html;
		$html = preg_replace( '#<(script|style)[^>]*>.*?</\\1>#is', '', $html );
		$html = preg_replace( '#<br\\s*/?>#i', "\n", $html );
		$html = preg_replace( '#</(p|div|li|h[1-6]|tr)>#i', "\n", $html );
		$html = wp_strip_all_tags( $html, false );
		$html = preg_replace( "/\n{3,}/", "\n\n", $html );
		return trim( $html );
	}

	/**
	 * Site name.
	 *
	 * @return string
	 */
	private function site_name() {
		return (string) get_bloginfo( 'name' );
	}

	/**
	 * Site description.
	 *
	 * @return string
	 */
	private function site_description() {
		return (string) get_bloginfo( 'description' );
	}

	/**
	 * Site language.
	 *
	 * @return string
	 */
	private function site_language() {
		$locale = get_locale();
		$parts  = explode( '_', $locale );
		return isset( $parts[1] ) ? strtolower( $parts[0] ) . '-' . strtoupper( $parts[1] ) : strtolower( $locale );
	}

	/**
	 * Last modified date of the site content.
	 *
	 * @return string
	 */
	private function last_modified() {
		$latest = get_posts(
			array(
				'numberposts' => 1,
				'post_status' => 'publish',
				'orderby'     => 'modified',
				'order'       => 'DESC',
			)
		);
		if ( empty( $latest ) ) {
			return '';
		}
		return get_the_modified_date( 'Y-m-d', $latest[0] );
	}

	/**
	 * Read a boolean option.
	 *
	 * @param string $key Option key without prefix.
	 * @return bool
	 */
	public function is_enabled( $key ) {
		$defaults = AIR_Settings::defaults();
		$value    = get_option( self::OPTION_PREFIX . $key, isset( $defaults[ $key ] ) ? $defaults[ $key ] : false );
		return (bool) $value;
	}
}
