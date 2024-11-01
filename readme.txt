=== Subversion Informations ===
Contributors: ClydeFrog
Tags: svn, subversion, info, informations
Requires at least: 2.0
Tested up to: 4.0
Stable tag: 0.7.1

A plugin designed to provide informations about local SVN repositories.

== Description ==

Subversion Informations is a plugin for WordPress, designed to provide informations about local SVN repositories.
It can make use of WebSVN to offer the current version as a direct download.

To display information in your postings, you can make use of one of the new [svn:&lt;element&gt;@&lt;repository&gt;] tags.

Features

*   Informations about current revision, author, date and log message
*   Admin panel for easy usage

See it in action on [my site](http://bitrage.eu/scripts/bitlbee-manager/ "bitrage.eu"). (Sorry, text is german only... but you get the catch!)

== Installation ==

To install the plugin follow the listed steps:

1. Download the package
2. Decompress the archive and upload it to /wp-content/plugins/ on your webspace
3. Activate the plugin through the 'Plugins' menu in WordPress
4. Change the settings (Settings -> SVN Informations) according to your machine setup
5. For the full experience you have to install WebSVN and add your repositories (this provides the download ability)

== Usage ==

To display information about a SVN repository in one of your texts you just have to insert the [svn:&lt;element&gt;@&lt;repository&gt;] tag in the desired spot.

The element can be one of these:

*   *head* for the current revision
*   *author* for the username of the last contributer
*   *date* for the actual time of the last change
*   *log* for the user provided message
*   *box* for a predefined box containing all four of the above along with a download link (using WebSVN)

The repository can be either a full path (i.e. /home/user/svn/repo) or just the name of the repository (i.e. repo).
This of course is according to the settings in the admin panel where you have to set the base path of your repositories for the name-method to work.

Example:
`The current version of my program is [svn:head@/home/user/svn/program].`
or
`The current version of my program is [svn:head@program].`
This is only working when the svn base path is set to /home/user/svn in the admin panel.

As of version 0.7 there are additional attributes which you can set for each repository inside the tag: 

*   [svn:&lt;element&gt;@&lt;category&gt;*&lt;repository&gt;] (With a specified WebSVN category. *This also works with the full path tag.*)
*   [svn:&lt;element&gt;@&lt;repository&gt;/path/path/] (A subdirectory for the WebSVN download. *This does not work with full path tag.*)
*   [svn:&lt;element&gt;@&lt;category&gt;*&lt;repository&gt;/path/path/] (Or a combination of both...)

Please note that in version 0.7.1 the separator for category and repository changed from a dot (.) to an asterisk (*) to allow paths with dots in it. (Bugfix for Josh, thanks for reporting!)

If you want to show the full box, you better insert it between paragraphs and not in the middle of your text, as it will bust your format.
Also it is a good idea to use a wider theme than the default one to get the best formated box.
The result is shown in the screenshot section.

== Screenshots ==

1. Example text with every five elements
2. Admin panel

== Changelog ==

= 0.7.1 =
* The separator for category and repository changed from a dot (.) to an asterisk (*) to allow paths with dots in it. (Bugfix for Josh, thanks for reporting!)

= 0.7 =
* Added possibility to specify WebSVN category and subdirectory for each svn tag.

= 0.6 =
* First public version.

== Frequently Asked Questions ==

*   Q: Is the plugin compatible with WordPress 3.0?
*   A: As far as I can see it is. (Tested with WP 3.0.1)

*   Q: Where can I report bugs?
*   A: Feel free to leave a comment on my [blog](http://bitrage.eu/wordpress-plugins/subversion-informations/ "bitrage.eu").

== Limitations ==

*   The Plugin is limited quite a bit in its current form, as there is only support for the current (HEAD) revision.
*   Also the use of WebSVN is mandatory to get a working download link.
*   Subdirectory download is only possible if the full path tag isn't in use.
