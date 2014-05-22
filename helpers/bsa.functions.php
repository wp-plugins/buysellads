<?php
/**
 * Functions
 *
 * @package WordPress
 * @subpackage BuySellAds
 * @since 1.0
 */

/**
 * Gets a remote url via curl
 * @param $address The remote address to retrieve
 * @return Returns the content of the url (string)
 */
if (!function_exists('curl_get_contents'))
{
	function curl_get_contents($address)
	{
		$ch = curl_init();
		$timeout = 5;
		curl_setopt($ch, CURLOPT_URL, $address);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
		$data = curl_exec($ch);
		curl_close($ch);
		return $data;
	}
}

/**
 * Get the contents of a remote url
 * @uses file_get_contents()
 * @param $address The remote address to retrieve
 * @return Returns the content of the url (string)
 */
if (!function_exists('get_contents'))
{
	function get_contents($address)
	{
		// Try file_get_contents
		$data = @file_get_contents($address);
		
		// If file_get_contents fails
		// This can happen if allow_url_fopen is disabled
		return ($data !== false ? $data : curl_get_contents($address));
	}
}

/**
 * Embeds BSA Asynchronous JavaScript
 *
 * @since 1.0
 *
 * @return string
 */
if (!function_exists('embed_bsa_async_js')) 
{
  function embed_bsa_async_js() 
  {
    if (!is_admin()) {
	  $cdn = get_option( 'buysellads_cdn', 's3.buysellads.com');
	  $src = get_option('buysellads_src', 'ac/bsa.js');
      printf("
        <!-- BuySellAds.com Ad Code -->
        <script type=\"text/javascript\">
        (function(){
          var bsa = document.createElement('script');
              bsa.type= 'text/javascript';
              bsa.async = true;
              bsa.src='//{$cdn}/{$src}';
          (document.getElementsByTagName('head')[0]||document.getElementsByTagName('body')[0]).appendChild(bsa);
        })();
        </script>
        <!-- END BuySellAds.com Ad Code --> 
      ");
    }
  }
}

/**
 * Fire the wp_body_open action
 *
 * @since 1.0
 * @deprecated 2.3.4
 * @uses do_action() Calls 'wp_body_open' hook.
 *
 * @return void
 */
if (!function_exists('wp_body_open')) 
{
  function wp_body_open() 
  {
    do_action('wp_body_open');
  }
}

/**
 * Returns BSA zone code
 *
 * @since 1.0
 * @uses get_option()
 *
 * @param int $ad_zone
 *
 * @return string
 */
if (!function_exists('get_buysellads')) 
{
  function get_buysellads($ad_zone = '') 
  {
    if ($site_key = get_option('bsa_site_key') ) 
    {
      return ("
        <!-- BuySellAds.com Zone Code -->
        <div id=\"bsap_{$ad_zone}\" class=\"bsarocks bsap_{$site_key}\"></div>
        <!-- END BuySellAds.com Zone Code -->
      ");
    }
  }
}
  
/**
 * Function to display BSA zone
 *
 * @since 1.0
 * @uses get_buysellads()
 *
 * @param int $ad_zone
 *
 * @return string
 */
if (!function_exists('buysellads')) 
{
  function buysellads($ad_zone = '') 
  {
    echo get_buysellads($ad_zone);
  }
}

/**
 * Function to grab BSA zones
 *
 * @since 1.0
 * @uses json_decode()
 *
 * @return JSON array
 */
if (!function_exists('get_buysellads_json'))
{
  function get_buysellads_json()
  {
    if ($bsa_site_key = get_option('bsa_site_key'))
    {
      $cdn = get_option( 'buysellads_cdn', 's3.buysellads.com');
      $json_url = "http://{$cdn}/r/s_".$bsa_site_key.".js";
      $json_contents = get_contents($json_url);
      
      // If @file_get_contents($json_url) returns true
      if ($json_contents) 
      {
        // Decode & return json data
        return json_decode(cleanBSAJsonString($json_contents), true);
      }
    }
  }
}

/**
 * Function to grab the configuration information for private labels
 *
 * @since 2.0
 * @uses json_decode()
 *
 * @return JSON array
 */
if (!function_exists('get_privatelabel_json'))
{
  function get_privatelabel_json()
  {
    $json_url = "http://s3.buysellads.com/config/wordpress.js";
    $json_contents = get_contents($json_url);
    $json = json_decode($json_contents, true);
		
    // If @file_get_contents($json_url) returns true
    return $json_contents  && isset($json['networks']) ? $json['networks'] : array(array("title"=>"BuySellAds.com","cdn"=>"s3.buysellads.com", "rss" => "rss.buysellads.com", "homepage" => "buysellads.com", "shortname" => "BSA"));
  }
}

/**
 * Returns an array of CDNs for the private labels
 *
 * @since 2.0
 * @param $json The json configuration to parse
 * @return Array
 */
if (!function_exists('buysellads_cdns'))
{
  function buysellads_cdns($json)
  {
    return array_map('buysellads_cdns_helper', $json);
  }
}

/**
 * Returns an array of SRC attributes for the private labels
 *
 * @since 2.1
 * @param $json The json configuration to parse
 * @return Array
 */
if (!function_exists('buysellads_srcs'))
{
	function buysellads_srcs($json)
	{
		return array_map('buysellads_src_helper', $json);
	}
}

/**
 * Returns an array of RSS urls for the private labels
 *
 * @since 2.0
 * @param $json The json configuration to parse
 * @return Array
 */
if (!function_exists('buysellads_rss_urls'))
{
  function buysellads_rss_urls($json)
  {
    return array_map('buysellads_rss_helper', $json);
  }
}

/**
 * Returns an array of shortnames for the private labels
 *
 * @since 2.0
 * @param $json The json configuration to parse
 * @return Array
 */
if (!function_exists('buysellads_shortnames'))
{
	function buysellads_shortnames($json)
	{
		return array_map('buysellads_shortnames_helper', $json);
	}
}

/**
 * Returns an array of homepages for the private labels
 *
 * @since 2.0
 * @param $json The json configuration to parse
 * @return Array
 */
if (!function_exists('buysellads_homepages'))
{
	function buysellads_homepages($json)
	{
		return array_map('buysellads_homepages_helper', $json);
	}
}

/**
 * Helper function to filter the CDNs
 *
 * @since 2.0
 *
 * @return String
 */
if (!function_exists('buysellads_cdns_helper'))
{
  function buysellads_cdns_helper($network)
  {
    return $network['cdn'];
  }
}

/**
 * Helper function to filter the src
 *
 * @since 2.0
 *
 * @return String
 */
if (!function_exists('buysellads_src_helper'))
{
  function buysellads_src_helper($network)
  {
    return $network['src'];
  }
}

/**
 * Helper function to filter the RSS urls
 *
 * @since 2.2
 *
 * @return String
 */
if (!function_exists('buysellads_rss_helper'))
{
  function buysellads_rss_helper($network)
  {
    return $network['rss'];
  }
}

/**
 * Helper function to filter the shortnames
 *
 * @since 2.2
 *
 * @return String
 */
if (!function_exists('buysellads_shortnames_helper'))
{
  function buysellads_shortnames_helper($network)
  {
    return $network['shortname'];
  }
}

/**
 * Helper function to filter the homepage
 *
 * @since 2.2
 *
 * @return String
 */
if (!function_exists('buysellads_homepages_helper'))
{
  function buysellads_homepages_helper($network)
  {
    return $network['homepage'];
  }
}

/**
 * Get the network for the specified CDN
 *
 * @since 2.0
 * @param $networks An array of networks to search
 * @param $cdn The cdn to search for
 * @param Associative array
 */
if (!function_exists('buysellads_network_for_cdn'))
{
	function buysellads_network_for_cdn($networks, $cdn)
	{
		foreach ($networks as $network)
		{
			if (stripos($network['cdn'], $cdn) !== false)
				return $network;
		}
		
		return array();
	}
}

/**
*	Returns a string without the bsa prefix.
*	@since 2.2
*	@uses strpos
*	@uses strlen
*	@uses substr
*	@param $json A JSON string to clean
*	@return String.
*/
if (!function_exists('cleanBSAJsonString'))
{
	function cleanBSAJsonString($json = '')
	{
		$prefix = '_bsap.interpret_json(';
		if (strpos($json, $prefix) === false)
			return $json;
	
		$l = strlen($prefix);
		return substr($json, $l, strlen($json)-$l-2);
	}
}

/**
*	Adds the BSA ads to the top or bottom of each item in the RSS feed
*	@since 2.2
*	@param $content The content of the feed
*	@return The content string
*/
if (!function_exists('bsa_rss_ads'))
{
	function bsa_rss_ads($content)
	{
		$article = get_the_ID();
		if (get_option('bsa_rss_zone_top') == 1)
			$content = bsa_rss_ad_on_top($article).$content;
		if (get_option('bsa_rss_zone_bottom') == 1)
			$content .= bsa_rss_ad_on_bottom($article);
		
		return $content;
	}
}

/**
*	Returns an ad suitable for placement at the top of an RSS feed item
*	@since 2.2
*	@param $article The article for the ad
*	@return String
*/
if (!function_exists('bsa_rss_ad_on_top'))
{
	function bsa_rss_ad_on_top($article)
	{
		$zone = get_option('bsa_rss_zone_top_id');
		$site = get_option('bsa_site_key');
		return (empty($zone) || empty($site)) ? '' : bsa_rss_ad($zone, $site, $article).'<br />';
	}
}

/**
*	Returns an ad suitable for placement at the bottom of an RSS feed item
*	@since 2.2
*	@param $article The article for the ad
*	@return String
*/
if (!function_exists('bsa_rss_ad_on_bottom'))
{
	function bsa_rss_ad_on_bottom($article)
	{
		$zone = get_option('bsa_rss_zone_bottom_id');
		$site = get_option('bsa_site_key');
		return (empty($zone) || empty($site)) ? '' : '<br />'.bsa_rss_ad($zone, $site, $article);
	}
}

/**
*	Returns an ad suitable for placement in a RSS feed
*	@since 2.2
*	@param $zone The zone for the ad
*	@param $site The sitekey for the ad
*	@param $article The unique article id
*	@return String.
*/
if (!function_exists('bsa_rss_ad'))
{
	function bsa_rss_ad($zone, $site, $article)
	{
		$random = rand();
		$rss = get_option('buysellads_rss', 'rss.buysellads.com');
		$network = get_option('bsa_shortname', 'BSA');
		$home = get_option('bsa_homepage', 'buysellads.com');
		$promote = get_option('bsa_advertise_here', false);
		
		$zone = trim($zone);
		$site = trim($site);
		$article = trim($article);
		
		return "<p><a href='http://${rss}/click.php?z=${zone}&k=${site}&a=${article}&c=${random}' target='_blank' rel='nofollow'>
				<img src='http://${rss}/img.php?z=${zone}&k=${site}&a=${article}&c=${random}' border='0' alt='' /></a></p>".
				($promote ? "<p><a href='http://${home}/buy/sitedetails/pubkey/${site}/zone/${zone}' target='_blank'>Advertise here with ${network}</a></p>" : '');
	}
}

/**
*	Adds the BSA ads to the top or bottom of each item in the RSS feed
*	@since 2.2
*	@param $content The content of the feed
*	@return The content string
*/
if (!function_exists('bsa_mobile_ads'))
{
	function bsa_mobile_ads($content)
	{
		if (!bsa_mobile_browser())
			return $content;
		
		$article = get_the_ID();
		if (get_option('bsa_mobile_zone_top') == 1)
			$content = bsa_mobile_ad_on_top($article).$content;
		if (get_option('bsa_mobile_zone_bottom') == 1)
			$content .= bsa_mobile_ad_on_bottom($article);
		
		return $content;
	}
}

/**
*	Returns an ad suitable for placement at the top of an RSS feed item
*	@since 2.2
*	@param $article The article for the ad
*	@return String
*/
if (!function_exists('bsa_mobile_ad_on_top'))
{
	function bsa_mobile_ad_on_top($article)
	{
		$zone = get_option('bsa_mobile_zone_top_id');
		$site = get_option('bsa_site_key');
		return (empty($zone) || empty($site)) ? '' : bsa_zone_code($zone, $site, $article).'<div style="clear: both; display: block;"></div>';
	}
}

/**
*	Returns an ad suitable for placement at the bottom of an RSS feed item
*	@since 2.2
*	@param $article The article for the ad
*	@return String
*/
if (!function_exists('bsa_mobile_ad_on_bottom'))
{
	function bsa_mobile_ad_on_bottom($article)
	{
		$zone = get_option('bsa_mobile_zone_bottom_id');
		$site = get_option('bsa_site_key');
		return (empty($zone) || empty($site)) ? '' : '<div style="clear: both; display: block;"></div>'.bsa_zone_code($zone, $site, $article);
	}
}

/**
*	Returns the BSA zone code
*	@since 2.3
*	@param $zone The zone for the ad
*	@param $site The sitekey for the ad
*	@param $article The unique article id
*	@return String.
*/
if (!function_exists('bsa_zone_code'))
{
	function bsa_zone_code($zone, $site, $article)
	{
		$zone = trim($zone);
		$site = trim($site);
		$article = trim($article);	
		return 	"<!-- BuySellAds.com Zone Code -->
	        <div id=\"bsap_{$zone}\" class=\"bsarocks bsap_{$site}\"></div>
	        <!-- END BuySellAds.com Zone Code -->";
	}
}

	

/**
*	Returns true if the user is on a Mobile browser
*	@since 2.3
*	@return bool
*/
if (!function_exists('bsa_mobile_broswer'))
{
	function bsa_mobile_browser()
	{
		$agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
		return (stripos($agent, 'iphone') !== false ||
				stripos($agent, 'ipod') !== false ||
				stripos($agent, 'ipad') !== false ||
				stripos($agent, 'android') !== false);
	}
}

?>