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

}