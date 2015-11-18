<?php
if ($_POST["modify"]<>"") {
	update_option("excerpt_everywhere_length",$_POST["excerpt_length"]);
	update_option("excerpt_everywhere_align",$_POST["align"]);
	update_option("excerpt_everywhere_moretext",$_POST['excerpt_text']);
	update_option("excerpt_everywhere_moreimg",$_POST['excerpt_img']);
	if ($_POST['excerpt_rss']=="yes"){
		update_option("excerpt_everywhere_rss",$_POST['excerpt_rss']);
	} else {
		update_option("excerpt_everywhere_rss","no");
	}
	if ($_POST['excerpt_homepage']=="yes"){
		update_option("excerpt_everywhere_homepage",$_POST['excerpt_homepage']);
	} else {
		update_option("excerpt_everywhere_homepage","no");
	}
	if ($_POST['excerpt_sticky']=="yes"){
		update_option("excerpt_everywhere_sticky",$_POST['excerpt_sticky']);
	} else {
		update_option("excerpt_everywhere_sticky","no");
	}
	update_option("excerpt_everywhere_thumb",$_POST['excerpt_thumb']);
	update_option("excerpt_everywhere_class",$_POST['excerpt_class']);
	echo '<div class="updated fade">Your settings have been saved</div>';
}
?>
<div class="wrap">
<?php if(function_exists('screen_icon')) screen_icon(); ?>
<h2>Auto Excerpt everywhere</h2><br />
<em>Here you can easily set your preferred excerpt length and custom read more link. You can also decide whether to display or not a post thumbnail in the excerpt (if there is one).</em>

<style>
td {padding:5px;}
</style>

<form method="post">
<table>
<tr>
<td>Excerpt length</td>
<td><input name="excerpt_length" type="text" value="<?php echo get_option("excerpt_everywhere_length");?>" /></td></tr>
<tr><td>Read more text</td>
<td><input name="excerpt_text" type="text" value="<?php echo get_option("excerpt_everywhere_moretext");?>" /> <small>Leave this field blank to disable the read more link</small></td>
</tr>
<tr><td>Read more button/image url</td>
<td><input name="excerpt_img" type="text" value="<?php echo get_option("excerpt_everywhere_moreimg");?>" /> <small>Leave this field blank if you want to use the read more text</small></td>
</tr>
<tr><td>Include post thumbnail</td>
<?php $whatthumb = get_option("excerpt_everywhere_thumb"); ?>
<td><input name="excerpt_thumb" type="radio" value="none" <?php if ($whatthumb=="none") {echo 'checked="checked"';} ?> />None&nbsp;&nbsp;&nbsp;<input name="excerpt_thumb" type="radio" value="thumbnail" <?php if ($whatthumb=="thumbnail") {echo 'checked="checked"';} ?> />Thumbnail&nbsp;&nbsp;&nbsp;<input name="excerpt_thumb" type="radio" value="medium" <?php if ($whatthumb=="medium") {echo 'checked="checked"';} ?> />Medium&nbsp;&nbsp;&nbsp;<input name="excerpt_thumb" type="radio" value="large" <?php if ($whatthumb=="large") {echo 'checked="checked"';} ?> />Large</td>
</tr>
<tr><td>Thumbnail alignment</td>
<?php $alignment = get_option("excerpt_everywhere_align"); ?>
<td><input name="align" type="radio" value="none" <?php if ($alignment=="none") {echo 'checked="checked"';} ?> />None&nbsp;&nbsp;&nbsp;<input name="align" type="radio" value="alignleft" <?php if ($alignment=="alignleft") {echo 'checked="checked"';} ?> />Left&nbsp;&nbsp;&nbsp;<input name="align" type="radio" value="aligncenter" <?php if ($alignment=="aligncenter") {echo 'checked="checked"';} ?> />Center&nbsp;&nbsp;&nbsp;<input name="align" type="radio" value="alignright" <?php if ($alignment=="alignright") {echo 'checked="checked"';} ?> />Right</td>
</tr>
<tr><td>Custom thumbnail class</td>
<td><input name="excerpt_class" type="text" value="<?php echo get_option("excerpt_everywhere_class");?>" /> <small>You can also style the .autoexcerpt_thumb class</small></td>
</tr>
<tr><td>Disable in rss feed</td>
<?php $rss_disable = get_option("excerpt_everywhere_rss"); ?>
<td><input name="excerpt_rss" type="checkbox" value="yes" <?php if ($rss_disable=="yes") {echo 'checked="checked"';} ?> /></td>
</tr>
<tr><td>Disable in home page</td>
<?php $homepage_disable = get_option("excerpt_everywhere_homepage"); ?>
<td><input name="excerpt_homepage" type="checkbox" value="yes" <?php if ($homepage_disable=="yes") {echo 'checked="checked"';} ?> /></td>
</tr>
<tr><td>Exclude sticky posts</td>
<?php $sticky_disable = get_option("excerpt_everywhere_sticky"); ?>
<td><input name="excerpt_sticky" type="checkbox" value="yes" <?php if ($sticky_disable=="yes") {echo 'checked="checked"';} ?> /></td>
</tr>
</table><br />
<input type="submit" class="button-primary" value="Update settings" name="modify" />
</form>
<br /><br />
Would like to have more options? <a href="http://www.josie.it/wordpress/wordpress-plugin-auto-excerpt-everywhere/#comments" target="_blank">Feel free to ask me</a>.<br />
Do you like this plugin? <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=LWABEBWA9U46S" target="_blank">I'm a girl so I'm not really into beers. Buy me a coffee instead!</a><br /><br />
<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="LWABEBWA9U46S">
<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
<img alt="" border="0" src="https://www.paypalobjects.com/it_IT/i/scr/pixel.gif" width="1" height="1">
</form>

</div>