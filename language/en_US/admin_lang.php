<?php
/**
 * English Language
 *
 * @package WordPress
 * @subpackage BuySellAds
 * @since 1.0
 */
$lang['plugin_title'] = 'BuySellAds';
$lang['setting_title'] = 'Settings';
$lang['widget_description'] = 'Display your ad zones from BuySellAds.com';
$lang['widget_title'] = 'Title';
$lang['widget_ad_zone'] = 'Ad Zone';
$lang['settings_updated'] = 'Settings <strong>Updated</strong>.';
$lang['settings_error'] = '<strong>Error</strong>! Your Site Key could not be verified.';
$lang['site_key'] = 'Site Key';
$lang['site_key_desc'] = 'Copy+paste your Site Key here. You can find this on the "install ad code" page.';
$lang['empty_site_key'] = 'A Site key has not been added yet. <a href="%s">Add One</a>.';
$lang['bsa_body_open'] = 'Asynchronous Code';
$lang['bsa_body_open_desc'] = 'Directly after the opening <strong>&lt;body&gt;</strong> tag insert <strong>&lt;?php if (function_exists(\'wp_body_open\')) { wp_body_open(); } ?&gt;</strong>. Otherwise, the necessary code snippet will be inserted using <strong>&lt;?php wp_footer(); ?&gt;</strong>';
$lang['submit_settings'] = 'Save Settings';
$lang['network'] = 'Network';

$lang['rss_zone'] = 'RSS';
$lang['bsa_rss_zone_top_id'] = '';
$lang['bsa_rss_zone_top_desc'] = 'Enable Ads in the header of each RSS feed item';
$lang['bsa_rss_zone_top_id_desc'] = 'Enter the zone id for the ad zone that corresponds to the header of the RSS feed';
$lang['bsa_rss_zone_bottom_desc'] = 'Enable Ads in the footer of each RSS feed item';
$lang['bsa_rss_zone_bottom_id_desc'] = 'Enter the zone id for the ad zone that corresponds to the footer of the RSS feed';
$lang['bsa_advertise_here_desc'] = 'Enter the Advertise Here text below the RSS Ad.';

$lang['mobile_zone'] = 'Mobile';
$lang['bsa_mobile_zone_bottom_desc'] = 'Enable mobile only ads on the bottom of posts.';
$lang['bsa_mobile_zone_bottom_id_desc'] = '';
$lang['bsa_mobile_zone_top_desc'] = 'Enable mobile only ads on the top of posts.';
$lang['bsa_mobile_zone_top_id_desc'] = '';

$lang['bsa_mobile_zone_top_id'] = '';
$lang['bsa_mobile_zone_top_id_desc'] = 'Enter the zone id for the ad zone that corresponds to the top of posts.';
$lang['bsa_mobile_zone_bottom_id_desc'] = 'Enter the zone id for the ad zone that corresponds to the bottom of posts.';