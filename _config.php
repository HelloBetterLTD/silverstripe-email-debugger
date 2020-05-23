<?php

use SilverStripers\EmailDebugger\Mailer\Mailer;
use SilverStripe\Control\Email\Mailer as SSMailer;
use SilverStripe\Core\Injector\Injector;

$mailer = new Mailer();
$mailer = $mailer->setSwiftMailer($swift = new Swift_Mailer(new Swift_MailTransport()));
Injector::inst()->registerService($mailer, SSMailer::class);