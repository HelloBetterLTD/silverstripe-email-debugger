<?php
/**
 * Created by PhpStorm.
 * User: Acer
 * Date: 4/9/2020
 * Time: 3:49 PM
 */

namespace Debugger;

use SilverStripe\Assets\Filesystem;
use SilverStripe\Control\Director;
use SilverStripe\Control\Email\Email;
use SilverStripe\SiteConfig\SiteConfig;

class SSEmail extends Email
{
    public function sendPlain()
    {
        if(Director::isDev() || Director::isTest()) {
            if($debug_email = SiteConfig::current_site_config()->DebugEmailAddress) {
                Email::config()->update('send_all_emails_to', $debug_email);
            }else {
                $data = [
                    'type' 		=> 'plain',
                    'to' 		=> $this->getTo(),
                    'from' 		=> $this->getFrom(),
                    'subject' 	=> $this->getSubject(),
                    'body' 	=> $this->getBody()
                ];
                $this->LogEmail($data);
                return true;
            }
        }
            return parent::sendPlain();
    }

    public function send()
    {
        if(Director::isDev() || Director::isTest()) {
            if($debug_email = SiteConfig::current_site_config()->DebugEmailAddress) {
                Email::config()->update('send_all_emails_to', $debug_email);
            }else {
                $data = [
                    'to' 		=> $this->getTo(),
                    'from' 		=> $this->getFrom(),
                    'subject' 	=> $this->getSubject(),
                    'body' 	=> $this->getBody()
                ];
                $this->LogEmail($data);
                return true;
            }
        }
        return parent::send();
    }

    /**
     * @return string
     */
    public function LogPath()
    {
        return BASE_PATH . '/logs/email.log';
    }

    /**
     * @param $data
     */
    public function LogEmail($data)
    {
        if(!file_exists($this->LogPath())) {
            Filesystem::makeFolder(dirname($this->LogPath()));
            if(!touch($this->LogPath())){
                user_error('Log path is not writable: ' . $this->LogPath());
            }
        }

        file_put_contents($this->LogPath(), print_r($data, 1) . "\n\n", FILE_APPEND);

    }
}