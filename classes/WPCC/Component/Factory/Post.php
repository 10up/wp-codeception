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

/**
 * Posts factory.
 *
 * @since 1.0.0
 * @category WPCC
 * @package Component
 * @subpackage Factory
 */
class Post extends \WPCC\Component\Factory {

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function __construct() {
		parent::__construct( array(
			'post_status'  => 'publish',
			'post_title'   => new Sequence( 'Post title %s' ),
			'post_content' => new Sequence( 'Post content %s' ),
			'post_excerpt' => new Sequence( 'Post excerpt %s' ),
			'post_type'    => 'post'
		) );
	}

	/**
	 * Generates a new post.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 * @param array $args The array of arguments to use during a new post creation.
	 * @return int|\WP_Error The newly created post's ID on success, otherwise a WP_Error object.
	 */
	protected function _createObject( $args ) {
		return wp_insert_post( $args, true );
	}

	/**
	 * Updates generated object.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 * @param mixed $post_id The post id to update.
	 * @param array $fields The array of fields to update.
	 * @return mixed Updated post ID on success, otherwise 0 or a WP_Error object.
	 */
	protected function _updateObject( $post_id, $fields ) {
		$fields['ID'] = $post_id;
		return wp_update_post( $fields );
	}

	/**
	 * Deletes previously generated post object.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 * @param int $post_id The post id to delete.
	 * @return boolean TRUE on success, otherwise FALSE.
	 */
	protected function _deleteObject( $post_id ) {
		$post = get_post( $post_id );
		if ( ! $post ) {
			return false;
		}

		$deleted = wp_delete_post( $post_id, true );
		
		return ! empty( $deleted ) && ! is_wp_error( $deleted );
	}

	/**
	 * Returns generated post by id.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @param int $post_id The post id.
	 * @return \WP_Post|null The generated post on success, otherwise NULL.
	 */
	public function getObjectById( $post_id ) {
		return get_post( $post_id );
	}

}