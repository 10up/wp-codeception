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

namespace WPCC\Component\Factory\Callback;

/**
 * Callback class intended to call a callback function after an object has been created.
 *
 * @since 1.0.0
 * @category WPCC
 * @package Component
 * @subpackage Factory
 */
class AfterCreate {

	/**
	 * The callback.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 * @var callable
	 */
	protected $callback;

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @param callable $callback The callback.
	 */
	public function __construct( $callback ) {
		$this->callback = $callback;
	}

	/**
	 * Calls the callback and returns results of the call.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @param mixed $object The incoming object.
	 * @return mixed The results of the callback call.
	 */
	public function call( $object ) {
		return call_user_func( $this->callback, $object );
	}

}