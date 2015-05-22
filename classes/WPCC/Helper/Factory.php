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

use WPCC\Component\Factory\Term as TermsFactory;

/**
 * Factory helper class.
 *
 * @since 1.0.0
 * @category WPCC
 * @package Helper
 *
 * @property \WPCC\Component\Factory\Post $post
 * @property \WPCC\Component\Factory\Attachment $attachment
 * @property \WPCC\Component\Factory\Comment $comment
 * @property \WPCC\Component\Factory\User $user
 * @property \WPCC\Component\Factory\Term $term
 * @property \WPCC\Component\Factory\Term $category
 * @property \WPCC\Component\Factory\Term $tag
 * @property \WPCC\Component\Factory\Blog $blog
 * @property \WPCC\Component\Factory\Network $network
 */
class Factory {

	/**
	 * The array of registered factories.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 * @var array
	 */
	protected $_factories = array();

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 * 
	 * @access public
	 */
	public function __construct() {
		$this->_factories = array(
			'user'       => new \WPCC\Component\Factory\User(),
			'post'       => new \WPCC\Component\Factory\Post(),
			'attachment' => new \WPCC\Component\Factory\Attachment(),
			'comment'    => new \WPCC\Component\Factory\Comment(),
			'term'       => new TermsFactory(),
			'category'   => new TermsFactory( 'category' ),
			'tag'        => new TermsFactory( 'post_tag' ),
		);

		if ( is_multisite() ) {
			$this->_factories['blog'] = new \WPCC\Component\Factory\Blog();
			$this->_factories['network'] = new \WPCC\Component\Factory\Network();
		}
	}

	/**
	 * Creates a new instance of the factory and returns it.
	 *
	 * @since 1.0.0
	 *
	 * @static
	 * @access public
	 * @return \WPCC\Helper\Factory The factory instance.
	 */
	public static function create() {
		return new static();
	}

	/**
	 * Returns concrete factory.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @param string $factory The factory name.
	 * @return \WPCC\Component\Factory The factory object if available, otherwise NULL.
	 */
	public function __get( $factory ) {
		return isset( $this->_factories[ $factory ] )
			? $this->_factories[ $factory ]
			: null;
	}

	/**
	 * Registers new terms factory.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @param string $factory_name The factory name.
	 * @param string $taxonomy The taxonomy name.
	 * @return boolean TRUE on success, otherwise FALSE.
	 */
	public function addTermsFactory( $factory_name, $taxonomy ) {
		// do nothing if factory name is already taken
		if ( ! empty( $this->_factories[ $factory_name ] ) ) {
			return false;
		}

		// do nothing if a taxonomy doesn't exist
		if ( ! taxonomy_exists( $taxonomy ) ) {
			return false;
		}

		$this->_factories[ $factory_name ] = new TermsFactory( $taxonomy );
		
		return true;
	}

	/**
	 * Cleans up all factories.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function cleanup() {
		foreach ( $this->_factories as $factory ) {
			if ( $factory instanceof \WPCC\Component\Factory ) {
				$factory->deleteAll();
			}
		}
	}

}