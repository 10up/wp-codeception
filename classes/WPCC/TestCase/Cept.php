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

namespace WPCC\TestCase;

/**
 * Test case class for Cept version of tests.
 *
 * @since 1.0.0
 * @category WPCC
 * @package TestCase
 */
class Cept extends \Codeception\TestCase\Cept {

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @param array $data The test data.
	 * @param string $dataName The test data name.
	 */
	public function __construct( array $data = array(), $dataName = '' ) {
		parent::__construct( $data, $dataName );
		$this->backupGlobalsBlacklist[] = 'wp_filter';
	}

	/**
	 * Overrides cept test preloading to do nothing instead of reading a file.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function preload() {
		// here is nothing to preload
	}

	/**
	 * Returns test signature.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @return string The test signature.
	 */
	public function getSignature() {
		return $this->testName;
	}

	/**
	 * Runs test.
	 *
	 * @since 1.0.0
	 * @throws \PHPUnit_Framework_Exception
	 * @throws \Exception
	 *
	 * @access protected
	 */
	protected function runTest() {
		if ( $this->getName() === null ) {
			throw new \PHPUnit_Framework_Exception( 'PHPUnit_Framework_TestCase::$name must not be null.' );
		}

		try {
			do_action( $this->testName, $this->scenario );
		} catch ( \Exception $e ) {
			$checkException = false;

			if ( is_string( $this->getExpectedException() ) ) {
				$checkException = true;

				if ( $e instanceof \PHPUnit_Framework_Exception ) {
					$checkException = false;
				}

				$reflector = new \ReflectionClass( $this->expectedException );
				if ( $this->expectedException == 'PHPUnit_Framework_Exception' || $reflector->isSubclassOf( 'PHPUnit_Framework_Exception' ) ) {
					$checkException = true;
				}
			}

			if ( $checkException ) {
				$this->assertThat( $e, new \PHPUnit_Framework_Constraint_Exception( $this->expectedException ) );

				if ( is_string( $this->expectedExceptionMessage ) && !empty( $this->expectedExceptionMessage ) ) {
					$this->assertThat( $e, new \PHPUnit_Framework_Constraint_ExceptionMessage( $this->expectedExceptionMessage ) );
				}

				if ( is_string( $this->expectedExceptionMessageRegExp ) && !empty( $this->expectedExceptionMessageRegExp ) ) {
					$this->assertThat( $e, new \PHPUnit_Framework_Constraint_ExceptionMessageRegExp( $this->expectedExceptionMessageRegExp ) );
				}

				if ( $this->expectedExceptionCode !== null ) {
					$this->assertThat( $e, new \PHPUnit_Framework_Constraint_ExceptionCode( $this->expectedExceptionCode ) );
				}

				return;
			} else {
				throw $e;
			}
		}

		if ( $this->getExpectedException() !== null ) {
			$this->assertThat( null, new \PHPUnit_Framework_Constraint_Exception( $this->expectedException ) );
		}
	}

}