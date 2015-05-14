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

use Codeception\Exception\ConfigurationException as ConfigurationException;
use Codeception\Lib\Interfaces\DependsOnModule;
use Codeception\Lib\Interfaces\PartedModule;

/**
 * Module container class.
 *
 * @since 1.0.0
 * @category WPCC
 */
class ModuleContainer extends \Codeception\Lib\ModuleContainer {

	/**
	 * Creates a module instance.
	 *
	 * @since 1.0.0
	 * @throws \Codeception\Exception\ConfigurationException If the module hasn't been found.
	 *
	 * @access public
	 * @param string $moduleName The module name.
	 * @param boolean $active Determines whether module is active or not.
	 * @return \Codeception\Module The module instance.
	 */
	public function create( $moduleName, $active = true ) {
		$this->active[ $moduleName ] = $active;
		$config = $this->getModuleConfig( $moduleName );

		// skip config validation on dependent module
		if ( empty( $config ) && ! $active ) {
			$config = null;
		}

		// helper
		$hasNamespace = mb_strpos( $moduleName, '\\' ) !== false;
		if ( $hasNamespace ) {
			return $this->_instantiateModule( $moduleName, $moduleName, $config );
		}

		// standard module
		$globalNamespaces = array( '\\WPCC\\Module\\', self::MODULE_NAMESPACE );
		foreach ( $globalNamespaces as $globalNamespace ) {
			$moduleClass = $globalNamespace . $moduleName;
			if ( class_exists( $moduleClass ) ) {
				return $this->_instantiateModule( $moduleName, $moduleClass, $config );
			}
		}

		// (deprecated) try find module under namespace setting
		$configNamespace = isset( $this->config['namespace'] ) ? $this->config['namespace'] : '';
		$moduleClass = $configNamespace . self::MODULE_NAMESPACE . $moduleName;
		if ( class_exists( $moduleClass ) ) {
			return $this->_instantiateModule( $moduleName, $moduleClass, $config );
		}

		throw new ConfigurationException( "Module $moduleName could not be found and loaded" );
	}

	/**
	 * Instantiates module instance.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @param string $name The module name.
	 * @param string $class The module class.
	 * @param array $config The config array.
	 * @return mixed The module instance.
	 */
	private function _instantiateModule( $name, $class, $config ) {
		$module = $this->di->instantiate( $class, array( $this, $config ), false );
		$this->modules[ $name ] = $module;

		if ( !$this->active[ $name ] ) {
			// if module is not active, its actions should not be included into actor class
			return $module;
		}

		if ( $module instanceof DependsOnModule ) {
			$this->injectDependentModule( $name, $module );
		}

		$class = new \ReflectionClass( $module );
		$methods = $class->getMethods( \ReflectionMethod::IS_PUBLIC );
		foreach ( $methods as $method ) {
			$inherit = $class->getStaticPropertyValue( 'includeInheritedActions' );
			$only = $class->getStaticPropertyValue( 'onlyActions' );
			$exclude = $class->getStaticPropertyValue( 'excludeActions' );

			// exclude methods when they are listed as excluded
			if ( in_array( $method->name, $exclude ) ) {
				continue;
			}

			if ( ! empty( $only ) ) {
				// skip if method is not listed
				if ( ! in_array( $method->name, $only ) ) {
					continue;
				}
			} else {
				// skip if method is inherited and inheritActions == false
				if ( ! $inherit && $method->getDeclaringClass() != $class ) {
					continue;
				}
			}
			// those with underscore at the beginning are considered as hidden
			if ( strpos( $method->name, '_' ) === 0 ) {
				continue;
			}

			if ( $module instanceof PartedModule && isset( $config['part'] ) ) {
				if ( ! $this->moduleActionBelongsToPart( $module, $method->name, $config['part'] ) ) {
					continue;
				}
			}

			$this->actions[ $method->name ] = $name;
		}
		
		return $module;
	}

}