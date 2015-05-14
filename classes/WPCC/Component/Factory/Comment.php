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

use WPCC\Component\Generator\Sequence\Faker as FakerSequence;

/**
 * Comments factory.
 *
 * @since 1.0.0
 * @category WPCC
 * @package Component
 * @subpackage Factory
 */
class Comment extends \WPCC\Component\Factory {

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function __construct() {
		parent::__construct( array(
			'comment_author'     => new FakerSequence( 'name' ),
			'comment_author_url' => new FakerSequence( 'url' ),
			'comment_approved'   => 1,
			'comment_content'    => new FakerSequence( 'sentences' ),
		) );
	}

	/**
	 * Generates a new comment.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 * @param array $args The array of arguments to use during a new comment creation.
	 * @return int|boolean The newly created comment's ID on success, otherwise FALSE.
	 */
	protected function _createObject( $args ) {
		return wp_insert_comment( $this->_addSlashesDeep( $args ) );
	}

	/**
	 * Updates generated comment.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 * @param mixed $comment_id The comment id.
	 * @param array $fields The array of fields to update.
	 * @return boolean TRUE on success, otherwise FALSE.
	 */
	protected function _updateObject( $comment_id, $fields ) {
		$fields['comment_ID'] = $comment_id;
		return (bool) wp_update_comment( $this->_addSlashesDeep( $fields ) );
	}

	/**
	 * Creates comments for a post.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @param int $post_id The post id to create comments for.
	 * @param int $count The number of comments to create.
	 * @param array $args The array of arguments to use during a new object creation.
	 * @param array $definitions Custom difinitions of default values for a new object properties.
	 * @return array The array of generated comment ids.
	 */
	public function createPostComments( $post_id, $count = 1, $args = array(), $definitions = null ) {
		$args['comment_post_ID'] = $post_id;
		return $this->createMany( $count, $args, $definitions );
	}

	/**
	 * Returns generated comment by id.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @param int $comment_id The comment id.
	 * @return mixed The generated comment on success, otherwise NULL.
	 */
	public function getObjectById( $comment_id ) {
		return get_comment( $comment_id );
	}
	
}