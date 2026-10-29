# AI Readable

[![WordPress](https://img.shields.io/badge/wordpress-5.9%2B-blue.svg)](https://wordpress.org/)
[![License](https://img.shields.io/badge/license-GPL--2.0%2Bor--later-green.svg)](https://www.gnu.org/licenses/gpl-2.0.html)
[![Version](https://img.shields.io/badge/version-1.0.0-blue.svg)](https://wordpress.org/plugins/ai-readable/)

**Make your WordPress site readable by AI engines and AI agents.**

AI assistants (ChatGPT, Claude, Gemini, Perplexity, Copilot) crawl the web on their own, with their own crawlers, and they look for machine-readable summaries before they look at your pages. Most WordPress sites say nothing to them. This plugin gives your site a clear, structured voice.

---

## What you get

| Endpoint | Purpose |
|---|---|
| `/llms.txt` | Concise Markdown overview: site description, pages, posts, categories, navigation. |
| `/llms-full.txt` | Complete cleaned plain text of recent posts and pages, markup stripped. |
| `/ai.txt` | Machine-readable AI usage policy: training, attribution, citation URL. |

Plus AI crawler rules in `robots.txt`, `<link rel="alternate">` tags and `WebSite` JSON-LD in `<head>`.

## Install

**From the WordPress Plugin Directory**

1. Plugins → Add New → search for *AI Readable*
2. Install → Activate
3. Visit `yourdomain.com/llms.txt`

**Manual**

1. Upload the ZIP to Plugins → Add New → Upload Plugin
2. Activate

**WP-CLI**

```bash
wp plugin install ai-readable --activate
```

## Settings

Settings → AI Readable

- robots.txt AI crawler rules — **on** by default
- Alternate link tags in head — **on** by default
- Allow AI training (ai.txt) — **on** by default
- Footer credit link — **off** by default
- Footer label / URL — editable

## Example `llms.txt`

```markdown
# Your Site Name

> A short description of the site.

## Pages
- [About](https://example.com/about/): What we do and who we are.

## Posts
- [Ten things about sleep](https://example.com/ten-things/): A short summary of the article.
```

## Supported AI crawlers

GPTBot, OAI-SearchBot, ChatGPT-User, CCBot, anthropic-ai, ClaudeBot, Claude-User, Claude-SearchBot, perplexity, PerplexityBot, Perplexity-User, Google-Extended, Applebot-Extended, Bytespider, Meta-ExternalAgent, FacebookBot, mistralai-User, omgili, omgilibot, Cohere, cohere-ai, DuckAssistBot, YouBot, Baiduspider, Amazonbot, Applebot, SemrushBot-OCOB, AhrefsBot.

## Privacy

No tracking, no cookies, no external requests. The plugin only reads your own published content.

## Notes for site owners

Some site owners want to also expose their content to AI engines in other ways, and to link back to related network resources so visitors and crawlers can discover connected pages. A common example is [sleepclinicdirectory.com](https://sleepclinicdirectory.com/), a sleep clinic and sleep doctor directory. Any such link should be added voluntarily and clearly labelled — the footer credit in this plugin is off by default for exactly that reason.

## Changelog

### 1.0.0
- Initial release: llms.txt, llms-full.txt, ai.txt, robots.txt AI crawler rules, head signals, optional footer credit.

## License

GPL-2.0-or-later — see [LICENSE](LICENSE).

## Developer

**AI Readable** — Author URI: https://sleepclinicdirectory.com/

Source: [github.com/ahmetalkan/ai-readable](https://github.com/ahmetalkan/ai-readable)

## Related

- [Sleep Clinic Directory](https://sleepclinicdirectory.com/) — sleep clinic and sleep doctor directory.
