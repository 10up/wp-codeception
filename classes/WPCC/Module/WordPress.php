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
 * WordPress module. This module is not completed yet, feel free to add a WordPress
 * related assertion if you need any.
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
	 * @param mixed $meta_value The meta value to check
	 */
	public function seeUserMetaFor( $user_id, $meta_key, $meta_value = null ) {
		$metas = get_user_meta( $user_id, $meta_key );

		$message = sprintf( 'User meta %s does not exist', $meta_key );
		$this->assertNotEmpty( $metas, $message );

		if ( func_num_args() > 2 ) {
			$message = sprintf( 'User meta %s does not contain expected value', $meta_key );
			$this->assertContains( $meta_value, $metas, $message );
		}
	}

	/**
	 * Checks if user meta doesn't exists.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @param int $user_id The user id.
	 * @param string $meta_key The meta key to check.
	 * @param mixed $meta_value The meta value to check
	 */
	public function dontSeeUserMetaFor( $user_id, $meta_key, $meta_value = null ) {
		$metas = get_user_meta( $user_id, $meta_key );

		if ( func_num_args() > 2 ) {
			$message = sprintf( 'User meta %s contains not expected value', $meta_key );
			$this->assertNotContains( $meta_value, $metas, $message );
		} else {
			$message = sprintf( 'User meta %s is not empty', $meta_key );
			$this->assertEmpty( $metas, $message );
		}
	}

	/**
	 * Checks a post meta exists for a post.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @param int $post_id The post id.
	 * @param string $meta_key The meta key to check.
	 * @param mixed $meta_value The meta value to check
	 */
	public function seePostMetaFor( $post_id, $meta_key, $meta_value = null ) {
		$metas = get_post_meta( $post_id, $meta_key );

		$message = sprintf( 'Post meta %s does not exist', $meta_key );
		$this->assertNotEmpty( $metas, $message );

		if ( func_num_args() > 2 ) {
			$message = sprintf( 'Post meta %s does not contain expected value', $meta_key );
			$this->assertContains( $meta_value, $metas, $message );
		}
	}

	/**
	 * Checks if post meta doesn't exists.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @param int $post The post id.
	 * @param string $meta_key The meta key to check.
	 * @param mixed $meta_value The meta value to check
	 */
	public function dontSeePostMetaFor( $post, $meta_key, $meta_value = null ) {
		$metas = get_post_meta( $post, $meta_key );

		if ( func_num_args() > 2 ) {
			$message = sprintf( 'Post meta %s contains not expected value', $meta_key );
			$this->assertNotContains( $meta_value, $metas, $message );
		} else {
			$message = sprintf( 'Post meta %s is not empty', $meta_key );
			$this->assertEmpty( $metas, $message );
		}
	}

}