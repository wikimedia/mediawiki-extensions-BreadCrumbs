<?php

/* The BreadCrumbs extension, an extension for providing a breadcrumbs navigation
 * to users.
 *
 * @file BreadCrumbsFunctions.php
 * @ingroup BreadCrumbs
 * @author Manuel Schneider <manuel.schneider@wikimedia.ch>, Tony Boyles <ABoyles@milcord.com>
 * @copyright © 2007 by Manuel Schneider, 2012 by Tony Boyles, Milcord llc
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License 2.0 or later
 */

if ( !defined( 'MEDIAWIKI' ) ) {
	echo( "This file is an extension to the MediaWiki software and cannot be used standalone.\n" );
	die();
}

/**
 * @param $out OutputPage
 * @param $parserOutput ParserOutput
 * @return bool
 */
function fnBreadCrumbsShowHook( $out, $parserOutput ) {
	global $wgUser, $wgRequest;
	global $wgBreadCrumbsShowAnons, $wgBreadCrumbsIgnoreRefreshes,
		$wgBreadCrumbsLink, $wgBreadCrumbsIgnoreNameSpaces;

	$wluOptions = $wgUser->getOptions();

	# Should we display breadcrumbs?
	if ( ( !$wgBreadCrumbsShowAnons && $wgUser->isAnon() ) || ( !$wluOptions['breadcrumbs-showcrumbs'] ) ) {
		return true;
	}

	# If we are Anons and should see breadcrumbs, but there's no session, let's start one so we can track from page-to-page
	if ( session_id() === "" ) {
		wfSetupSession();
	}

	# Get our data from $_SESSION:
	$m_BreadCrumbs = $wgRequest->getSessionData( 'BreadCrumbs' );

	# if we have breadcrumbs, let's use them:
	if ( $m_BreadCrumbs === null ) {
		$m_BreadCrumbs = array();
	}

	# cache index of last element:
	$m_count = count( $m_BreadCrumbs );

	# Title string for the page we're viewing
	$title = $out->getTitle()->getPrefixedText();

	# Are there any Breadcrumbs to see?
	if ( $m_count > 0 ) {
		# Was this a page refresh and do we care?
		if ( !( $wgBreadCrumbsIgnoreRefreshes && strcmp( $title, $m_BreadCrumbs[$m_count - 1] ) == 0 ) ) {
			if ( !$wluOptions['breadcrumbs-filter-duplicates'] || !in_array( $title, $m_BreadCrumbs ) ) {
				array_push( $m_BreadCrumbs, $title );
			}
			# serialize data from array to session:
			$wgRequest->setSessionData( "BreadCrumbs", $m_BreadCrumbs );
		}
		# If there aren't any breadcrumbs, we still want to add to the current page to the list.
	} else {
		# add new page:
		array_push( $m_BreadCrumbs, $title );
		# serialize data from array to session:
		$wgRequest->setSessionData( "BreadCrumbs", $m_BreadCrumbs );
	}

	# Build the breadcrumbs trail:
	$breadcrumbs = '';
	$max = min( array( $wluOptions['breadcrumbs-numberofcrumbs'], count( $m_BreadCrumbs ) ) );
	for ( $i = 1; $i <= $max; $i++ ) {
		$j = count( $m_BreadCrumbs ) - $i;
		$title = Title::newFromText( $m_BreadCrumbs[$j] );
		if ( !in_array( $title->getNsText(), $wgBreadCrumbsIgnoreNameSpaces ) ) {
			if ( $wgBreadCrumbsLink ) {
				# For whatever reason, the Linker doesn't play nice in Versions before 1.18.0...
				if ( version_compare( SpecialVersion::getVersion(), '1.18.0' ) > -1 ) {
					if ( $wluOptions['breadcrumbs-namespaces'] ) {
						$breadcrumb = Linker::link( $title, $m_BreadCrumbs[$j] );
					} else {
						$breadcrumb = Linker::link( $title, $title->getText() );
					}
				} elseif ( $wluOptions['breadcrumbs-namespaces'] ) {
					$breadcrumb = '<a href="' . $title->getFullURL()
						. '" title="' . $m_BreadCrumbs[$j] . '">'
						. $m_BreadCrumbs[$j] . '</a>';
				} else {
					$breadcrumb = '<a href="' . $title->getFullURL()
						. '" title="' . $title->getText() . '">'
						. $title->getText() . '</a>';
				}
			} elseif ( $wluOptions['breadcrumbs-namespaces'] ) {
				$breadcrumb = $m_BreadCrumbs[$j];
			} else {
				$breadcrumb = $title->getText();
			}
			$breadcrumbs = $breadcrumb . $breadcrumbs;
			if ( $i < $max ) {
				$breadcrumbs = ' ' . htmlspecialchars( $wluOptions['breadcrumbs-delimiter'] ) . ' ' . $breadcrumbs;
			}
		}
	}
	$breadcrumbs = '<div id="breadcrumbs">'
		. htmlspecialchars( $wluOptions['breadcrumbs-preceding-text'] )
		. ' ' . $breadcrumbs . '</div>';

	# Set up that styling...
	$out->addModuleStyles( 'ext.breadCrumbs' );

	# And add our BreadCrumbs!
	$out->prependHTML( $breadcrumbs );

	# Finally, invalidate internal MediaWiki cache:
	$wgUser->invalidateCache();
	# Must be done so that stale Breadcrumbs aren't cached into pages the user visits repeatedly.
	# This makes this a risky extension to run on a wiki which relies heavily on caching.

	# Return true to let the rest work.
	return true;
}

/**
 * @param $user User
 * @param $defaultPreferences array
 * @return bool
 */
function fnBreadCrumbsAddPreferences( $user, &$defaultPreferences ) {
	global $wgBreadCrumbsAllowUPOs;

	if ( $wgBreadCrumbsAllowUPOs ) {
		$defaultPreferences['breadcrumbs-showcrumbs'] = array(
			'type' => 'toggle',
			'section' => 'rendering/breadcrumbs',
			'label-message' => 'prefs-breadcrumbs-showcrumbs' );

		$defaultPreferences['breadcrumbs-namespaces'] = array(
			'type' => 'toggle',
			'section' => 'rendering/breadcrumbs',
			'label-message' => 'prefs-breadcrumbs-namespaces', );

		$defaultPreferences['breadcrumbs-filter-duplicates'] = array(
			'type' => 'toggle',
			'section' => 'rendering/breadcrumbs',
			'label-message' => 'prefs-breadcrumbs-filter-duplicates' );

		$defaultPreferences['breadcrumbs-numberofcrumbs'] = array(
			'type' => 'int',
			'min' => 1,
			'max' => 20,
			'section' => 'rendering/breadcrumbs',
			'size' => 2,
			'maxlength' => 2,
			'label-message' => 'prefs-breadcrumbs-numberofcrumbs',
			'help-message' => 'prefs-breadcrumbs-numberofcrumbs-max' );

		$defaultPreferences['breadcrumbs-preceding-text'] = array(
			'type' => 'text',
			'section' => 'rendering/breadcrumbs',
			'size' => 34,
			'maxlength' => 30,
			'label-message' => 'prefs-breadcrumbs-preceding-text',
			'help-message' => 'prefs-breadcrumbs-preceding-text-max' );

		$defaultPreferences['breadcrumbs-delimiter'] = array(
			'type' => 'text',
			'section' => 'rendering/breadcrumbs',
			'size' => 2,
			'maxlength' => 2,
			'label-message' => 'prefs-breadcrumbs-separator',
			'help-message' => 'prefs-breadcrumbs-separator-max' );
	}

	return true;
}
