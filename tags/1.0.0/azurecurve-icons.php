<?php
/*
Plugin Name: azurecurve Icons
Plugin URI: http://development.azurecurve.co.uk/plugins/icons

Description: Allows a 16x16 icon to be displayed in a post of page using a shortcode.
Version: 1.0.0

Author: azurecurve
Author URI: http://development.azurecurve.co.uk

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.

The full copy of the GNU General Public License is available here: http://www.gnu.org/licenses/gpl.txt

*/

function azc_i_icon($atts, $content = null) {
	if (empty($atts)){
		$icon = 'none';
	}else{
		$attribs = implode('',$atts);
		$icon = trim ( trim ( trim ( trim ( trim ( $attribs , '=' ) , '"' ) , "'" ) , '&#8217;' ) , "&#8221;" );
	}
	return "<img class='azc_icons' src='".plugin_dir_url(__FILE__)."images/$icon.png' />";
}
add_shortcode( 'icon', 'azc_i_icon' );
add_shortcode( 'icons', 'azc_i_icon' );
add_shortcode( 'ICON', 'azc_i_icon' );
add_shortcode( 'ICONS', 'azc_i_icon' );

function azc_i_load_css(){
	wp_enqueue_style( 'azurecurve-icons', plugins_url( 'style.css', __FILE__ ) );
}
add_action('wp_enqueue_scripts', 'azc_i_load_css');


// azurecurve menu
if (!function_exists(azc_create_plugin_menu)){
	function azc_create_plugin_menu() {
		global $admin_page_hooks;
		
		if ( empty ( $admin_page_hooks['azc-menu-test'] ) ){
			add_menu_page( "azurecurve Plugins"
							,"azurecurve"
							,'manage_options'
							,"azc-plugin-menus"
							,"azc_plugin_menus"
							,plugins_url( '/images/Favicon-16x16.png', __FILE__ ) );
			add_submenu_page( "azc-plugin-menus"
								,"Plugins"
								,"Plugins"
								,'manage_options'
								,"azc-plugin-menus"
								,"azc_plugin_menus" );
		}
	}
	add_action("admin_menu", "azc_create_plugin_menu");
}

function azc_create_i_plugin_menu() {
	global $admin_page_hooks;
    
	add_submenu_page( "azc-plugin-menus"
						,"icons"
						,"Icons"
						,'manage_options'
						,"azc-i"
						,"azc_i_settings" );
}
add_action("admin_menu", "azc_create_i_plugin_menu");

function azc_i_settings() {
	if (!current_user_can('manage_options')) {
		$error = new WP_Error('not_found', __('You do not have sufficient permissions to access this page.' , 'azc-i'), array('response' => '200'));
		if(is_wp_error($error)){
			wp_die($error, '', $error->get_error_data());
		}
    }
	?>
	<div id="azc-t-general" class="wrap">
			<h2>azurecurve icons</h2>

			<label for="explanation">
				<p>azurecurve Icons <?php _e('allows a 16x16 icon to be displayed in a post or page using the [icon] shortcode.', 'azc-i'); ?></p>
				<p><?php _e('Format of shortcode is [icon=accept] to display the accept icon.', 'azc-i'); ?></p>
				<p>Included icons are from the famfamfam Silk icon set 1.3 by Mark James (<a href="http://www.famfamfam.com/lab/icons/silk/">http://www.famfamfam.com/lab/icons/silk/</a>). Extra icons can be added by simply placing them in PNG format into the /images folder; the filename, without the extension, is the shortcode parameter.</p>
			</label>
			<p>
			Available icons are:
				
				<?php
				$dir = plugin_dir_path(__FILE__) . '/images';
				if (is_dir( $dir )) {
					if ($directory = opendir($dir)) {
						while (($file = readdir($directory)) !== false) {
							if ($file != '.' and $file != '..' and $file != 'Thumbs.db'){
								$filewithoutext = preg_replace('/\\.[^.\\s]{3,4}$/', '', $file);
								echo "<div style='width: 180px; display: inline-block;'><img src='";
								echo plugin_dir_url(__FILE__) . "images/$filewithoutext.png;' title='$filewithoutext' alt='$filewithoutext' />&nbsp;<em>$filewithoutext</em></div>";
							}
						}
						closedir($directory);
					}
				}
				?>
				
			</p>
			
			<label for="additional-plugins">
				azurecurve <?php _e('has the following plugins which allow shortcodes to be used in comments and widgets:', 'azc-i'); ?>
			</label>
			<ul class='azc_plugin_index'>
				<li>
					<?php
					if ( is_plugin_active( 'azurecurve-shortcodes-in-comments/azurecurve-shortcodes-in-comments.php' ) ) {
						echo "<a href='admin.php?page=azc-sic' class='azc_plugin_index'>Shortcodes in Comments</a>";
					}else{
						echo "<a href='https://wordpress.org/plugins/azurecurve-shortcodes-in-comments/' class='azc_plugin_index'>Shortcodes in Comments</a>";
					}
					?>
				</li>
				<li>
					<?php
					if ( is_plugin_active( 'azurecurve-shortcodes-in-widgets/azurecurve-shortcodes-in-widgets.php' ) ) {
						echo "<a href='admin.php?page=azc-siw' class='azc_plugin_index'>Shortcodes in Widgets</a>";
					}else{
						echo "<a href='https://wordpress.org/plugins/azurecurve-shortcodes-in-widgets/' class='azc_plugin_index'>Shortcodes in Widgets</a>";
					}
					?>
				</li>
			</ul>
	</div>
<?php }
if (!function_exists(azc_plugin_index_load_css)){
	function azc_plugin_index_load_css(){
		wp_enqueue_style( 'azurecurve_plugin_index', plugins_url( 'pluginstyle.css', __FILE__ ) );
	}
	add_action('admin_head', 'azc_plugin_index_load_css');
}

if (!function_exists(azc_plugin_menus)){
	function azc_plugin_menus() {
		echo "<h3>azurecurve Plugins";
		
		echo "<div style='display: block;'><h4>Active</h4>";
		echo "<div class='azc_plugin_index'>";
		if ( is_plugin_active( 'azurecurve-bbcode/azurecurve-bbcode.php' ) ) {
			echo "<a href='admin.php?page=azc-bbcode' class='azc_plugin_index'>BBCode</a>";
		}
		if ( is_plugin_active( 'azurecurve-comment-validator/azurecurve-comment-validator.php' ) ) {
			echo "<a href='admin.php?page=azc-cv' class='azc_plugin_index'>Comment Validator</a>";
		}
		if ( is_plugin_active( 'azurecurve-conditional-links/azurecurve-conditional-links.php' ) ) {
			echo "<a href='admin.php?page=azc-cl' class='azc_plugin_index'>Conditional Links</a>";
		}
		if ( is_plugin_active( 'azurecurve-display-after-post-content/azurecurve-display-after-post-content.php' ) ) {
			echo "<a href='admin.php?page=azc-dapc' class='azc_plugin_index'>Display After Post Content</a>";
		}
		if ( is_plugin_active( 'azurecurve-filtered-categories/azurecurve-filtered-categories.php' ) ) {
			echo "<a href='admin.php?page=azc-fc' class='azc_plugin_index'>Filtered Categories</a>";
		}
		if ( is_plugin_active( 'azurecurve-flags/azurecurve-flags.php' ) ) {
			echo "<a href='admin.php?page=azc-f' class='azc_plugin_index'>Flags</a>";
		}
		if ( is_plugin_active( 'azurecurve-floating-featured-image/azurecurve-floating-featured-image.php' ) ) {
			echo "<a href='admin.php?page=azc-ffi' class='azc_plugin_index'>Floating Featured Image</a>";
		}
		if ( is_plugin_active( 'azurecurve-get-plugin-info/azurecurve-get-plugin-info.php' ) ) {
			echo "<a href='admin.php?page=azc-gpi' class='azc_plugin_index'>Get Plugin Info</a>";
		}
		if ( is_plugin_active( 'azurecurve-icons/azurecurve-icons.php' ) ) {
			echo "<a href='admin.php?page=azc-f' class='azc_plugin_index'>Icons</a>";
		}
		if ( is_plugin_active( 'azurecurve-insult-generator/azurecurve-insult-generator.php' ) ) {
			echo "<a href='admin.php?page=azc-ig' class='azc_plugin_index'>Insult Generator</a>";
		}
		if ( is_plugin_active( 'azurecurve-mobile-detection/azurecurve-mobile-detection.php' ) ) {
			echo "<a href='admin.php?page=azc-md' class='azc_plugin_index'>Mobile Detection</a>";
		}
		if ( is_plugin_active( 'azurecurve-multisite-favicon/azurecurve-multisite-favicon.php' ) ) {
			echo "<a href='admin.php?page=azc-msf' class='azc_plugin_index'>Multisite Favicon</a>";
		}
		if ( is_plugin_active( 'azurecurve-page-index/azurecurve-page-index.php' ) ) {
			echo "<a href='admin.php?page=azc-pi' class='azc_plugin_index'>Page Index</a>";
		}
		if ( is_plugin_active( 'azurecurve-posts-archive/azurecurve-posts-archive.php' ) ) {
			echo "<a href='admin.php?page=azc-pa' class='azc_plugin_index'>Posts Archive</a>";
		}
		if ( is_plugin_active( 'azurecurve-rss-feed/azurecurve-rss-feed.php' ) ) {
			echo "<a href='admin.php?page=azc-rssf' class='azc_plugin_index'>RSS Feed</a>";
		}
		if ( is_plugin_active( 'azurecurve-rss-suffix/azurecurve-rss-suffix.php' ) ) {
			echo "<a href='admin.php?page=azc-rsss' class='azc_plugin_index'>RSS Suffix</a>";
		}
		if ( is_plugin_active( 'azurecurve-series-index/azurecurve-series-index.php' ) ) {
			echo "<a href='admin.php?page=azc-si' class='azc_plugin_index'>Series Index</a>";
		}
		if ( is_plugin_active( 'azurecurve-shortcodes-in-comments/azurecurve-shortcodes-in-comments.php' ) ) {
			echo "<a href='admin.php?page=azc-sic' class='azc_plugin_index'>Shortcodes in Comments</a>";
		}
		if ( is_plugin_active( 'azurecurve-shortcodes-in-widgets/azurecurve-shortcodes-in-widgets.php' ) ) {
			echo "<a href='admin.php?page=azc-siw' class='azc_plugin_index'>Shortcodes in Widgets</a>";
		}
		if ( is_plugin_active( 'azurecurve-tag-cloud/azurecurve-tag-cloud.php' ) ) {
			echo "<a href='admin.php?page=azc-tc' class='azc_plugin_index'>Tag Cloud</a>";
		}
		if ( is_plugin_active( 'azurecurve-taxonomy-index/azurecurve-taxonomy-index.php' ) ) {
			echo "<a href='admin.php?page=azc-ti' class='azc_plugin_index'>Taxonomy Index</a>";
		}
		if ( is_plugin_active( 'azurecurve-theme-switcher/azurecurve-theme-switcher.php' ) ) {
			echo "<a href='admin.php?page=azc-ts' class='azc_plugin_index'>Theme Switcher</a>";
		}
		if ( is_plugin_active( 'azurecurve-timelines/azurecurve-timelines.php' ) ) {
			echo "<a href='admin.php?page=azc-t' class='azc_plugin_index'>Timelines</a>";
		}
		if ( is_plugin_active( 'azurecurve-toggle-showhide/azurecurve-toggle-showhide.php' ) ) {
			echo "<a href='admin.php?page=azc-tsh' class='azc_plugin_index'>Toggle Show/Hide</a>";
		}
		echo "</div></div>";
		echo "<p style='clear: both' />";
		
		echo "<div style='display: block;'><h4>Other Available Plugins</h4>";
		echo "<div class='azc_plugin_index'>";
		if ( !is_plugin_active( 'azurecurve-bbcode/azurecurve-bbcode.php' ) ) {
			echo "<a href='https://wordpress.org/plugins/azurecurve-bbcode/' class='azc_plugin_index'>BBCode</a>";
		}
		if ( !is_plugin_active( 'azurecurve-comment-validator/azurecurve-comment-validator.php' ) ) {
			echo "<a href='https://wordpress.org/plugins/azurecurve-comment-validator/' class='azc_plugin_index'>Comment Validator</a>";
		}
		if ( !is_plugin_active( 'azurecurve-conditional-links/azurecurve-conditional-links.php' ) ) {
			echo "<a href='https://wordpress.org/plugins/azurecurve-conditional-links/' class='azc_plugin_index'>Conditional Links</a>";
		}
		if ( !is_plugin_active( 'azurecurve-display-after-post-content/azurecurve-display-after-post-content.php' ) ) {
			echo "<a href='https://wordpress.org/plugins/azurecurve-display-after-post-content/' class='azc_plugin_index'>Display After Post Content</a>";
		}
		if ( !is_plugin_active( 'azurecurve-filtered-categories/azurecurve-filtered-categories.php' ) ) {
			echo "<a href='https://wordpress.org/plugins/azurecurve-filtered-categories/' class='azc_plugin_index'>Filtered Categories</a>";
		}
		if ( !is_plugin_active( 'azurecurve-flags/azurecurve-flags.php' ) ) {
			echo "<a href='https://wordpress.org/plugins/azurecurve-flags/' class='azc_plugin_index'>Flags</a>";
		}
		if ( !is_plugin_active( 'azurecurve-floating-featured-image/azurecurve-floating-featured-image.php' ) ) {
			echo "<a href='https://wordpress.org/plugins/azurecurve-floating-featured-image/' class='azc_plugin_index'>Floating Featured Image</a>";
		}
		if ( !is_plugin_active( 'azurecurve-get-plugin-info/azurecurve-get-plugin-info.php' ) ) {
			echo "<a href='https://wordpress.org/plugins/azurecurve-get-plugin-info/' class='azc_plugin_index'>Get Plugin Info</a>";
		}
		if ( !is_plugin_active( 'azurecurve-icons/azurecurve-icons.php' ) ) {
			echo "<a href='https://wordpress.org/plugins/azurecurve-icons/' class='azc_plugin_index'>Icons</a>";
		}
		if ( !is_plugin_active( 'azurecurve-insult-generator/azurecurve-insult-generator.php' ) ) {
			echo "<a href='https://wordpress.org/plugins/azurecurve-insult-generator/' class='azc_plugin_index'>Insult Generator</a>";
		}
		if ( !is_plugin_active( 'azurecurve-mobile-detection/azurecurve-mobile-detection.php' ) ) {
			echo "<a href='https://wordpress.org/plugins/azurecurve-mobile-detection/' class='azc_plugin_index'>Mobile Detection</a>";
		}
		if ( !is_plugin_active( 'azurecurve-multisite-favicon/azurecurve-multisite-favicon.php' ) ) {
			echo "<a href='https://wordpress.org/plugins/azurecurve-multisite-favicon/' class='azc_plugin_index'>Multisite Favicon</a>";
		}
		if ( !is_plugin_active( 'azurecurve-page-index/azurecurve-page-index.php' ) ) {
			echo "<a href='https://wordpress.org/plugins/azurecurve-page-index/' class='azc_plugin_index'>Page Index</a>";
		}
		if ( !is_plugin_active( 'azurecurve-posts-archive/azurecurve-posts-archive.php' ) ) {
			echo "<a href='https://wordpress.org/plugins/azurecurve-posts-archive/' class='azc_plugin_index'>Posts Archive</a>";
		}
		if ( !is_plugin_active( 'azurecurve-rss-feed/azurecurve-rss-feed.php' ) ) {
			echo "<a href='https://wordpress.org/plugins/azurecurve-rss-feed/' class='azc_plugin_index'>RSS Feed</a>";
		}
		if ( !is_plugin_active( 'azurecurve-rss-suffix/azurecurve-rss-suffix.php' ) ) {
			echo "<a href='https://wordpress.org/plugins/azurecurve-rss-suffix/' class='azc_plugin_index'>RSS Suffix</a>";
		}
		if ( !is_plugin_active( 'azurecurve-series-index/azurecurve-series-index.php' ) ) {
			echo "<a href='https://wordpress.org/plugins/azurecurve-series-index/' class='azc_plugin_index'>Series Index</a>";
		}
		if ( !is_plugin_active( 'azurecurve-shortcodes-in-comments/azurecurve-shortcodes-in-comments.php' ) ) {
			echo "<a href='https://wordpress.org/plugins/azurecurve-shortcodes-in-comments/' class='azc_plugin_index'>Shortcodes in Comments</a>";
		}
		if ( !is_plugin_active( 'azurecurve-shortcodes-in-widgets/azurecurve-shortcodes-in-widgets.php' ) ) {
			echo "<a href='https://wordpress.org/plugins/azurecurve-shortcodes-in-widgets/' class='azc_plugin_index'>Shortcodes in Widgets</a>";
		}
		if ( !is_plugin_active( 'azurecurve-tag-cloud/azurecurve-tag-cloud.php' ) ) {
			echo "<a href='https://wordpress.org/plugins/azurecurve-tag-cloud/' class='azc_plugin_index'>Tag Cloud</a>";
		}
		if ( !is_plugin_active( 'azurecurve-taxonomy-index/azurecurve-taxonomy-index.php' ) ) {
			echo "<a href='https://wordpress.org/plugins/azurecurve-taxonomy-index/' class='azc_plugin_index'>Taxonomy Index</a>";
		}
		if ( !is_plugin_active( 'azurecurve-theme-switcher/azurecurve-theme-switcher.php' ) ) {
			echo "<a href='https://wordpress.org/plugins/azurecurve-theme-switcher/' class='azc_plugin_index'>Theme Switcher</a>";
		}
		if ( !is_plugin_active( 'azurecurve-timelines/azurecurve-timelines.php' ) ) {
			echo "<a href='https://wordpress.org/plugins/azurecurve-timelines/' class='azc_plugin_index'>Timelines</a>";
		}
		if ( !is_plugin_active( 'azurecurve-toggle-showhide/azurecurve-toggle-showhide.php' ) ) {
			echo "<a href='https://wordpress.org/plugins/azurecurve-toggle-showhide/' class='azc_plugin_index'>Toggle Show/Hide</a>";
		}
		echo "</div></div>";
	}
}

?>