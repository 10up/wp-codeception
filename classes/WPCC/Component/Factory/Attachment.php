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

/**
 * Attachments factory.
 *
 * @since 1.0.0
 * @category WPCC
 * @package Component
 * @subpackage Factory
 */
class Attachment extends Post {

	/**
	 * Generates a new attachemnt.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 * @param array $args The array of arguments to use during a new attachment creation.
	 * @return int The newly created attachment's ID on success, otherwise 0.
	 */
	protected function _createObject( $args = array() ) {
		if ( empty( $args['post_mime_type'] ) ) {
			if ( ! empty( $args['file'] ) && is_readable( $args['file'] ) ) {
				$this->_debug( 'Reading mime type of the file: ' . $args['file'] );

				$filetype = wp_check_filetype( basename( $args['file'] ), null );
				if ( ! empty( $filetype['type'] ) ) {
					$args['post_mime_type'] = $filetype['type'];
					$this->_debug( 'Mime type found: ' . $filetype['type'] );
				} else {
					$this->_debug( 'Mime type not found' );
				}
			}
		}

		$attachment_id = wp_insert_attachment( $args );
		if ( $attachment_id ) {
			$this->_debug(
				'Generated attachment ID: %d (file: %s)',
				$attachment_id,
				! empty( $args['file'] ) ? $args['file'] : 'not-provided'
			);

			$this->_debug( 'Generating attachment metadata' );
			wp_generate_attachment_metadata( $attachment_id, $args['file'] );
		} else {
			$this->_debug( 'Attachment generation failed' );
		}

		return $attachment_id;
	}

}