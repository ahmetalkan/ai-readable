=== AI Readable ===
Contributors: urfayoresi
Tags: llms.txt, llms, ai, seo, gptbot, claudebot
Requires at least: 5.9
Tested up to: 7.1
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPL-2.0-or-later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Makes your WordPress site readable by AI engines and AI agents: llms.txt, llms-full.txt, ai.txt and AI crawler signals.

== Description ==

Search engines are no longer the only way people find information. AI assistants such as ChatGPT, Claude, Gemini, Perplexity and Copilot crawl websites on their own, with their own crawlers, and they often look for machine-readable summaries first.

Most WordPress sites say nothing to those crawlers. AI Readable gives your site a clear, structured voice for them.

= What it does =

1. **llms.txt** - a concise Markdown overview of your site, generated from your live content: pages, posts, categories and main navigation.
2. **llms-full.txt** - the complete cleaned plain text of your recent posts and pages, with markup stripped so models do not waste tokens on HTML.
3. **ai.txt** - a machine-readable AI usage policy: training allowed or not, attribution required, and the URL to cite.
4. **AI crawler rules in robots.txt** - explicit `Allow: /` for GPTBot, OAI-SearchBot, ChatGPT-User, ClaudeBot, Claude-SearchBot, PerplexityBot, Google-Extended, Applebot-Extended, Bytespider, Meta-ExternalAgent, Amazonbot, mistralai-User, Cohere, DuckAssistBot and 20 more.
5. **Head signals** - `<link rel="alternate">` tags pointing at llms.txt and ai.txt, plus a `WebSite` schema.org JSON-LD block with a `SearchAction`.
6. **Optional footer credit** - off by default, enabled from the settings screen.

= Privacy =

No tracking, no cookies, no external requests. The plugin only reads your own published content and writes plain text files on demand.

== Installation ==

= From the plugin directory =

1. Go to Plugins > Add New and search for "AI Readable".
2. Click Install, then Activate.
3. Open `yourdomain.com/llms.txt` to confirm.

= Manual =

1. Upload the plugin ZIP to Plugins > Add New > Upload Plugin.
2. Activate it.

= WP-CLI =

`wp plugin install ai-readable --activate`

== Frequently Asked Questions ==

= Do I need to change my permalinks? =

No. The endpoints work with any permalink structure.

= Which AI tools can use it? =

Any tool that can fetch a URL can read llms.txt. The crawler rules mainly help AI crawlers such as GPTBot, ClaudeBot, PerplexityBot and Bytespider index your content for retrieval and citation.

= Does this affect Google rankings? =

Not directly. The plugin adds standard alternate link tags and JSON-LD. It does not add hidden text, keyword stuffing or cloaking.

= Does it slow my site down? =

No. Content is generated on request and there are no extra database queries on normal page loads.

== Screenshots ==

1. The settings screen under Settings > AI Readable.

== Changelog ==

= 1.0.0 =
* Initial release.
* llms.txt, llms-full.txt and ai.txt endpoints.
* AI crawler rules in robots.txt.
* Alternate link tags and WebSite JSON-LD.
* Optional footer credit.
* Settings page.

== Upgrade Notice ==

= 1.0.0 =
Initial release.

== Credits ==

* Author URI: https://sleepclinicdirectory.com/
* Related: https://sleepclinicdirectory.com/

== Copyright ==

AI Readable WordPress Plugin, (C) 2026 AI Readable.
AI Readable is distributed under the terms of the GNU GPL v2 or later.

This plugin is based on the concept of the llms.txt convention, first proposed by Jeremy Howard and expanded by the llm.txt community.
