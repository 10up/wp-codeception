<?php
/*
Plugin Name: WP Codeception
Plugin URI:
Description:
Author: 10up Inc
Author URI: https://10up.com/
Version: 1.0.0
License: GPL version 2 or later - http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
*/

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

// do nothing if PHP version is less than 5.5
if ( version_compare( PHP_VERSION, '5.5', '<' ) ) {
	return;
}

// do nothing if WP_CLI or Composer dependecies are not installed
if ( ! defined( 'WP_CLI' ) || ! WP_CLI || ! file_exists( __DIR__ . '/vendor/codeception/codeception/autoload.php' ) ) {
	return;
}

// define basic constants
define( 'WPCC_VERSION', '1.0.0' );
define( 'WPCC_ABSPATH', __DIR__ );

// load autoloader
require_once __DIR__ . '/vendor/codeception/codeception/autoload.php';

// register CLI command
WP_CLI::add_command( 'codeception', '\WPCC\CLI' );

/**
 * Handles caught error and displays error message.
 *
 * @since 1.0.0
 *
 * @param int $code The error code.
 * @param string $message The error message.
 * @param string $file The path to a file where error has been triggered.
 * @param int $line The line number where error happens.
 */
function wpcc_error_hanlder( $code, $message, $file, $line ) {
	switch ( $code ) {
		case E_ALL:               $code = 'E_ALL';               break;
		case E_COMPILE_ERROR:     $code = 'E_COMPILE_ERROR';     break;
		case E_COMPILE_WARNING:   $code = 'E_COMPILE_WARNING';   break;
		case E_CORE_ERROR:        $code = 'E_CORE_ERROR';        break;
		case E_CORE_WARNING:      $code = 'E_CORE_WARNING';      break;
		case E_ERROR:             $code = 'E_ERROR';             break;
		case E_NOTICE:            $code = 'E_NOTICE';            break;
		case E_PARSE:             $code = 'E_PARSE';             break;
		case E_RECOVERABLE_ERROR: $code = 'E_RECOVERABLE_ERROR'; break;
		case E_STRICT:            $code = 'E_STRICT';            break;
		case E_USER_ERROR:        $code = 'E_USER_ERROR';        break;
		case E_USER_NOTICE:       $code = 'E_USER_NOTICE';       break;
		case E_USER_WARNING:      $code = 'E_USER_WARNING';      break;
		case E_WARNING:           $code = 'E_WARNING';           break;
		default: break;
	}

	debug_print_backtrace( DEBUG_BACKTRACE_IGNORE_ARGS );
	WP_CLI::error( sprintf( '[%s] %s at %s:%d', $code, $message, $file, $line ) );
}

/**
 * Hanldes caught exception and displays error message.
 *
 * @since 1.0.0
 *
 * @param Exception $e The exception object.
 */
function wpcc_exception_handler( Exception $e ) {
	wpcc_error_hanlder( E_ERROR, $e->getMessage(), $e->getFile(), $e->getLine() );
}

/**
 * Handles FATAL error and displays proper message.
 *
 * @since 1.0.0
 */
function wpcc_shutdown_handler() {
	$last_error = error_get_last();
	if ( E_ERROR === $last_error['type'] ) {
		wpcc_error_hanlder( E_ERROR, $last_error['message'], $last_error['file'], $last_error['line'] );
	}
}

// register error and exception handlers
set_error_handler( 'wpcc_error_hanlder' );
set_exception_handler( 'wpcc_exception_handler' );
register_shutdown_function( 'wpcc_shutdown_handler' );