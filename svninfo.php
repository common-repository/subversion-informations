<?php
/*
 Plugin Name: Subversion Informations
 Plugin URI: http://bitrage.eu/wordpress-plugins/subversion-informations/
 Description: Transforms [svn:&lt;element&gt;@&lt;repository&gt;] tags to information regarding the SVN repository you specified with &lt;repository&gt;
 Version: 0.7.1
 Author: Felix Heide
 Author URI: http://bitrage.eu/

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
function svninfo_get_header ($shortrepo, $element = "log", $revision = "HEAD")
{
	$repository = "file://".$shortrepo."/db/revprops/";
  if (is_dir($repository))
  {
  	if ($revision == "HEAD")
   		$revision = svninfo_get_head($shortrepo);
   	$rev = file($repository.$revision);
		foreach ($rev as $key => $value)
		{
			if ((substr($value, 0, 4) == 'svn:') && ($part == 'K'))
			{
				$ele = trim($value);
				$infos[$ele] = '';
			}
			elseif (preg_match('@((K \d+)|(V \d+))@is', trim($value)))
				$part = substr($value, 0, 1);
			elseif (($part == 'V') && (trim($value) != 'END'))
				$infos[$ele] .= $value;
		}
   	$return = nl2br(substr($infos['svn:'.$element], 0, -1));
   	if ($element == "date")
   	{
   		$return = date("c",strtotime($return));
    	sscanf($return, "%d-%d-%dT%d:%d:%d.", $y, $mo, $d, $h, $m, $s);

    	$mo = substr("00".$mo, -2);
    	$d =  substr("00".$d, -2);
    	$h =  substr("00".$h, -2);
    	$m =  substr("00".$m, -2);
    	$s =  substr("00".$s, -2);
         
    	$return = "$y-$mo-$d $h:$m:$s ".date("T");
    }
  }
  else
  	exit("Archiv nicht vorhanden: ".$repository);
  return $return;
}
function svninfo_get_head ($shortrepo)
{
	$repository = "file://".$shortrepo."/db/revprops/";
	if (is_dir($repository))
  {
		$revs = scandir($repository);
 		$revision = count($revs) - 3;
  }
  else
  	exit("Archiv nicht vorhanden: ".$repository);
  return $revision;
}
function svninfo_replace_tags ($text)
{
	$svninfoall = get_option('svninfo');
	$pattern = '@(\[svn\:(?P<element>.*?)\@(?P<cat>.*?\*)?(?P<repo>/?.*?)(?P<path>/.*/?)?\])@is';
	if (preg_match_all($pattern, $text, $matches))
	{
		for ($i = 0; $i < count($matches[0]); $i++)
		{
			$element = $matches['element'][$i];
			if ($svninfoall[repo_name])
			{
				$repository = $svninfoall[repo_path].'/'.$matches['repo'][$i];
				$reponame = $matches['repo'][$i];
			}
			else
			{
				$repository = $matches['repo'][$i].$matches['path'][$i];
				$matches['path'][$i] = null;
				$archive = split("/", $repository);
				$reponame = array_pop($archive);
				if ($reponame == null)
					$reponame = array_pop($archive);
			}
			if ($matches['path'][$i])
				$path = $matches['path'][$i];
			else
				$path = "/";
			$category = str_replace('*', '.', $matches['cat'][$i]);
			if (is_dir($repository))
			{
				switch ($element)
				{
					case "head":
						$info = svninfo_get_head($repository);
						break;
					case "author":
						$info = svninfo_get_header($repository,"author");
						break;
					case "date":
						$info = svninfo_get_header($repository,"date");
						break;
					case "log":
						$info = svninfo_get_header($repository);
						break;
					case "box":
						$info = '<br /><div style="position: relative; float: left; background: #eee; -moz-border-radius: 10px; -khtml-border-radius: 10px;">'.PHP_EOL;
						$info .= '<div style="margin-right:148px; min-width:330px; min-height:160px; float: left;">'.PHP_EOL;
						$info .= '<div style="padding: 10px;"><strong>'.the_title('', '', FALSE).'</strong></div>'.PHP_EOL;
						$info .= '<div style="position:absolute; left:0px; width:110px; padding: 10px;">Current Revision:<br />Last Author:<br />Last Edit:<br />Last Changes:</div>'.PHP_EOL;
						$info .= '<div style="margin-left:120px; float:left; padding: 10px;">'.svninfo_get_head($repository).'<br />';
						$info .= svninfo_get_header($repository,"author").'<br />'.svninfo_get_header($repository,"date").'<br />'.svninfo_get_header($repository).'</div>'.PHP_EOL.'</div>'.PHP_EOL;
						$info .= '<div style="position:absolute; width:128px; right:12px; padding: 10px;"><a style="border-bottom: none" href="'.$svninfoall[websvn_url].'/dl.php?repname='.$category.$reponame.'&path='.$path.'&rev=0&isdir=1">';
						$info .= '<img src="/wp-content/plugins/subversion-informations/download.png" alt="Download jetzt!" /></a></div>'.PHP_EOL.'</div>'.PHP_EOL.'<br style="clear: both;" /><br />'.PHP_EOL;
						break;
				}
			}
			else
				$info = '<div style="background: red; color: white;"><strong>SVN Informations: No such dir "'.$repository.'"</strong></div>';
			$text = str_replace($matches[0][$i], $info, $text);
		}
	}
	return $text;
}


function svninfo_insert_options()
{
    add_submenu_page('options-general.php', 'Subversion Informations Settings', 'SVN Informations', 8, 'subversion-informations/svninfo_admin.php');
}
add_action('admin_menu', 'svninfo_insert_options');

add_filter('the_content', 'svninfo_replace_tags');
add_filter('the_excerpt', 'svninfo_replace_tags');
?>