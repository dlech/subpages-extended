=== Subpages Extended ===
Contributors: Matt Say
Donate link: http://shailan.com/donate
Tags: subpages, widget, shortcode, navigation, menu, auto
Requires at least: 2.5
Tested up to: 2.9.2
Stable tag: 1.0

A multi widget to list subpages of a page. It also comes with a [[subpages]] shortcode. You can find more widgets, plugins and themes at shailan.com (http://shailan.com).

== Description ==

This widget displays subpages of a page easily. Though it�s main power is the [subpages] shortcode. Using this shortcode on a page you can create subpage indexes. You can view live demo on my wordpress page. It automatically generates subpage indexes. You can also list subpages of another page using the childof attribute of shortcode. See the examples below:

Here are subpages of my wordpress page with a depth level of 1:
`[[subpages depth="1" childof="286"]]`

Outputs:

* Plugins
* Themes

If the page doesn�t have any subpages it will display the following error for you to fix it:
`[[subpages depth="1" childof="257"]]`

Outputs: 

"Services" doesn't have any sub pages.

== Installation ==

1. Download the widget and upload it to your server through `WP Admin -> Plugins -> Add New -> Upload`
1. After the upload is complete activate the plugin.
1. Go to Appearance -> Widgets page, drag and drop the widget to your sidebar.
1. Fill in the blanks as needed, and done!

== Frequently Asked Questions ==

= Any questions? =

You can ask your questions [here](http://shailan.com/contact)

== Screenshots ==

1. A snapshot of the widget form.

== Changelog ==

= 1.0 =
* First release.