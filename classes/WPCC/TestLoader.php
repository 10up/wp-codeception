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
use WPCC\TestCase\Cest;
use Codeception\Util\Annotation;

/**
 * Tests loader class.
 *
 * @since 1.0.0
 * @category WPCC
 */
class TestLoader extends \Codeception\TestLoader {

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
		$cept->configName( $name );
		$cept->configFile( $file );
		$cept->initConfig();

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

		$testClass = get_class( $cestInstance );

		$cest = new Cest();
		$cest->configName( $methodName );
		$cest->configFile( $file );
		$cest->config( 'testClassInstance', $cestInstance );
		$cest->config( 'testMethod', $methodName );
		$cest->initConfig();

		$cest->getScenario()->env( Annotation::forMethod( $testClass, $methodName )->fetchAll( 'env' ) );
		$cest->setDependencies( \PHPUnit_Util_Test::getDependencies( $testClass, $methodName ) );

		return $cest;
	}

}