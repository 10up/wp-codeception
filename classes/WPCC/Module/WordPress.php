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

namespace WPCC\Module;

/**
 * WordPress module.
 *
 * @since 1.0.0
 * @category WPCC
 * @package Module
 */
class WordPress extends \Codeception\Module {

	/**
	 * The array of temporary created users.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 * @var array
	 */
	protected $_temp_users = array();

	/**
	 * Deletes temp user.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @param \WP_User|int $user The user object or ID.
	 */
	public function deleteTempUser( $user ) {
		if ( is_a( $user, '\WP_User' ) ) {
			$user = $user->ID;
		}
		
		$user_index = array_search( $user, $this->_temp_users );
		if ( false !== $user_index ) {
			if ( is_multisite() ) {
				wpmu_delete_user( $user );
			} else {
				wp_delete_user( $user );
			}

			unset( $this->_temp_users[ $user_index ] );
		}
	}

	/**
	 * Deletes all temp users.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function deleteTempUsers() {
		foreach ( $this->_temp_users as $user_id ) {
			$this->deleteTempUser( $user_id );
		}

		$this->_temp_users = array();
	}

	/**
	 * Creates temp user.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @param string $role A user role to set.
	 * @return \WP_User|\WP_Error The user object on success, otherwise WP_Error object.
	 */
	public function createTempUser( $role ) {
		$faker = \Faker\Factory::create();

		$first_name = $faker->firstName;
		$last_name = $faker->lastName;
		$display_name = sprintf( '%s %s', $first_name, $last_name );

		$user_data = array(
			'user_pass'    => 'qwerty',
			'user_login'   => sanitize_title( $display_name ),
			'user_email'   => $faker->email,
			'display_name' => $display_name,
			'first_name'   => $first_name,
			'last_name'    => $last_name,
			'role'         => $role,
		);

		$user_id = wp_insert_user( $user_data );
		if ( ! is_wp_error( $user_id ) ) {
			$this->_temp_users[] = $user_id;
			add_user_to_blog( get_current_blog_id(), $user_id, $role );

			$message = sprintf(
				'Created temporary user %s (%s) with %s role',
				$display_name,
				$user_id,
				$role
			);
			
			$this->debug( $message );
			
			return get_user_by( 'id', $user_id );
		}

		return $user_id;
	}

}