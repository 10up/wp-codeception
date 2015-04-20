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
	 * Checks user meta exists for an user.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @param int $user_id The user id.
	 * @param string $meta_key The meta key to check.
	 * @param string $meta_value The meta value to check
	 */
	public function seeUserMetaFor( $user_id, $meta_key, $meta_value = null ) {
		$metas = get_user_meta( $user_id, $meta_key );
		$this->assertNotEmpty( $metas, 'User meta does not exist' );

		if ( func_num_args() > 2 ) {
			$this->assertContains( $meta_value, $metas, 'User does not have expected meta' );
		}
	}

}