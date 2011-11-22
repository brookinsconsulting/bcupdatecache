<?php
/**
 * File containing the updatecache/cache module view.
 *
 * @copyright Copyright (C) 1999 - 2011 Brookins Consulting. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2 (or later)
 * @version //autogentag//
 * @package bcupdatecache
 */

/**
 * Default module parameters
 */
$Module = $Params['Module'];

/**
 * Fetch bcupdatecache.ini settings values required by module view
 */
$restrictCacheGenerationByRoleID = eZINI::instance( 'bcupdatecache.ini' )->variable( 'BCUpdateCacheSettings', 'UpdateCacheChecksForUserRole' ) == 'enabled' ? true : false;
$restrictCacheGenerationRoleID = is_numeric( eZINI::instance( 'bcupdatecache.ini' )->variable( 'BCUpdateCacheSettings', 'UpdateCacheChecksForRoleID' ) ) ? eZINI::instance( 'bcupdatecache.ini' )->variable( 'BCUpdateCacheSettings', 'UpdateCacheChecksForRoleID' ) : 2;

/**
 * Fetch current user and related attributes
 */
$currentUser = eZUser::currentUser();
$UserID = $currentUser->attribute( 'contentobject_id' );
$UserRoleIDList = $currentUser->attribute( 'role_id_list' );

/**
 * Give the unknown Anonymous users a frienly kernel access denied error
 * also give authenticated users without the required role permissions,
 * provided in the bcupdatecache.ini settings,
 * a frienly kernel access denied error as well.
 * Better safe than sorry in this regard
 */
if( $currentUser->isAnonymous() == true or ( $restrictCacheGenerationByRoleID == true && !in_array( $restrictCacheGenerationRoleID, $UserRoleIDList ) ) )
{
    return $Module->handleError( eZError::KERNEL_ACCESS_DENIED, 'kernel' );
}

/**
 * Default class instances
 */
$http = eZHTTPTool::instance();
$tpl = eZTemplate::factory();

/**
 * Fetch site.ini settings for Site url
 */
$ini = eZINI::instance();
$siteURL = 'http://' . $ini->variable( 'SiteSettings', 'SiteURL' );

/**
 * Request parameters
 */
$parameters = array( 'node' => 2,
                     'base_url' => $siteURL );

/**
 * Test for existance of post variable, 'Parameters'
 */
if ( $http->hasPostVariable( 'Parameters' ) )
{
    $parameters = $http->postVariable( 'Parameters' );
}

/**
 * Test for existance of post variable, 'ParametersSerialized'
 */
if ( $http->hasPostVariable( 'ParametersSerialized' ) )
{
    $parameters = unserialize( $http->postVariable( 'ParametersSerialized' ) );
}

/**
 * Validate request to update cache
 */
if ( $http->hasPostVariable( 'GenerateButton' ) )
{
    /**
     * Perform update cache requests
     */
    BCUpdateCacheRequester::requestPage( $parameters );

    /**
     * Calculate update cache request results on status page
     */
    if ( $parameters['created_count'] < $parameters['total_count'] )
    {
        $tpl->setVariable( 'parameters_serialized', serialize( $parameters ) );
        $parameters['time'] = time();
        $tpl->setVariable( 'parameters', $parameters );

        $Result['content'] = $tpl->fetch( 'design:updatecache/progress.tpl' );
        $Result['pagelayout'] = 'updatecache/progress_pagelayout.tpl';
        return;
    }
}

/**
 * Validate browse content tree to select root node to perform update cache requests upon
 */
if ( $http->hasPostVariable( 'BrowseButton' ) )
{
    return eZContentBrowse::browse( array( 'action_name' => 'BCUpdateCacheAddNode',
                                           'from_page' => '/updatecache/cache',
                                           'persistent_data' => array( 'ParametersSerialized' => serialize( $parameters ) ) ), $Module );
}

/**
 * Test for selected nodes from browse selection request
 */
if ( $http->hasPostVariable( 'SelectedNodeIDArray' ) &&
     !$http->hasPostVariable( 'BrowseCancelButton' ) )
{
    $selectedNodeIDArray = $http->postVariable( 'SelectedNodeIDArray' );
    $parameters['node'] = $selectedNodeIDArray[0];
}

/**
 * Pass module view default template parameters
 */
$tpl->setVariable( 'parameters', $parameters );

/**
 * Prepare module view content results for display to user
 */
$Result['content'] = $tpl->fetch( 'design:updatecache/main.tpl' );
$Result['path'] = array( array( 'url' => false,
                                'text' => 'Update Cache' ) );

?>
