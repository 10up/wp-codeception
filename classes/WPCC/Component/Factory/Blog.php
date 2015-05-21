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
 * Blogs factory.
 *
 * @since 1.0.0
 * @category WPCC
 * @package Component
 * @subpackage Factory
 */
class Blog extends \WPCC\Component\Factory {

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @global object $current_site The current site info.
	 * @global string $base The current site base path.
	 */
	public function __construct() {
		global $current_site, $base;
		
		parent::__construct( array(
			'domain'  => $current_site->domain,
			'path'    => new Sequence( $base . 'testpath%s' ),
			'title'   => new FakerSequence( 'company' ),
			'site_id' => $current_site->id,
		) );
	}

	/**
	 * Generates a new blog.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 * @global \wpdb $wpdb The database connection.
	 * @param array $args The array of arguments to use during a new blog creation.
	 * @return int|\WP_Error The newly created blog's ID on success, otherwise a WP_Error object.
	 */
	protected function _createObject( $args ) {
		global $wpdb;

		$meta = isset( $args['meta'] ) ? $args['meta'] : array();
		$user_id = isset( $args['user_id'] ) ? $args['user_id'] : get_current_user_id();

		// temp tables will trigger db errors when we attempt to reference them as new temp tables
		$suppress = $wpdb->suppress_errors();
		$blog = wpmu_create_blog( $args['domain'], $args['path'], $args['title'], $user_id, $meta, $args['site_id'] );
		$wpdb->suppress_errors( $suppress );

		return $blog;
	}

	/**
	 * Does nothing, just implements abstract method.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 * @param mixed $blog_id The blog id.
	 * @param array $fields The array of fields to update.
	 * @return int The blog id.
	 */
	protected function _updateObject( $blog_id, $fields ) {
		return $blog_id;
	}

	/**
	 * Deletes previously generated blog.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 * @global \wpdb $wpdb The database connection.
	 * @param int $blog_id The blog id.
	 * @return boolean Always returns TRUE.
	 */
	protected function _deleteObject( $blog_id ) {
		global $wpdb;
		
		$suppress = $wpdb->suppress_errors();
		wpmu_delete_blog( $blog_id, true );
		$wpdb->suppress_errors( $suppress );

		return true;
	}

	/**
	 * Returns generated blog by id.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @param int $blog_id The blog id.
	 * @return object|boolean The generated blog details on success, otherwise FALSE.
	 */
	public function getObjectById( $blog_id ) {
		return get_blog_details( $blog_id, false );
	}
}