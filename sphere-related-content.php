<?php
/*
Plugin Name: Sphere Related Content Widget
Plugin URI: http://www.sphere.com/tools#wpwidget
Description: Automatically show related blog posts and news articles from Sphere -- thanks to <a href="http://moeffju.net">Matthias Bauer</a> for the thresholding and other ideas incorporated in this version. 
Author: Watershed Studio, LLC 
Author URI: http://watershedstudio.com/portfolio/software/sphereit-contextual-widget.html
Version: 1.2 
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

<script type="text/javascript" src="http://www.sphere.com/widgets/sphereit/js?siteid=wordpressorg"></script>
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

?>
