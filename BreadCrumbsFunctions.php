<?php
/* The BreadCrumbs extension, an extension for providing an breadcrumbs
 * navigation to users.
 *
 * @file
 * @ingroup Extensions
 * @author Manuel Schneider <manuel.schneider@wikimedia.ch>, Tony Boyles <ABoyles@milcord.com>, Ryan Lane
 * @copyright © 2007 by Manuel Schneider, 2012 by Tony Boyles, Milcord llc
 * @licence GNU General Public Licence 2.0 or later
 */

if (!defined('MEDIAWIKI')) {
	echo("This file is an extension to the MediaWiki software and cannot be used standalone.\n");
	die();
}

function fnBreadCrumbsShowHook(&$article) {
	global $wgOut, $wgUser, $wgDefaultUserOptions, $wgBreadCrumbsShowAnons;

	$wluOptions = $wgUser -> getOptions();
	
	# Should we display breadcrumbs?
	if ((!$wgBreadCrumbsShowAnons && $wgUser -> isAnon()) || (!$wluOptions['breadcrumbs-showcrumbs'])) {
		return true;
	}

	# deserialize data from session into array:
	$m_BreadCrumbs = array();

	# if we have breadcrumbs, let's use them:
	if (isset($_SESSION['BreadCrumbs'])) {
		$m_BreadCrumbs = $_SESSION['BreadCrumbs'];
	}

	# cache index of last element:
	$m_count = count($m_BreadCrumbs) - 1;

	# Title string for the page we're viewing
	$title = $article -> getTitle() -> getPrefixedText();
	
	# Are there any Breadcrumbs to see?
	if ($m_count > -1){
		# Was this a page refresh and do we care?
		if (!($wgDefaultUserOptions['breadcrumbs-ignore-refreshes'] && 
			strcmp($title, $m_BreadCrumbs[$m_count]) == 0)) {
			if (!$wluOptions['breadcrumbs-filter-duplicates'] || !in_array($title, $m_BreadCrumbs)) {
				array_push($m_BreadCrumbs, $title);
			}
			# serialize data from array to session:
			$_SESSION['BreadCrumbs'] = $m_BreadCrumbs;
			# update cache:
			$m_count = count($m_BreadCrumbs) - 1;
		}
	# If there aren't any breadcrumbs, we still want to add to the current page to the list.
	} else {
		# add new page:
		array_push($m_BreadCrumbs, $title);
		# serialize data from array to session:
		$_SESSION['BreadCrumbs'] = $m_BreadCrumbs;
		# update cache:
		$m_count = count($m_BreadCrumbs) - 1;
	}
	
	# Build the breadcrumbs trail:
	#TODO: Fix edge case so Users can select 1 breadcrumb and it shows only the latest page
	$breadcrumbs = '';
	$max = min(array($wluOptions['breadcrumbs-numberofcrumbs'], count($m_BreadCrumbs)));
	for ($i = 1; $i <= $max; $i++) {
		$j = count($m_BreadCrumbs) - $i;
		$title = Title::newFromText($m_BreadCrumbs[$j]);
		if ($wluOptions['breadcrumbs-namespaces']){
			$breadcrumbs = Linker::link($title, $m_BreadCrumbs[$j]) . $breadcrumbs;
		} else {
			$breadcrumbs = Linker::link($title, $title->getText()) . $breadcrumbs;
		}
		if ($i < $max) {
			$breadcrumbs = ' ' . htmlspecialchars($wluOptions['breadcrumbs-delimiter']) . ' ' . $breadcrumbs;
		}
	}
	$breadcrumbs = htmlspecialchars($wluOptions['breadcrumbs-preceding-text']) . ' ' . $breadcrumbs;

	# Set up camp according to the user's choice
	switch($wluOptions['breadcrumbs-location']){
		case 0:
			$m_trail = $breadcrumbs.'<br />'.$wgOut -> getSubtitle();
			$wgOut -> setSubtitle($m_trail);
			break;
		case 1:
			$wgOut -> setSubtitle($breadcrumbs);
			break;
		case 2:
			$m_trail = $wgOut->getSubtitle() . '<br />' . $breadcrumbs;
			$wgOut -> setSubtitle($m_trail);
			break;
		case 3:
			$wgOut->prependHTML($breadcrumbs);
			break;
		#TODO: It would be awesome to have these cases working.
		/*case 4:
			$wgOut->setPageTitle($breadcrumbs);
			break;
		case 5:
			$wgOut->addWikiMsg("Breadcrumbs", $breadcrumbs);
			break;*/
	}

	# invalidate internal MediaWiki cache:
	$wgUser -> invalidateCache();
	# Must be done so that stale Breadcrumbs aren't cached into pages the user visits repeatedly.
	# This makes this a risky extension to run on a wiki which relies heavily on caching.

	# Return true to let the rest work:
	return true;
}

function fnBreadCrumbsAddPreferences( $user, $defaultPreferences ) {
	$defaultPreferences['breadcrumbs-showcrumbs'] = array(
		'type' => 'toggle',
		'section' => 'rendering/breadcrumbs',
		'label-message' => 'prefs-breadcrumbs-showcrumbs'
	);
	
	$defaultPreferences['breadcrumbs-namespaces'] = array(
		'type' => 'toggle',
		'section' => 'rendering/breadcrumbs',
		'label-message' => 'prefs-breadcrumbs-namespaces',
	);
	
	$defaultPreferences['breadcrumbs-filter-duplicates'] = array(
		'type' => 'toggle',
		'section' => 'rendering/breadcrumbs',
		'label-message' => 'prefs-breadcrumbs-filter-duplicates'
	);
	
	$defaultPreferences['breadcrumbs-location'] = array(
		'type' => 'select',
		'section' => 'rendering/breadcrumbs',
		'label-message' => 'prefs-breadcrumbs-location',
		'options' => array(
			'Before Subtitle' => 0,
			'Instead of Subtitle' => 1,
			'After Subtitle' =>2,
			'Before Article' => 3,
			/*'After Article' => 4,
			'In Header' => 5*/
         )
	);

	$defaultPreferences['breadcrumbs-numberofcrumbs'] = array(
		'type' => 'int',
		'min' => 1,
		'max' => 20,
		'section' => 'rendering/breadcrumbs',
		'label-message' => 'prefs-breadcrumbs-numberofcrumbs',
		'help' => wfMsgHtml( 'prefs-breadcrumbs-numberofcrumbs-max' ),
	);

	$defaultPreferences['breadcrumbs-preceding-text'] = array(
		'type' => 'text',
		'section' => 'rendering/breadcrumbs',
		'label-message' => 'prefs-breadcrumbs-preceding-text',
	);
		
	$defaultPreferences['breadcrumbs-delimiter'] = array(
		'type' => 'text',
		'section' => 'rendering/breadcrumbs',
		'label-message' => 'prefs-breadcrumbs-separator',
	);
	
	return true;
}
