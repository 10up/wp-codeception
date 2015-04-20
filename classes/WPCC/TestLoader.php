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

use Codeception\TestCase;
use Codeception\TestCase\Cept;
use Codeception\TestCase\Cest;
use Codeception\TestCase\Interfaces\Configurable;
use Codeception\Util\Annotation;

/**
 * Tests loader class.
 *
 * @since 1.0.0
 * @category WPCC
 */
class TestLoader extends \Codeception\TestLoader {

	/**
	 * Setups common environment for a test case.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 * @param \Codeception\TestCase $testCase The test case object.
	 * @param string $name Test case name.
	 * @param string $file The file name.
	 */
	protected function _setupTestCase( TestCase $testCase, $name, $file ) {
		$testCase->setBackupGlobals( false );
		$testCase->setBackupStaticAttributes( false );
		$testCase->setRunTestInSeparateProcess( false );
		$testCase->setInIsolation( false );
		$testCase->setPreserveGlobalState( false );

		if ( $testCase instanceof Configurable ) {
			$testCase->configName( $name );
			$testCase->configFile( $file );
			$testCase->initConfig();
		}
	}

	/**
	 * Adds Cept test to the tests list.
	 *
	 * @sicne 1.0.0
	 *
	 * @access public
	 * @param string $file The Cept test action name.
	 */
	public function addCept( $file ) {
		$name = $this->relativeName( $file );

		$cept = new Cept();
		$this->_setupTestCase( $cept, $name, $file );

		$this->tests[] = $cept;
	}

	/**
	 * Creates test from Cest method.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 * @param object $cestInstance The instance of a Cest class.
	 * @param string $methodName The method name to create test from.
	 * @param mixed $file Deprecated parameter.
	 * @return \WPCC\TestCase\Cest Instance of Cest test.
	 */
	protected function createTestFromCestMethod( $cestInstance, $methodName, $file ) {
		if ( strpos( $methodName, '_' ) === 0 || $methodName == '__construct' ) {
			return null;
		}

		$cest = new Cest();
		$cest->config( 'testClassInstance', $cestInstance );
		$cest->config( 'testMethod', $methodName );
		
		$this->_setupTestCase( $cest, $methodName, $file );

		$testClass = get_class( $cestInstance );
		$cest->getScenario()->env( Annotation::forMethod( $testClass, $methodName )->fetchAll( 'env' ) );
		$cest->setDependencies( \PHPUnit_Util_Test::getDependencies( $testClass, $methodName ) );

		return $cest;
	}

	/**
	 * Creates test from PHPUnit method.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 * @param \ReflectionClass $class The class object.
	 * @param \ReflectionMethod $method The method object.
	 * @return \PHPUnit_Framework_Test The test object.
	 */
    protected function createTestFromPhpUnitMethod( \ReflectionClass $class, \ReflectionMethod $method ) {
		if ( ! \PHPUnit_Framework_TestSuite::isTestMethod( $method ) ) {
			return;
		}
		
		$test = \PHPUnit_Framework_TestSuite::createTest( $class, $method->name );
		if ( $test instanceof \PHPUnit_Framework_TestSuite_DataProvider ) {
			foreach ( $test->tests() as $t ) {
				$this->enhancePhpunitTest( $t );
			}
		} else {
			$this->enhancePhpunitTest( $test );
		}

		$test->setBackupGlobals( false );
		$test->setBackupStaticAttributes( false );
		$test->setRunTestInSeparateProcess( false );
		$test->setInIsolation( false );
		$test->setPreserveGlobalState( false );
		
		return $test;
	}

}