<?php
/*
Plugin Name: CM Subscriber Stats
Plugin URI: http://dialect.ca/code/cm-subscriber-stats/
Description: See your email list subscriber statistics on your WordPress dashboard.
Author: Dialect
Version: 1.0.1
Author URI: http://dialect.ca/
*/

include_once(ABSPATH . WPINC . '/rss.php');

define('CM_SUBSCRIBER_STATS_DOMAIN', 'cm_subscriber_stats');

add_action('admin_init', 'cm_subscriber_stats_admin_init');
add_action('admin_menu', 'cm_subscriber_stats_admin_menu');
add_action('wp_dashboard_setup', 'cm_subscriber_stats_dashboard_setup');

register_activation_hook(__FILE__,'cm_subscriber_stats_install');

function cm_subscriber_stats_install() {
	$title = cm_subscriber_stats_get_title();
	add_option( 'cm_subscriber_stats_title' , $title );
}

/**
 * General setup
 */
function cm_subscriber_stats_admin_init() {
	load_plugin_textdomain( 'cm_subscriber_stats' );

	if( function_exists( 'register_setting' ) ) {
		register_setting( CM_SUBSCRIBER_STATS_DOMAIN , 'cm_subscriber_stats_title' );
		register_setting( CM_SUBSCRIBER_STATS_DOMAIN , 'cm_subscriber_stats_intro' );
		register_setting( CM_SUBSCRIBER_STATS_DOMAIN , 'cm_subscriber_stats_feeds' );
	}
}

/**
 * Add the options page.
 */
function cm_subscriber_stats_admin_menu() {
	$title = cm_subscriber_stats_get_title();
	add_options_page($title, $title, 8, dirname(__FILE__) . '/options.php');
}

/**
 * Add the dashboard widget.
 */
function cm_subscriber_stats_dashboard_setup() {
	if ( current_user_can('edit_posts') ) {
		$title = cm_subscriber_stats_get_title();
		wp_add_dashboard_widget( 'cm_subscriber_stats', $title, 'cm_subscriber_stats_dashboard' );
	}
}

/**
 * Output the dashboard widget.
 */
function cm_subscriber_stats_dashboard() {
	$feeds = get_option('cm_subscriber_stats_feeds');

	print "<div class=\"rss-widget\">\n";

	$intro = get_option('cm_subscriber_stats_intro');

	// display the custom intro
	if ( !empty($intro) )
		print wpautop($intro);

	if( empty($feeds) ) {
		$options_page = dirname(plugin_basename(__FILE__));
		printf("<p>%s</p><p>%s</p>\n</div>\n",
				__('You haven\'t set up any feeds yet.', CM_SUBSCRIBER_STATS_DOMAIN),
				__("See up your feeds on the <a href='options-general.php?page=$options_page/options.php'>options page</a>.", CM_SUBSCRIBER_STATS_DOMAIN)
		);
		return;
	}

	$date_format = get_option('date_format');

	// iterate through the feeds and print the output
	$feeds = preg_split('/[\n\r\f]/m', $feeds, -1, PREG_SPLIT_NO_EMPTY);

	echo "\n<ul>\n";

	foreach ( $feeds as $feed ) {
		$rss = @fetch_rss( $feed );
		if ( !isset($rss->items) || 0 == count($rss->items) )
			continue;

		echo cm_subscriber_stats_format_entry($rss->channel['title'], $rss->items[0], $date_format);
	}

	echo "</ul>\n</div>\n";
}

/**
 * Re-format Campaign Monitor's data to be a little prettier.
 */
function cm_subscriber_stats_format_entry($title, $entry, $date_format) {
	$last_date = date($date_format, strtotime($entry['pubdate']));
	$data = str_replace( 'Today:', 'Latest (' . $last_date . '):', $entry['description'] );
	$out = sprintf("\t<li><strong class='rsswidget'>%s</strong><br/><span class='rssSummary'>%s</span></li>\n",
					$title,
					$data);
	return $out;

}

/**
 * Get the stats widget's title to display to users.
 */
function cm_subscriber_stats_get_title() {
	$title =  get_option( 'cm_subscriber_stats_title' );

	if ( empty($title) ) {
		$title = __('Campaign Monitor Subscriber Stats', CM_SUBSCRIBER_STATS_DOMAIN);
	}

	return $title;
}