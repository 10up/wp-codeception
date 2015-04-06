<?php
/*
Plugin Name: WP Codeception
Plugin URI:
Description: Registers WP-CLI commands which allow you to execute Codeception tests.
Author: 10up Inc
Author URI: https://10up.com/
Version: 1.0.0-dev
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
define( 'WPCC_VERSION', '1.0.0-dev' );
define( 'WPCC_ABSPATH', __DIR__ );

// load autoloader
require_once __DIR__ . '/vendor/codeception/codeception/autoload.php';

// register CLI commands
WP_CLI::add_command( 'codeception', '\WPCC\CLI\Codeception' );
WP_CLI::add_command( 'selenium', '\WPCC\CLI\Selenium' );