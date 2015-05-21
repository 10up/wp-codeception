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

namespace WPCC\Component\Factory;

use WPCC\Component\Generator\Sequence;
use WPCC\Component\Generator\Sequence\Faker as FakerSequence;

/**
 * Users factory.
 *
 * @since 1.0.0
 * @category WPCC
 * @package Component
 * @subpackage Factory
 */
class User extends \WPCC\Component\Factory {

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function __construct() {
		parent::__construct( array(
			'user_login' => new Sequence( 'User %s' ),
			'user_pass'  => 'password',
			'user_email' => new FakerSequence( 'email' ),
		) );
	}

	/**
	 * Generates a new user.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 * @param array $args The array of arguments to use during a new user creation.
	 * @return int|\WP_Error The newly created user's ID on success, otherwise a WP_Error object.
	 */
	protected function _createObject( $args ) {
		return wp_insert_user( $args );
	}

	/**
	 * Updates generated user.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 * @param mixed $user_id The user id to update.
	 * @param array $fields The array of fields to update.
	 * @return mixed Updated user ID on success, otherwise a WP_Error object.
	 */
	protected function _updateObject( $user_id, $fields ) {
		$fields['ID'] = $user_id;
		return wp_update_user( $fields );
	}

	/**
	 * Deletes previously generated user.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 * @param int $user_id The user id to delete.
	 * @return boolean TRUEY on success, otherwise FALSE.
	 */
	protected function _deleteObject( $user_id ) {
		$user = get_user_by( 'id', $user_id );
		if ( ! $user ) {
			return false;
		}

		$deleted = wp_delete_user( $user_id );

		return ! empty( $deleted ) && ! is_wp_error( $deleted );
	}

	/**
	 * Returns generated user by id.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @param int $user_id The user id.
	 * @return \WP_User The generated user.
	 */
	public function getObjectById( $user_id ) {
		return new \WP_User( $user_id );
	}

}