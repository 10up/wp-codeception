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

		$config = array(
			'settings' => array( 'colors' => true ),
		);

		$config = self::mergeConfigs( self::$defaultConfig, $config );
		$config = apply_filters( 'wpcc_config', $config );
		if ( ! isset( $config['paths']['log'] ) ) {
			$dir = wp_upload_dir();
			$config['paths']['log'] = $dir['basedir'] . DIRECTORY_SEPARATOR . 'wpcc';
		}

		$suites = array( /*'unit', 'functional', */'acceptance' );

		self::$dir = WPCC_ABSPATH;
		self::$logDir = 'logs';
		self::$config = $config;
		self::$suites = $suites;

		return $config;
	}

	/**
	 * Returns suite configuration.
	 *
	 * @since 1.0.0
	 * @throws \Exception When a suite is not loaded.
	 *
	 * @static
	 * @access public
	 * @param string $suite The suite name.
	 * @param array $config The global config array.
	 * @return array Array of suite settings.
	 */
	public static function suiteSettings( $suite, $config ) {
		// cut namespace name from suite name
		if ( $suite != $config['namespace'] ) {
			$namespace_len = strlen( $config['namespace'] );
			if ( substr( $suite, 0, $namespace_len ) == $config['namespace'] ) {
				$suite = substr( $suite, $namespace_len );
			}
		}

		if ( ! in_array( $suite, self::$suites ) ) {
			throw new \Exception( "Suite $suite was not loaded" );
		}

		$global = $config['settings'];
		$keys = array( 'modules', 'coverage', 'namespace', 'groups', 'env' );
		foreach ( $keys as $key ) {
			if ( isset( $config[$key] ) ) {
				$global[$key] = $config[$key];
			}
		}

		$local = self::_getSuiteConfig( $suite );
		$local = apply_filters( "wpcc_{$suite}_suite_config", $local );

		$settings = self::mergeConfigs( self::$defaultSuiteSettings, $global );
		$settings = self::mergeConfigs( $settings, $local );

		return $settings;
	}

	/**
	 * Returns basic configuration for a suite.
	 *
	 * @since 1.0.0
	 *
	 * @static
	 * @access public
	 * @param string $suite The suite name.
	 * @return array The suite configuration array.
	 */
	protected static function _getSuiteConfig( $suite ) {
		$capital_suite = ucfirst( $suite );
		$path = WPCC_ABSPATH . '/classes/WPCC/' . $capital_suite . '/';

		if ( 'acceptance' == $suite ) {
			$binary = WPCC_ABSPATH . '/node_modules/phantomjs/bin/phantomjs';

			return array(
				'class_name' => 'Tester',
				'namespace'  => "WPCC\\{$capital_suite}",
				'path'       => $path,
				'modules'    => array(
					'enabled' => array(
						'\WPCC\Module\WebDriver',
						'\WPCC\Module\WordPress',
					),
					'config' => array(
						'\WPCC\Module\WebDriver' => array(
							'url'          => home_url( '/' ),
							'browser'      => 'phantomjs',
							'window_size'  => '1280x768',
							'capabilities' => array(
								'phantomjs.binary.path' => $binary,
							),
						),
					),
				),
			);
		}

		return array();
	}

	/**
	 * Returns all possible suite configurations according environment rules.
	 * Suite configurations will contain `current_environment` key which
	 * specifies what environment used.
	 *
	 * @since 1.0.0
	 *
	 * @static
	 * @access public
	 * @param string $suite The suite name.
	 * @return array Array of all possible suite environments.
	 */
	public static function suiteEnvironments( $suite ) {
		$environments = array();
		$settings = self::suiteSettings( $suite, self::config() );
		if ( ! isset( $settings['env'] ) || ! is_array( $settings['env'] ) ) {
			return $environments;
		}

		foreach ( $settings['env'] as $env => $envConfig ) {
			$environments[ $env ] = $envConfig
				? self::mergeConfigs( $settings, $envConfig )
				: $settings;

			$environments[ $env ]['current_environment'] = $env;
		}

		return $environments;
	}

}