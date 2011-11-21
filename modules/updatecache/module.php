<?php
/**
 * File containing the module.php module configuration file.
 *
 * @copyright Copyright (C) 1999 - 2011 Brookins Consulting. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2 (or later)
 * @version //autogentag//
 * @package bcupdatecache
 */

// Define module name
$Module = array( 'name' => 'Update Cache for eZ Publish' );

// Define module view and parameters
$ViewList = array();

// Define cache module view and parameters
$ViewList['cache'] = array(
                           'script' => 'cache.php',
                           'default_navigation_part' => 'bcupdatecachepart',
                           'params' => array() );

?>
