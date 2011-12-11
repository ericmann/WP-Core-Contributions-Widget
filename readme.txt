=== WP Core Contributions Widget ===
Contributors: ericmann
Donate link: http://jumping-duck.com/wordpress
Tags: core, widget, contributions
Requires at least: 3.2.1
Tested up to: 3.3
Stable tag: 0.3

Add a list of your accepted contributions to WordPress Core as a sidebar widget.

== Description ==

A lot of people write code.  A lot of people write WordPress plugins.  A lot of people write WordPress themes.  But only a handful of people contribute code back to the core WordPress project.  Take a second to show off the patches that you've submitted that have made it into core.  It's a great way to highlight your coding credentials and back up your resume.

== Installation ==

= Manual Installation =

1. Upload the entire `wp-core-contributions-widget` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Add the WP Core Contributions Widget to your sidebar (be sure to enter your Trac username!)

== Frequently Asked Questions ==

= Not all of my patches show up, what's wrong? =

The system currently only parses the first page of search results.  So your latest 10 patches will always be displayed.

= How can I customize the template? =

Take a look at the default template in `/inc/wp-core-contributions-widget-template.php`.  This is the way the widget displays by default.

However, you can place a custom template in your theme directory to override this.  Just place a `wp-core-contributions-widget-template.php` file in your theme directory to override the defaults.

Remember, all of the parsed Trac tickets are contained in the `$items` array and you must populate the `$out` string with your desired markup.  Aside from that, use any variables you like.

The `$items` array is a collection of associative arrays each containing:

* `link` -> A link to the actual Trac changeset.
* 'changeset' -> The ID of the changeset.
* 'description' -> The commit message for the changeset.
* 'ticket' -> The ID of the ticket fixed by the patch.

== Screenshots ==

1. Example widget showing the default markup on the Twenty Eleven theme and contributions by [ericmann](http://profiles.wordpress.org/users/ericmann/).

== Changelog ==

= 0.3 =
* Better formatting for widget template
* Handle changesets not bound to tickets
* Better i18n support

= 0.2 =
* Added text domain
* Improved RegEx parsing
* Customizable output templates

= 0.1 =
* First release

== Upgrade Notice ==

= 0.1 =
First release