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
 * Main class responsible for suites running and else stuff.
 *
 * @since 1.0.0
 * @category WPCC
 */
class Codecept extends \Codeception\Codecept {

	/**
	 * Runs suite tests.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @param array $settings The array of suite settings.
	 * @param string $suite The suite name to run.
	 * @param string $test The test name to run.
	 * @return \PHPUnit_Framework_TestResult The suite execution results.
	 */
	public function runSuite( $settings, $suite, $test = null ) {
		$suiteManager = new SuiteManager( $this->dispatcher, $suite, $settings );
		$suiteManager->initialize();
		$suiteManager->loadTests( $test );
		$suiteManager->run( $this->runner, $this->result, $this->options );

		return $this->result;
	}

}