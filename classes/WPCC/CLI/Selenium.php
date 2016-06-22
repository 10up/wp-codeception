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

// do nothing if WP_CLI is not available
if ( ! class_exists( '\WP_CLI_Command' ) ) {
	return;
}

/**
 * Responsible for managing selenium server.
 *
 * @since 1.0.0
 * @category WPCC
 * @package CLI
 */
class Selenium extends \WP_CLI_Command {

	/**
	 * Returns full path to the selenium executable file.
	 *
	 * @since 1.0.0
	 *
	 * @access private
	 * @return string The path to the selenium server executable file.
	 */
	private function _get_executable() {
		return WPCC_ABSPATH . '/node_modules/selenium-server/bin/selenium';
	}

	/**
	 * Stops selenium server.
	 *
	 * ### OPTIONS
	 *
	 * ### EXAMPLE
	 *
	 *     wp selenium stop
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @param array $args Unassociated array of arguments passed to this command.
	 * @param array $assoc_args Associated array of arguments passed to this command.
	 */
	public function stop( $args, $assoc_args ) {
		$selenium = $this->_get_executable();
		$pids = trim( shell_exec( "pgrep -l -f {$selenium}" ) );
		$pids = explode( PHP_EOL, (string) $pids );

		if ( ! empty( $pids ) && count( $pids ) >= 1 ) {
			foreach ( $pids as $pid ) {
				shell_exec( "kill -15 {$pid} > /dev/null 2>/dev/null" );
			}
			\WP_CLI::success( 'Selenium server is stopped.' );
		} else {
			\WP_CLI::warning( 'Selenium server is not started yet.' );
		}
	}

	/**
	 * Starts selenium server.
	 *
	 * ### OPTIONS
	 *
	 * ### EXAMPLE
	 *
	 *     wp selenium start
	 *
	 * @since 1.0.0
	 * @alias run
	 *
	 * @access public
	 * @param array $args Unassociated array of arguments passed to this command.
	 * @param array $assoc_args Associated array of arguments passed to this command.
	 */
	public function start( $args, $assoc_args ) {
		$selenium = $this->_get_executable();
		if ( is_executable( $selenium ) ) {

			$pids = explode( PHP_EOL, trim( shell_exec( "pgrep -f {$selenium}" ) ) );
			if ( count( $pids ) < 2 ) {
				shell_exec( "{$selenium} > /dev/null 2>/dev/null &" );
				\WP_CLI::success( 'Selenium server started.' );
			} else {
				\WP_CLI::warning( 'Selenium server is already started.' );
			}

		} else {
			\WP_CLI::error( 'Selenium server is not executable or not installed.' );
		}
	}

	/**
	 * Restarts selenium server.
	 *
	 * ### OPTIONS
	 *
	 * ### EXAMPLE
	 *
	 *     wp selenium restart
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @param array $args Unassociated array of arguments passed to this command.
	 * @param array $assoc_args Associated array of arguments passed to this command.
	 */
	public function restart( $args, $assoc_args ) {
		$this->stop( $args, $assoc_args );
		$this->start( $args, $assoc_args );
	}

}
