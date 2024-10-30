<?php
	$cm_subscriber_stats_title = get_option('cm_subscriber_stats_title');
	$cm_subscriber_stats_feeds = get_option('cm_subscriber_stats_feeds');
	$cm_subscriber_stats_intro = get_option('cm_subscriber_stats_intro');


?>
<div class="wrap">
<h2><?php _e('Subscriber Stats', CM_SUBSCRIBER_STATS_DOMAIN); ?></h2>
<form method="post" action="options.php">
<input type="hidden" name="action" value="update" />
<?php wp_nonce_field('update-options'); ?>

<input type="hidden" name="page_options" value="cm_subscriber_stats_title,cm_subscriber_stats_intro,cm_subscriber_stats_feeds" />


<table class="form-table">
<tr valign="top">
<th scope="row"><label for="cm_subscriber_stats_title"><?php _e('Dashboard widget title', CM_SUBSCRIBER_STATS_DOMAIN); ?></label></th>
<td><input name="cm_subscriber_stats_title" type="text" id="cm_subscriber_stats_title" value="<?php echo $cm_subscriber_stats_title; ?>" class="regular-text" />
</td>
</tr>

<tr valign="top">
<th scope="row"><label for="cm_subscriber_stats_feeds"><?php _e('Dashboard introductory text', CM_SUBSCRIBER_STATS_DOMAIN); ?></label></th>
<td><textarea name="cm_subscriber_stats_intro" id="cm_subscriber_stats_intro" class="regular-text code" cols="60" rows="2"><?php echo $cm_subscriber_stats_intro; ?></textarea>
</td>
</tr>

<tr valign="top">
<th scope="row"><label for="cm_subscriber_stats_feeds"><?php _e('Feed URLs', CM_SUBSCRIBER_STATS_DOMAIN); ?></label></th>
<td><textarea name="cm_subscriber_stats_feeds" id="cm_subscriber_stats_feeds" class="regular-text code" cols="80" rows="5"><?php echo $cm_subscriber_stats_feeds; ?></textarea>
<span class="setting-description"><?php _e('One per line', CM_SUBSCRIBER_STATS_DOMAIN); ?></span>
</td>
</tr>
</table>


<p class="submit"><input type="submit" name="Submit" value="<?php _e('Save Changes') ?>" /></p>
</form>
</div>