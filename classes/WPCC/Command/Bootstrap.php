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

namespace WPCC\Command;

use Symfony\Component\Yaml\Yaml;

/**
 * Creates default config, tests directory and sample suites. Use this command
 * to start building a test suite.
 *
 * @since 1.0.0
 * @category WPCC
 * @package Command
 */
class Bootstrap extends \Codeception\Command\Bootstrap {

	/**
	 * Creates acceptance suite.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 * @param string $actor The actor name.
	 */
	protected function createAcceptanceSuite( $actor = 'Acceptance' ) {
		$suiteConfig = array(
			'class_name' => $actor . $this->actorSuffix,
			'modules'    => array(
				'enabled' => array( 'WordPress', "\\{$this->namespace}Helper\\{$actor}" ),
			),
		);

		$str  = "# Codeception Test Suite Configuration\n\n";
		$str .= "# suite for acceptance tests.\n";
		$str .= "# perform tests in browser using the WebDriver or PhpBrowser.\n";
		$str .= "# If you need both WebDriver and PHPBrowser tests - create a separate suite.\n\n";

		$str .= Yaml::dump( $suiteConfig, 5 );
		$this->createSuite( 'acceptance', $actor, $str );
	}

	/**
	 * Creates functional suite.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 * @param string $actor The actor name.
	 */
	protected function createFunctionalSuite( $actor = 'Functional' ) {
		$suiteConfig = array(
			'class_name' => $actor . $this->actorSuffix,
			'modules'    => array( 
				'enabled' => array( 'WordPress', "\\{$this->namespace}Helper\\{$actor}" )
			),
		);

		$str  = "# Codeception Test Suite Configuration\n\n";
		$str .= "# suite for functional (integration) tests.\n";
		$str .= "# emulate web requests and make application process them.\n\n";
		$str .= Yaml::dump( $suiteConfig, 2 );
		
		$this->createSuite( 'functional', $actor, $str );
	}

	/**
	 * Creates global config file.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function createGlobalConfig() {
		$basicConfig = array(
			'actor' => $this->actorSuffix,
			'paths' => array(
				'tests'   => 'tests',
				'log'     => $this->logDir,
				'data'    => $this->dataDir,
				'support' => $this->supportDir,
				'envs'    => $this->envsDir,
			),
			'settings' => array(
				'bootstrap'    => '_bootstrap.php',
				'colors'       => strtoupper( substr( PHP_OS, 0, 3 ) ) != 'WIN',
				'memory_limit' => WP_MAX_MEMORY_LIMIT
			),
			'extensions' => array(
				'enabled' => array( 'Codeception\Extension\RunFailed' ),
			),
			'modules'  => array(
				'config' => array(
				),
			),
		);

		$str = Yaml::dump( $basicConfig, 4 );
		if ( $this->namespace ) {
			$str = "namespace: {$this->namespace} \n" . $str;
		}
		
		file_put_contents( 'codeception.yml', $str );
	}

	/**
	 * Creates appropriate folders.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	protected function createDirs() {
		@mkdir( 'tests' );
		@mkdir( $this->logDir );
		@mkdir( $this->dataDir );
		@mkdir( $this->supportDir );
		@mkdir( $this->envsDir );
	}

}