<?php

// +----------------------------------------------------------------------+
// | Copyright 2015 10up Inc                                              |
// +----------------------------------------------------------------------+
// | This program is free software; you can redistribute it and/or modify |
// | it under the terms of the GNU General Public License, version 2, as  |
// | published by the Free Software Foundation.                           |
// |                                                                      |
// | This program is distributed in the hope that it will be useful,      |
// | but WITHOUT ANY WARRANTY; without even the implied warranty of       |
// | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the        |
// | GNU General Public License for more details.                         |
// |                                                                      |
// | You should have received a copy of the GNU General Public License    |
// | along with this program; if not, write to the Free Software          |
// | Foundation, Inc., 51 Franklin St, Fifth Floor, Boston,               |
// | MA 02110-1301 USA                                                    |
// +----------------------------------------------------------------------+

namespace WPCC\Subscriber;

use Codeception\Event\SuiteEvent;
use WPCC\Component\Generator\Actor;
use WPCC\SuiteManager;

/**
 * Rebuilds test guys before tests start.
 *
 * @since 1.0.0
 * @category WPCC
 * @package Subscriber
 */
class AutoRebuild extends \Codeception\Subscriber\AutoRebuild {

	/**
	 * Updates test guy class.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @param SuiteEvent $e Event object.
	 */
	public function updateGuy( SuiteEvent $e ) {
		$settings = $e->getSettings();
		$guyFile = $settings['path'] . $settings['class_name'] . '.php';

		// load guy class to see hash
		$handle = fopen( $guyFile, "r" );
		if ( $handle ) {
			$line = fgets( $handle );
			if ( preg_match( '~\[STAMP\] ([a-f0-9]*)~', $line, $matches ) ) {
				$hash = $matches[1];
				$currentHash = Actor::genHash( SuiteManager::$actions, $settings );

				// regenerate guy class when hashes do not match
				if ( $hash != $currentHash ) {
					codecept_debug( "Rebuilding {$settings['class_name']}..." );
					$guyGenerator = new Actor( $settings );
					fclose( $handle );
					$generated = $guyGenerator->produce();
					file_put_contents( $guyFile, $generated );
					return;
				}
			}
			
			fclose( $handle );
		}
	}

}