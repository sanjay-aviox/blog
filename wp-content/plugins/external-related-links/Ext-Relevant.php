<?php
/*
Plugin Name: External related links Easy-to-use Version
Plugin URI: http://URI_Of_Page_Describing_Plugin_and_Updates
Description: You can use this plugin to add external related links to your blog.
Version: 1.1
Author: Duan Zhiyan
Author URI: http://dzy.jiachunqiu.net
*/
/*  Copyright YEAR  PLUGIN_AUTHOR_NAME  (email : dzy0451@gmail.com)

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
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

function Ext_install()
{
	global $wpdb;

	$table_name = $wpdb->prefix . 'ext_relevant';

	$cur_version = "1.0";

	if($wpdb->get_var("show tables like '$table_name'") != $table_name)
	{
		$sql = "CREATE TABLE ". $table_name . " (
			id int(11) NOT NULL AUTO_INCREMENT,
			keyword varchar(255) NOT NULL,
			text varchar(255) NOT NULL,
			url varchar(255) NOT NULL,
			PRIMARY KEY id(id)
		)ENGINE=MyISAM  DEFAULT CHARSET=utf8;";

		require_once(ABSPATH . "wp-admin/includes/upgrade.php");
		dbDelta($sql);

		$sql = "insert into ". $table_name ."(keyword, text, url) values('hotel', 'Hotels Combined', 'http://www.hotelscombined.com')";

		add_option('Ext_relevant_version', $cur_version);
	}

	$pre_version = get_option('Ext_relevant_version');

	if($pre_version != $cur_version)
	{
		update_option('Ext_relevant_version', $cur_version);
	}
}

function Ext_related_filter($content)
{
	$link = Ext_related(5);
	if($link == false)
	{
		return $content;
	}
	$content .= '<hr/>';
	$content .= '<div class="Ext_related_links">';
	$content .= '<h2>External Related Links</h2>';
	$content .= $link;
	$content .= '</div>';
	return $content;
}

function Ext_related_links($number)
{
	echo Ext_related($number);
}

function Ext_related($number)
{
	global $wpdb;
	global $post;

	$out = '';

	$sql = "select * from ". $wpdb->prefix . "ext_relevant";

	$result = $wpdb->get_results($sql);

	$links = array();

	foreach($result as $row)
	{
		if(preg_match("/". $row->keyword. "/i", $post->post_content))
		{
			$links[] = $row;
		}
	}

	$out .= '<ul>';
	if(count($links) < 1)
	{
		return false;
	}
	for($i = 0; $i < $number; $i++)
	{
		if(!$links[$i])
			break;
		$out .= '<li><a href="'. $links[$i]->url .'">'. $links[$i]->text .'</a></li>';
	}
	$out .= '</ul>';
	return $out;
}

register_activation_hook(__FILE__,'Ext_install');

add_action('admin_menu', 'Ext_panel');
add_filter('the_content', 'Ext_related_filter');

function Ext_panel()
{
	add_submenu_page("edit.php", "External Relevant Panel", "Ext-relevant", 8, "Ext-panel", "Ext_panel_page");

}
function Ext_panel_page()
{
	global $wpdb;

	if(isset($_POST['ext_type']))
	{
		if($_POST['ext_type'] == "Add"){
			$sql = "insert into ". $wpdb->prefix ."ext_relevant(keyword, text, url) values('". $_POST['keyword'] ."', '". $_POST['text'] ."', '". $_POST['url'] ."')";
		}else{
			$sql = "update ". $wpdb->prefix ."ext_relevant set keyword='". $_POST['keyword'] ."', text='". $_POST['text'] ."', url='". $_POST['url'] ."' where id='". $_POST['kid'] ."'";
		}
		$wpdb->query($sql);
	}
	if(isset($_POST['ext_submit']) && $_POST['ext_submit'] == "Delete")
	{
		$sql = "delete from ". $wpdb->prefix ."ext_relevant where id=". $_POST['kid'];
		$wpdb->query($sql);
	}

	$sql = "select * from ". $wpdb->prefix ."ext_relevant order by keyword asc";
	$results = $wpdb->get_results($sql);
?>
<div class="wrap">
	<h2>Keyword List</h2>
	<table class="widefat">
		<thead>
			<tr><th>ID</th><th>Keyword</th><th>Link Text</th><th>Link URL</th><th>Action</th></tr>
		</thead>
		<tbody>
<?php
	foreach($results as $row){
		echo "<tr><td>$row->id</td><td>$row->keyword</td><td>$row->text</td><td>$row->url</td><td>";
		echo "<form action=\"\" method=\"post\">";
		echo "<input type=\"hidden\" name=\"kid\" value=\"$row->id\"/>";
		echo "<input type=\"submit\" name=\"ext_submit\" value=\"Update\" />";
		echo "<input type=\"submit\" name=\"ext_submit\" value=\"Delete\" />";
		echo "</form>";
		echo "</td></tr>";
	}
?>
		</tbody>
	</table>

	<hr/>

	<h2>Add / Update Keywords</h2>
	<table class="widefat">
		<form action="" method="post">
<?php
	if($_POST['ext_submit'] == "Update")
	{
		echo "<input type=\"hidden\" name=\"ext_type\" value=\"Update\" />";
		$result = $wpdb->get_results('select * from '. $wpdb->prefix .'ext_relevant where id='. $_POST['kid']);
		$item = $result[0];
	}else{
		echo "<input type=\"hidden\" name=\"ext_type\" value=\"Add\" />";
	}
	echo "<input type=\"hidden\" name=\"kid\" value=\"". $_POST['kid'] ."\" />";
?>
		<tbody>
			<tr><td>Keyword</td><td><input type="text" name="keyword" value="<?php echo $item->keyword;?>"/></td></tr>
			<tr><td>Text</td><td><input type="text" name="text" value="<?php echo $item->text;?>" /></td></tr>
			<tr><td>URL</td><td><input type="text" name="url" value="<?php echo $item->url;?>" /></td></tr>
			<tr><td colspan="2"><input type="submit" value="Add / Update" /></td></tr>
		</tbody>
		</form>
	</table>
</div>
<?php
}
?>
