<?php
/*
Plugin Name: Favourite Posts
Plugin URI: http://www.ssdesigninteractive.com/blog/?p=104
Description: Mark posts as 'favourite'.  Based on <a href="http://wp-plugins.net/plugin/noteworthy/">Noteworthy</a>.&nbsp;&nbsp;&nbsp;      
Author: Sajid Saiyed
Version: 0.0.1 beta
Author URI: http://www.ssdesigninteractive.com/blog
Based on: Noteworthy Plugin by Jamie Saunders <jamie@webdeveloper.uk.com>
Noteworthy Plugin does not work with Wordpress 2.3. If you are using an older version of Wordpress, download Noteworthy from: [http://wp-plugins.net/plugin/noteworthy/]
*/

/*
License:
    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/


/***************************************************************************
 *  Plugin options
 ***************************************************************************/

$ssfav_options = Array(
	"set_text"			=>		"Set as favourite",		// link text to set post as noteworthy
	"unset_text"		=>		"Unset as Favourite",		// link text to unset post as noteworthy

	"user_level"		=>		10,			 				// user level required to set/unset posts as noteworthy (default is administrator = 10)

	"ssfav_cat"	=>		"Favourites", 				// category used to store noteworthy categories
	"cat_description"	=>		"Favourite entries." 		// category description
	);

/***************************************************************************
 *  Core methods
 ***************************************************************************/

function ssfav_Favourite($action, $postId) {
	if($action == "set") {
		ssfav_setFavourite($postId);
	} elseif ($action == "unset") {
		ssfav_unsetFavourite($postId);
	}
}

function ssfav_setFavourite($postId) {
	global $table_prefix;
	if(!ssfav_isFavourite($postId)) {
		$query = "INSERT INTO " . $table_prefix . "term_relationships SET object_id='" . mysql_escape_string($postId) . "', term_taxonomy_id='" .  mysql_escape_string(ssfav_getFavouriteCategoryId()) . "'";
		$result = mysql_query($query);
	}
}

function ssfav_unsetFavourite($postId) {
	global $table_prefix;	
	if(ssfav_isFavourite($postId)) {
		$query = "DELETE FROM " . $table_prefix . "term_relationships WHERE object_id='" . mysql_escape_string($postId) . "' && term_taxonomy_id='" .  mysql_escape_string(ssfav_getFavouriteCategoryId()) . "'";
		$result = mysql_query($query);
	}	
}


/***************************************************************************
 *  Helper methods
 ***************************************************************************/
 function confirm($msg)
{
echo "<script langauge=\"javascript\">alert(\"".$msg."\");</script>";
}//end function 

function ssfav_isFavourite($postId) {
	$favouriteCatId = ssfav_getFavouriteCategoryId();
	$postCats = ssfav_getPostCategoriesByPostId($postId);
	if(in_array($favouriteCatId, $postCats)) return true;
	return false;
}

function ssfav_getPostCategoriesByPostId($postId) {
	global $table_prefix;
	$categories = Array();
	$query = "SELECT term_taxonomy_id FROM " . $table_prefix . "term_relationships WHERE object_id='" . mysql_escape_string($postId) . "'";
	$result = mysql_query($query);
	
	
	while($row = mysql_fetch_object($result)) {
	//confirm($result );
		array_push($categories, $row->term_taxonomy_id);
	}
	$mycat = Array();
	$termid;
	foreach ($categories as &$cat) {
	    //$value = $value * 2;
	  
	    $termid = "SELECT term_id FROM " . $table_prefix . "term_taxonomy WHERE term_taxonomy_id=".$cat."";
	    $myresult = mysql_query($termid);
		while($row = mysql_fetch_object($myresult)) {
		     $mynewresult = $row->term_id;
		     array_push($mycat, $row->term_id);
		     // confirm($mynewresult );
		}
	   $finalCatName=Array();
	foreach ($mycat as &$mcat) {
	    //$value = $value * 2;
	  
	    $termname = "SELECT name FROM " . $table_prefix . "terms INNER JOIN " . $table_prefix . "term_taxonomy ON " . $table_prefix . "term_taxonomy.term_id = " . $table_prefix . "terms.term_id  WHERE " . $table_prefix . "term_taxonomy.term_id=" . $mcat . "";
	    $termnameresult = mysql_query($termname);
	    while($row = mysql_fetch_object($termnameresult)) {
	     $tehcatname = $row->name;
	     array_push($finalCatName, $row->tehcatname);
	     //confirm($finalCatName);
	     // confirm($mynewresult );
	     }
	}
	    
	    //$mycateg = "SELECT name FROM " . $table_prefix . "terms INNER JOIN " . $table_prefix . "term_taxonomy ON " . $table_prefix . "term_taxonomy.term_id = " . $table_prefix . "terms.term_id  WHERE " . $table_prefix . "term_taxonomy.term_id=" . $result . "";
	   // array_push($mycat, $mycateg);
	}
	return $mycat;
}

function ssfav_setFavouriteCategory() {
	global $wpdb, $ssfav_options;
		
	$cat_name= wp_specialchars($ssfav_options['ssfav_cat']);
	$cat_desc= wp_specialchars($ssfav_options['cat_description']);
	$id_result = $wpdb->get_row("SHOW TABLE STATUS LIKE '$wpdb->terms'");
	$cat_ID = $id_result->Auto_increment;
	
	
	
	$fav_db_version = "0.0.1";
	global $fav_db_version;
	 $table_name = $wpdb->prefix . "Favourite";
	 $table_nname = $table_name . "postcat";
	 if($id_result !=1){
		 if($wpdb->get_var("SHOW TABLES LIKE '$table_nname'") != $table_nname) {
			$wpdb->query("INSERT INTO $wpdb->terms (name, slug) VALUES ('$cat_name', '$cat_name');");		
			
			/*$wpdb->query("CREATE TABLE " . $table_nname . " (
			
				`rel_id` bigint(20) NOT NULL auto_increment,
				`post_id` bigint(20) NOT NULL default '0',
				 `category_id` bigint(20) NOT NULL default '0',
				  PRIMARY KEY  (`rel_id`),
				  KEY `post_id` (`post_id`, `category_id`)
			  );");*/
	
			  require_once(ABSPATH . 'wp-admin/upgrade-functions.php');
			$sajSql = $wpdb->get_results("SELECT * FROM $wpdb->terms WHERE name='$cat_name'");
			foreach ( $sajSql as $saj ) {
				$sajNew = $saj->term_id;
			}
			$wpdb->query("INSERT INTO $wpdb->term_taxonomy (term_id, taxonomy, description, parent, count) VALUES ('$sajNew', 'category', '$cat_desc', '0', '0');");
			
			  add_option("fav_db_version", $fav_db_version);
		}
	 }
	
	return $cat_ID;

	return;
}

function ssfav_getFavouriteCategoryId() {
	global $table_prefix, $ssfav_options;

	$query = "SELECT term_id FROM " . $table_prefix . "terms WHERE name='{$ssfav_options[ssfav_cat]}' LIMIT 1";
	$result = mysql_query($query);
	if(mysql_num_rows($result) > 0) {
		$row = mysql_fetch_object($result);
		return $row->term_id;
	}
	return ssfav_setFavouriteCategory();
}


/***************************************************************************
 *  Display methods
 ***************************************************************************/
function ssfav_favouriteLink() {
	global $user_level, $ssfav_options, $post;
		
	// get url of blog
	$blogUrl = get_bloginfo('url');
	if(substr($blogUrl, -1) == '/') $blogUrl = substr($blogUrl, 0, strlen($blogUrl)-1);	
	
	// check user level requirement is met
	get_currentuserinfo();

	if ($user_level != $ssfav_options['user_level']) return;
	
	// print link
	print '<span class="favouriteLink">';
	print '<a title="Toggle favourite status" href="'.$blogUrl.'/index.php?ssfav_action=';
	if(ssfav_isFavourite($post->ID)) { print "unset"; } else { print "set"; } 
	print '&ssfav_postId='.	$post->ID . '">';
	if(ssfav_isFavourite($post->ID)) { print $ssfav_options['unset_text']; } else { print $ssfav_options['set_text']; }
	print '</a>';
	print "</span>";
}

function ssfav_favouriteIcon() {
	global $post;
	if(ssfav_isFavourite($post->ID)) {
		print "<div class=\"notew\"><div class=\"favourite\"></div></div>"; 	
	}
	else {
		print "<div class=\"notew\"><div class=\"no_favourite\" id='fav_".$post->ID."'></div></div>"; 
	}
}

/***************************************************************************
 *  Listen for setter action
 ***************************************************************************/
if($_REQUEST['ssfav_action'] && $_REQUEST['ssfav_postId']) {
	ssfav_favourite($_REQUEST['ssfav_action'], $_REQUEST['ssfav_postId']);
}
//add_action('activate_ssdFavourite/favourite.php', 'ssfav_setFavouriteCategory');
?>