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

namespace WPCC\Component;

use WPCC\Component\Generator\Sequence;
use WPCC\Component\Factory\Callback\AfterCreate as AfterCreateCallback;

/**
 * Base class for all factory types.
 *
 * @abstract
 * @since 1.0.0
 * @category WPCC
 * @package Component
 * @subpackage Factory
 */
abstract class Factory {

	/**
	 * The array of default definitions for a new object.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 * @var array
	 */
	protected $_definitions = array();

	/**
	 * The array of generated objects.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 * @var array
	 */
	protected $_objects = array();

	/**
	 * Constructor.
	 * 
	 * @since 1.0.0
	 * 
	 * @access public
	 * @param array $definitions Defines what default values should the properties of the object have.
	 */
	public function __construct( $definitions = array() ) {
		$this->_definitions = $definitions;
	}

	/**
	 * Generates a new object.
	 *
	 * @since 1.0.0
	 *
	 * @abstract
	 * @access protected
	 * @param array $args The array of arguments to use during a new object creation.
	 * @return object|boolean|\WP_Error A new object on success, otherwise FALSE or instance of WP_Error.
	 */
	protected abstract function _createObject( $args );

	/**
	 * Updates generated object.
	 *
	 * @since 1.0.0
	 *
	 * @abstract
	 * @access protected
	 * @param mixed $object The generated object.
	 * @param array $fields The array of fields to update.
	 * @return mixed Updated object on success, otherwise FALSE, 0 or a WP_Error object.
	 */
	protected abstract function _updateObject( $object, $fields );

	/**
	 * Deletes an object.
	 *
	 * @since 1.0.0
	 *
	 * @abstract
	 * @access protected
	 * @param mixed $object The generated object.
	 * @return boolean|\WP_Error TRUE on success, otherwise FALSE or WP_Error object.
	 */
	protected abstract function _deleteObject( $object );

	/**
	 * Returns generated object by id.
	 *
	 * @since 1.0.0
	 *
	 * @abstract
	 * @access public
	 * @param int $object_id The object id.
	 * @return mixed The generated object.
	 */
	public abstract function getObjectById( $object_id );

	/**
	 * Creates a new object.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @param array $args The array of arguments to use during a new object creation.
	 * @param array $definitions Custom difinitions of default values for a new object properties.
	 * @return object|boolean|\WP_Error A new object identifier on success, otherwise FALSE or a WP_Error object.
	 */
	public function create( $args = array(), $definitions = null ) {
		if ( is_null( $definitions ) ) {
			$definitions = $this->_definitions;
		}

		$callbacks = array();
		$generated_args = $this->_generateArgs( $args, $definitions, $callbacks );
		if ( is_wp_error( $generated_args ) ) {
			return $generated_args;
		}
		
		$created = $this->_createObject( $generated_args );
		if ( ! $created || is_wp_error( $created ) ) {
			return $created;
		}

		if ( ! empty( $callbacks ) ) {
			$updated_fields = $this->_applyCallbacks( $callbacks, $created );
			$save_result = $this->_updateObject( $created, $updated_fields );
			if ( ! $save_result || is_wp_error( $save_result ) ) {
				return $save_result;
			}
		}

		if ( ! empty( $created ) && !is_wp_error( $created ) ) {
			$this->_objects[] = $created;
		}
		
		return $created;
	}

	/**
	 * Creates a new object and returns it.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @param array $args The array of arguments to use during a new object creation.
	 * @param array $definitions Custom difinitions of default values for a new object properties.
	 * @return object|boolean|\WP_Error A new object on success, otherwise FALSE or a WP_Error object.
	 */
	public function createAndGet( $args = array(), $definitions = null ) {
		$object_id = $this->create( $args, $definitions );
		if ( ! $object_id || is_wp_error( $object_id ) ) {
			return $object_id;
		}
		
		return $this->getObjectById( $object_id );
	}

	/**
	 * Creates many new objects and returns their ids.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @param int $count The number of objects to created.
	 * @param array $args The array of arguments to use during a new object creation.
	 * @param array $definitions Custom difinitions of default values for a new object properties.
	 * @return array The array of generated object ids.
	 */
	public function createMany( $count, $args = array(), $definitions = null ) {
		$results = array();
		for ( $i = 0; $i < $count; $i++ ) {
			$results[] = $this->create( $args, $definitions );
		}
		
		return $results;
	}

	/**
	 * Deletes an object generated by this factory instance.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @param mixed $object The generated object.
	 * @return boolean|\WP_Error TRUE on success, otherwise FALSE or WP_Error object.
	 */
	public function delete( $object ) {
		// do nothing if an object was generated not by this factory
		if ( ! in_array( $object, $this->_objects ) ) {
			return false;
		}

		// delete object and remove it from the objects list
		$deleted = $this->_deleteObject( $object );
		if ( $deleted && !is_wp_error( $deleted ) ) {
			$index = array_search( $object, $this->_objects );
			if ( false !== $index ) {
				unset( $this->_objects[ $index ] );
			}
		}

		return $deleted;
	}

	/**
	 * Deletes all objects generated by this factory instance.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function deleteAll() {
		foreach ( $this->_objects as $object ) {
			$this->delete( $object );
		}
	}

	/**
	 * Generates arguments for a new object.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 * @param array $args The initial set of arguments.
	 * @param array $definitions The definitions for auto generated properties.
	 * @param array $callbacks The array of callbacks.
	 * @return array|\WP_Error The array of arguments on success, otherwise a WP_Error object.
	 */
	protected function _generateArgs( $args, $definitions, &$callbacks = array() ) {
		foreach ( $definitions as $field => $generator ) {
			if ( isset( $args[ $field ] ) ) {
				continue;
			}

			if ( is_scalar( $generator ) ) {
				$args[ $field ] = $generator;
			} elseif ( $generator instanceof AfterCreateCallback ) {
				$callbacks[ $field ] = $generator;
			} elseif ( $generator instanceof Sequence ) {
				$args[ $field ] = $generator->next();
			} else {
				return new \WP_Error( 'invalid_argument', 'Factory default value should be either a scalar or an generator object.' );
			}
		}
		
		return $args;
	}

	/**
	 * Applies callbacks and returns updated fields.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 * @param array $callbacks The array of callbacks to call.
	 * @param mixed $created The newly created object.
	 * @return array The array of updated fields.
	 */
	protected function _applyCallbacks( $callbacks, $created ) {
		$updated_fields = array();
		foreach ( $callbacks as $field => $callback ) {
			$updated_fields[ $field ] = $callback->call( $created );
		}
		
		return $updated_fields;
	}

	/**
	 * Returns callback wrapper which will be called after a new object created.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @param callable $function The callback to call after a new object created.
	 * @return \WPCC\Component\Factory\Callback\AfterCreate
	 */
	public function callback( $function ) {
		return new AfterCreateCallback( $function );
	}

	/**
	 * Adds slashes recursively to each item of incomming value.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 * @param mixed $value The incomming value to add slashes to.
	 * @return mixed Updated value with slashes.
	 */
	protected function _addSlashesDeep( $value ) {
		if ( is_array( $value ) ) {
			$value = array_map( array( $this, '_addSlashesDeep' ), $value );
		} elseif ( is_object( $value ) ) {
			$vars = get_object_vars( $value );
			foreach ( $vars as $key => $data ) {
				$value->{$key} = $this->_addSlashesDeep( $data );
			}
		} elseif ( is_string( $value ) ) {
			$value = addslashes( $value );
		}

		return $value;
	}

}