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

namespace WPCC\Util;

/**
 * This class is used only when Codeception is executed in `--debug` mode.
 *
 * @since 1.0.2
 * @category WPCC
 * @package Util
 */
class Debug extends \Codeception\Util\Debug {

	/**
	 * Prints formatted data to screen.
	 *
	 * Example:
	 * <pre><code>
	 * <?php
	 *
	 * // using multiple arguments
	 * \WPCC\Util\Debug::debugf( 'Here are %s and %s parameters', $first, $second );
	 *
	 * // or an array
	 * \WPCC\Util\Debug::debugf( 'Here are %s and %s parameters', array( $first, $second ) );
	 *
	 * ?>
	 * </code></pre>
	 *
	 * @since 1.0.2
	 * @uses sprintf() to build debug message.
	 * @uses \Codeception\Util\Debug::debug() to print data to screen.
     *
	 * @static
	 * @access public
	 * @param string $message The message pattern.
	 * @param type $args
	 */
	public static function debugf( $message, $args ) {
		$args = array_slice( func_get_args(), 1 );
		if ( count( $args ) == 1 && is_array( $args[0] ) ) {
			$args = $args[0];
		}

		array_unshift( $args, $message );
		$message = call_user_func_array( 'sprintf', $args );
		self::debug( $message );
	}

}