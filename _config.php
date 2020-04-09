<?php

$mailer = new SSMailer();
Injector::inst()->registerService($mailer, 'Mailer');