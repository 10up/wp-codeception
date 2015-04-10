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

namespace WPCC\Helper;

/**
 * General page object helper class.
 *
 * @since 1.0.0
 * @category WPCC
 * @package Helper
 * @subpackage PageObject
 */
class PageObject {

	/**
	 * Current actor.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 * @var \Codeception\Actor
	 */
	protected $_actor;

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @param \Codeception\Actor $I The current actor.
	 */
	public function __construct( \Codeception\Actor $I ) {
		$this->_actor = $I;
	}

	/**
	 * Creates new instance of page object for an actor and returns it.
	 *
	 * @since 1.0.0
	 *
	 * @static
	 * @access public
	 * @param \Codeception\Actor $I The actor to create a page object for.
	 * @return \WPCC\Helper\PageObject The new instance of a page object.
	 */
	public static function of( \Codeception\Actor $I ) {
		return new static( $I );
	}

}