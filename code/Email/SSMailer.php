<?php
/**
 * Created by PhpStorm.
 * User: Acer
 * Date: 4/9/2020
 * Time: 3:49 PM
 */

class SSMailer extends Mailer
{
    private $allTo = null;
    private $allFrom = null;

    public function setAllFrom($email)
    {
        $this->allFrom = $email;
    }

    public function setAllTo($email)
    {
        $this->allTo = $email;
    }

    public function sendPlain($to, $from, $subject, $plainContent, $attachedFiles = array(), $customHeaders = array())
    {

        if ($this->allTo) {
            $to = $this->allTo;
        }

        if ($this->allFrom) {
            $from = $this->allFrom;
        }

        if(Director::isDev() || Director::isTest()){
            if($debug_email = SiteConfig::current_site_config()->DebugEmailAddress) {
                return parent::sendPlain($debug_email, $from, $subject, $plainContent, $attachedFiles, $customHeaders);
            } else {
                $data = array(
                    'type' 		=> 'plain',
                    'to' 		=> $to,
                    'from' 		=> $from,
                    'subject' 	=> $subject,
                    'content' 	=> $plainContent,
                    'plainContent' => $plainContent,
                    'attachedFiles' => $attachedFiles,
                    'customHeaders' => $customHeaders,
                );
                $this->LogEmail($data);
                return true;
            }
        }
        else {
            return parent::sendPlain($to, $from, $subject, $plainContent, $attachedFiles, $customHeaders);
        }
    }

    public function sendHTML($to, $from, $subject, $htmlContent,
                             $attachedFiles = array(), $customHeaders = array(), $plainContent = ''
    ) {

        if ($this->allTo) {
            $to = $this->allTo;
        }

        if ($this->allFrom) {
            $from = $this->allFrom;
        }

        if(Director::isDev() || Director::isTest()){
            if($debug_email = SiteConfig::current_site_config()->DebugEmailAddress) {
                return parent::sendHTML($debug_email, $from, $subject, $htmlContent, $attachedFiles, $customHeaders, $plainContent);
            }else{
                $data = array(
                    'type' 		=> 'html',
                    'to'		=> $to,
                    'from' 		=> $from,
                    'subject' 	=> $subject,
                    'content' 	=> $htmlContent,
                    'plainContent'	=> $plainContent,
                    'htmlContent' 	=> $htmlContent,
                    'attachedFiles' => $attachedFiles,
                    'customHeaders' => $customHeaders,
                );
                $this->LogEmail($data);
                return true;
            }
        }
        else {
            return parent::sendHTML($to, $from, $subject, $htmlContent, $attachedFiles, $customHeaders, $plainContent);
        }
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