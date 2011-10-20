<?php
/**
 * Plugin Class
 *
 * @package WordPress
 * @subpackage BuySellAds
 * @since 1.0
 */
class BSA_Plugin 
{

  /**
   * Initiate the widget class
   *
   * @since 1.0
   * @uses register_widget() Calls 'BSA_Widget' class.
   *
   * @return void
   */
  function widget_init() 
  {
    register_widget('BSA_Widget');
  }
  
  /**
   * Add Menu Item
   *
   * @since 1.0
   * @uses add_object_page()
   * @uses add_submenu_page()
   *
   * @return void
   */
  function bsa_admin() 
  {
	if (current_user_can('activate_plugins'))
	{
	    global $bsa_lang;
    
	    // Grab language text
	    $plugin_title = $bsa_lang->line('plugin_title');
	    $setting_title = $bsa_lang->line('setting_title');
    
	    // Set Menu Icon
	    $icon = BSA_PLUGIN_URL.'/assets/images/icon.png';
    
	    // Create Menu Items
	    add_object_page( $plugin_title, $plugin_title, 'upload_files', 'bsa_settings', array( $this, 'bsa_admin_settings' ), $icon );
	    $bsa_admin_page = add_submenu_page( 'bsa_settings', $plugin_title, $setting_title, 'upload_files', 'bsa_settings', array( $this, 'bsa_admin_settings' ) );
    
	    // Add Menu Items
	    add_action("admin_print_styles-$bsa_admin_page", array( $this, 'bsa_admin_load' ) );
	}
  }
  
  /**
   * Load Scripts & Styles
   *
   * @since 1.0
   * @uses wp_enqueue_style()
   * @uses wp_enqueue_script()
   *
   * @return void
   */
  function bsa_admin_load()
  {
    // Enqueue Styles
    wp_enqueue_style('bsa-css', BSA_PLUGIN_URL.'/assets/css/buysellads.css', false, false, 'screen');
    
    // Enqueue Scripts
    wp_enqueue_script('bsa-js', BSA_PLUGIN_URL.'/assets/js/buysellads.js', array('jquery'), false);
    
  }
  
  /**
   * Load Scripts & Styles
   *
   * @since 1.0
   *
   * @return string
   */
  function bsa_admin_settings()
  {
    global $bsa_lang;

	if (!current_user_can('activate_plugins'))
	{
		wp_die(__('You do not have sufficient permissions to access this page.'));
	}
    
    if( isset($_POST[ 'option_values' ]) && $_POST[ 'option_values' ] == 'save' ) 
    {
      // Check Referer
      check_admin_referer( 'buysellads_settings' );
      
      // Read posted value
      $bsa_site_key = $_POST[ 'bsa_site_key' ];
      $bsa_body_open = $_POST[ 'bsa_body_open' ];
      
      // Save posted values
      update_option( 'bsa_site_key', $bsa_site_key );
      update_option( 'bsa_body_open', $bsa_body_open );

	  // The json configuration file
	  $private_label_json = get_privatelabel_json();
      
      // new cdn
      $buysellads_cdn = $_POST['buysellads_cdn'];
	  $network = buysellads_network_for_cdn($private_label_json, $buysellads_cdn);
      $cdns = buysellads_cdns($private_label_json);
      
      // Make sure the CDN is valid before saving it.
      update_option( 'buysellads_cdn' , (in_array($buysellads_cdn, $cdns) ? $buysellads_cdn : 's3.buysellads.com'));

	  // RSS options
	  $rss = buysellads_rss_urls($private_label_json);
	  update_option('buysellads_rss', (isset($network['rss']) && in_array($network['rss'], $rss) ? $network['rss'] : 'rss.buysellads.com'));
	
      $bsa_rss_zone_top_id = $_POST['bsa_rss_zone_top_id'];
      $bsa_rss_zone_top = $_POST['bsa_rss_zone_top'];
      
      update_option('bsa_rss_zone_top_id', $bsa_rss_zone_top_id);
      update_option('bsa_rss_zone_top', $bsa_rss_zone_top);

      $bsa_rss_zone_bottom_id = $_POST['bsa_rss_zone_bottom_id'];
      $bsa_rss_zone_bottom = $_POST['bsa_rss_zone_bottom'];
      
      update_option('bsa_rss_zone_bottom_id', $bsa_rss_zone_bottom_id);
      update_option('bsa_rss_zone_bottom', $bsa_rss_zone_bottom);

	  update_option('bsa_advertise_here', isset($_POST['bsa_advertise_here']) ? $_POST['bsa_advertise_here'] : false);

	  // Mobile settings
	  $bsa_mobile_zone_top_id = $_POST['bsa_mobile_zone_top_id'];
      $bsa_mobile_zone_top = $_POST['bsa_mobile_zone_top'];
     
      update_option('bsa_mobile_zone_top_id', $bsa_mobile_zone_top_id);
      update_option('bsa_mobile_zone_top', $bsa_mobile_zone_top);

      $bsa_mobile_zone_bottom_id = $_POST['bsa_mobile_zone_bottom_id'];
      $bsa_mobile_zone_bottom = $_POST['bsa_mobile_zone_bottom'];
     
      update_option('bsa_mobile_zone_bottom_id', $bsa_mobile_zone_bottom_id);
      update_option('bsa_mobile_zone_bottom', $bsa_mobile_zone_bottom);

	  // Javascript src
	  $srcs = buysellads_srcs($private_label_json);
	  update_option('buysellads_src', (isset($network['src']) && in_array($network['src'], $srcs) ? trim($network['src'], '/') : 'ac/bsa.js'));

	  // Shortname
	  $shortnames = buysellads_shortnames($private_label_json);
	  update_option('bsa_shortname', (isset($network['shortname']) && in_array($network['shortname'], $shortnames) ? $network['shortname'] : 'BSA'));
	
	  // Homepage
      $homepages = buysellads_homepages($private_label_json);
	  update_option('bsa_homepage', (isset($network['homepage']) && in_array($network['homepage'], $homepages) ? $network['homepage'] : 'buysellads.com'));
      
      $json_data = get_buysellads_json();
      if ($json_data)
      {
        // Success Message
        printf( '<div class="updated fade"><p>%s</p></div>', $bsa_lang->line('settings_updated') );
      }
      else
      {
        // Error Message
        printf( '<div class="error fade"><p>%s</p></div>', $bsa_lang->line('settings_error') );
        delete_option( 'bsa_site_key' );
      }
    }
    ?>
    <div class="wrap" id="buysellads">
      <h2><?php echo $bsa_lang->line('plugin_title'); ?></h2>
      <form method="post" action="">
        <table class="form-table">
          <tbody>
            <tr valign="top">
              <th scope="row">
                <label for="bsa_site_key"><?php echo $bsa_lang->line('site_key'); ?></label>
              </th>
              <td>
                <input type="text" class="regular-text" value="<?php echo get_option('bsa_site_key'); ?>" id="bsa_site_key" name="bsa_site_key">
                <span class="description"><?php echo $bsa_lang->line('site_key_desc'); ?></span>
              </td>
            </tr>
            <tr valign="top">
              <th scope="row">
                <label for="bsa_network"><?php echo $bsa_lang->line('network'); ?></label>
              </th>
              <td>
                <select  id="bsa_network" name="buysellads_cdn">
				<?php $private_labels = get_privatelabel_json();?>
                <?php foreach($private_labels as $private_label): ?>
                  <option <?php echo(stripos($private_label['cdn'], get_option('buysellads_cdn', 's3.buysellads.com')) === false ? '' : 'selected'); ?> value=<?php echo("\"{$private_label['cdn']}\""); ?> ><?php echo (htmlspecialchars($private_label['title'])); ?></option>
                <?php endforeach; ?>
                </select>
              </td>
            </tr>
            <tr valign="top">
            	<th scope="row"><label for="bsa_rss_zone_top"><?php echo $bsa_lang->line('rss_zone'); ?></label></th>
            	<td> 
                  	<input type="checkbox" value="1" id="bsa_rss_zone_top" name="bsa_rss_zone_top"<?php echo (get_option('bsa_rss_zone_top') == 1) ? ' checked="checked"': ''; ?>> Insert Ads in header of Feed
                	<p><span class="description"><?php echo $bsa_lang->line('bsa_rss_zone_top_desc'); ?></span></p>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">
				  <label for="bsa_rss_zone_top_id"><?php echo $bsa_lang->line('bsa_rss_zone_top_id'); ?></label>
				</th>
				<td>
				  <input type="text" class="regular-text" value="<?php echo get_option('bsa_rss_zone_top_id'); ?>" id="bsa_rss_zone_top_id" name="bsa_rss_zone_top_id">
				  <span class="description"><?php echo $bsa_lang->line('bsa_rss_zone_top_id_desc'); ?></span>
				</td>
            </tr>
            
			<tr valign="top">
				<tr>
	            	<th scope="row"><label for="bsa_rss_zone_bottom"></label></th>
	            	<td> 
	                  	<input type="checkbox" value="1" id="bsa_rss_zone_bottom" name="bsa_rss_zone_bottom"<?php echo (get_option('bsa_rss_zone_bottom') == 1) ? ' checked="checked"': ''; ?>> Insert Ads in footer of Feed
	                	<p><span class="description"><?php echo $bsa_lang->line('bsa_rss_zone_bottom_desc'); ?></span></p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
					  <label for="bsa_rss_zone_bottom_id"><?php echo $bsa_lang->line('bsa_rss_zone_bottom_id'); ?></label>
					</th>
					<td>
					  <input type="text" class="regular-text" value="<?php echo get_option('bsa_rss_zone_bottom_id'); ?>" id="bsa_rss_zone_bottom_id" name="bsa_rss_zone_bottom_id">
					  <span class="description"><?php echo $bsa_lang->line('bsa_rss_zone_bottom_id_desc'); ?></span>
					</td>
	            </tr>
	         </tr>
			<tr valign="top">
				<tr>
	            	<th scope="row"><label for="bsa_advertise_here"></label></th>
	            	<td> 
	                  	<input type="checkbox" value="1" id="bsa_advertise_here" name="bsa_advertise_here"<?php echo (get_option('bsa_advertise_here') == 1) ? ' checked="checked"': ''; ?>> Insert Advertise Here text in Feed
	                	<p><span class="description"><?php echo $bsa_lang->line('bsa_advertise_here_desc'); ?></span></p>
					</td>
				</tr>
	        </tr>
			
	        <tr valign="top">
				<tr>
	            	<th scope="row"><label for="bsa_mobile_zone_top"><?php echo $bsa_lang->line('mobile_zone'); ?></label></th>
	            	<td> 
	                  	<input type="checkbox" value="1" id="bsa_mobile_zone_top" name="bsa_mobile_zone_top"<?php echo (get_option('bsa_mobile_zone_top') == 1) ? ' checked="checked"': ''; ?>> Insert Mobile Ads in top of Posts
	                	<p><span class="description"><?php echo $bsa_lang->line('bsa_mobile_zone_top_desc'); ?></span></p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
					  <label for="bsa_mobile_zone_top_id"><?php echo $bsa_lang->line('bsa_mobile_zone_top_id'); ?></label>
					</th>
					<td>
					  <input type="text" class="regular-text" value="<?php echo get_option('bsa_mobile_zone_top_id'); ?>" id="bsa_mobile_zone_top_id" name="bsa_mobile_zone_top_id">
					  <span class="description"><?php echo $bsa_lang->line('bsa_mobile_zone_top_id_desc'); ?></span>
					</td>
	            </tr>
	        </tr>
			<tr valign="top">
				<tr>
	            	<th scope="row"><label for="bsa_mobile_zone_bottom"></label></th>
	            	<td> 
	                  	<input type="checkbox" value="1" id="bsa_mobile_zone_bottom" name="bsa_mobile_zone_bottom"<?php echo (get_option('bsa_mobile_zone_bottom') == 1) ? ' checked="checked"': ''; ?>> Insert Mobile Ads in bottom of Posts
	                	<p><span class="description"><?php echo $bsa_lang->line('bsa_mobile_zone_bottom_desc'); ?></span></p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
					  <label for="bsa_mobile_zone_bottom_id"><?php echo $bsa_lang->line('bsa_mobile_zone_bottom_id'); ?></label>
					</th>
					<td>
					  <input type="text" class="regular-text" value="<?php echo get_option('bsa_mobile_zone_bottom_id'); ?>" id="bsa_mobile_zone_bottom_id" name="bsa_mobile_zone_bottom_id">
					  <span class="description"><?php echo $bsa_lang->line('bsa_mobile_zone_bottom_id_desc'); ?></span>
					</td>
	            </tr>
	        </tr>
	
            <tr valign="top">
              <th scope="row"><?php echo $bsa_lang->line('bsa_body_open'); ?></th>
              <td> 
                <fieldset>
                  <legend class="screen-reader-text"><span><?php echo $bsa_lang->line('bsa_body_open'); ?></span></legend>
                  <label for="bsa_body_open">
                    <input type="checkbox" value="1" id="bsa_body_open" name="bsa_body_open"<?php echo (get_option('bsa_body_open') == 1) ? ' checked="checked"': ''; ?>> Use <strong>wp_body_open()</strong>
                  </label>
                  <p><span class="description"><?php echo $bsa_lang->line('bsa_body_open_desc'); ?></span></p>
                </fieldset>
              </td>
            </tr>


          </tbody>
        </table>
        <?php 
        if ( function_exists( 'wp_nonce_field' ) && wp_nonce_field( 'buysellads_settings' ) ) {
          printf('
          <p class="submit">
            <input type="submit" name="submit_settings" class="button-primary" value="Save Settings" />
          </p>
          ',
          $bsa_lang->line('submit_settings')
          );
        }
        ?>
        <input type="hidden" name="option_values" value="save" />
      </form>
    </div><!-- buysellads -->
  <?php
  }
  
}