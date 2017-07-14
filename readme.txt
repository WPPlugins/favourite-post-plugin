=== Favourite Post plugin ===
Contributors: ssdesign
Donate link: 
Tags: favourite, post
Requires at least: 2.3
Tested up to: 2.3
Stable tag: 0.1

This plugin is a replica ot 'noteworthy' plugin. 

== Description ==

Using 'Favourite post plugin' you can mark any post as favourite and display favourite icon next to the post title. This is similar to 'noteworthy' plugin, but noteworthy does not work with WP 2.3, hence I modified it to work with the new taxonomy feature.

== Installation ==

This section describes how to install the plugin and get it working.

1. Extract the zip file and upload the 'ssdFavourite' folder to your wordpress plugins directory  ('/wp-content/plugins/').

1. You will need to make a couple of additions to index.php located in '/wp-contents/themes/[folder of theme used on your blog]'.

	Code-1:  Set/Unset link.  Paste the following line after the </h2> tag (heading of the post):

	<?php if(function_exists('ssfav_favouriteLink')) { ssfav_favouriteLink(); } ?>
	
	
	Code-2:  Favourite icon. Paste the following line after the '</small>' tag (or above the article content):

	<?php if(function_exists('ssfav_favouriteIcon')) { ssfav_favouriteIcon(); } ?>


	NOTE: 
	* These are guidlines only and the code can go where you wish as long as it remains in the post loop.
	* To display the Icon, you must use Code-2.
	* If you use only Code-1, only the set/unset link will appear, the icon will not be displayed.
	* Alternatively if you choose to set/unset favourites through Admin (as described below in USAGE section) then you can avoid Code-1 ans use only Code-2 for icon display.
	

1. You can activate the plugin as usual by navigating to the 'Plugins' page in Wordpress admin.

	e.g.

	* Upload `plugin-name.php` to the `/wp-content/plugins/` directory
	* Activate the plugin through the 'Plugins' menu in WordPress
	* Place `<?php do_action('plugin_name_hook'); ?>` in your templates

1. Add following CSS style to your theme:

	.favourite{
		width: 35px;
		height:28px;
		background: url('images/favourite.png') center center no-repeat;
		clear:both;
		left: 30px;
		position: relative;
	}
	.no_favourite{
		width: 35px;
		height:28px;
		clear:both;
		left: 30px;
		position: relative;
	}
	.notew{
		width: 35px;
		height:28px;
		clear:both;
		float: left;
	}


== Frequently Asked Questions ==

= What do I need to make this plugin work? =

The plugin will work straight out of the box, however there are a couple of config options you can change at the top of 'favourite.php'.
Most importantly the category name for storing your favourite posts (default category is 'Favourite' - this will be created automatically if it doesn't already exist).

= How to create Set/Unset favourite links? =

If you used Code-1. You will see a 'Set/Unset Favourite' link displayed under each post, simply click this link to toggle the favourite status for that post.

= How do I make a post favourite? =

you can simply add/remove the post from the favourite category in the Wordpress admin.  Once a post is set to Favourite a heart icon will appear under the post
indicating that it is of noteworthy status.  The div surrounding this image has a class of 'notew' to enable you to give it custom styling.  You can further modify the html
code spat out by editing the function named 'ssfav_favouriteIcon' near the bottom of favourite.php'.

= Where should I place the image of Favourite Icon? =

Copy the image from the images folder from the 'ssdFavourite/images' and place it in your '{YOUR THEME}/images' folder.


== Contact ==

Please feel free to contact me regarding this plugin by sending an e-mail to: Sajid Saiyed <sajid.saiyed@gmail.com>