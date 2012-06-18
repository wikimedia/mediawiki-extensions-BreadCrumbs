BreadCrumbs
===========

A Mediawiki Extension to provide a user-browsing history breadcrumb 
trail on wiki pages.


INSTALLING
--------------------------------------------------------------------------

Copy the BreadCrumbs directory into the extensions folder of your
MediaWiki installation. Then add the following lines to your
LocalSettings.php file (near the end):

    require_once( 'extensions/BreadCrumbs/BreadCrumbs.php' );


PARAMETERS/APPEARANCE
--------------------------------------------------------------------------

To customize the design of the breadcrumb trail, there are several user-
accessible options (default values given):

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

    # Text to appear before breadcrumbs
    $wgDefaultUserOptions['breadcrumbs-preceding-text'] = '';

Additionally, there are some configurations available to administrators

    # Whether to ignore page refreshes
    $wgDefaultUserOptions['breadcrumbs-ignore-refreshes'] = true;

    # Whether to provide the links also for anonymous users:
    $wgBreadCrumbsShowAnons = false;

Any of these may be overridden in LocalSettings.php.


BUGS, CONTACT
--------------------------------------------------------------------------

Write us on http://www.mediawiki.org/wiki/Extension_talk:BreadCrumbs and
we'll see what we can do for you.

Alternatively you can reach me (Tony Boyles) [by e-mail](mailto:ABoyles@milcord.com)

The description page of this extension can be found at:
http://www.mediawiki.org/wiki/Extension:BreadCrumbs


ATTRIBUTION
--------------------------------------------------------------------------

This software was originally written by Manuel Schneider. It was modified 
by Tony Boyles to add functionality for a project by
[Milcord llc.](http://milcord.com)