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

	/**
	 * Adds Cest test to the tests list.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @param string $testClass The Cest class name.
	 */
	public function addCest( $testClass ) {
		if ( ! class_exists( $testClass ) ) {
			return;
		}
		
		$reflected = new \ReflectionClass( $testClass );
		if ( $reflected->isAbstract() ) {
			return;
		}

		$unit = new $testClass();
		$methods = get_class_methods( $testClass );
		foreach ( $methods as $method ) {
			if ( strpos( $method, '_' ) !== 0 && $method != '__construct' ) {
				$test = $this->createTestFromCestMethod( $unit, $method, null );
				if ( $test ) {
					$this->tests[] = $test;
				}
			}
		}
	}

	/**
	 * Creates test from Cest method.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 * @param object $instance The instance of a Cest class.
	 * @param string $method The method name to create test from.
	 * @param mixed $deprecated Deprecated parameter.
	 * @return \WPCC\TestCase\Cest Instance of Cest test.
	 */
	protected function createTestFromCestMethod( $instance, $method, $deprecated ) {
		$class = get_class( $instance );

		$cest = new Cest();
		$cest->configName( $method );
		$cest->config( 'testClassInstance', $instance );
		$cest->config( 'testMethod', $method );
		$cest->initConfig();

		$cest->getScenario()->env( Annotation::forMethod( $class, $method )->fetchAll( 'env' ) );
		$cest->setDependencies( \PHPUnit_Util_Test::getDependencies( $class, $method ) );
		
		return $cest;
	}

}