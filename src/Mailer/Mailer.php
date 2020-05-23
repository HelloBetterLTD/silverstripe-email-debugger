<?php
/**
 * Created by PhpStorm.
 * User: Acer
 * Date: 4/9/2020
 * Time: 3:49 PM
 */

namespace SilverStripers\EmailDebugger\Mailer;

use League\Flysystem\Filesystem;
use SilverStripe\Control\Director;
use SilverStripe\Control\Email\Email;
use SilverStripe\Control\Email\SwiftMailer;
use SilverStripe\SiteConfig\SiteConfig;

class Mailer extends SwiftMailer
{

	private static $send_debug_emails_to = '';


	/**
	 * @param Email $message
	 * @return bool Whether the sending was "successful" or not
	 */
	public function send($message)
	{
		if ($this->canSendEmail($message)) {
			parent::send($message);
		} else {
			$this->logEmail($message);
			return true;
		}
	}

	/**
	 * @param Email $message
	 * @return bool
	 */
	private function canSendEmail($message)
	{
		$ret = true;
		if (Director::isDev() || Director::isTest()) {
			$ret = false;
			if ($email = SiteConfig::current_site_config()->SendAllEmailsTo) {
				$message->setTo($email);
				$ret = true;
			} elseif ($email = self::config()->get('send_debug_emails_to')) {
				$message->setTo($email);
				$ret = true;
			}
		}
		return $ret;
	}


	private function getLogPath()
	{
		return BASE_PATH . '/logs/email.log';
	}

	/**
	 * @param Email $message
	 */
	private function logEmail($message)
	{
		if(!file_exists($this->LogPath())) {
			Filesystem::makeFolder(dirname($this->LogPath()));
			if(!touch($this->LogPath())){
				user_error('Log path is not writable: ' . $this->LogPath());
			}
		}
		$data = $message->getSwiftMessage()->toString();
		file_put_contents($this->LogPath(), print_r($data, 1) . "\n\n", FILE_APPEND);
	}
}
