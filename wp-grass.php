<?php
/*
   Plugin Name: WP-Grass
   Plugin URI: http://wordpress.org/extend/plugins/wp-grass/
   Version: 0.2.1
   Author: ruocaled
   Description: The grass grows higher while your blog is being inactive.
   Text Domain: wp-grass
   License: GPLv3
  */


$WpGrass_minimalRequiredPhpVersion = '5.0';

/**
 * Check the PHP version and give a useful error message if the user's version is less than the required version
 * @return boolean true if version check passed. If false, triggers an error which WP will handle, by displaying
 * an error message on the Admin page
 */
function WpGrass_noticePhpVersionWrong() {
    global $WpGrass_minimalRequiredPhpVersion;
    echo '<div class="updated fade">' .
      __('Error: plugin "wp grass" requires a newer version of PHP to be running.',  'wp-grass').
            '<br/>' . __('Minimal version of PHP required: ', 'wp-grass') . '<strong>' . $WpGrass_minimalRequiredPhpVersion . '</strong>' .
            '<br/>' . __('Your server\'s PHP version: ', 'wp-grass') . '<strong>' . phpversion() . '</strong>' .
         '</div>';
}


function WpGrass_PhpVersionCheck() {
    global $WpGrass_minimalRequiredPhpVersion;
    if (version_compare(phpversion(), $WpGrass_minimalRequiredPhpVersion) < 0) {
        add_action('admin_notices', 'WpGrass_noticePhpVersionWrong');
        return false;
    }
    return true;
}


/**
 * Initialize internationalization (i18n) for this plugin.
 * References:
 *      http://codex.wordpress.org/I18n_for_WordPress_Developers
 *      http://www.wdmac.com/how-to-create-a-po-language-translation#more-631
 * @return void
 */
function WpGrass_i18n_init() {
    $pluginDir = dirname(plugin_basename(__FILE__));
    load_plugin_textdomain('wp-grass', false, $pluginDir . '/languages/');
}


//////////////////////////////////
// Run initialization
/////////////////////////////////

// First initialize i18n
WpGrass_i18n_init();


// Next, run the version check.
// If it is successful, continue with initialization for this plugin
if (WpGrass_PhpVersionCheck()) {
    // Only load and run the init function if we know PHP version can parse it
    include_once('wp-grass_init.php');
    WpGrass_init(__FILE__);
}
