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
use Symfony\Component\Console\Input\ArgvInput;

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
	 * : Optional. The suite name to run.
	 *
	 * <test>
	 * : Optional. The test name to run.
	 *
	 * ### EXAMPLE
	 *
	 *     wp composer run
	 *     wp composer run my_test1
	 *     wp composer run my_suit1
	 *
	 * @synopsis [<suite>] [<test>]
	 *
	 * @since 1.0.0
	 * 
	 * @access public
	 * @global array $argv Global array of console arguments passed to script.
	 * @param array $args Unassociated array of arguments passed to this command.
	 * @param array $assoc_args Associated array of arguments passed to this command.
	 */
	public function run( $args, $assoc_args ) {
		global $argv;

		$new_argv = array_slice( (array) $argv, 1 );

		$app = new Application( 'Codeception', \Codeception\Codecept::VERSION );
		$app->add( new \WPCC\Command\Run( 'run' ) );
		$app->run( new ArgvInput( $new_argv ) );
	}
	
}