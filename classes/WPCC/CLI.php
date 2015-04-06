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
 */
class CLI extends \WP_CLI_Command {

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
		$app->add( new \Codeception\Command\Run( 'run' ) );
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
		$app->add( new \Codeception\Command\Build( 'build' ) );
		$app->add( new \Codeception\Command\Console( 'console' ) );
		$app->add( new \Codeception\Command\Bootstrap( 'bootstrap' ) );
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
	 *     wp codeception generate-cept acceptance Login
	 *     wp codeception generate-cept unit Front
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

}