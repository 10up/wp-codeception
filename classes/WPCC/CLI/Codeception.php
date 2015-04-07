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

namespace WPCC\CLI;

use Symfony\Component\Console\Application;
use WPCC\Component\Console\Input\ArgvInput;

// do nothing if WP_CLI is not available
if ( ! class_exists( '\WP_CLI_Command' ) ) {
	return;
}

/**
 * Performs Codeception tests.
 *
 * @since 1.0.0
 * @category WPCC
 * @package CLI
 */
class Codeception extends \WP_CLI_Command {

	/**
	 * Runs Codeception tests.
	 *
	 * ### OPTIONS
	 * 
	 * <suite>
	 * : The suite name to run. There are three types of suites available to
	 * use: unit, functional and acceptance, but currently only acceptance tests
	 * are supported.
	 *
	 * <test>
	 * : The test name to run.
	 *
	 * <steps>
	 * : Determines whether to show test steps in output or not.
	 *
	 * <debug>
	 * : Determines whether to show debug and scenario output or not.
	 *
	 * ### EXAMPLE
	 *
	 *     wp codeception run
	 *     wp codeception run --steps
	 *     wp codeception run --debug
	 *
	 * @synopsis [<suite>] [<test>] [--steps] [--debug]
	 *
	 * @since 1.0.0
	 * 
	 * @access public
	 * @param array $args Unassociated array of arguments passed to this command.
	 * @param array $assoc_args Associated array of arguments passed to this command.
	 */
	public function run( $args, $assoc_args ) {
		$app = new Application( 'Codeception', \Codeception\Codecept::VERSION );
		$app->add( new \WPCC\Command\Run( 'run' ) );
		$app->run( new ArgvInput() );
	}

	/**
	 * Executes command.
	 *
	 * @since 1.0.0
	 *
	 * @access private
	 */
	private function _execute_command() {
		$app = new Application( 'Codeception', \Codeception\Codecept::VERSION );
		$app->add( new \WPCC\Command\Build( 'build' ) );
		$app->add( new \Codeception\Command\Console( 'console' ) );
		$app->add( new \WPCC\Command\Bootstrap( 'bootstrap' ) );
		$app->add( new \Codeception\Command\GenerateCept( 'generate-cept' ) );
		$app->add( new \Codeception\Command\GenerateCest( 'generate-cest' ) );
		$app->add( new \Codeception\Command\GenerateTest( 'generate-test' ) );
		$app->add( new \Codeception\Command\GeneratePhpUnit( 'generate-phpunit' ) );
		$app->add( new \Codeception\Command\GenerateSuite( 'generate-suite' ) );
		$app->add( new \Codeception\Command\GenerateHelper( 'generate-helper' ) );
		$app->add( new \Codeception\Command\GenerateScenarios( 'generate-scenarios' ) );
		$app->add( new \Codeception\Command\Clean( 'clean' ) );
		$app->add( new \Codeception\Command\GenerateGroup( 'generate-group' ) );
		$app->add( new \Codeception\Command\GeneratePageObject( 'generate-pageobject' ) );
		$app->add( new \Codeception\Command\GenerateStepObject( 'generate-stepobject' ) );
		$app->run( new ArgvInput() );
	}

	/**
	 * Creates default config, tests directory and sample suites for current
	 * project. Use this command to start building a test suite.
	 *
	 * By default it will create 3 suites acceptance, functional, and unit. To
	 * customize run this command with --customize option.
	 *
	 * ### OPTIONS
	 *
	 * <customize>
	 * : Sets manually actors and suite names during setup.
	 *
	 * <namespace>
	 * : Creates tests with provided namespace for actor classes and helpers.
	 *
	 * <actor>
	 * : Sets actor name to create {SUITE}{NAME} actor class.
	 *
	 * <path>
	 * : Sets path to a project, where tests should be placed.
	 * 
	 * ### EXAMPLE
	 *
	 *     wp codeception bootstrap
	 *     wp codeception bootstrap --customize
	 *     wp codeception bootstrap --namespace="Frontend\Tests"
	 *     wp codeception bootstrap --actor=Tester
	 *     wp codeception bootstrap path/to/the/project --customize
	 *
	 * @synopsis [<path>] [--customize] [--namespace=<namespace>] [--actor=<actor>]
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @param array $args Unassociated arguments passed to the command.
	 * @param array $assoc_args Associated arguments passed to the command.
	 */
	public function bootstrap( $args, $assoc_args ) {
		$this->_execute_command();
	}

	/**
	 * Creates a new group class.
	 *
	 * ### OPTIONS
	 *
	 * <group>
	 * : The group class name to create.
	 *
	 * <config>
	 * : Path to the custom config file.
	 *
	 * ### EXAMPLE
	 *
	 *     wp codeception generate-group Admin
	 *     wp codeception generate-group Admin --config=/path/to/config.yml
	 *
	 * @subcommand generate-group
	 * @synopsis <group> [--config=<config>]
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @param array $args Unassociated arguments passed to the command.
	 * @param array $assoc_args Associated arguments passed to the command.
	 */
	public function generate_group( $args, $assoc_args ) {
		$this->_execute_command();
	}

	/**
	 * Creates a new test suite.
	 *
	 * ### OPTIONS
	 *
	 * <suite>
	 * : The suite name to create.
	 *
	 * <actor>
	 * : The actor name for the suite.
	 *
	 * <config>
	 * : Path to the custom config file.
	 *
	 * ### EXAMPLE
	 *
	 *     wp codeception generate-suite api
	 *     wp codeception generate-suite integration Code
	 *     wp codeception generate-suite frontend Front
	 *
	 * @subcommand generate-suite
	 * @synopsis <suite> [<actor>] [--config=<config>]
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @param array $args Unassociated arguments passed to the command.
	 * @param array $assoc_args Associated arguments passed to the command.
	 */
	public function generate_suite( $args, $assoc_args ) {
		$this->_execute_command();
	}

	/**
	 * Creates a new Cept (scenario-driven test) file.
	 *
	 * ### OPTIONS
	 *
	 * <suite>
	 * : The suite name where to add a new Cept.
	 *
	 * <test>
	 * : The name for a new Cept file.
	 *
	 * <config>
	 * : Path to the custom config file.
	 *
	 * ### EXAMPLE
	 *
	 *     wp codeception generate-cept suite Login
	 *     wp codeception generate-cept suite Front
	 *     wp codeception generate-cept suite subdir/subdir/testnameCept.php
	 *
	 * @subcommand generate-cept
	 * @synopsis <suite> <test> [--config=<config>]
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @param array $args Unassociated arguments passed to the command.
	 * @param array $assoc_args Associated arguments passed to the command.
	 */
	public function generate_cept( $args, $assoc_args ) {
		$this->_execute_command();
	}

	/**
	 * Creates a new Cest (scenario-driven object-oriented test) file.
	 *
	 * ### OPTIONS
	 *
	 * <suite>
	 * : The suite name where to add a new Cest.
	 *
	 * <class>
	 * : The name for a new Cest class.
	 *
	 * <config>
	 * : Path to the custom config file.
	 *
	 * ### EXAMPLE
	 *
	 *     wp codeception generate-cest suite Login
	 *     wp codeception generate-cest suite subdir/subdir/testnameCest.php
	 *     wp codeception generate-cest suite "App\Login"
	 *
	 * @subcommand generate-cest
	 * @synopsis <suite> <class> [--config=<config>]
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @param array $args Unassociated arguments passed to the command.
	 * @param array $assoc_args Associated arguments passed to the command.
	 */
	public function generate_cest( $args, $assoc_args ) {
		$this->_execute_command();
	}

	/**
	 * Creates a skeleton for Unit Test that extends \Codeception\TestCase\Test class.
	 *
	 * ### OPTIONS
	 *
	 * <suite>
	 * : The suite name where to add a new test.
	 *
	 * <class>
	 * : The name for a new test class.
	 *
	 * <config>
	 * : Path to the custom config file.
	 *
	 * ### EXAMPLE
	 *
	 *     wp codeception generate-test unit User
	 *     wp codeception generate-test unit "App\User"
	 *
	 * @subcommand generate-test
	 * @synopsis <suite> <class> [--config=<config>]
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @param array $args Unassociated arguments passed to the command.
	 * @param array $assoc_args Associated arguments passed to the command.
	 */
	public function generate_test( $args, $assoc_args ) {
		$this->_execute_command();
	}

	/**
	 * Creates a skeleton for unit test as in classical PHPUnit.
	 *
	 * ### OPTIONS
	 *
	 * <suite>
	 * : The suite name where to add a new test.
	 *
	 * <class>
	 * : The name for a new test class.
	 *
	 * <config>
	 * : Path to the custom config file.
	 *
	 * ### EXAMPLE
	 *
	 *     wp codeception generate-phpunit unit User
	 *     wp codeception generate-phpunit unit "App\User"
	 *
	 * @subcommand generate-phpunit
	 * @synopsis <suite> <class> [--config=<config>]
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @param array $args Unassociated arguments passed to the command.
	 * @param array $assoc_args Associated arguments passed to the command.
	 */
	public function generate_phpunit( $args, $assoc_args ) {
		$this->_execute_command();
	}

	/**
	 * Creates an empty Helper class.
	 *
	 * ### OPTIONS
	 *
	 * <name>
	 * : The hlper name to create.
	 *
	 * <config>
	 * : Path to the custom config file.
	 *
	 * ### EXAMPLE
	 *
	 *     wp codeception generate-helper MyHelper
	 *
	 * @subcommand generate-helper
	 * @synopsis <name> [--config=<config>]
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @param array $args Unassociated arguments passed to the command.
	 * @param array $assoc_args Associated arguments passed to the command.
	 */
	public function generate_helper( $args, $assoc_args ) {
		$this->_execute_command();
	}

	/**
	 * Generates user-friendly text scenarios from scenario-driven tests (Cest, Cept).
	 *
	 * ### OPTIONS
	 *
	 * <suite>
	 * : The suite name to create scenarios for.
	 *
	 * <config>
	 * : Path to the custom config file.
	 *
	 * <path>
	 * : The specified path as destination instead of default.
	 *
	 * <format>
	 * : Specifies output format: html or text (default).
	 *
	 * <single-file>
	 * : Indicates to render all scenarios to only one file.
	 *
	 * ### EXAMPLE
	 *
	 *     wp codeception generate-scenarios acceptance
	 *     wp codeception generate-scenarios acceptance --format html
	 *     wp codeception generate-scenarios acceptance --path doc
	 *
	 * @subcommand generate-scenarios
	 * @synopsis <suite> [--config=<config>] [--path=<path>] [--format=<format>] [--single-file]
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @param array $args Unassociated arguments passed to the command.
	 * @param array $assoc_args Associated arguments passed to the command.
	 */
	public function generate_scenarios( $args, $assoc_args ) {
		$this->_execute_command();
	}

	/**
	 * Creates a new PageObject class.
	 *
	 * ### OPTIONS
	 *
	 * <suite>
	 * : The suite name where to add a new page object.
	 *
	 * <page>
	 * : The page object name to create.
	 *
	 * <config>
	 * : Path to the custom config file.
	 *
	 * ### EXAMPLE
	 *
	 *     wp codeception generate-pageobject acceptance Login
	 *
	 * @subcommand generate-pageobject
	 * @synopsis <suite> <page> [--config=<config>]
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @param array $args Unassociated arguments passed to the command.
	 * @param array $assoc_args Associated arguments passed to the command.
	 */
	public function generate_pageobject( $args, $assoc_args ) {
		$this->_execute_command();
	}

	/**
	 * Creates a new StepObject class.
	 *
	 * ### OPTIONS
	 *
	 * <suite>
	 * : The suite name where to add a new step object.
	 *
	 * <step>
	 * : The step object name to create.
	 *
	 * <config>
	 * : Path to the custom config file.
	 *
	 * <silent>
	 * : Determines whether or not to skip verification questions.
	 *
	 * ### EXAMPLE
	 *
	 *     wp codeception generate-stepobject acceptance AdminSteps
	 *
	 * @subcommand generate-stepobject
	 * @synopsis <suite> <step> [--config=<config>] [--silent]
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @param array $args Unassociated arguments passed to the command.
	 * @param array $assoc_args Associated arguments passed to the command.
	 */
	public function generate_stepobject( $args, $assoc_args ) {
		$this->_execute_command();
	}

	/**
	 * Cleans output directory.
	 *
	 * ### OPTIONS
	 *
	 * <config>
	 * : Path to the custom config file.
	 *
	 * ### EXAMPLE
	 *
	 *     wp codeception clean --config=/path/to/the/config.yml
	 *
	 * @synopsis [--config=<config>]
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @param array $args Unassociated arguments passed to the command.
	 * @param array $assoc_args Associated arguments passed to the command.
	 */
	public function clean( $args, $assoc_args ) {
		$this->_execute_command();
	}

	/**
	 * Generates Actor classes from suite configs. Currently actor classes are
	 * auto-generated. Use this command to generate them manually.
	 *
	 * ### OPTIONS
	 *
	 * <config>
	 * : Path to the custom config file.
	 *
	 * ### EXAMPLE
	 *
	 *     wp codeception build --config=/path/to/the/config.yml
	 *
	 * @synopsis [--config=<config>]
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @param array $args Unassociated arguments passed to the command.
	 * @param array $assoc_args Associated arguments passed to the command.
	 */
	public function build( $args, $assoc_args ) {
		$this->_execute_command();
	}

}