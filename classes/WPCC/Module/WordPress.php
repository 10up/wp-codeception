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
	 * Creates temp user.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @param string $role A user role to set.
	 * @param array $random_user Data used to create a temp user.
	 * @return \WP_User|\WP_Error The user object on success, otherwise WP_Error object.
	 */
	public function createTempUser( $role = 'administrator', &$random_user = null ) {
		$response = wp_remote_get( 'http://api.randomuser.me/' );
		if ( is_wp_error( $response ) ) {
			return $response;
		}

		$response_code = wp_remote_retrieve_response_code( $response );
		if ( 200 != $response_code ) {
			return new \WP_Error( $response_code, 'Random user generation error.' );
		}

		$response = json_decode( wp_remote_retrieve_body( $response ), true );
		$random_user = current( $response['results'] );
		$random_user = $random_user['user'];

		$user_data = array(
			'user_pass'    => $random_user['password'],
			'user_login'   => $random_user['username'],
			'user_email'   => $random_user['email'],
			'display_name' => sprintf( '%s %s', $random_user['name']['first'], $random_user['name']['last'] ),
			'first_name'   => $random_user['name']['first'],
			'last_name'    => $random_user['name']['last'],
			'role'         => $role,
		);
		
		$user_id = wp_insert_user( $user_data );
		$user = is_wp_error( $user_id ) ? $user_id : get_user_by( 'id', $user_id );

		return $user;
	}

}