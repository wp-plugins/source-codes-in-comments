=== Source Codes in Comments ===
Contributors: Zen
Donate link: http://zenverse.net/support/
Tags: source,codes,comment
Requires at least: 2.5
Tested up to: 2.8.4
Stable tag: 1.0.2

Allow users to post source codes in comments using [code][/code] tag. No syntax highlight at the moment, it just replaces the special characters.

== Description ==

Allow users to post source codes in comments using [code][/code] tag. No syntax highlight at the moment, it just replaces the special characters.

**Features**

*   Replaces the special characters for the comment texts wrapped in [code][/code] tags
*   You can show a message after comment form to tell your visitor know they can post codes using [code][/code] tags

[Plugin Info Page](http://zenverse.net/source-codes-in-comments-plugin/) | [Plugin Author](http://zenverse.net/)

== Installation ==

1. Download the plugin package
2. Extract and upload the "source-codes-in-comments" folder to your-wordpress-directory/wp-content/plugins/
3. Activate the plugin and its ready
4. Go to Admin Panel > Settings > Codes in Comments and customise it to suit your needs.

== Frequently Asked Questions ==
= What is the output? Show me an example. =
If you use `[code]This is a <b>bold text</b>.[/code]`, the output will be `<div class="cic_codes_div"><code >This is a &lt;b&gt;bold text&lt;/b&gt;</code></div>.`

= How do I style the source code DIV? =
The DIV that contains the codes has CSS class &quot;`cic_codes_div`&quot;. Style it in CSS.

== Screenshots ==
1. Plugin Option Page
2. Comment form

== Changelog ==
= 1.0.2 =
* Added str_replace to the stripslashes so that "plugin message" field works with double quotes

= 1.0.1 =
* Added stripslashes to input area
* Updated the link to plugin support

= 1.0 =
* First version of Source Codes in Comments