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
 * @license https://www.gnu.org/copyleft/gpl.html GNU General Public License 2.0 or later
 */
if ( function_exists( 'wfLoadExtension' ) ) {
	wfLoadExtension( 'BreadCrumbs' );
	// Keep i18n globals so mergeMessageFileList.php doesn't break
	$wgMessagesDirs['BreadCrumbs'] = __DIR__ . '/i18n';
	wfWarn(
		'Deprecated PHP entry point used for the BreadCrumbs extension. ' .
		'Please use wfLoadExtension instead, ' .
		'see https://www.mediawiki.org/wiki/Extension_registration for more details.'
	);
	return;
} else {
	die( 'This version of the BreadCrumbs extension requires MediaWiki 1.29+' );
}

// Code below this is never reached and present for IDE convenience only

/** @var bool Whether to provide the links also for anonymous users */
$wgBreadCrumbsShowAnons = true;

/** @var bool Should users be allowed to configure BreadCrumbs Options? */
$wgBreadCrumbsAllowUPOs = true;

/** @var bool Whether to ignore page refreshes */
$wgBreadCrumbsIgnoreRefreshes = true;

/** @var bool Whether to rearrange history - Not yet used */
$wgBreadCrumbsRearrangeHistory = false;

/** @var bool Whether to link our breadcrumbs */
$wgBreadCrumbsLink = true;

/** @var string[] If you don't want certain Namespaces recorded, add them here */
$wgBreadCrumbsIgnoreNameSpaces = [];
