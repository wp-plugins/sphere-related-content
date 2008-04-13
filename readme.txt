=== Sphere Related Content ===
Tags: Related Content, Sphere, SphereIt Widget
Contributors: Watershed Studio LLC, Matthias Bauer

The Sphere Related Content plug-in displays an icon link at the end of your blog posts. When a reader clicks on the icon link, Sphere finds blog posts and media articles related to your content. See it in action on over 1 billion article pages on popular media sites and blogs like the WSJ, New York Times, TechCrunch and Real Clear Politics. Our plug-in works best on English language blog posts and topics that are being actively discussed in the blogosphere.  <br/><br/>NEW VIDEO WIDGET - With our newest widget, you can display related VIDEO from Sphere Partners in your Sphere Related Content widget. The Sphere Video widget is ideal for bloggers posting about highly topical events like politics, sports, celebrity gossip and all things that are buzzing in media.<br/><br/>FOR POLITICAL BLOGGERS - You can adapt your plug-in to the election campaign: Left, Right, Balanced ... If you're a political blogger, this is an informative and fun way to add to your site's user experience. Just go to the "Plugins" section in your WordPress admin UI, you'll see a new section called "Sphere Configuration" after you've installed the plugin. You'll be able to select your widget type there. More widget types (politics and beyond) coming soon.

== Installation ==

By installing the Sphere Related Content plugin you agree to the Sphere Related Content Terms of Service listed here: http://www.sphere.com/tosrc

1. Copy the file sphere-related-content.php into your plugins directory (wp-content/plugins/).

2. Login to your WordPress Admin Panel 

3. Go to the Plugins tab

4. Activate the Sphere Related Content Widget plugin.  (Click Activate in the right column).

   Now, the Sphere link will be inserted at the bottom of all posts longer than 30 words.
   For most blogs, this works just peachy and you're done.  If you're an advanced user 
   and want to customize the behavior and look/placement of the link, read on.

5. You can now change the type/theme of the Sphere Related Content plugin.  In the admin, click on the
   "Plugins" menu and then click on the "Sphere Configuration" sub-menu for details and to 
   change your widget type.  If you're in doubt about which type to use, stick with Classic.  
   More types coming soon.  When you change a type, reload one of your pages and click on the 
   Sphere Related Content link to see the widget type you selected.
 
6. The default behavior is to place the Sphere link automatically within a <span> tag
   at the end of posts that have more than a minimum length.  You can modify the plugin's
   sphere-related-content.php file to change that behavior.  There are instructions in that file,
   but here are the basics:

   If you NEVER want Sphere links inserted automatically, or if you want to decide where to place
   the link, rather than having it appended to your posts automatically, then set $auto_sphereit to
   FALSE below and follow the instructions in the readme to customize the look and placement of the link.

   If you want Sphere links inserted into ALL of your posts, then set $sphereit_threshold to FALSE below.

   You can always force a Sphere link to be inserted by including `<!--sphereit-->` in the text of a post.

   You can force a Sphere link NOT to be inserted by including `<!--nosphereit-->` in the text of your post.
 
   If you're not sure what to do, leave the settings below at their default value.

7. You can always force a Sphere link to be inserted into a post by putting
   `<!--sphereit-->` anywhere into the post's text.  That comment will not be displayed, but will force
   a Sphere link to be inserted for that post.

8. Conversely, You can keep a Sphere link from being inserted in a post by adding 
   `<!--nosphereit-->` to that post.  Again, this marker will not be displayed, but will prevent 
   a Sphere link from being shown for that post.

9. If you want to override the default placement of the link, you should:

   i) Turn off $auto_sphereit by setting 

         `$auto_sphereit = FALSE;`

      in sphere-related-content.php (search for auto_sphereit to find that line).

   ii) Put this tag anywhere with in a post context (either in your index, archive or single post templates):

	  `<?= get_sphereit_link( get_permalink(), $post->post_content ) ?>`

       If you want to wrap a block-level element around the Sphere link you can do something like this:

	  `<?= (enableSphereItLink($post->post_content) ? '<p class="sphere">'.get_sphereit_link(get_permalink(), $post->post_content).'</p>' : '') ?>`

       The enableSphereItLink() check makes sure that your <p> tags are only gong to show up if the Sphere link is turned on 
       for that post, as decided by the length of your post or your overrides.

       If you're doing your own block-level element like this, you'll want to define stules for in your CSS template, e.g.

	`
	  .sphere {
	     padding: 10px;
	     margin: 10px;
	     border-left: 2px solid red;
	   }	
	`

       Note these styles are an example only, you can go crazy with the styles and make it look just so in your blog.

       That's it, you're ready to discover Sphere Related Content.  Automatically.

== Requirements ==

1. Your WordPress theme must contain a call to the get_header() function

2. Your WordPress theme must contain a call to the the_content()function

== Support ==

Please contact Sphere: http://www.sphere.com/feedback

