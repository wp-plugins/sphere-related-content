<?php
/*
Plugin Name: Sphere Related Content
Plugin URI: http://www.sphere.com/tools#wpwidget
Description: Automatically show related blog posts and news articles from Sphere.  NEW in this version, related video for news bloggers.  You can now select from several plug-in types, see the <a href="plugins.php?page=sphere-related-content.php">Sphere Configuration Page</a> for details.  More plug-in types for politics and other categories coming soon.
Author: Watershed Studio, LLC 
Author URI: http://watershedstudio.com/portfolio/software/sphereit-contextual-widget.html
Version: 1.3
*/

/*  Copyright 2007 Sphere (email : plugins@sphere.com)
    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

/*
 * Optionally modify behavior here.
 *
 * The default behavior is to place the Sphere link automatically in posts that have more than 
 * a minimum length.
 *
 * If you NEVER want Sphere links inserted automatically, or if you want to decide where to place 
 * the link, rather than having it appended to your posts automatically, then set $auto_sphereit to 
 * FALSE below and follow the instructions in the readme to customize the look and placement of the link.
 *
 * If you want Sphere links inserted into ALL of your posts, then set $sphereit_threshold to FALSE below.
 *
 * You can always force a Sphere link to be inserted by including <!--sphereit--> in the text of a post.
 *
 * You can force a Sphere link NOT to be inserted by including <!--nosphereit--> in the text of your post.
 *
 * If you're not sure what to do, leave the settings below at their default value.
 *
 */

$auto_sphereit= TRUE;
$sphereit_threshold= TRUE;

// the following options only apply if $sphereit_threshold is TRUE

// minimum number of words to trigger adding sphere link
$min_words= 30;

// minimum number of chars to trigger adding sphere link (if the #words check failed)
$min_chars= 500;

function sphere_header() {

  echo '
<style type="text/css">
A.iconsphere {
  background: url(http://www.sphere.com/images/sphereicon.gif) top left no-repeat;
  padding-left: 20px;
  padding-bottom: 10px;
  font-size: 10px;
  white-space: nowrap;
}
</style>

<script type="text/javascript" src="http://www.sphere.com/widgets/sphereit/js?t='.get_sphere_rc_wtype().'&p=wordpressorg"></script>
';

}

/*
 * enable Sphere link for this post?
 */
function enableSphereItLink( $content ) {

	global $min_words;
	global $min_chars;
	global $sphereit_threshold;

	// negation always takes precedence	
	if ( strpos( $content, '<!--nosphereit-->' ) !== FALSE ) {
		return FALSE;
	}

	// manual override?
	if ( strpos( $content, '<!--sphereit-->' ) !== FALSE ) {
		return TRUE;
	}

	// passes threshold?
	if ($sphereit_threshold) {
		$num_words= count( explode( ' ', $content) );
		$num_chars= strlen( $content );
	
		if ( $num_words < $min_words && $num_chars < $min_chars )
			return FALSE;
	}

	return TRUE;
}

function sphere_content( $content ) {

	global $auto_sphereit;

	// if not in entry context, no link, bail
	if ( ! ( is_home() || is_single() || is_category() || is_date() || is_archive() || is_search() ) ) {
		return $content;
	}

	$content_orig = $content;

	$content= "<!-- sphereit start -->\r";
	$content.= $content_orig; 
	$content.= "<!-- sphereit end -->\r";		

	// if automatically placing link and link is a go, attach to end of content and append <br/> (user can override with custom tag)
	if ($auto_sphereit && enableSphereItLink( $content_orig )) {
		$content.= get_sphereit_link( get_permalink() ); 
		$content.= "<br/><br/>";
	}
	
	return $content;
}

/*
 * The link itself.  It's in a <span> so it can be wrapped any way the user likes into block-level elements
 */
function get_sphereit_link( $link, $content=NULL ) {

	// did we get some content to check?
	if (!is_null($content) && !enableSphereItLink($content)) {
		return "";
	}

	$content= "<span style=\"margin-bottom:40px; border-bottom:none;\">"; 
	$content.= '<a class="iconsphere" title="Sphere: Related Content" onclick="return Sphere.Widget.search(\'';
	$content.= $link;
	$content.= '\')" href="http://www.sphere.com/search?q=sphereit:';
	$content.= $link;
	$content.= '">Sphere: Related Content</a>'; 
	$content.= "</span>";
	
	return $content;
}

add_action('the_content','sphere_content');

add_action('wp_head', 'sphere_header');

add_action('admin_menu', 'sphere_rc_config_page');

/* === admin panel below here === */

function get_sphere_rc_wtype()
{
	$wtype = get_option('sphere_rc_wtype');
	if (!$wtype) { $wtype = "wordpressorg"; }
	return $wtype;
}

function sphere_rc_config_page() {
        global $wpdb;
        if ( function_exists('add_submenu_page') )
                add_submenu_page('plugins.php', __('Sphere Configuration'), __('Sphere Configuration'), 'manage_options', __FILE__, 'sphere_rc_conf');
}

function sphere_rc_conf() {
		$wtype = get_sphere_rc_wtype();
        if ( isset($_POST['src_frmsubmit']) ) {
                if ( !current_user_can('manage_options'))
                        die(__('Cheatin&#8217; uh?'));

                $wtype = $_POST['wtype'];
                update_option('sphere_rc_wtype', $wtype);
        }
?>
<div class="wrap">
<h2><?php _e('Sphere Related Content Configuration'); ?></h2>
        <p>Select the type of Sphere Related Content plug-in you'd like to have on your blog.</p>
		<p>If you blog about technology, news, gossip, sports or any other topics, stick with the CLASSIC Sphere plug-in for now 
			(we'll add more topic-specific plug-ins soon, in a future version -- check <a target="_blank" href="http://wordpress.org/extend/plugins/sphere-related-content/">here</a> for updates). 
			If you blog about POLITICS, you may want to consider one of our new POLITICS plug-ins. The currently available plug-ins include:
		</p>
		<p>
		<ul>
			<li>The CLASSIC plug-in -- shows related blog posts and news from a wide variety of sources, not category specific.  If in doubt, stick with this one. (You're done here, nothing to change.)</li>
			<li>The NEWS VIDEO plug-in for news bloggers -- shows related video from mainstream news sources.</li>
			<li>The POLITICS plug-in for <i>Democrats</i> -- shows related blog posts from Democratic and other left-leaning blogs, as well as from a variety of news sources.</li>
			<li>The POLITICS plug-in for <i>Republicans</i> -- shows related blog posts from Rebublican and other right-leaning blogs, as well as from a variety of news sources..</li>
			<li>The POLITICS plug-in with <i>Balance</i> -- shows related blog posts from both sides of the political divide, as well as from a variety of news sources.</li>
		</ul>
		</p>
<form action="" method="post" name="sphererc_frm" id="sphere-rc-conf" style="margin: auto; width: 25em; ">
<h3><label>I want my Sphere plug-in to be:</label></h3>
	<p>
	<input type="hidden" name="src_frmsubmit" value="1">
	<select name="wtype" onchange="document.sphererc_frm.submit()">
		<option <?php if ($wtype === "wordpressorg") echo "selected"; ?> value="wordpressorg">Classic</option>
		<option <?php if ($wtype === "news_video") echo "selected"; ?> value="news_video">News Video</option>
		<option <?php if ($wtype === "political_dem") echo "selected"; ?> value="political_dem">Politics, Democrat/Left</option>
		<option <?php if ($wtype === "political_rep") echo "selected"; ?> value="political_rep">Politics, Republican/Right</option>
		<option <?php if ($wtype === "political_gen") echo "selected"; ?> value="political_gen">Politics, Balanced</option>
	</select>	
	</p>
</form>
</div>
<?php
}
?>
