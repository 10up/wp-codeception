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

/**
 * Suite manager class.
 *
 * @since 1.0.0
 * @category WPCC
 */
class SuiteManager extends \Codeception\SuiteManager {

	/**
	 * Loads tests to run.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @param string $test The test to load.
	 */
	public function loadTests( $test = null ) {
        $testLoader = new TestLoader( $this->settings['path'] );
		if ( ! empty( $test ) && has_action( $test ) ) {
            $testLoader->loadTest( $test );
		} else {
			$testLoader->loadTests();
		}

        $tests = $testLoader->getTests();
		foreach ( $tests as $test ) {
			$this->addToSuite( $test );
		}
	}

}