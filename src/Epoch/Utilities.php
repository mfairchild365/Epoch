<?php
namespace Epoch;

class Utilities
{
    static function formatTime($timeStamp)
    {
        if ($account = \Epoch\Controller::getAccount()) {
            // set this to the time zone provided by the user
            $tz = $account->timezone;
             
            // create the DateTimeZone object for later
            $dtzone = new DateTimeZone($tz);
             
            // create a DateTime object
            $dtime = new DateTime();
             
            // set it to the timestamp (PHP >= 5.3.0)
            $dtime->setTimestamp($timeStamp);
             
            // convert this to the user's timezone using the DateTimeZone object
            $dtime->setTimeZone($dtzone);
             
            // print the time using your preferred format
            return $dtime->format('g:i A m/d/y');
        }
        
        return  date("F j, Y, g:i a", $timeStamp);
    }
}