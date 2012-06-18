<?php
/* The BreadCrumbs extension, an extension for providing an breadcrumbs
 * navigation to users.
 *
 * @file
 * @ingroup Extensions
 * @author Manuel Schneider <manuel.schneider@wikimedia.ch>, Tony Boyles <ABoyles@milcord.com>
 * @copyright © 2007 by Manuel Schneider, 2012 by Tony Boyles
 * @licence GNU General Public Licence 2.0 or later
 */

if ( !defined( 'MEDIAWIKI' ) ) {
	echo( "This file is an extension to the MediaWiki software and cannot be used standalone.\n" );
	die();
}

# Default Options:

# $wgBreadCrumbsDelimiter - set the delimiter
$wgDefaultUserOptions['breadcrumbs-delimiter'] = '->';

# $wgBreadCrumbsCount - number of breadcrumbs to use
$wgDefaultUserOptions['breadcrumbs-numberofcrumbs'] = 5;

# Whether to provide breadcrumbs to users by default
$wgDefaultUserOptions['breadcrumbs-showcrumbs'] = true;

# Whether to show the breadcrumbs' namesoaces
$wgDefaultUserOptions['breadcrumbs-namespaces'] = true;

# Where to put the Breadcrumbs
$wgDefaultUserOptions['breadcrumbs-location'] = 3; #Before Article

# Whether to ignore pages that are already in breadcrumbs
$wgDefaultUserOptions['breadcrumbs-filter-duplicates'] = false;

# Whether to ignore page refreshes
$wgDefaultUserOptions['breadcrumbs-ignore-refreshes'] = true;

# Text to appear before breadcrumbs
$wgDefaultUserOptions['breadcrumbs-preceding-text'] = '';

# Whether to provide the links also for anonymous users:
$wgBreadCrumbsShowAnons = false;

# Register the Internationalization file
$wgExtensionMessagesFiles['Breadcrumbs'] = dirname( __FILE__ ) . '/BreadCrumbs.i18n.php';

# Register extension credits:
$wgExtensionCredits['parserhook'][] = array(
	'path'           => __FILE__,
	'name'           => 'BreadCrumbs',
	'version'		 => '0.2b',
	'author'         => array( 'Manuel Schneider', '[http://milcord.com Tony Boyles, Milcord llc]' ),
	'url'            => 'https://www.mediawiki.org/wiki/Extension:BreadCrumbs',
	'descriptionmsg' => 'breadcrumbs-desc',
);

# Showing and updating the breadcrumbs trail
# Hook when viewing article header:
$wgHooks['ArticleViewHeader'][] = 'fnBreadCrumbsShowHook';
$wgHooks['GetPreferences'][] = 'fnBreadCrumbsAddPreferences';

# Infrastructure
# Load the file containing the hook functions:
require_once( 'BreadCrumbsFunctions.php' );
