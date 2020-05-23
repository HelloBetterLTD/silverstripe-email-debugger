<?php

use SilverStripers\EmailDebugger\Mailer;
use SilverStripe\Control\Email\Mailer as SSMailer;

$mailer = new Mailer();
$mailer = $mailer->setSwiftMailer($swift = new Swift_Mailer(new Swift_MailTransport()));
Injector::inst()->registerService($mailer, SSMailer::class);