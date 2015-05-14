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
 * Terms factory.
 *
 * @since 1.0.0
 * @category WPCC
 * @package Component
 * @subpackage Factory
 */
class Term extends \WPCC\Component\Factory {

	const DEFAULT_TAXONOMY = 'post_tag';

	/**
	 * The taxonomy name.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 * @var string
	 */
	protected $_taxonomy;

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @param string $taxonomy The taxonomy name.
	 */
	public function __construct( $taxonomy = null ) {
		$this->_taxonomy = $taxonomy ? $taxonomy : self::DEFAULT_TAXONOMY;
		parent::__construct( array(
			'name'        => new Sequence( 'Term %s' ),
			'taxonomy'    => $this->_taxonomy,
			'description' => new Sequence( 'Term description %s' ),
		) );
	}

	/**
	 * Generates a new term.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 * @param array $args The array of arguments to use during a new term creation.
	 * @return int|\WP_Error The newly created term's ID on success, otherwise a WP_Error object.
	 */
	protected function _createObject( $args ) {
		$args = array_merge( array( 'taxonomy' => $this->_taxonomy ), $args );
		$term_id_pair = wp_insert_term( $args['name'], $args['taxonomy'], $args );
		if ( is_wp_error( $term_id_pair ) ) {
			return $term_id_pair;
		}
		
		return $term_id_pair['term_id'];
	}

	/**
	 * Updates generated term.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 * @param mixed $term The term id to update.
	 * @param array $fields The array of fields to update.
	 * @return mixed Updated term ID on success, otherwise a WP_Error object.
	 */
	protected function _updateObject( $term, $fields ) {
		$fields = array_merge( array( 'taxonomy' => $this->_taxonomy ), $fields );
		$taxonomy = is_object( $term ) ? $term->taxonomy : $this->_taxonomy;
		
		$term_id_pair = wp_update_term( $term, $taxonomy, $fields );
		if ( is_wp_error( $term_id_pair ) ) {
			return $term_id_pair;
		}
		
		return $term_id_pair['term_id'];
	}

	/**
	 * Adds terms to a post.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @param int $post_id The post id to add terms to.
	 * @param array $terms The array of terms to add.
	 * @param string $taxonomy The taxonomy name of terms.
	 * @param boolean $append Determines whether to add or replace terms.
	 * @return array The array of affected term IDs.
	 */
	public function addPostTerms( $post_id, $terms, $taxonomy, $append = true ) {
		return wp_set_post_terms( $post_id, $terms, $taxonomy, $append );
	}

	/**
	 * Returns generated term by id.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @param int $term_id The term id.
	 * @return mixed The generated term on success, otherwise NULL or a WP_Error object.
	 */
	public function getObjectById( $term_id ) {
		return get_term( $term_id, $this->_taxonomy );
	}

}