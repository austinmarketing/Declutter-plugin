<?php
/*
Plugin Name: Wordpress Declutter
Description: Removes unwanted core WP actions that are not needed.
Version: 1.0
*/

/* 
 * Shorten the interval for emptying the trash bin (3 Days)
 */
define( 'EMPTY_TRASH_DAYS', 3 );

/**
 * Disable the emoji's
 */
remove_action( 'wp_head', 'print_emoji_detection_script', 7 ); 
remove_action( 'admin_print_scripts', 'print_emoji_detection_script' ); 
remove_action( 'wp_print_styles', 'print_emoji_styles' ); 
remove_action( 'admin_print_styles', 'print_emoji_styles' );
/**
 * Remove Gutenberg Styles on Frontend (This needs to be in the theme functions file until this is fixed)
 */
//remove_action( 'wp_enqueue_scripts', 'gutenberg_common_scripts_and_styles' ); 
/**
 * Disable the shortlink
 */
remove_action( 'wp_head', 'wp_shortlink_wp_head', 10, 0);
/**
 * Remove WP json link
 */
remove_action( 'template_redirect', 'rest_output_link_header', 11 );
remove_action( 'wp_head', 'rest_output_link_wp_head');
remove_action( 'wp_head', 'wp_oembed_add_discovery_links');
/**
 * Remove all wp image classes and width/height attributes
 */
remove_action( 'begin_fetch_post_thumbnail_html', '_wp_post_thumbnail_class_filter_add' );
function remove_image_size_attributes( $html ) {
	return preg_replace( '/(width|height)="\d*"/', '', $html );
}
add_filter( 'post_thumbnail_html', 'remove_image_size_attributes' );
add_filter( 'image_send_to_editor', 'remove_image_size_attributes' );
/**
 * Cleanup the head and remove WP rubbish
 */
remove_action( 'wp_head', 'wp_resource_hints', 2 );
remove_action( 'wp_head', 'rsd_link');
remove_action( 'wp_head', 'wp_generator');
remove_action( 'wp_head', 'feed_links', 2);
remove_action( 'wp_head', 'index_rel_link');
remove_action( 'wp_head', 'wlwmanifest_link');
remove_action( 'wp_head', 'feed_links_extra', 3);
remove_action( 'wp_head', 'start_post_rel_link', 10, 0);
remove_action( 'wp_head', 'parent_post_rel_link', 10, 0);
remove_action( 'wp_head', 'adjacent_posts_rel_link', 10, 0);
remove_action( 'wp_head', 'wp_oembed_add_discovery_links');
remove_action( 'wp_head', 'wp_oembed_add_host_js');
remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );
/**
 * Remove WP version from scripts
 */
function decultter_remove_wp_ver_css_js( $src ) {
	if ( strpos( $src, 'ver=' ) )
		$src = remove_query_arg( 'ver', $src );
	return $src;
}
/**
 * Remove WP version from RSS
 */
function decultter_rss_version() { return ''; }
/**
 * Remove injected CSS for recent comments widget
 */
function decultter_remove_wp_widget_recent_comments_style() {
	if ( has_filter( 'wp_head', 'wp_widget_recent_comments_style' ) ) {
		remove_filter( 'wp_head', 'wp_widget_recent_comments_style' );
	}
}
/**
 * Remove all Yoast HTML comments if installed
 */
if (defined('WPSEO_VERSION')){
  add_action('get_header',function (){ ob_start(function ($o){
  return preg_replace('/^<!--.*?[Y]oast.*?-->$/mi','',$o); }); });
  add_action('wp_head',function (){ ob_end_flush(); }, 999);
}
/**
 * Remove injected CSS from gallery
 */
function ogle_gallery_style($css) {
	return preg_replace( "!<style type='text/css'>(.*?)</style>!s", '', $css );
}
/**
 * Remove the p from around images
 */
function ogle_filter_ptags_on_images($content){
	return preg_replace('/<p>\s*(<a .*>)?\s*(<img .* \/>)\s*(<\/a>)?\s*<\/p>/iU', '\1\2\3', $content);
}

?>