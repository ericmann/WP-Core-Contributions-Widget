=== WP Core Contributions Widget ===
Contributors: ericmann, mfields, JohnPBloch, mbijon
Donate link: http://jumping-duck.com/wordpress
Tags: core, widget, contributions
Requires at least: 3.2.1
Tested up to: 3.4
Stable tag: 1.2.1
License: GPLv2+

Add a list of your accepted contributions to WordPress Core as a sidebar widget.

== Description ==

A lot of people write code.  A lot of people write WordPress plugins.  A lot of people write WordPress themes.

Only a handful of people contribute code back to the core WordPress project.  Even fewer contribute documentation to the WordPress Codex.

Take a second to show off the patches that you've submitted that have made it into core and the updates you've submitted to the Codex.  It's a great way to highlight your coding credentials and back up your resume.

== Installation ==

= Manual Installation =

1. Upload the entire `wp-core-contributions-widget` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Add the WP Core Contributions Widget to your sidebar (be sure to enter your Trac username!)
1. Add the WP Codex Contributions Widget to your sidebar (be sure to enter your Codex username!)

== Frequently Asked Questions ==

= Not all of my Trac patches show up, what's wrong? =

The system currently only parses the first page of search results.  So your latest 10 patches will always be displayed.

= How can I customize the templates? =

Take a look at the default templates in the `/inc/1 directory that the widgets display by default:

* `wp-core-contributions-widget-template.php`
* `wp-codex-contributions-widget-template.php`

However, you can place a custom template in your theme directory to override this.  Just place a `wp-core-contributions-widget-template.php` file in your theme directory to override the defaults.

Remember, all of the parsed Trac tickets are contained in the `$items` array.  The total count of the user's contributions is contained in the `$total` variable.  Aside from that, use any variables you like.

The Trac `$items` array is a collection of associative arrays each containing:

* `link` -> A link to the actual Trac changeset.
* `changeset` -> The ID of the changeset.
* `description` -> The commit message for the changeset.
* `ticket` -> The ID of the ticket fixed by the patch.

The parsed Codex pages are also contained in an `$items` array.  The total count of the user's contributions is contained in the `$total` variable.

The Codex `$items` array is a collection of associative arrays, each containing:

* `title` -> Title of the page being changed.
* `description` -> Description of the change made.
* `revision` -> Revision number according to the Codex wiki (used to create a link).
* `function_ref` -> Boolean flag regarding whether or not the change was to a function reference. Removes "Function Reference/" from the page title.

== Screenshots ==

1. Example widget showing the default markup on the Twenty Eleven theme and contributions by [ericmann](http://profiles.wordpress.org/users/ericmann/).
1. Example widget showing the default Codex contributions widget on the Twenty Eleven theme (contributions by [ericmann](http://profiles.wordpress.org/users/ericmann/).

== Changelog ==

= 1.2.1 =
* Switch to PHP return for Codex data
* Shorten page names when returned from Codex
* Add title tags to Codex links for context

= 1.2 =
* Add Codex contributions widget

= 1.1 =
* Update regex to support matching "see #{ticket}"
* Fix undefined index warnings on first activation

= 1.0 =
* Update translations
* Allow users to change the display count of the widget.

= 0.4 =
* Add a link to the full Trac results with a total result count listed.

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