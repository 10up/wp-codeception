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
	 * Creates a new instance of a module and configures it. Module class is
	 * searched and resolves according following rules:
	 *
	 * 1. if "class" element is fully qualified class name, it will be taken to create module;
	 * 2. module class will be searched under default namespace, according $namespace parameter: $namespace . '\Codeception\Module\' . $class;
	 * 3. module class will be searched under Codeception and WPCC module namespace, that are "\Codeception\Module" and "\WPCC\Module".
	 *
	 * @since 1.0.0
	 * @throws \Codeception\Exception\Configuration
	 *
	 * @param string $class The module class name.
	 * @param array $config The module configuration.
	 * @param string $namespace The default namespace for module.
	 * @return \Codeception\Module The module instance.
	 */
	public static function createModule( $class, $config, $namespace = '' ) {
		$hasNamespace = (mb_strpos( $class, '\\' ) !== false);

		if ( $hasNamespace ) {
			return new $class( $config );
		}

		// try find module under users suite namespace setting
		$className = $namespace . '\\Codeception\\Module\\' . $class;

		if ( ! @class_exists( $className ) ) {
			// fallback to default namespace
			$className = '\\WPCC\\Module\\' . $class;
			if ( ! @class_exists( $className ) ) {
				$className = '\\Codeception\\Module\\' . $class;
				if ( ! @class_exists( $className ) ) {
					throw new ConfigurationException( $class . ' could not be found and loaded' );
				}
			}
		}

		return new $className( $config );
	}

}