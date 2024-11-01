<?php
/*
********************************************************************************

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

********************************************************************************
*/
function svninfo_update_options($options)
{
	global $svninfoall;
	//This is the section where we add individual rules to single options (see checkbox part.)
	if ((!$options['repo_name']) || (!$options['repo_path'])) { $options['repo_name'] = 0; }
	if (substr($options['repo_path'], -1) == '/') { $options['repo_path'] = substr($options['repo_path'], 0, -1); }
	// End that section.
		while (list($option, $value) = each($options)) {
			if (get_magic_quotes_gpc()) 
				$value = stripslashes($value);
			$svninfoall[$option] = $value;
		}
	return $svninfoall;
}
function svninfo_get_owner()
{
	$user = posix_getpwuid(fileowner($_SERVER['SCRIPT_FILENAME']));
	return $user['name'];
}
if ($_POST["action"] == "saveconfiguration")
{
			svninfo_update_options($_REQUEST['svninfo']);
			update_option('svninfo',$svninfoall);

	echo '<div class="updated"><p><strong>SVN Informations settings updated.</strong></p></div>';
}
$svninfoall = get_option('svninfo');
if ($svninfoall['repo_name'])
	$repo_name1 = 'checked="checked"';
else
	$repo_name0 = 'checked="checked"';
?>

<div class="wrap">
	<h2>Subversion Informations configuration</h2>
	<form method="post" action="">
	<h3>Subversion Integration</h3>
	<p>Get informations of the current version straight from the SVN repository.</p>
	<table class="form-table">
		<tr valign="top">
			<th scope="row">Usage of &lt;repository&gt;</th>
			<td>
				<p><input id="svninfo[repo_name]" type="radio" name="svninfo[repo_name]" <?php echo($repo_name0); ?> value="0" />
				Use full paths like <code>[svn:&lt;element&gt;@/home/user/svn/repo]</code></p>
				<p><input id="svninfo[repo_name]" type="radio" name="svninfo[repo_name]" <?php echo($repo_name1); ?> value="1" />
				Use just repository name like <code>[svn:&lt;element&gt;@repo]</code>.<br />
				You have to set the absolute path to the SVN repositories in the box below.</p>
			</td>
		</tr>
		<tr valign="top">
			<th scope="row">Absolute path to SVN repositories (optional)</th>
			<td><input value="<?php echo($svninfoall[repo_path]); ?>" type="text" name="svninfo[repo_path]" id="svninfo[repo_path]" class="code" size="40" />
			<br />
			Suggestion: <code>/home/<?php echo(svninfo_get_owner()); ?>/svn</code> (example based on script owner)</td>
		</tr>
	</table>
	<h3>WebSVN Integration</h3>
	<p>Use WebSVN to offer the current version for download.</p>
	<table class="form-table">
		<tr valign="top">
			<th scope="row">Absolute URL to WebSVN</th>
			<td><input value="<?php echo($svninfoall[websvn_url]); ?>" type="text" name="svninfo[websvn_url]" id="svninfo[websvn_url]" class="code" size="40" />
			<br />
			Suggestion: <code>http://<?php echo($_SERVER['SERVER_NAME']); ?>/websvn</code> (example based on current address)</td>
		</tr>
	</table>

	<p class="submit">
		<input type="hidden" name="action" value="saveconfiguration">
		<input type="submit" name="Submit" value="Save Changes" class="button" />
	</p>
	</form>

</div>
