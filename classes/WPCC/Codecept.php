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
	 * Register event listeners.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function registerSubscribers() {
		// required
		$this->dispatcher->addSubscriber( new \Codeception\Subscriber\ErrorHandler() );
		$this->dispatcher->addSubscriber( new \WPCC\Subscriber\Bootstrap() );
		$this->dispatcher->addSubscriber( new \Codeception\Subscriber\Module() );
		$this->dispatcher->addSubscriber( new \Codeception\Subscriber\BeforeAfterTest() );
		$this->dispatcher->addSubscriber( new \Codeception\Subscriber\AutoRebuild() );

		// optional
		if ( ! $this->options['silent'] ) {
			$this->dispatcher->addSubscriber( new \WPCC\Subscriber\Console( $this->options ) );
		}

		if ( $this->options['fail-fast'] ) {
			$this->dispatcher->addSubscriber( new \Codeception\Subscriber\FailFast() );
		}

		if ( $this->options['coverage'] ) {
			$this->dispatcher->addSubscriber( new \Codeception\Coverage\Subscriber\Local( $this->options ) );
			$this->dispatcher->addSubscriber( new \Codeception\Coverage\Subscriber\LocalServer( $this->options ) );
			$this->dispatcher->addSubscriber( new \Codeception\Coverage\Subscriber\RemoteServer( $this->options ) );
			$this->dispatcher->addSubscriber( new \Codeception\Coverage\Subscriber\Printer( $this->options ) );
		}

		// extensions
		foreach ( $this->extensions as $subscriber ) {
			$this->dispatcher->addSubscriber( $subscriber );
		}
	}

	/**
	 * Runs tests.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @param string $suite The suite name to run.
	 * @param string $test The test name to run.
	 */
	public function run( $suite, $test = null ) {
		@ini_set( 'memory_limit', apply_filters( 'admin_memory_limit', WP_MAX_MEMORY_LIMIT ) );

		$settings = Configuration::suiteSettings( $suite, Configuration::config() );

		$selectedEnvironments = $this->options['env'];
		$environments = Configuration::suiteEnvironments( $suite );

		if ( ! $selectedEnvironments || empty( $environments ) ) {
			$this->runSuite( $settings, $suite, $test );
		} else {
			foreach ( $environments as $env => $config ) {
				if ( in_array( $env, $selectedEnvironments ) ) {
					$suiteToRun = is_int( $env ) ? $suite : "{$suite}-{$env}";
					$this->runSuite( $config, $suiteToRun, $test );
				}
			}
		}
	}

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
		$this->result->convertErrorsToExceptions( false );

		$suiteManager = new SuiteManager( $this->dispatcher, $suite, $settings );
		$suiteManager->initialize();
		$suiteManager->loadTests( $test );
		$suiteManager->run( $this->runner, $this->result, $this->options );

		return $this->result;
	}

}