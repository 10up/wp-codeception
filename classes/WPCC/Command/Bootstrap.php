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
		$namespace = trim( $this->namespace, '\\' );

		$str = <<<EOF
# Codeception Test Suite Configuration
#
# Suite for acceptance tests.
# Perform tests in browser using the WebDriver or PhpBrowser.
# If you need both WebDriver and PHPBrowser tests - create a separate suite.

class_name: {$actor}{$this->actorSuffix}
modules:
    enabled:
        - \WPCC\Module\WebDriver:
            browser: phantomjs
        - \WPCC\Module\WordPress
        - \\{$namespace}\Helper\Acceptance
EOF;

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
		$namespace = trim( $this->namespace, '\\' );

		$str = <<<EOF
# Codeception Test Suite Configuration
#
# Suite for functional (integration) tests
# Emulate web requests and make application process them

class_name: {$actor}{$this->actorSuffix}
modules:
    enabled:
        # add framework module here
        - \WPCC\Module\WordPress
        - \\{$namespace}\Helper\Functional
EOF;

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
				'memory_limit' => WP_MAX_MEMORY_LIMIT,
			),
			'extensions' => array(
				'enabled' => array( 'Codeception\Extension\RunFailed' ),
			),
		);

		$str = Yaml::dump( $basicConfig, 4 );
		if ( $this->namespace ) {
			$namespace = rtrim( $this->namespace, '\\' );
			$str = "namespace: $namespace\n" . $str;
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