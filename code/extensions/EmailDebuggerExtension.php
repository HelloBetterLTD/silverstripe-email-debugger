<?php
/**
 * Created by PhpStorm.
 * User: Acer
 * Date: 4/9/2020
 * Time: 12:59 PM
 */

namespace Debugger;

use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\TextField;
use SilverStripe\ORM\DataExtension;

class EmailDebuggerExtension extends DataExtension
{
    private static $db = array(
        'DebugEmailAddress' => 'Varchar'
    );

    public function updateCMSFields(FieldList $fields)
    {
        $fields->addFieldToTab('Root.Debug Email', TextField::create('DebugEmailAddress', 'Email Address'));
        return $fields;
    }
}