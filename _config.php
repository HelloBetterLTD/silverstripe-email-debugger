<?php

use \SilverStripe\Core\Injector\Injector;
use \SilverStripe\Control\Email\Email;
use \Debugger\SSEmail;

$mailer = new SSEmail();
Injector::inst()->registerService($mailer, Email::class );
