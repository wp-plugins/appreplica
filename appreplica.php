<?php
/*
Plugin Name: Appreplica
Plugin URI: http://appreplica.com
Description: Appreplica for WordPress, embed your favorite websites.
Author: Appreplica
Version: 1.8
Author URI: http://appreplica.com
*/

# Prevent direct access
if (!defined('ABSPATH')) die('Error!');

# Add jquery
wp_enqueue_script('jquery');

# Register [appreplica] shortcode
add_shortcode( 'appreplica', 'embed_appreplica' );

# Function to process content in appreplica shortcode, ex: [appreplica]YouTube[/appreplica]
function embed_appreplica( $atts, $app = '' ) {

    # Read url from 'url' attribute if not empty, this corresponds to the App
    if ( !empty( $atts['url'] ) ) $app = $atts['url'];
	
	$app_array = array("youtube", "vimeo", "itunes", "spotify", "deezer", "soundcloud", "socialfeed", "instagram", "twitter", "facebook", "tumblr", "pinterest", "behance", "500px", "flickr", "smugmug", "photos", "videos", "music");
	
	# return for valid apps only
	if (in_array(strtolower($app), $app_array)) {
	
	# Stored API Key
	$api_key = trim( get_site_option( 'appreplica_api_key' ) );
	
	# Url of the current site
    $site_name = preg_replace( '#^https?://#i', '', get_bloginfo( 'url' ) );
	
	# Generate code
	/* $code = '<script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script> '; */
	$code = '<script> ';
	$code .= 'appreplicaapp = "' . strtolower($app) . '"; ';
	$code .= 'appreplicaapikey = "' . strtolower($api_key) . '"; ';
	$code .= 'appreplicasitename = "' . strtolower($site_name) . '"; ';
	$code .= '</script> ';
	$code .= '<script src="http://js.appreplica.com/1/arwp.js"></script> ';
	
	}
	
	# Return code
    return $code;
}


# Create appreplica settings menu for admin
add_action( 'admin_menu', 'appreplica_create_menu' );
add_action( 'network_admin_menu', 'appreplica_network_admin_create_menu' );

# Create link to plugin options page from plugins list
function appreplica_plugin_add_settings_link( $links ) {
    $settings_link = '<a href="admin.php?page=appreplica_settings_page">Settings</a>';
    array_push( $links, $settings_link );
    return $links;
}

$appreplica_plugin_basename = plugin_basename( __FILE__ );
add_filter( 'plugin_action_links_' . $appreplica_plugin_basename, 'appreplica_plugin_add_settings_link' );

# Create new top level menu for sites
function appreplica_create_menu() {
    add_menu_page('Appreplica Options', 'Appreplica', 'install_plugins', 'appreplica_settings_page', 'appreplica_settings_page');
}

# Create new top level menu for network admin
function appreplica_network_admin_create_menu() {
    add_menu_page('Appreplica Options', 'Appreplica', 'manage_options', 'appreplica_settings_page', 'appreplica_settings_page');
}

function appreplica_update_option($name, $value) {
    return is_multisite() ? update_site_option($name, $value) : update_option($name, $value);
}

function appreplica_settings_page() {

?>

<div id="appreplica_admin" class="wrap">

<div style="padding-bottom: 10px;">
<h1>Appreplica (v.1.8)</h1>
</div>

<?php $appreplica_active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'configure'; ?>
<h2 class="nav-tab-wrapper">
<a href="?page=appreplica_settings_page&amp;tab=configure" class="nav-tab <?php echo $appreplica_active_tab == 'configure' ? 'nav-tab-active' : ''; ?>">Configure</a>
<a href="?page=appreplica_settings_page&amp;tab=apps" class="nav-tab <?php echo $appreplica_active_tab == 'apps' ? 'nav-tab-active' : ''; ?>">Apps</a>
<a href="?page=appreplica_settings_page&amp;tab=addtopages" class="nav-tab <?php echo $appreplica_active_tab == 'addtopages' ? 'nav-tab-active' : ''; ?>">Add to Pages</a>
<a href="?page=appreplica_settings_page&amp;tab=support" class="nav-tab <?php echo $appreplica_active_tab == 'support' ? 'nav-tab-active' : ''; ?>">Support / FAQ</a>

</h2>

<?php if( $appreplica_active_tab == 'configure' ) { // Configure Tab ?>

<form name="form1" method="post" action="">
    
<?php
if (isset($_POST['_wpnonce']) && isset($_POST['submit'])) {
	appreplica_update_option('appreplica_api_key', trim($_POST['appreplica_api_key']));
	$confirmSave = 1;
}
wp_nonce_field('form-settings');
?>

<table class="form-table">
<tbody>

<div style="padding-top: 10px;"><h3>Configure</h3></div>

<tr valign="top">
<th scope="row"><label>Appreplica API Key</label></th>
<td>

<input type="text" style="width: 545px; font-size: 18px;" name="appreplica_api_key" value="<?php echo get_site_option('appreplica_api_key'); ?>"  placeholder="Enter your Appreplica API Key" />

<br /><br />

<div style="padding: 10px; max-width: 525px; font-size: 14px; background-color: #ffffff; border-radius: 3px; line-height: 22px;">
This is a unique API key that links your account on Appreplica to the plugin here. The API key can be found on your Dasboard page on <a target="_blank" href="http://www.appreplica.com">Appreplica.com</a></div>

<br />

<div style="padding: 10px; max-width: 525px; font-size: 14px; background-color: #fdfde0; border-radius: 3px; line-height: 22px;">
To preview our apps with sample data on your own pages before signing up for Appreplica please enter &nbsp; <span style="color: #000000; font-size: 20px;"><b>demo</b></span> &nbsp; as the API key.</div>

<?php if ( !is_plugin_active( 'appreplica-social-icons/appreplica-social-icons.php' ) ) { ?>

<br />
<div style="padding: 10px; max-width: 525px; font-size: 14px; background-color: #fefec3; border-radius: 3px; line-height: 22px;">
Need a social icons plugin to use with this plugin? We also offer a free <a target="_blank" href="https://wordpress.org/plugins/appreplica-social-icons/">Appreplica&nbsp;Social&nbsp;Icons</a> plugin you can use.</div>

<?php } ?>

<?php if ($confirmSave) { echo '<br /><div style="padding: 10px; font-size: 18px; color: #ff0000;"><b>Your API Key has been saved</b></div>'; } ?>

<br /><br />

<?php submit_button(); ?>
</div>
</td>
</tr>

</tbody>
</table>
</form>

<br /><br /><br />
<hr style="border: none; border-bottom: 1px solid #ccc;" />

<?php } // End Configure Tab ?>




<?php if( $appreplica_active_tab == 'apps' ) { // Apps Tab ?>

<style>
#appreplica_admin .appreplica-app-box {
float: left;
width: 195px;
min-height: 70px;
height: auto;
margin: 5px;
padding: 5px;
padding-top: 10px;
background-color: #e4e4e4;
border: 1px solid #cccccc;
border-radius: 3px;
}
#appreplica_admin .appreplica-app-title {
font-size: 16px;
}
#appreplica_admin .appreplica-app-text {
font-size: 12px;
}
</style>

<table class="form-table">
<tbody>

<tr valign="top">
<td>

<h3>Free apps</h3>

<div style="max-width: 1100px;">
<?php 
$apps = array("socialfeed", "youtube", "vimeo", "itunes", "spotify", "deezer", "soundcloud", "instagram", "twitter", "facebook", "tumblr", "pinterest", "behance", "500px", "flickr", "smugmug");
foreach ($apps as $value) {
?>
<div class="appreplica-app-box">
<table>
<tr>
<td width="1%" style="padding: 5px;"><img src="<?php echo plugins_url( 'icons/' . $value . '.png', __FILE__ ); ?>" width="50" height="50" align="absmiddle" alt=""/></td>
<td style="padding: 5px;">
<div class="appreplica-app-title"><b><?php echo strtoupper($value); ?></b></div>
<div class="appreplica-app-text"><a target="_blank" href="http://wordpress.appreplica.com/<?php echo $value; ?>">Sample</a></div>
</td>
</tr>
</table>
</div>
<?php
}
?>
</div>
</td>
</tr>

<tr valign="top">
<td>
<h3>Apps available with a subscription</h3>

<div style="max-width: 1100px;">
<?php 
$apps = array("photos", "videos");
foreach ($apps as $value) {
?>
<div class="appreplica-app-box">
<table>
<tr>
<td width="1%" style="padding: 5px;"><img src="<?php echo plugins_url( 'icons/' . $value . '.png', __FILE__ ); ?>" width="50" height="50" align="absmiddle" alt=""/></td>
<td style="padding: 5px;">
<div class="appreplica-app-title"><b><?php echo strtoupper($value); ?></b></div>
<div class="appreplica-app-text"><a target="_blank" href="http://wordpress.appreplica.com/<?php echo $value; ?>">Sample</a></div>
</td>
</tr>
</table>
</div>
<?php
}
?>
</div>

</td>
</tr>

</tbody>
</table>

<br /><br /><br />
<hr style="border: none; border-bottom: 1px solid #ccc;" />

<?php } // End Apps Tab ?>



<?php if( $appreplica_active_tab == 'addtopages' ) { // Add to Pages Tab ?>

<style>
#appreplica_admin table.appreplica_shortcode_table{
	border-collapse: collapse;
}
#appreplica_admin table.appreplica_shortcode_table th {
  border: 1px solid #999;
  padding: 10px;
  text-align: left;
  font-size: 15px;
}
#appreplica_admin table.appreplica_shortcode_table td{
  border: 1px solid #999;
  padding: 8px;
  text-align: left;
  font-size: 13px;
}
#appreplica_admin table.appreplica_shortcode_table th{
	background: rgba(0,0,0,0.1);
}
#appreplica_admin table.appreplica_shortcode_table td{
	background: rgba(255,255,255,0.5);
}
#appreplica_admin .appreplica_table_header{
	background: #ddd;
	font-weight: bold;
	color: #999;
}
</style>

<table class="form-table">
<tbody>

<tr valign="top">
<td>

<h3>How to add these apps to your Pages</h3>

<div style="max-width: 750px; font-size: 13px;">

Copy and paste the following shortcodes directly into the page, post or widget where you would like to display it. Our Apps work best when they appear on a page by themselves and take advantage of the full width/height of the page. The apps are fully responsive and automatically adjust their width to match the width of your theme.<br /><br /><br />

<table class="appreplica_shortcode_table">
  <tbody>
  <tr valign="top">
  <th scope="row" style="width: 170px;">APP</th>
  <th scope="row" style="width: 500px;">SHORTCODE</th>
  </tr>
  
  <tr class="appreplica_table_header"><td colspan=3>MEDIA APPS</td></tr>
  <?php 
  $apps = array("youtube", "vimeo", "itunes", "spotify", "deezer", "soundcloud");
  foreach ($apps as $value) {
  ?>
  <tr class="appreplica_pro">
  <td nowrap><img src="<?php echo plugins_url( 'icons/' . $value . '.png', __FILE__ ); ?>" width="18" height="18" align="absmiddle" alt=""/>&nbsp;&nbsp;<b><?php echo strtoupper($value); ?></b></td>
  <td><code>[appreplica]<?php echo $value; ?>[/appreplica]</code></td>
  </tr>
  <?php
  }
  ?>
  
  <tr class="appreplica_table_header"><td colspan=3>SOCIAL APPS</td></tr>
  <?php 
  $apps = array("socialfeed", "instagram", "twitter", "facebook", "tumblr", "pinterest", "500px");
  foreach ($apps as $value) {
  ?>
  <tr class="appreplica_pro">
  <td nowrap><img src="<?php echo plugins_url( 'icons/' . $value . '.png', __FILE__ ); ?>" width="18" height="18" align="absmiddle" alt=""/>&nbsp;&nbsp;<b><?php echo strtoupper($value); ?></b></td>
  <td><code>[appreplica]<?php echo $value; ?>[/appreplica]</code></td>
  </tr>
  <?php
  }
  ?>

  <tr class="appreplica_table_header"><td colspan=3>PORTFOLIO APPS</td></tr>
  <?php 
  $apps = array("flickr", "smugmug", "behance", "photos", "videos");
  foreach ($apps as $value) {
  ?>
  <tr class="appreplica_pro">
  <td nowrap><img src="<?php echo plugins_url( 'icons/' . $value . '.png', __FILE__ ); ?>" width="18" height="18" align="absmiddle" alt=""/>&nbsp;&nbsp;<b><?php echo strtoupper($value); ?></b></td>
  <td><code>[appreplica]<?php echo $value; ?>[/appreplica]</code></td>
  </tr>
  <?php
  }
  ?>
  
  </tbody>
</table>
        
</div>
</td>
</tr>

</tbody>
</table>

<br /><br /><br />
<hr style="border: none; border-bottom: 1px solid #ccc;" />

<?php } // End Add to Pages Tab ?>



<?php if( $appreplica_active_tab == 'support' ) { // Support Tab ?>

<table class="form-table">
<tbody>

<tr valign="top">
<td>

<div style="max-width: 1000px; font-size: 13px;">

<h3>How do I get started?</h3>

First sign up for a free account on <a target="_blank" href="http://www.appreplica.com"><b>www.appreplica.com</b></a>. It will take just a few minutes to configure the apps on Appreplica. Then return here and enter your Appreplica&nbsp;API&nbsp;Key under the <b>Configure</b> tab of this plugin. Then add the corresponding shortcodes as shown under the <b>Add to Pages</b> tab of this plugin to the pages on which would like display your content.<br /><br />

<h3>What makes Appreplica different than other plugins?</h3>

Appreplica is different than almost all other WordPress plugins in that it's a cloud based solution. This means rather than the complex code running on your own server where it can slow down your own server or possibly cause conflict with other plugins and themes, it instead runs on Appreplica's distributed cloud based servers and the content is remotely displayed in your website using a combination of AJAX and iFrames.<br /><br />It's very similar for example to an embedded YouTube video. YouTube's servers handle the complex tasks of rendering the video, paying for the bandwidth, etc. and your browser simply displays the video with virtually no impact on your own server's performance.<br /><br />

<h3>Can I add these apps using icons instead of menu entries?</h3>

Absolutely! Create a page as usual but do not add it as a menu option. The page will not appear as a menu option but will be available when linked to directly. Then use our a free <a target="_blank" href="https://wordpress.org/plugins/appreplica-social-icons/"><b>Appreplica Social Icons</b></a> plugin to add social media icons to your widget areas.<br /><br />

<h3>Will it work with all themes?</h3>

Our plugin should work with virtually all WordPress themes. Our plugin is very &quot;lightweight&quot; and should not conflict with any other plugins or impact the performance of your overall website. With one plugin, you can add one or all of our apps. There is no need to install a separate plugin for each app, our unique plugin handles all the apps with just one plugin.<br /><br />

<h3>Is this service really free?</h3>

Absolutely! We offer a 100% free basic plan without all the limitations imposed by the many other providers. Affordable subscription plans are also available for users who would like customize their plugins with custom colors and select from different design choices. Please review our <a target="_blank" href="http://www.appreplica.com/pricing.php"><b>pricing</b></a> for details.<br /><br />

<h3>How often is the content updated?</h3>

All apps are updated in realtime. You never have to manually sync or do anything special for your latest social media postings and updates to be reflected in your pages. Just add content to your social media sites and magically see the changes reflected on your pages.<br /><br />
 
<h3>Are there limits on visitors, bandwidth, etc.?</h3>

Nope! We offer truly unlimited usage! Whether you get one visitor or millions of visitors, our service will display your content from our worldwide network of servers powered by Amazon Web Services with virtually no performance impact on your own hosting service.<br /><br />

<h3>How do you make money?</h3>

Some of our users upgrade to take advantage of the more advanced customization options and that's plenty for us to support and maintain this service.<br /><br />

<h3>Why aren't there free versions of your Photo and Videos apps?</h3>

These two apps are very special in that they provide a powerful photo and video gallery content management system with unlimited storage and bandwidth powered by Amazon's worldwide CloudFront content delivery network. Since the storage and bandwidth costs are quite costly, these two apps are limited to subscribing members only.<br /><br />

<h3>How can I get support?</h3>

This service is so easy to use that we're confident 99% of you won't need any extra help, but feel free to email us at <b>support@appreplica.com</b> if you have any questions about this service. Free unlimited support is available to all our valued users.<br /><br />

<h3>Like our service?</h3>

Please consider <a target="_blank" href="https://wordpress.org/support/view/plugin-reviews/appreplica"><b>leaving us a review</b></a>.<br /><br /><br />

</div>
</td>
</tr>

</tbody>
</table>

<br /><br /><br />
<hr style="border: none; border-bottom: 1px solid #ccc;" />

<?php } // End Support Tab ?>




</div>
<?php } ?>