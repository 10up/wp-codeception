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

namespace WPCC;

use WPCC\TestCase\Cept;

/**
 * Tests loader class.
 *
 * @since 1.0.0
 * @category WPCC
 */
class TestLoader extends \Codeception\TestLoader {

	/**
	 * Loads singular test.
	 *
	 * @since 1.0.0
	 * @throws \Exception when a test format is not recognized.
	 *
	 * @access public
	 * @param string $test The test name.
	 */
    public function loadTest( $test ) {
		foreach ( self::$formats as $format ) {
			$pattern = sprintf( "~^wpcc_%s_.+~", strtolower( $format ) );
			if ( preg_match( $pattern, $test ) ) {
				call_user_func( array( $this, "add$format" ), $test );
				return;
			}
		}

		throw new \Exception( 'Test format not supported. Please, check you use the right suffix. Available filetypes: Cept, Cest, Test' );
	}

	/**
	 * Loads multiple tests.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function loadTests() {
		foreach ( self::$formats as $format ) {
			$lower_format = strtolower( $format );
			$tests = apply_filters( "wpcc_{$lower_format}_tests", array() );
			
			foreach ( $tests as $test ) {
				call_user_func( array( $this, "add$format" ), $test );
			}
		}
	}

	/**
	 * Adds Cept test to the tests list.
	 *
	 * @sicne 1.0.0
	 *
	 * @access public
	 * @param string $test The Cept test action name.
	 */
    public function addCept( $test ) {
		$cept = new Cept();
		$cept->configName( $test );
		$cept->initConfig();

		$this->tests[] = $cept;
	}

}