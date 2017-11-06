<?php

print PHP_EOL . '<!--  BEGIN include validation-functions -->' . PHP_EOL;

//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// series of functions to help you validate your data. notice that each
// function returns true or false
function verifyAlphaNum($testString) {
    // Check for letters, numbers and dash, period, space and single quote only.
    // added & ; and # as a single quote sanitized with html entities will have
    // this in it bob's will be come bob&#039;s
    return (preg_match ("/^([[:alnum:]]|-|\.| |\'|&|;|#)+$/", $testString));
}

function verifyEmail($testString) {
    // Check for a valid email address
    // see: http://www.php.net/manual/en/filter.examples.validation.php
    return filter_var($testString, FILTER_VALIDATE_EMAIL);
}

function verifyNumeric($testString) {
    // Check for numbers and period.
    return (is_numeric($testString));
}

function verifyPhone($testString) {
    // Check for usa phone number
    //  see: http://www.php.net/manual/en/function.preg-match.php
    // NOTE: An area code cannot begin with the number 1, often when you type
    // a number for testing you type 123 ... and it will not pass validation :)
    $regex = '/^(?:1(?:[. -])?)?(?:\((?=\d{3}\)))?([2-9]\d{2})(?:(?<=\(\d{3})\))? ?(?:(?<=\d{3})[.-])?([2-9]\d{2})[. -]?(\d{4})(?: (?i:ext)\.? ?(\d{1,5}))?$/';
    return (preg_match($regex, $testString));
}

print PHP_EOL . '<!--  END include validation-functions -->' . PHP_EOL;
?>