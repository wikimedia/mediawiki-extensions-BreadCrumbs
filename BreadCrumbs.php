<?php

/* The BreadCrumbs extension, an extension for providing a breadcrumbs navigation
 * to users.
 *
 * @link https://www.mediawiki.org/wiki/Extension:BreadCrumbs Documentation
 * @file BreadCrumbs.php
 * @ingroup Extensions
 * @defgroup BreadCrumbs
 * @package MediaWiki
 * @author Manuel Schneider <manuel.schneider@wikimedia.ch>, Tony Boyles <ABoyles@milcord.com>
 * @copyright © 2007 by Manuel Schneider, 2012 by Tony Boyles, Milcord llc
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License 2.0 or later
 */

if ( !defined( 'MEDIAWIKI' ) ) {
	echo( "This file is an extension to the MediaWiki software and cannot be used standalone.\n" );
	die();
}

# Register extension credits:
$wgExtensionCredits['other'][] = array(
	'path' => __FILE__,
	'name' => 'BreadCrumbs',
	'descriptionmsg' => 'breadcrumbs-desc',
	'version' => '0.4.0',
	'author' => array( 'Manuel Schneider', '[http://milcord.com Tony Boyles, Milcord llc]' ),
	'url' => 'https://www.mediawiki.org/wiki/Extension:BreadCrumbs',
);

# Default Options:

# Whether to provide breadcrumbs to users by default
$wgDefaultUserOptions['breadcrumbs-showcrumbs'] = true;

# Whether to provide the links also for anonymous users:
$wgBreadCrumbsShowAnons = true;

# $wgBreadCrumbsAllowUPOs - Should users be allowed to configure BreadCrumbs Options?
$wgBreadCrumbsAllowUPOs = true;

# $wgBreadCrumbsDelimiter - set the delimiter
$wgDefaultUserOptions['breadcrumbs-delimiter'] = '>';

# $wgBreadCrumbsCount - number of breadcrumbs to use
$wgDefaultUserOptions['breadcrumbs-numberofcrumbs'] = 5;

# Whether to show the breadcrumbs' namespaces
$wgDefaultUserOptions['breadcrumbs-namespaces'] = true;

# Whether to ignore pages that are already in breadcrumbs
$wgDefaultUserOptions['breadcrumbs-filter-duplicates'] = false;

# Whether to ignore page refreshes
$wgBreadCrumbsIgnoreRefreshes = true;

# Whether to rearrange history - Not yet used
$wgBreadCrumbsRearrangeHistory = false;

# Whether to link our breadcrumbs
$wgBreadCrumbsLink = true;

# Text to appear before breadcrumbs
$wgDefaultUserOptions['breadcrumbs-preceding-text'] = '';

# If you don't want certain Namespaces recorded, add them here:
$wgBreadCrumbsIgnoreNameSpaces = array();

# Hooks:

# Load BreadCrumbs when viewing article header
$wgHooks['BeforePageDisplay'][] = 'fnBreadCrumbsShowHook';

# When presenting options to users, add BreadCrumbs configurations
$wgHooks['GetPreferences'][] = 'fnBreadCrumbsAddPreferences';


# Infrastructure:

# Register the internationalization file
$wgMessagesDirs['Breadcrumbs'] = __DIR__ . '/i18n';
$wgExtensionMessagesFiles['Breadcrumbs'] = dirname( __FILE__ ) . '/BreadCrumbs.i18n.php';

# Load the file containing the hook functions:
require_once( 'BreadCrumbsFunctions.php' );

# Resource loader
$wgResourceModules['ext.breadCrumbs'] = array(
	'styles' => 'BreadCrumbs.css',
	'localBasePath' => dirname( __FILE__ ),
	'remoteExtPath' => 'BreadCrumbs'
);

