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

use Codeception\Exception\Configuration as ConfigurationException;

/**
 * Configuration class.
 *
 * @since 1.0.0
 * @category WPCC
 */
class Configuration extends \Codeception\Configuration {
	
    /**
     * Loads global config. When config is already loaded - returns it.
     *
	 * @since 1.0.0
	 *
	 * @static
	 * @access public
     * @param null $deprecated
     * @return array The configuration array.
     */
	public static function config( $deprecated = null ) {
		if ( self::$config ) {
			return self::$config;
		}

		self::$dir = WPCC_ABSPATH;
		self::$config = $config = apply_filters( 'wpcc_config', self::$defaultConfig );

		if ( ! isset( $config['paths']['log'] ) ) {
			$dir = wp_upload_dir();
			$config['paths']['log'] = $dir['basedir'] . DIRECTORY_SEPARATOR . 'wpcc';
		}

		self::$logDir = 'logs';
		self::$suites = apply_filters( 'wpcc_suites', array() );

		return $config;
	}

}