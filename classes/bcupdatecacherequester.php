<?php
/**
 * File containing the BCUpdateCacheRequester class.
 *
 * @copyright Copyright (C) 1999 - 2011 Brookins Consulting. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2 (or later)
 * @version //autogentag//
 * @package bcupdatecache
 */

class BCUpdateCacheRequester
{
    /**
     * Default class constants
     *
     * @param bool REQUEST_LIMIT eZContentObjectTreeNode::subTreeByNodeID fetch limit parameter
     * @param bool REQUEST_DEBUG false no debug, true debug included
     */
    const REQUEST_LIMIT = 1;
    const REQUEST_DEBUG = false;
    
    /**
     * Default method. Performs request for page based on paramters
     *
     * @param array $parameters Options used by the object method
     * @return void
     * @static
     */
    static function requestPage( &$parameters )
    {
        if (!isset( $parameters[ 'total_count' ] ))
        {
            $parameters['start_time'] = time();

            $count = eZFunctionHandler::execute( 'content','tree_count', 
                    array( 'parent_node_id' => $parameters[ 'node' ] ) );

            $parameters['total_count'] = $count;
            $parameters['created_count'] = 0;

            if ( BCUpdateCacheRequester::REQUEST_DEBUG )
                BCUpdateCacheRequester::file_put_contents( '/tmp/loadlog.html', 'Starting '.time()."\n", 'w' );
        }

        $fetchParameters = array( 'AsObject' => false, 
                                  'Limit' => BCUpdateCacheRequester::REQUEST_LIMIT,
                                  'Offset' => $parameters[ 'created_count' ] );
        $nodes = eZContentObjectTreeNode::subTreeByNodeID( $fetchParameters , $parameters[ 'node' ] );

        $total = 0;
        $success = 0;

        foreach ( $nodes as $node )
        {
            $url = $parameters['base_url'] . "/content/view/full/" . $node[ 'node_id' ];
            $res = BCUpdateCacheRequester::doRequest( $url );
            $total++;

            if ( $res )
                $success++;
        }
        $parameters['created_count'] = $parameters['created_count'] + $total;
    }

    /**
     * Performs actual curl request for page
     *
     * @param string $url Url of page to be requested via curl
     * @return bool true if successful, false otherwise
     * @static
     */
    static function doRequest( $url )
    {

        if ( extension_loaded( 'curl' ) )
        {
            $ch = curl_init( $url );
            curl_setopt( $ch, CURLOPT_HEADER, 0 );
            curl_setopt( $ch, CURLOPT_FAILONERROR, 1 );
            curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );

            $ini = eZINI::instance();
            $proxy = $ini->hasVariable( 'ProxySettings', 'ProxyServer' ) ? $ini->variable( 'ProxySettings', 'ProxyServer' ) : false;
            if ( $proxy )
            {
                curl_setopt ( $ch, CURLOPT_PROXY , $proxy );
                $userName = $ini->hasVariable( 'ProxySettings', 'User' ) ? $ini->variable( 'ProxySettings', 'User' ) : false;
                $password = $ini->hasVariable( 'ProxySettings', 'Password' ) ? $ini->variable( 'ProxySettings', 'Password' ) : false;
                if ( $userName )
                {
                    curl_setopt ( $ch, CURLOPT_PROXYUSERPWD, "$userName:$password" );
                }
            }
            $result = curl_exec( $ch );
            if ( BCUpdateCacheRequester::REQUEST_DEBUG )
                BCUpdateCacheRequester::file_put_contents( '/tmp/loadlog.html', "\n$url\n" . $result, 'a' );
            if ( !$result )
            {
                return false;
            }
            curl_close( $ch );
            return true;
        }
        else 
        {
            $res = file_get_contents( $url );
            if ( BCUpdateCacheRequester::REQUEST_DEBUG )
                BCUpdateCacheRequester::file_put_contents( '/tmp/loadlog.html',  "\n$url\n" . $result, 'a' );
            if ( $res )
                return true;
        }
        return false;
    }

    /**
     * Store file contents on disk
     *
     * @param string $filename Filepath to store contents
     * @param mixed $contents Contents of file to write
     * @param string $mode File write mode to use
     * @return void
     * @static
     */
    static function file_put_contents( $filename, $contents, $mode = 'w' )
    {
        $fp = fopen( $filename, $mode );
        fwrite( $fp, $contents );
        fclose( $fp );
    }
}

?>
