<?php
/*
Plugin Name: Appreplica
Plugin URI: http://appreplica.com
Description: Appreplica for WordPress, embed your favorite websites.
Author: Appreplica
Version: 1.4
Author URI: http://appreplica.com
*/

# Define appreplica plugin path
if ( !defined( 'APPREPLICA_URL' ) ) {
  define( 'APPREPLICA_URL', WP_PLUGIN_URL.'/appreplica' );
}

# Register [appreplica] shortcode
add_shortcode( 'appreplica', 'embed_appreplica' );

# Add jquery
wp_enqueue_script('jquery');

# Function to process content in appreplica shortcode, ex: [appreplica]YouTube[/appreplica]
function embed_appreplica( $atts, $app = '' ) {

    # Read url from 'url' attribute if not empty, this corresponds to the App
    if ( !empty( $atts['url'] ) ) $app = $atts['url'];
	
	$app_array = array("youtube", "vimeo", "itunes", "spotify", "deezer", "soundcloud", "instagram", "twitter", "facebook", "tumblr", "500px", "flickr", "smugmug", "photos", "videos", "music");
	
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

</br>
<h1>How to use Appreplica (version 1.3)</h1>

<p>Before you can use this plugin, you must first sign up for a free account on <a href="http://appreplica.com" target="_blank">Appreplica.com</a> and configure the Apps your wish to add to your WordPress pages.</p></br>

<form method="post" action="">

<h1>Provide your Appreplica API Key</h1>

    <?php

        if (isset($_POST['_wpnonce']) && isset($_POST['submit'])) {
            appreplica_update_option('appreplica_api_key', trim($_POST['appreplica_api_key']));
        }

        wp_nonce_field('form-settings');
    ?>

    <ul>
        <li>
            <p></br><span style="font-size:larger;"><b>Appreplica API Key</b></span></p>
            <p><input type="text" style="width: 350px;" name="appreplica_api_key" value="<?php echo get_site_option('appreplica_api_key'); ?>" /></p>
            <p>This is unique API key that links your account on Appreplica.com to the plugin here. The API key can be found in your account on Apprelica.com under the installation instructions for each App. Please keep this API key private.</br></p>
        </li>
        
        <li><?php submit_button(); ?></li>
                
    </ul>
    
</form>

<h1>How to Embed</h1>

<p>First, configure the apps you would like to add on Appreplica.com. Then return here and enter shortcode for the App you wish to add to one or more of your Pages. We strongly recommend you allocate a full page to each App and add a menu or an icon linked to the page. Our Apps work best when they appear on a page by themselves and take advantage of the full width/height of the page. The Apps are fully responsive and automatically adjust their width to match the width of your theme.</p>

<p><span style="font-size:larger;"><b>The following apps are completely free, all you need is a free account on Appreplica.com</b></span></br></br></p>

<ul>

<li><p><img src="<?php echo plugins_url( 'icons/youtube.png', __FILE__ ); ?>" width="20" height="20" align="absmiddle" alt=""/>&nbsp; <b>YouTube</b>&nbsp;&nbsp; <code>[appreplica]YouTube[/appreplica]</code></p></li>
<li><p><img src="<?php echo plugins_url( 'icons/vimeo.png', __FILE__ ); ?>" width="20" height="20" align="absmiddle" alt=""/>&nbsp; <b>Vimeo</b>&nbsp;&nbsp; <code>[appreplica]Vimeo[/appreplica]</code></p></li>

<li><p><img src="<?php echo plugins_url( 'icons/itunes.png', __FILE__ ); ?>" width="20" height="20" align="absmiddle" alt=""/>&nbsp; <b>iTunes</b>&nbsp;&nbsp; <code>[appreplica]iTunes[/appreplica]</code></p></li>
<li><p><img src="<?php echo plugins_url( 'icons/spotify.png', __FILE__ ); ?>" width="20" height="20" align="absmiddle" alt=""/>&nbsp; <b>Spotify</b>&nbsp;&nbsp; <code>[appreplica]Spotify[/appreplica]</code></p></li>
<li><p><img src="<?php echo plugins_url( 'icons/deezer.png', __FILE__ ); ?>" width="20" height="20" align="absmiddle" alt=""/>&nbsp; <b>Deezer</b>&nbsp;&nbsp; <code>[appreplica]Deezer[/appreplica]</code></p></li>
<li><p><img src="<?php echo plugins_url( 'icons/soundcloud.png', __FILE__ ); ?>" width="20" height="20" align="absmiddle" alt=""/>&nbsp; <b>SoundCloud</b>&nbsp;&nbsp; <code>[appreplica]SoundCloud[/appreplica]</code></p></li>

<li><p><img src="<?php echo plugins_url( 'icons/instagram.png', __FILE__ ); ?>" width="20" height="20" align="absmiddle" alt=""/>&nbsp; <b>Intagram</b>&nbsp;&nbsp; <code>[appreplica]Instagram[/appreplica]</code></p></li>
<li><p><img src="<?php echo plugins_url( 'icons/twitter.png', __FILE__ ); ?>" width="20" height="20" align="absmiddle" alt=""/>&nbsp; <b>Twitter</b>&nbsp;&nbsp; <code>[appreplica]Twitter[/appreplica]</code></p></li>
<li><p><img src="<?php echo plugins_url( 'icons/facebook.png', __FILE__ ); ?>" width="20" height="20" align="absmiddle" alt=""/>&nbsp; <b>Facebook</b>&nbsp;&nbsp; <code>[appreplica]Facebook[/appreplica]</code></p></li>
<li><p><img src="<?php echo plugins_url( 'icons/tumblr.png', __FILE__ ); ?>" width="20" height="20" align="absmiddle" alt=""/>&nbsp; <b>Tumblr</b>&nbsp;&nbsp; <code>[appreplica]Tumblr[/appreplica]</code></p></li>
<li><p><img src="<?php echo plugins_url( 'icons/500px.png', __FILE__ ); ?>" width="20" height="20" align="absmiddle" alt=""/>&nbsp; <b>500px</b>&nbsp;&nbsp; <code>[appreplica]500px[/appreplica]</code></p></li>

<li><p><img src="<?php echo plugins_url( 'icons/flickr.png', __FILE__ ); ?>" width="20" height="20" align="absmiddle" alt=""/>&nbsp; <b>Flickr</b>&nbsp;&nbsp; <code>[appreplica]Flickr[/appreplica]</code></p></li>
<li><p><img src="<?php echo plugins_url( 'icons/smugmug.png', __FILE__ ); ?>" width="20" height="20" align="absmiddle" alt=""/>&nbsp; <b>SmugMug</b>&nbsp;&nbsp; <code>[appreplica]Smugmug[/appreplica]</code></p></li>

</ul>

<p></br><span style="font-size:larger;"><b>The following apps require a paid subscription on Appreplica.com</b></span></br></br></p>

<ul>

<li><p><img src="<?php echo plugins_url( 'icons/photos.png', __FILE__ ); ?>" width="20" height="20" align="absmiddle" alt=""/>&nbsp; <b>Photos</b>&nbsp;&nbsp; <code>[appreplica]Photos[/appreplica]</code></p></li>
<li><p><img src="<?php echo plugins_url( 'icons/videos.png', __FILE__ ); ?>" width="20" height="20" align="absmiddle" alt=""/>&nbsp; <b>Videos</b>&nbsp;&nbsp; <code>[appreplica]Videos[/appreplica]</code></p></li>
<li><p><img src="<?php echo plugins_url( 'icons/music.png', __FILE__ ); ?>" width="20" height="20" align="absmiddle" alt=""/>&nbsp; <b>Music</b>&nbsp;&nbsp; <code>[appreplica]Music[/appreplica]</code></p></li>

</ul>

<?php } ?>