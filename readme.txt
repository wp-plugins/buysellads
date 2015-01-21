=== BuySellAds ===
Contributors: barchard
Tags: bsa, buy sell ads, ads, ad, ad management, widget, buysellads
Requires at least: 2.8
Tested up to: 4.1
Stable tag: 2.3.4

Official BuySellAds.com WordPress plugin.

== Description ==

This official BuySellAds.com WordPress plugin gives you simple ways to insert your BSA ad code, including BSA Widgets and RSS/mobile advertisements.

== Installation ==

* Download and install the plugin in the `wp-content/plugins` directory.
* Activate the plugin in WordPress. 
* Go to `BuySellAds->Settings` and insert your Site Key.
* Display your Ad Zones via the Widgets menu found at `Appearance->Widgets`.
* If you don't want to use Widgets, you can add the STEP 2 code from your Ad Code page in the specific location that you want each zone to appear.

== Frequently Asked Questions ==

= Is this plugin PHP5 only? =

Yes!

== Screenshots ==
1. Settings Page
2. Widgets Menu

== Changelog ==

= 2.3.4 =
* Insert step-one code via wp_head, deprecate wp_body_open
* Text changes, including additional highlighting of the BSA WP plugin tutorial
* Remove English es_ES translation

= 2.3.3 =
* Prevent whitespace from making its way into the site key or zoneid values.

= 2.3.2 =
* Only allow Admin level users or greater to view and edit the plugin settings
* Remove an unnecessary trim call
* Add rel='nofollow' to RSS links

= 2.3.1 =
* Fix default homepage value if configuration file isn't reachable.

= 2.3.0 =
* Add the ability to detect and display specific ads in Posts for iPhones, iPads, iPods or Android devices.
* Add the ability to disable the 'Advertise Here' text in the RSS feed.

= 2.2.3 =
* Corrected network name for private labels in RSS ads.

= 2.2.2 =
* Corrected an issue with javascript includes for private labels.

= 2.2.1 =
* Added a <p> wrapper around RSS ad image.

= 2.2.0 =
* RSS Ads. Add the ability to render ads at the top or bottom of a RSS feed item.
* Abstracted features for private labels.

= 2.1.1 =
* If file_get_contents fails, we now fall back to curl to try to get the javascript ad code.

= 2.1 =
* Fixed an empty array
* Fixed caching issue for json configuration
* Removed a deprecated PHP function call
* Dead code removal

= 2.0 =
* Added support for private labels

= 1.1 =
* Removed Backfill

= 1.0 =
* First Release 7/19/2010

== Upgrade Notice ==

= 2.0 =
Cleaned up the repo UPGRADE if you downloaded any previous version

= 1.1 =
The backfill option has been removed. BuySellAds.com is implementing this feature server side and will no longer require a plugin.

