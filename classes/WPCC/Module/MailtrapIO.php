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
 * The module allows to track email using mailtrap.io service.
 *
 * @link http://docs.mailtrap.apiary.io/ Mailtrap.io API documentation
 *
 * @since 1.0.0
 * @category WPCC
 * @package Module
 */
class MailtrapIO extends \Codeception\Module {

	/**
	 * Array of required fields to be included into a config.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 * @var array
	 */
	protected $requiredFields = array(
		'username',
		'password',
		'api_token',
		'inbox_id',
	);

	/**
	 * Default config.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 * @var array
	 */
	protected $config = array(
		'port' => '2525',
	);

	/**
	 * Setups module environment.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function _initialize() {
		add_action( 'phpmailer_init', array( $this, '_setup_smtp_settings' ) );
	}

	/**
	 * Setups SMPT settings for the mailer object.
	 *
	 * @since 1.0.0
	 * @action phpmailer_init
	 *
	 * @access public
	 * @param \PHPMailer $phpmailer The mailer object.
	 */
	public function _setup_smtp_settings( \PHPMailer $phpmailer ) {
		$phpmailer->Host = 'mailtrap.io';
		$phpmailer->Port = $this->config['port'];
		$phpmailer->Username = $this->config['username'];
		$phpmailer->Password = $this->config['password'];
		$phpmailer->SMTPAuth = true;
		$phpmailer->SMTPDebug = 1;

		$phpmailer->IsSMTP();
	}

	/**
	 * Sends request to the Mailtrap.io API.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 * @param string $url The relative URL to a resource.
	 * @param array $args The array of arguments for a request.
	 * @return array The response array.
	 */
	protected function _send_request( $url, $args = array() ) {
		$url = 'https://mailtrap.io/api/v1/inboxes/' . $this->config['inbox_id'] . $url;

		$args = wp_parse_args( $args, array(
			'method'  => 'GET',
			'headers' => array(),
		) );

		$args['headers'] = array_merge( $args['headers'], array(
			'Api-Token' => $this->config['api_token'],
			'Accept'    => 'application/json',
		) );

		$response = wp_remote_request( $url, $args );

		return $response;
	}

	/**
	 * Sends GET request to the Mailtrap.io API.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @param string $url The relative URL to a resource.
	 * @return array The response array.
	 */
	protected function _send_get_request( $url ) {
		$response = $this->_send_request( $url, array( 'method' => 'GET' ) );
		
		$response_code = wp_remote_retrieve_response_code( $response );
		$this->assertEquals( 200, $response_code, 'The Mailtrap.io API resonse code is not equals to 200.' );

		return $response;
	}

	/**
	 * Sends DELETE request to the Mailtrap.io API.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 * @param string $url The relative URL to a resource.
	 * @return array The response array.
	 */
	protected function _send_delete_request( $url ) {
		$response = $this->_send_request( $url, array( 'method' => 'DELETE' ) );

		$response_code = wp_remote_retrieve_response_code( $response );
		$this->assertEquals( 200, $response_code, 'The Mailtrap.io API resonse code is not equals to 200.' );

		return $response;
	}

	/**
	 * Sends PATCH request to the Mailtrap.io API.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 * @param string $url The relative URL to a resource.
	 * @param array $body The request body.
	 * @return array The response array.
	 */
	protected function _send_patch_request( $url, $body ) {
		$response = $this->_send_request( $url, array(
			'method' => 'PATCH',
			'body'   => $body,
		) );

		$response_code = wp_remote_retrieve_response_code( $response );
		$this->assertEquals( 200, $response_code, 'The Mailtrap.io API resonse code is not equals to 200.' );

		return $response;
	}

	/**
	 * Checks whether a new email exists for a recipient or not.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @param string $recipient The email address of a recipient.
	 */
	public function seeNewEmailFor( $recipient ) {
		$email = $this->grabLatestEmailFor( $recipient );
		$this->assertEquals( $recipient, $email['to_email'], 'The email recipient is wrong.' );
		$this->assertFalse( $email['is_read'], 'The email is already read.' );
	}

	/**
	 * Grabs latest email for a recipient.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @param string $recipient The email address of a recipient.
	 * @return array The email array.
	 */
	public function grabLatestEmailFor( $recipient ) {
		$response = $this->_send_get_request( '/messages?search=' . urlencode( $recipient ) );

		$emails = json_decode( wp_remote_retrieve_body( $response ), true );
		$this->assertNotEmpty( $emails, 'Received emails array is empty.' );

		$email = current( $emails );

		return $email;
	}

	/**
	 * Deletes an email from the inbox.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @param int $email_id The email id to delete.
	 */
	public function deleteEmail( $email_id ) {
		$this->_send_delete_request( '/messages/' . $email_id );
	}

	/**
	 * Marks email read.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @param int $email_id The email id.
	 */
	public function markEmailRead( $email_id ) {
		$this->_send_patch_request( '/messages/' . $email_id, array(
			'message' => array( 'is_read' => true )
		) );
	}

}