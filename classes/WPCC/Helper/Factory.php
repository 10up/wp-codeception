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
 */
class Factory {

	/**
	 * Posts factory.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @var \WPCC\Component\Factory\Post
	 */
	public $post;

	/**
	 * Attachments factory.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @var \WPCC\Component\Factory\Attachment
	 */
	public $attachment;

	/**
	 * Comments factory.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @var \WPCC\Component\Factory\Comment
	 */
	public $comment;

	/**
	 * Users factory.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @var \WPCC\Component\Factory\User
	 */
	public $user;

	/**
	 * Terms factory.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @var \WPCC\Component\Factory\Term
	 */
	public $term;

	/**
	 * Categories factory.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @var \WPCC\Component\Factory\Term
	 */
	public $category;

	/**
	 * Tags factory.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @var \WPCC\Component\Factory\Term
	 */
	public $tag;

	/**
	 * Blogs factory.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @var \WPCC\Component\Factory\Blog
	 */
	public $blog;

	/**
	 * Networks factory.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @var \WPCC\Component\Factory\Network
	 */
	public $network;

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 * 
	 * @access public
	 */
	public function __construct() {
		$this->user = new \WPCC\Component\Factory\User();

		$this->post = new \WPCC\Component\Factory\Post();
		$this->attachment = new \WPCC\Component\Factory\Attachment();
		$this->comment = new \WPCC\Component\Factory\Comment();

		$this->term = new TermsFactory();
		$this->category = new TermsFactory( 'category' );
		$this->tag = new TermsFactory( 'post_tag' );

		if ( is_multisite() ) {
			$this->blog = new \WPCC\Component\Factory\Blog();
			$this->network = new \WPCC\Component\Factory\Network();
		}
	}

}